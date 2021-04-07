@extends('layouts.admin')
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
            <table id="tableview" class="table table-bordered table-striped table-hover datatable">
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
                        <table id="ListView" class="table table-bordered table-striped respponsive" width="100%">
                            <thead>
                                <tr width="100%">
                                    <th></th>
                                    <th>No.</th>
                                    <th>Particulars</th>
                                    <th class="text-right">Total (RM)</th>                                                        
                                </tr>
                            </thead>
                            <tbody>                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan='3'>Total (RM)</th>
                                    <th class="text-right"></th>                                                                      		
                                </tr>                                    	
                            </tfoot>
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
                                    <option value="cash_individual">Cash</option>
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
                // window.location=response.url;
            },
        });
    });

    //adjust table in bootstrap modal
    $('#paymentForm').on('shown.bs.modal', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    //clear everything on the modal
    $('#paymentForm').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
        location.reload();
    });

});

function add_payment(row_id){        
    if (row_id !='') {     
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
                    var list = data.insurance_data;
                    var company = data.company;    
                    //set the company name and set comp_id
                    $("#company_name").text(company.compDesc);   
                    $("#company_id").val(company.id);   
                    if ($.fn.DataTable.isDataTable("#ListView")) {//First check if dataTable exist or not, if it does then destroy dataTable and recreate it		 
                        $('#ListView').DataTable().clear().destroy();
                    }
                    var table = $('#ListView').DataTable( {
                        data: list,
                        lengthChange:false,
                        paging:false,
                        searching:false,
                        info:false,
                        ordering:false,
                        dom: 'Bfrtip',
                        buttons: [{
                            init: function(api, node, config) {
                                $("#ListView").removeClass("dt-buttons");
                            }
                        }],
                        columnDefs: [ 
                            {
                                targets:0,
                                visible: false 
                            },
                            {
                                targets:3,
                                className : "text-right" 
                            },
                        ], 
                        footerCallback: function( tfoot, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display;

                            api.columns([3], { page: 'current'}).every(function() {
                                    var sum = this
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );			
                                    
                                    $(this.footer()).html(numFormat(sum));
                            }); 
                            
                        },                               
                    });      
                    $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();                                                 
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
