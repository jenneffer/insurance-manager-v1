@extends('layouts.admin')
@section('content')
<?php 
$tabContent = "<div class='row form-group'>
                    <div class='col-sm-6'>
                        <label for='risk_location'>Location <span style='color:red;'>*</span></label>
                        <input name='risk_location[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-6'>
                        <label for='risk_address'>Address <span style='color:red;'>*</span></label>
                        <input name='risk_address[{index}]' class='form-control'>
                    </div>                              
                </div>
                <hr>
                <label><b>Properties Insured</b></label>
                <div class='row form-group'>
                    <div class='col-sm-6'>
                        <label for='properties_insured'>Description<span style='color:red;'>*</span></label>
                        <textarea name='properties_insured[{index}]' class='form-control' row='5'/>
                    </div>
                </div>
                <hr>
                <label><b>Additional items</b></label>
                <div class='row form-group'>
                    <div class='col-sm-2'>
                        <label for='ins_item_no'>Item No.<span style='color:red;'>*</span></label>
                        <input name='ins_code[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-6'>
                        <label for='ins_desc_perils'>Description<span style='color:red;'>*</span></label>
                        <input name='ins_desc_perils[{index}]' class='form-control'>
                    </div>   
                    <div class='col-sm-1'>
                        <label for='ins_rate'>Rate (%)<span style='color:red;'>*</span></label>
                        <input name='ins_rate[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-2'>
                        <label for='ins_sum_insured'>Sum Insured (RM)<span style='color:red;'>*</span></label>
                        <input name='ins_sum_insured[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-1 text-right'>                        
                        <input class='btn btn-info btn_perils' name='btn_perils[{index}]' type='button' value='Add'>
                    </div>
                </div>
                <div class='col-sm-12'>
                    <table class='table table-sm' id='perils_table[{index}]'>
                        <thead>
                            <tr>
                                <th>Item No.</th>
                                <th>Description</th>
                                <th style='text-align:right;'>Rate (%)</th>
                                <th style='text-align:right;'>Sum Insured (RM)</th>                                
                            </tr>
                        </thead>
                        <tbody>                            
                        </tbody>
                    </table>
                </div>                
                ";
