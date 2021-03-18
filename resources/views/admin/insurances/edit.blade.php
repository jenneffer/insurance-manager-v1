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
                <div class='row form-group'>
                    <div class='col-sm-6'>
                        <label for='properties_insured'>Property Insured <span style='color:red;'>*</span></label>
                        <textarea name='properties_insured[{index}]' class='form-control' row='5'/>
                    </div>  
                </div>
                <hr>
                <label><b>Additional Items</b></label>
                <div class='row form-group'>
                    <div class='col-sm-2'>
                        <label for='ins_code'>Code<span style='color:red;'>*</span></label>
                        <input name='ins_code[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-6'>
                        <label for='ins_desc_perils'>Description<span style='color:red;'>*</span></label>
                        <input name='ins_desc_perils[{index}]' class='form-control'>
                    </div>   
                    <div class='col-sm-1'>
                        <label for='ins_rate'>Rate (%)</label>
                        <input name='ins_rate[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-2'>
                        <label for='ins_sum_insured'>SUm Insured (RM)<span style='color:red;'>*</span></label>
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
                                <th>Code</th>
                                <th>Description</th>
                                <th>Rate (%)</th>
                                <th>Sum Insured (RM)</th>
                                <th>&nbsp;</th>                                
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
        {{ trans('global.edit') }} {{ trans('cruds.insurance.title_singular') }}
    </div>

    <div class="card-body">
        <form name='insuranceFormUpdate' id='insuranceFormUpdate'>            
            <input type='hidden' id='perils' name='perils' value=''> 
            <input type='hidden' id='ins_id' name='ins_id' value='{{$insurance->id}}'>
			@csrf
            @method('PUT')
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="title">Agent <span style="color:red;">*</span></label>
                    <select name="ins_agent" id="ins_agent" class="form-control select2">
                        @foreach($agent as $id => $ag)
                            <option value="{{ $id }}" {!! old('ins_agent', $insurance->ins_agent) == $id ? 'selected="selected"' : '' !!}>{{ $ag }}</option>
                        @endforeach
                    </select>  
                </div>
                <div class="col-sm-3">
                    <label for="title">Insurance Company <span style="color:red;">*</span></label>
                    <select name="insurance_comp_id" id="insurance_comp_id" class="form-control select2">
                        @foreach($insuranceCompany as $id => $ins_comp)
                            <option value="{{ $id }}" {!! old('insurance_comp_id', $insurance->insurance_comp_id) == $id ? 'selected="selected"' : '' !!}>{{ $ins_comp }}</option>
                        @endforeach
                    </select>  
                </div>
                <div class="col-sm-3 {{ $errors->has('company_id') ? 'has-error' : '' }}">
                    <label for="company">Policy Holder <span style="color:red;">*</span></label>
                    <select name="company_id" id="company_id" class="form-control select2">
                        @foreach($company as $id => $comp)
                            <!-- <option value="{{ $id }}" {{ (isset($insurance) && $insurance->comp ? $insurance->comp->id : old('ins_company')) == $id ? 'selected' : '' }}>{{ $comp }}</option> -->
                            <option value="{{ $id }}" {!! old('company_id', $insurance->ins_company) == $id ? 'selected="selected"' : '' !!}>{{ $comp }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('company_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('company_id') }}
                        </em>
                    @endif
                </div>
                <div class="col-sm-3">
                    <label for="ins_class">Product <span style="color:red;">*</span></label>
                    <input name="ins_class" id="ins_class" class="form-control" value="{{ old('ins_class', isset($insurance) ? $insurance->ins_class : '') }}">
                </div>
            <!-- policy no-->                 
            </div>
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_correspond_address">Correspondence Address</label>
                    <input name="ins_correspond_address" id="ins_correspond_address" class="form-control" value="{{ old('ins_correspond_address', isset($insurance) ? $insurance->ins_correspond_address : '') }}">
                </div>
                <div class="col-sm-3">
                    <label for="ins_issuing_branch">Issuing Branch</label>
                    <input name="ins_issuing_branch" id="ins_issuing_branch" class="form-control" value="{{ old('ins_issuing_branch', isset($insurance) ? $insurance->ins_issuing_branch : '') }}">
                </div>
                <div class="col-sm-3">
                    <label for="ins_issuing_date">Issuing date</label>
                    <input name="ins_issuing_date" id="ins_issuing_date" class="form-control date" value="{{ old('ins_issuing_date', isset($insurance) ? $insurance->ins_issuing_date : '') }}">
                </div>  
            <!--self rating-->
            </div>
            <!-- insurance by year-->            
            <ul class="nav nav-tabs" id="tabs" role="tablist">                 
                @foreach($ins_details as $key => $ins_data)                                              
                <li class="nav-item">
                    <a class='nav-link {{ $loop->last ? "active" : "" }}' data-toggle='tab' id='{{$key}}' href='#tab-{{$key}}' role='tab' aria-controls="+id+" aria-selected='true'>YEAR {{$key}}</a>
                </li>   
                @endforeach                          
            </ul>
            <div class="tab-content" id="myTabContent">            
                @foreach($ins_details as $year => $ins_data) 
                <input type="hidden" name="ins_details_id[{{$year}}]" value="{{$ins_data['id']}}">
                <div class='tab-pane fade show {{ $loop->last ? "active" : "" }}' id='tab-{{$year}}' role='tabpanel' aria-labelledby='{{$key}}'> 
                    <div class='row form-group'>
                        <div class='col-sm-3'>
                            <label for='policy_no'>Policy No. <span style='color:red;'>*</span></label>
                            <input name='policy_no[{{$year}}]' class='form-control' value="{{$ins_data['policy_no']}}">
                        </div>
                        <div class='col-sm-3'>
                            <label for='date_start'>Date start<span style='color:red;'>*</span></label>
                            <input name='date_start[{{$year}}]' class='form-control date'  value="{{$ins_data['date_start']}}">
                        </div>
                        <div class='col-sm-3'>
                            <label for='date_end'>Date end<span style='color:red;'>*</span></label>
                            <input name='date_end[{{$year}}]' class='form-control date'  value="{{$ins_data['date_end']}}">
                        </div> 
                        <div class='col-sm-3'>
                            <label for='sum_insured'>Sum Insured(RM)<span style='color:red;'>*</span></label>
                            <input name='sum_insured[{{$year}}]' class='form-control'  value="{{number_format($ins_data['sum_insured'],2)}}">
                        </div>                               
                    </div>                     
                    <div class='row form-group'>
                        <div class='col-sm-3'>
                            <label for='gross_premium'>Gross Premium(RM)</label>
                            <input name='gross_premium[{{$year}}]' class='form-control' value="{{number_format($ins_data['gross_premium'],2)}}">
                        </div>
                        <div class='col-sm-3'>
                            <label for='service_tax'>Service Tax(RM)</label>
                            <input name='service_tax[{{$year}}]' class='form-control'  value="{{number_format($ins_data['service_tax'],2)}}">
                        </div>
                        <div class='col-sm-3'>
                            <label for='stamp_duty'>Stamp Duty(RM)</label>
                            <input name='stamp_duty[{{$year}}]' class='form-control'  value="{{number_format($ins_data['stamp_duty'],2)}}">
                        </div>
                        <div class='col-sm-3'>
                            <label for='rate'>Rate(%)</label>
                            <input name='rate[{{$year}}]' class='form-control' value="{{$ins_data['self_rating']}}">
                        </div>                                
                    </div>  

                    <div class='row form-group'>                        
                        <div class='col-sm-6'>
                            <label for='remark'>Remark</label>
                            <textarea name='remark[{{$year}}]' class='form-control' rows="3">{{$ins_data['remark']}}</textarea>
                        </div>
                        <div class='col-sm-3'>
                            <label for='excess'>Excess</label>
                            <input name='excess[{{$year}}]' class='form-control'  value="{{$ins_data['excess']}}">
                        </div>
                        <div class='col-sm-3'>
                            <label for='policy_status'>Policy Status</label>
                            <select name="policy_status[{{$year}}]" id="policy_status" class="form-control select2">
                            @foreach($arr_policy_status as $key => $value)
                                <!-- <option value="{{ $id }}" {{ (isset($insurance) && $insurance->comp ? $insurance->comp->id : old('ins_company')) == $id ? 'selected' : '' }}>{{ $comp }}</option> -->
                                <option value="{{ $key }}" {!! old('policy_status', $ins_data['policy_status']) == $key ? 'selected="selected"' : '' !!}>{{ $value }}</option>
                            @endforeach
                            </select>                            
                        </div>
                    </div>
                    <hr>  
                    <ul class="nav nav-tabs" id="tabs" role="tablist">  
                        @foreach($risk as $key => $risk_data)               
                        <li class="nav-item">
                            <a class='nav-link {{ $loop->first ? "active" : "" }}' data-toggle='tab' id='{{$risk_data->id}}' href='#tab-{{$risk_data->id}}' role='tab' aria-controls="+id+" aria-selected='true'>RISK {{$key +1}}</a>
                        </li>   
                        @endforeach
                        <!-- <li class="nav-item">
                            <button class="btn alt-btn-black alt-btn newTravelLegButton" type="button" >
                            <i class="fa fa-plus" aria-hidden="true" color="black"></i>
                            </button>
                        </li>       -->    
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        @foreach($risk as $key => $risk_data)   
                        <div class='tab-pane fade show {{ $loop->first ? "active" : "" }}' id='tab-{{$risk_data->id}}' role='tabpanel' aria-labelledby='{{$risk_data->id}}'> 
                            <div class='row form-group'>
                                <div class='col-sm-6'>
                                    <label for='risk_location'>Location <span style='color:red;'>*</span></label>
                                    <input name='risk_location[{{$risk_data->id}}]' class='form-control' value="{{$risk_data->risk_location}}">
                                </div>
                                <div class='col-sm-6'>
                                    <label for='risk_address'>Address <span style='color:red;'>*</span></label>
                                    <input name='risk_address[{{$risk_data->id}}]' class='form-control'  value="{{$risk_data->risk_address}}">
                                </div>                                
                            </div>  
                            <div class='row form-group'>
                                <div class='col-sm-6'>
                                    <label for='risk_description'>Description <span style='color:red;'>*</span></label>
                                    <textarea name='risk_description[{{$risk_data->id}}]' class='form-control'>{{$risk_data->risk_description}}</textarea>
                                </div>
                            </div>
                            <hr>                                     
                            <label><b>Additional item(s) insured</b></label>
                            <!-- Unable to add new items unless its new policy number or upon renewal-->
<!--                             <div class='row form-group'>
                                <div class='col-sm-2'>
                                    <label for='ins_code'>Item No.<span style='color:red;'>*</span></label>
                                    <input name='ins_code[{{$risk_data->id}}]' class='form-control'>
                                </div>
                                <div class='col-sm-5'>
                                    <label for='ins_desc_perils'>Description<span style='color:red;'>*</span></label>
                                    <input name='ins_desc_perils[{{$risk_data->id}}]' class='form-control'>
                                </div>   
                                <div class='col-sm-2'>
                                    <label for='ins_rate'>Rate (%)<span style='color:red;'>*</span></label>
                                    <input name='ins_rate[{{$risk_data->id}}]' class='form-control'>
                                </div>
                                <div class='col-sm-2'>
                                    <label for='ins_rate'>Sum Insured (RM)<span style='color:red;'>*</span></label>
                                    <input name='ins_sum_insured[{{$risk_data->id}}]' class='form-control'>
                                </div>
                                <div class='col-sm-1 text-right'>                        
                                    <input class='btn btn-info btn_perils' name='btn_perils[{{$risk_data->id}}]' type='button' value='Add'>
                                </div>
                            </div> -->
                            <div class='col-sm-12'>
                                <table class='table table-sm' id='perils_table[{{$risk_data->id}}]'>
                                    <thead>
                                        <tr>
                                            <th>Item No.</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-right">Rate (%)</th>
                                            <th class="text-right">Sum Insured (RM)</th>
                                            <th width="5%">&nbsp;</th>                                
                                        </tr>
                                    </thead>
                                    <tbody>   
                                    @foreach($perils[$risk_data->id] as $peril)
                                        @foreach($peril as $key => $data)  
                                            @foreach($data as $dat) 
                                            @if($key == $year)                                                       
                                            <tr>
                                                <td>{{$dat->ref_no}}</td>
                                                <td>{{$dat->description}}</td>
                                                <td class="text-right">{{$dat->rate}}</td>
                                                <td class="text-right">{{number_format($dat->sum_insured,2)}}</td>
                                                <td class="text-right">                                         
                                                    @can('perils_edit')
                                                        <span id="{{$dat->id}}" data-toggle="modal" class="edit_data_perils" data-target="#editItemPerils"><i class="fas fa-edit"></i></span>
                                                    @endcan

                                                    @can('perils_delete')
                                                           <span id="{{$dat->id}}" data-toggle="modal" class="delete_data_perils" data-target="#deleteItemPerils"><i class="fas fa-trash-alt"></i></span>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endforeach  
                                    @endforeach                          
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                        @endforeach
                    </div>                                                          
                </div>
                @endforeach
            </div> 
            <!-- Risk!-->
                       
            <!-- Interest insured!-->
            <!-- Perils!-->      
             <!-- save form-->
            <div style="text-align: center;">
                <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" style="margin-top:20px;" class="btn btn-danger" value="{{ trans('global.update') }}"/>                                    
            </div>  
            	
        </form>
    </div>
</div>

<!-- Modal edit perils  -->
<div id="editItemPerils" class="modal fade">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Perils</h4>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="update_perils">                
                <input type="hidden" id="ins_id" name="ins_id" value="{{$insurance->id}}">
                <input type="hidden" id="prls_id" name="prls_id" value="">
                <div class="form-group row col-sm-12">
                    <label for="prls_ref_no" class=" form-control-label">Code</label>
                    <input type="text" id="prls_ref_no" name="prls_ref_no" class="form-control">
                </div>
                <div class="form-group row col-sm-12">
                    <label for="prls_description" class=" form-control-label">Description</label>
                    <textarea id="prls_description" name="prls_description" class="form-control"></textarea>            
                </div> 
                <div class="form-group row col-sm-12">
                    <label for="prls_rate" class=" form-control-label">Rate (%)</label>
                    <input type="text" id="prls_rate" name="prls_rate" class="form-control">                    
                </div>  
                <div class="form-group row col-sm-12">
                    <label for="sum_insured" class=" form-control-label">Sum Insured (RM)</label>
                    <input type="text" id="sum_insured" name="sum_insured" class="form-control">                    
                </div>                         
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary update_data ">Update</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal delete perils!-->
<div class="modal fade" id="deleteItemPerils">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel">Delete Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                   Are you sure you want to delete?
               </p>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" id="delete_record_perils" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
    var TABLE_INTEREST_DATA = [];
    var TABLE_PERILS_DATA = [];
    $(document).ready(function(){    

        //initialize select
        var select_agent = $("#ins_agent").select2({
            selectOnClose: true
        });
        var select_comp = $("#company_id").select2({
            selectOnClose: true
        });
        var select_ins_comp = $("#insurance_comp_id").select2({
            selectOnClose: true
        });     

        var select_policyStatus = $("#policy_status").select2({
            selectOnClose: true
        });   
        //insurance id 
        var insurance_id = {!! json_encode($insurance->id) !!};
        //exisitng risk
        var risk_count = {!! json_encode($risk_count) !!};
        
        select_agent.data('select2').$selection.css('height', '35px');
        select_agent.data('select2').$selection.css('border', '1px solid #e4e7ea');     
        select_comp.data('select2').$selection.css('height', '35px');
        select_comp.data('select2').$selection.css('border', '1px solid #e4e7ea');    
        select_ins_comp.data('select2').$selection.css('height', '35px');
        select_ins_comp.data('select2').$selection.css('border', '1px solid #e4e7ea');
        select_policyStatus.data('select2').$selection.css('height', '35px');
        select_policyStatus.data('select2').$selection.css('border', '1px solid #e4e7ea');

        //get the default first row                     
        // addTab();
        var dummyTabCount = risk_count +1;

        var tabs = $( "#tabs" ).tab();
        $("form[name=insuranceFormUpdate]").on( 'click', "input.btn_perils", function(event) {
            var name = $(this).attr('name');
            var number_matches = name.match(/[0-9]+/g);
            var index = number_matches[0];    
            if($("input[name=ins_code\\["+index+"\\]]").val() == ''){
                alert('Code is required!');
                $( "input[name=ins_code\\["+index+"\\]]" ).focus();
            } 
            else if($("input[name=ins_desc_perils\\["+index+"\\]]").val() == ''){
                alert('Description is required!');
                $("input[name=ins_desc_perils\\["+index+"\\]]").focus();
            } 
            else{
                addRowPerils(index);         
            }                                          
                    
        });
        
        // $('.newTravelLegButton').click(function(){
        //     addTab();
        //     tabs.tab({ active: -1 }); //activate the newly created tab
        // });

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
        $('#insuranceFormUpdate').on('submit',function(event){           
            event.preventDefault();
            $("#perils").val(JSON.stringify(TABLE_PERILS_DATA));
            var insuranceData = $('#insuranceFormUpdate').serialize();
            // console.log(insuranceData);
            $.ajax({
                url: "/admin/insurance/update",
                type:"POST",                
                data:{
                    "_token": "{{ csrf_token() }}",
                    data: insuranceData                    
                },
                success:function(response){
                    window.location=response.url;
                },
            });
        }); 

        $('#update_perils').on('submit', function(event){
            event.preventDefault();
            var data = $('#update_perils').serialize();
            console.log(data);
            $.ajax({
                url: "/admin/perils/update",
                type:"POST",                
                data:{
                    "_token": "{{ csrf_token() }}",
                    data: data                    
                },
                success:function(response){ 
                console.log(response);                   
                    window.location=response.url;
                },
            });
        })
 
        //perils modal
        $(document).on('click', '.edit_data_perils', function(){
            var id = $(this).attr("id");
            $.ajax({
                url:"/admin/perils/retrieve",
                method:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    id:id
                },
                success:function(data){
                console.log(data);                                                         
                    $('#prls_id').val(id);                   
                    $('#prls_ref_no').val(data.ref_no);  
                    $('#prls_description').val(data.description);     
                    $('#prls_rate').val(data.rate);     
                    $('#sum_insured').val(data.sum_insured);     
                    $('#editItemPerils').modal('show');
                }
            });
        }); 

        $(document).on('click', '.delete_data_perils', function(){
            var id = $(this).attr("id");            
            $('#delete_record_perils').data('id', id); //set the data attribute on the modal button
        });

        $( "#delete_record_perils" ).click( function() {
            var ID = $(this).data('id');
            $.ajax({
                url:"/admin/insurance/perils/destroy",
                method:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    id:ID,
                    ins_id:insurance_id
                },
                success:function(response){
                    $('#deleteItemPerils').modal('hide');     
                    window.location=response.url;
                }
            });
        });

        // function addTab(){            
        //     var id = "tab-" + tabCounter;
        //     var liTabId = tabCounter;

        //     $('#tabs a').removeClass('active');
        //     $('.tab-pane').removeClass('active');
        //     tabHTML = "<li class='nav-item'>";
        //     tabHTML += "<a class='nav-link active' data-toggle='tab' id='"+liTabId+"' href='#"+id+"' role='tab' aria-controls="+id+" aria-selected='true'>RISK "+dummyTabCount+"</a><span>x</span>";
        //     tabHTML += "</li>";
        //     lastLI = $('.newTravelLegButton').closest('li');
        //     $( tabHTML ).insertBefore( lastLI );
        //     var riskTab = <?= json_encode(utf8_encode($tabContent))?>;  
        //     var contentHTML = riskTab.replace( /{index}/g, tabCounter );       
        //     $('.tab-content').append( "<div class='tab-pane fade show active' id='"+id+"' role='tabpanel' aria-labelledby='"+liTabId+"'>"+contentHTML+"</div>" );            
        //     tabCounter++; 
        //     dummyTabCount++;          
        // }   

        function addRowPerils(index){   
            var indexPerilsObj = {};           
            var code = $("input[name=ins_code\\["+index+"\\]]").val();
            var desc = $("input[name=ins_desc_perils\\["+index+"\\]]").val();
            var rate = $("input[name=ins_rate\\["+index+"\\]]").val();
            var sum_insured = $("input[name=ins_sum_insured\\["+index+"\\]]").val();
            var currentPerilsData = new perilsData(code, desc, rate ,sum_insured);
            indexPerilsObj[index] = currentPerilsData;
            TABLE_PERILS_DATA.push(indexPerilsObj);

            var markup = "<tr><td>"+code+"</td><td>"+desc+"</td><td>"+rate+"</td><td>"+sum_insured+"</td></tr>";
            $("#perils_table\\["+index+"\\] tbody").append(markup);
            
            //clear input fields after populated in the table
            $("input[name=ins_code\\["+index+"\\]]").val('');
            $("input[name=ins_desc_perils\\["+index+"\\]]").val('');
            $("input[name=ins_rate\\["+index+"\\]]").val('');         
            $("input[name=sum_insured\\["+index+"\\]]").val('');             
        }
        
        function perilsData(code, desc, rate, sum_insured){
            this.ins_code = code;
            this.ins_desc_perils = desc;
            this.ins_rate = rate;
            this.ins_sum_insured = sum_insured;
        } 


        
    });    
</script>
@endsection