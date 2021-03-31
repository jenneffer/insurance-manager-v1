@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="path/to/asset.css">
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Payment 
    </div>

    <div class="card-body">
        <form name="insurance_filter" role="form">
            @csrf                     
            <div class="row form-group">
                <div class="col-sm-4">
                    <label for="title">Company</label>
                    <select id="select_company" name="select_company" class="form-control">                      
                        @foreach ($company as $id => $comp_name)
                            <option value="{{$id}}">{{strtoupper($comp_name)}}</option>
                        @endforeach
                    </select>
                </div>  
                <div class="col-sm-3">
                    <label for="title">Date Start</label>
                    <input name="ins_date_start" id="ins_date_start" value="{{$start}}" class="form-control date">
                </div>  
                <div class="col-sm-3">
                    <label for="title">Date End</label>
                    <input name="ins_date_end" id="ins_date_end" value="{{$end}}" class="form-control date">
                </div>
                <!-- <div class="col-sm-2">     
                    <label for="title">&nbsp;</label>               
                    <a class="btn form-control btn-danger" href="#" id="add_payment" data-toggle="modal" data-target="#paymentForm">Add Payment</a>
                </div>   -->
            </div>       
        </form>
        <br>
        <hr>
        <div class="table-responsive">
            <table id="tableview" class="table table-bordered table-striped table-hover datatable datatable-Expense">
                <thead>
                    <tr>
                        <th width="10"><input type="checkbox" id="checkAll"/></th>
                        <th>ID</th>
                        <th>Policy No</th>
                        <th>Company</th>                        
                        <th>Insurance Class</th>
                        <th>Location</th>
                        <th>Property Insured</th>
                        <th>Sum Insured (RM)</th>                        
                        <th>Date start</th>
                        <th>Date end</th>
                        
                    </tr>
                </thead>
                <tbody>                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentForm">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Make Payment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <form name='createPaymentForm' id='createPaymentForm'>
            @csrf 
            <input type="hidden" id="insurance_details_id" name="insurance_details_id" value="">   
            <input type="hidden" id="company_id" name="company_id" value="">       
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    <h5 id="company_name"></h5>
                </div>                
                <br>
                <div class="form-group col-sm-12">
                    <div class="table-responsive" >
                        <table id="ListView" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No.</th>
                                    <th>Particulars</th>
                                    <th>Total (RM)</th>                                                        
                                </tr>
                            </thead>
                            <tbody>                    
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="payment_to">Payment To<span style="color:red;">*</span></label>
                                <input type="text" id="payment_to" name="payment_to" class="form-control" required>                
                                <p class="helper-block"></p>
                            </div>                           
                        </div>                                                                                               
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="paid_amount">Paid amount (RM)<span style="color:red;">*</span></label>
                                <input type="text" id="paid_amount" name="paid_amount" class="form-control" required>                
                                <p class="helper-block"></p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="payment_date">Payment date <span style="color:red;">*</span></label>
                                <input id="payment_date" name="payment_date" class="form-control date" required>
                                <p class="helper-block"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="payment_mode">Payment mode<span style="color:red;">*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-control" required="required">                    
                                    <option value="payment_company">Payment Company</option>
                                    <option value="cash_individual">Cash Individual</option>
                                </select>  
                                <p class="helper-block"></p>
                            </div>  
                            <div class="form-group col-sm-6">
                                <label for="remark">Remark</label>
                                <textarea name="remark" id="remark" class="form-control" rows="2" cols="20"></textarea>
                                <p class="helper-block"></p>
                            </div>
                        </div>
                </div>
            </div>
            
            
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
            </div>
            </form>
        
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
var INSURANCE_IDS = [];
$(document).ready(function() {    
    var comp_id = $('#select_company').val();
    var date_start = $('#ins_date_start').val();
    var date_end = $('#ins_date_end').val();      

    //get the initial filtered data
    get_table_result(comp_id, date_start, date_end);

    $('#ins_date_start').on('dp.change', function(e){ 
        var selectedDate = e.date.format(e.date._f);
        $('#ins_date_start').val(selectedDate);     
        //refresh table
        get_table_result($('#select_company').val(), selectedDate, $('#ins_date_end').val());   
    });

    $('#ins_date_end').on('dp.change', function(e){ 
        var selectedDate = e.date.format(e.date._f);
        $('#ins_date_end').val(selectedDate);     
        //refresh table
        get_table_result($('#select_company').val(), $('#ins_date_start').val(), selectedDate);      
    });

    $('#select_company').on('change', function() {        
        var comp_id = $(this).val();
        //set comp_id  
        $('#company_id').val(comp_id);
        var date_start = $('#ins_date_start').val();
        var date_end = $('#ins_date_end').val()      
        get_table_result(comp_id, date_start, date_end);

    });

    //add button action - check if there's any checked row
    // $('#add_payment').on('click',function(event){
    

    //submit form
    $('#createPaymentForm').on('submit',function(event){           
        event.preventDefault();
        //set the insurance_details_id to hidden value
        $("#insurance_details_id").val(INSURANCE_IDS);

        var paymentData = $('#createPaymentForm').serialize();
        $.ajax({
            url: "/admin/payments/add",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                data: paymentData                    
            },                
            success:function(response){
                window.location=response.url;
            },
        });
    });
});