?>
<div class="card">
    <div class="card-header">
        {{ trans('global.renew') }} Insurance with Additional Risk 
    </div>

    <div class="card-body">
        <form name='InsuranceRenewalForm' id='InsuranceRenewalForm'>
            <input type='hidden' id='perils' name='perils' value=''>
            @csrf            
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_policy_no">Policy No. <span style="color:red;">*</span></label>
                    <input name="ins_policy_no" id="ins_policy_no" class="form-control" required="required">
                </div> 
                <div class="col-sm-6">
                    <label for="ins_total_sum_insured">Total Sum Insured (RM) <span style="color:red;">*</span></label>
                    <input name="ins_total_sum_insured" id="ins_total_sum_insured" class="form-control" required="required">
                </div> 
            </div> 
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_date_start">Date start <span style="color:red;">*</span></label>
                    <input name="ins_date_start" id="ins_date_start" class="form-control date" required="required">
                </div>
                <div class="col-sm-6">
                    <label for="ins_date_end">Date end <span style="color:red;">*</span></label>
                    <input name="ins_date_end" id="ins_date_end" class="form-control date" required="required">
                </div>
            </div> 
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_remark">Remarks</label>
                    <textarea name="ins_remark" id="ins_remark" class="form-control" rows="2" cols="40"></textarea>
                </div>
            </div>   

            <!-- Insurance add risk tab -->
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="tabs" role="tablist"> 
                @foreach($risk as $key => $risk_data)               
                <li class="nav-item">
                    <a class='nav-link {{ $risk_data->id == $lowest_index ? "active" : "" }}' data-toggle='tab' id='{{$risk_data->id}}' href='#tab-{{$risk_data->id}}' role='tab' aria-controls="+id+" aria-selected='true'>RISK {{$key +1}}</a>
                </li>   
                @endforeach                
                <li class="nav-item">
                    <button class="btn alt-btn-black alt-btn newTravelLegButton" type="button" >
                    <i class="fa fa-plus" aria-hidden="true" color="black"></i>
                    </button>
                </li>             
            </ul>
            <div class="tab-content" id="myTabContent">  
                <input type="hidden" name="risk_id" value="{{$risk_data->id}}">               
                @foreach($risk as $key => $risk_data) 
                <div class='tab-pane fade show {{ $risk_data->id == $lowest_index ? "active" : "" }}' id='tab-{{$risk_data->id}}' role='tabpanel' aria-labelledby='{{$risk_data->id}}'> 
                    <div class='row form-group'>                        
                        <div class='col-sm-6'>
                            <label for='risk_location'>Location<span style='color:red;'>*</span></label>
                            <input name='risk_location[{{$risk_data->id}}]' class='form-control' value="{{$risk_data->risk_location}}">
                        </div>
                        <div class='col-sm-6'>
                            <label for='risk_address'>Address <span style='color:red;'>*</span></label>
                            <input name='risk_address[{{$risk_data->id}}]' class='form-control'  value="{{$risk_data->risk_address}}">
                        </div>                                                      
                    </div>  
                    <div class='row form-group'>
                        <div class='col-sm-6'>
                            <label for='properties_insured'>Property Insured <span style='color:red;'>*</span></label>
                            <textarea name='properties_insured[{{$risk_data->id}}]' class='form-control' >{{$risk_data->risk_description}}</textarea>
                        </div> 
                    </div>              
                    <hr>
                    <label><b>Additional Items</b></label>
                    <div class='row form-group'>
                        <div class='col-sm-2'>
                            <label for='ins_code'>Item No.<span style='color:red;'>*</span></label>
                            <input name='ins_code[{{$risk_data->id}}]' class='form-control'>
                        </div>
                        <div class='col-sm-6'>
                            <label for='ins_desc_perils'>Description<span style='color:red;'>*</span></label>
                            <input name='ins_desc_perils[{{$risk_data->id}}]' class='form-control'>
                        </div>   
                        <div class='col-sm-1'>
                            <label for='ins_rate'>Rate (%)<span style='color:red;'>*</span></label>
                            <input name='ins_rate[{{$risk_data->id}}]' class='form-control'>
                        </div>
                        <div class='col-sm-2'>
                            <label for='ins_sum_insured'>Sum Insured(RM)<span style='color:red;'>*</span></label>
                            <input name='ins_sum_insured[{{$risk_data->id}}]' class='form-control'>
                        </div>
                        <div class='col-sm-1 text-right'>                        
                            <input class='btn btn-info btn_perils' name='btn_perils[{{$risk_data->id}}]' type='button' value='Add'>
                        </div>
                    </div>
                    <div class='col-sm-12'>
                        <table class='table table-sm' id='perils_table[{{$risk_data->id}}]'>
                            <thead>
                                <tr>
                                    <th width="8%">Item No.</th>
                                    <th class="text-center" width="60%">Description</th>
                                    <th class="text-right" width="10%">Rate (%)</th>
                                    <th class="text-right" width="18%">Sum Insured (RM)</th>  
                                    <th width="5%">&nbsp;</th>                              
                                </tr>
                            </thead>
                            <tbody>   
                            @foreach($perils[$risk_data->id] as $peril)
                                @foreach($peril as $data)                                                         
                                    <tr>
                                        <td>{{$data->ref_no}}</td>
                                        <td>{{$data->description}}</td>
                                        <td class="text-right">{{$data->rate}}</td>
                                        <td class="text-right">{{number_format($data->sum_insured,2)}}</td>
                                        <td class="text-right">&nbsp;</td>
                                    </tr>
                                @endforeach  
                            @endforeach                          
                            </tbody>
                        </table>
                    </div>  
                </div>
                @endforeach
            </div>
            <br>
            <div class="d-flex justify-content-center">
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
                &nbsp;&nbsp;&nbsp;
                <a class="btn btn-default" href="{{url()->previous()}}">
                    Go Back   
                </a>
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    // var TABLE_INTEREST_DATA = [];
    var TABLE_PERILS_DATA = [];
    $(document).ready(function(){    
        var risk_count = {!! json_encode($risk_count) !!};
        //get the highest tab index
        var highest_index = {!! json_encode($highest_index) !!};
        //get the default first row              
        tabCounter = highest_index+1;        
        var dummyTabCount = risk_count +1;

        var tabs = $( "#tabs" ).tab();

        $("form[name=InsuranceRenewalForm]").on( 'click', "input.btn_perils", function(event) {
            var name = $(this).attr('name');
            var number_matches = name.match(/[0-9]+/g);
            var index = number_matches[0];                                           
            addRowPerils(index);                 
        });
        
        $('.newTravelLegButton').click(function(){
            addTab();
            tabs.tab({ active: -1 }); //activate the newly created tab
        });

        $(".nav-tabs").on("click", "a", function(e){
            e.preventDefault();
            $(this).tab('show');           
        
        }).on("click", "span", function () {
            var anchor = $(this).siblings('a');
            $(anchor.attr('href')).remove();
            $(this).parent().remove();
            $(".nav-tabs li").children('a').first().click();
        });  

        //submit form
        $('#InsuranceRenewalForm').on('submit',function(event){           
            event.preventDefault();
            var insurance_id = {!! json_encode($ins_id) !!};   
            // $("#interest_insured").val(JSON.stringify(TABLE_INTEREST_DATA));
            $("#perils").val(JSON.stringify(TABLE_PERILS_DATA));
            var insuranceData = $('#InsuranceRenewalForm').serialize();
            $.ajax({
                url: "/admin/insurance/update_renewal_add",
                type:"POST",               
                data:{
                    "_token": "{{ csrf_token() }}",
                    data: insuranceData,
                    ins_id: insurance_id                    
                },                
                success:function(response){
                    window.location=response.url;
                },
            });
        });    
        function addRowPerils(index){   
            var indexPerilsObj = {};           
            var code = $("input[name=ins_code\\["+index+"\\]]").val();
            var desc = $("input[name=ins_desc_perils\\["+index+"\\]]").val();
            var rate = $("input[name=ins_rate\\["+index+"\\]]").val();
            var sum_insured = $("input[name=ins_sum_insured\\["+index+"\\]]").val();
            var currentPerilsData = new perilsData(code, desc, rate, sum_insured);
            indexPerilsObj[index] = currentPerilsData;
            TABLE_PERILS_DATA.push(indexPerilsObj);

            var markup = "<tr><td>"+code+"</td><td>"+desc+"</td><td style='text-align:right;'>"+rate+"</td><td style='text-align:right;'>"+sum_insured+"</td></tr>";
            $("#perils_table\\["+index+"\\] tbody").append(markup);
            
            //clear input fields after populated in the table
            $("input[name=ins_code\\["+index+"\\]]").val('');
            $("input[name=ins_desc_perils\\["+index+"\\]]").val('');
            $("input[name=ins_rate\\["+index+"\\]]").val('');
            $("input[name=ins_sum_insured\\["+index+"\\]]").val('');                
        }

        function addTab(){            
            var id = "tab-" + tabCounter;
            var liTabId = tabCounter;

            $('#tabs a').removeClass('active');
            $('.tab-pane').removeClass('active');
            tabHTML = "<li class='nav-item'>";
            tabHTML += "<a class='nav-link active' data-toggle='tab' id='"+liTabId+"' href='#"+id+"' role='tab' aria-controls="+id+" aria-selected='true'>RISK "+dummyTabCount+"</a><span>x</span>";
            tabHTML += "</li>";
            lastLI = $('.newTravelLegButton').closest('li');
            $( tabHTML ).insertBefore( lastLI );
            var riskTab = <?= json_encode(utf8_encode($tabContent))?>;  
            var contentHTML = riskTab.replace( /{index}/g, tabCounter );       
            $('.tab-content').append( "<div class='tab-pane fade show active' id='"+id+"' role='tabpanel' aria-labelledby='"+liTabId+"'>"+contentHTML+"</div>" );            
            tabCounter++; 
            dummyTabCount++;          
        }      
        
        // function interestData(item_no, desc, sum_insured){
        //     this.ins_item_no = item_no;
        //     this.ins_desc = desc;
        //     this.ins_sum_insured = sum_insured;
        // } 

        function perilsData(code, desc, rate, sum_insured){
            this.ins_code = code;
            this.ins_desc_perils = desc;
            this.ins_rate = rate;
            this.ins_sum_insured = sum_insured;
        } 


        
    });    
</script>
@endsection