function add_payment(row_id){           
    var isChecked = $("input[type='checkbox']").is(":checked");
    if (isChecked) {
        

        // $.each($("input:checkbox[class='select-checkbox']:checked"), function(){                         
        //     INSURANCE_IDS.push($(this).val());
        // });     
        INSURANCE_IDS.push(row_id);   
        //redirect to payment page
        if ($('#select_company').val() == ''){
            alert("Please select company name to create Payment Requisition Form");
            return false;
        }else{
            $.ajax({
                url:"/admin/payments/create_payment",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    selected_ids : row_id,
                    comp_id : $('#company_id').val()           
                },
                success:function(data){  
                    // console.log(data);
                    // var tbody = "";
                    // var tfoot = "";
                    var list = data.insurance_data;
                    var company = data.company;    
                    //set the company name and set comp_id
                    $("#company_name").text(company.compDesc);   
                    $("#company_id").val(company.id);   
                    if ($.fn.DataTable.isDataTable("#ListView")) {//First check if dataTable exist or not, if it does then destroy dataTable and recreate it		 
                        $('#ListView').DataTable().clear().destroy();
                    }
                    $('#ListView').DataTable( {
                        data: list,
                        lengthChange:false,
                        paging:false,
                        searching:false,
                        info:false,
                        ordering:false,
                        columnDefs: [ {
                                targets:0,
                                visible: false
                            }],                                
                    });                        
                    // $("#ListView").find("tbody").empty(); //clear all the content from tbody here.       
                    // if( list.length > 0 ){
                    //     var count = 0;
                    //     var result_sum = 0;
                    //     $( list ).each( function( index, element ){                             
                    //         result_sum += parseInt(element.sum_insured);
                    //         count++;      
                    //         console.log(element);     
                    //         tbody +="<tr><td>"+count+".</td><td width='70%'><span>Classification : "+element.ins_class+"</span><br><span>Policy No. : "+element.policy_no+"</span><br><span>Period of Insurance : "+element.date_start+" to "+element.date_end+"</span><br><span>Location : "+element.risk_location+"</span><br><span>Property Insured : "+element.risk_description+"</span></td><td class='text-right'>RM "+element.sum_insured.toFixed(2)+"</td></tr>"
                    //     });
                    //     //calculate total
                    //     tfoot +="<tr><th colspan='2' class='text-right'>TOTAL (RM)</th><th class='text-right'>RM "+result_sum.toFixed(2)+"</th></tr>"
                    // }else{
                    //     tbody +="<tr><td colspan='9' class='text-center'> No records found.</td></tr>";
                    // }
                    
                    // $('#ListView').find('tbody').append(tbody);
                    // $('#ListView').find('tfoot').append(tfoot);
                    $('#paymentForm').modal('show');
                    
                }
            });
        }
        

    } else {
        
        alert("No rows selected.");
        return false;
    }

}
function get_table_result(comp_id, date_start, date_end){
    var tbody = "";
    var table = "";
    $.ajax({
        url:"/admin/payments/filter",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            comp_id : comp_id,
            date_start : date_start,
            date_end : date_end
        },
        success:function(data){      
            if ($.fn.DataTable.isDataTable("#tableview")) {//First check if dataTable exist or not, if it does then destroy dataTable and recreate it		 
                $('#tableview').DataTable().clear().destroy();
            }
            var table = $('#tableview').DataTable( {
                data: data,
                lengthChange:false,
                paging:false,
                searching:false,
                info:false,
                ordering:false,
                dom: 'Bfrtip',
                columnDefs: [ {
                        targets:0,
                        className:'select-checkbox',
                    },
                    {
                        targets:1,
                        visible: false
                }],
                select: {
                    style:'multi',
                    selector:'td:first-child'
                },
                dom:'Bfrtip',
                buttons:{
                    buttons: [
                        {
                            text: 'Add Payment',
                            action: function ( e, dt, node, config ) {                                
                                var row_id = table.rows( { selected: true } ).data().pluck(1).toArray();                                
                                add_payment(row_id);
                            }
                        }
                    ],
                    dom: {
                        button : {
                            tag : 'button',
                            className : 'btn form-control btn-danger'
                        },
                        buttonLiner: {
                            tag: null
                        }
                    }
                } 	         
            });                                  
            // $("#tableview").find("tbody").empty(); //clear all the content from tbody here.       
            // if( data.length > 0 ){
            //     $( data ).each( function( index, element ){            
            //         tbody +="<tr><td><input type='checkbox' name='insurance' value="+element.ins_details_id+"></td><td>"+element.policy_no+"</td><td>"+element.compCode+"</td><td>"+element.ins_class+"</td><td>"+element.risk_location+"</td><td width='30%'>"+element.risk_description+"</td><td class='text-right'>RM "+element.sum_insured.toFixed(2)+"</td><td>"+element.date_start+"</td><td>"+element.date_end+"</td></tr>"
            //     });
            // }
            
            // $('#tableview').find('tbody').append(tbody);
            //check all
            $("#checkAll").click(function () {                
                if ($('#checkAll:checked').val() === 'on')
                    table.rows().select();
                else
                    table.rows().deselect();
            });
        }
    });
}

</script>
@endsection
