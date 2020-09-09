@extends('layouts.admin')
@section('content')
<?php 
$tabContent = "<div class='row form-group'>
                    <div class='col-sm-3'>
                        <label for='risk_location'>Location <span style='color:red;'>*</span></label>
                        <input name='risk_location[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-3'>
                        <label for='risk_address'>Address <span style='color:red;'>*</span></label>
                        <input name='risk_address[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-3'>
                        <label for='risk_description'>Occupation/BTC & Description <span style='color:red;'>*</span></label>
                        <input name='risk_description[{index}]' class='form-control'>
                    </div>   
                    <div class='col-sm-3'>
                        <label for='risk_construction_code'>Construction Code & Description <span style='color:red;'>*</span></label>
                        <input name='risk_construction_code[{index}]' class='form-control'>
                    </div>           
                </div>
                <hr>
                <label><b>Interest Insured</b></label>
                <div class='row form-group'>
                    <div class='col-sm-2'>
                        <label for='ins_item_no'>Item No.<span style='color:red;'>*</span></label>
                        <input name='ins_item_no[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-7'>
                        <label for='ins_desc'>Interest Description<span style='color:red;'>*</span></label>
                        <input name='ins_desc[{index}]' class='form-control'>
                    </div>   
                    <div class='col-sm-2'>
                        <label for='ins_sum_insured'>Sum Insured (RM) <span style='color:red;'>*</span></label>
                        <input name='ins_sum_insured[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-1 text-right'>                        
                        <input class='btn btn-info btn_interest_insured' name='btn_interest_insured[{index}]' type='button' value='Add'>
                    </div>
                </div>
                <div class='col-sm-12'>
                    <table class='table table-sm' id='interest_table[{index}]'>
                        <thead>
                            <tr>
                                <th>Item No.</th>
                                <th>Interest Description</th>
                                <th>Sum Insured (RM)</th>
                                <th>&nbsp;</th>                                
                            </tr>
                        </thead>
                        <tbody>                            
                        </tbody>
                    </table>
                </div>
                <hr>
                <label><b>Perils/Extensions/Clauses/Warranties/Memorandum</b></label>
                <div class='row form-group'>
                    <div class='col-sm-2'>
                        <label for='ins_code'>Code<span style='color:red;'>*</span></label>
                        <input name='ins_code[{index}]' class='form-control'>
                    </div>
                    <div class='col-sm-7'>
                        <label for='ins_desc_perils'>Description<span style='color:red;'>*</span></label>
                        <input name='ins_desc_perils[{index}]' class='form-control'>
                    </div>   
                    <div class='col-sm-2'>
                        <label for='ins_rate'>Rate (%)<span style='color:red;'>*</span></label>
                        <input name='ins_rate[{index}]' class='form-control'>
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
            <input type='hidden' id='interest_insured' name='interest_insured' value=''>
            <input type='hidden' id='perils' name='perils' value=''> 
            <input type='hidden' id='ins_id' name='ins_id' value='{{$insurance->id}}'> 
			@csrf
            @method('PUT')
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="title">Insurance Agent <span style="color:red;">*</span></label>
                    <select name="ins_agent" id="ins_agent" class="form-control select2">
                        @foreach($agent as $id => $ag)
                            <option value="{{ $id }}" {!! old('ins_agent', $insurance->ins_agent) == $id ? 'selected="selected"' : '' !!}>{{ $ag }}</option>
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
                <div class="col-sm-3">
                    <label for="ins_policy_no">Policy No. <span style="color:red;">*</span></label>
                    <input name="ins_policy_no" id="ins_policy_no" class="form-control" value="{{ old('ins_policy_no', isset($insurance) ? $insurance->ins_policy_no : '') }}">
                </div>                  
            </div>
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_correspond_address">Correspondence Address <span style="color:red;">*</span></label>
                    <input name="ins_correspond_address" id="ins_correspond_address" class="form-control" value="{{ old('ins_correspond_address', isset($insurance) ? $insurance->ins_correspond_address : '') }}">
                </div>
                <div class="col-sm-6">
                    <label for="ins_self_rating">Self Rating</label>
                    <input name="ins_self_rating" id="ins_self_rating" class="form-control" value="{{old('ins_self_rating',isset($insurance) ? $insurance->ins_self_rating : '') }}">
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="ins_date_start">Date start <span style="color:red;">*</span></label>
                    <input name="ins_date_start" id="ins_date_start" class="form-control date" value="{{ old('ins_date_start', isset($insurance) ? $insurance->ins_date_start : '') }}">
                </div>
                <div class="col-sm-3">
                    <label for="ins_date_end">Date end <span style="color:red;">*</span></label>
                    <input name="ins_date_end" id="ins_date_end" class="form-control date" value="{{ old('ins_date_end', isset($insurance) ? $insurance->ins_date_end : '') }}">
                </div>  
                <div class="col-sm-3">
                    <label for="ins_issuing_branch">Issuing Branch</label>
                    <input name="ins_issuing_branch" id="ins_issuing_branch" class="form-control" value="{{ old('ins_issuing_branch', isset($insurance) ? $insurance->ins_issuing_branch : '') }}">
                </div>
                <div class="col-sm-3">
                    <label for="ins_issuing_date">Issuing date</label>
                    <input name="ins_issuing_date" id="ins_issuing_date" class="form-control date" value="{{ old('ins_issuing_date', isset($insurance) ? $insurance->ins_issuing_date : '') }}">
                </div>                      
            </div>              
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="ins_gross_premium">Gross Premium </label>
                    <input name="ins_gross_premium" id="ins_gross_premium" class="form-control" value="{{ old('ins_gross_premium', isset($insurance) ? $insurance->ins_gross_premium : '') }}">
                </div>
                <div class="col-sm-3">
                    <label for="ins_service_tax">Service Tax</label>
                    <input name="ins_service_tax" id="ins_service_tax" class="form-control" value="{{ old('ins_service_tax', isset($insurance) ? $insurance->ins_service_tax : '') }}">
                </div>   
                <div class="col-sm-3">
                    <label for="ins_stamp_duty">Stamp Duty</label>
                    <input name="ins_stamp_duty" id="ins_stamp_duty" class="form-control" value="{{ old('ins_stamp_duty', isset($insurance) ? $insurance->ins_stamp_duty : '') }}">
                </div>
                <div class="col-sm-3">
                    <label for="ins_total_sum_insured">Total Sum Insured (RM) <span style="color:red;">*</span></label>
                    <input name="ins_total_sum_insured" id="ins_total_sum_insured" class="form-control" value="{{ old('ins_total_sum_insured', isset($insurance) ? $insurance->ins_total_sum_insured : '') }}">
                </div>                  
            </div>
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_remark">Remarks</label>
                    <textarea name="ins_remark" id="ins_remark" class="form-control" rows="2" cols="40">{{ old('ins_remark',isset($insurance) ? $insurance->ins_remark : '') }}</textarea>
                </div> 
            </div>
            <!-- Risk!-->
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
                @foreach($risk as $key => $risk_data)   
                <div class='tab-pane fade show {{ $risk_data->id == $lowest_index ? "active" : "" }}' id='tab-{{$risk_data->id}}' role='tabpanel' aria-labelledby='{{$risk_data->id}}'> 
					<div class='row form-group'>
						<div class='col-sm-3'>
							<label for='risk_location'>Location <span style='color:red;'>*</span></label>
							<input name='risk_location[{{$risk_data->id}}]' class='form-control' value="{{$risk_data->risk_location}}">
						</div>
						<div class='col-sm-3'>
							<label for='risk_address'>Address <span style='color:red;'>*</span></label>
							<input name='risk_address[{{$risk_data->id}}]' class='form-control'  value="{{$risk_data->risk_address}}">
						</div>
						<div class='col-sm-3'>
							<label for='risk_description'>Occupation/BTC & Description <span style='color:red;'>*</span></label>
							<input name='risk_description[{{$risk_data->id}}]' class='form-control'  value="{{$risk_data->risk_description}}">
						</div>   
						<div class='col-sm-3'>
							<label for='risk_construction_code'>Construction Code & Description <span style='color:red;'>*</span></label>
							<input name='risk_construction_code[{{$risk_data->id}}]' class='form-control' value="{{$risk_data->risk_construction_code}}">
						</div>           
					</div>
					<hr>
                	<label><b>Interest Insured</b></label>
                	<div class='row form-group'>
	                    <div class='col-sm-2'>
	                        <label for='ins_item_no'>Item No.<span style='color:red;'>*</span></label>
	                        <input name='ins_item_no[{{$risk_data->id}}]' class='form-control'>
	                    </div>
	                    <div class='col-sm-7'>
	                        <label for='ins_desc'>Interest Description<span style='color:red;'>*</span></label>
	                        <input name='ins_desc[{{$risk_data->id}}]' class='form-control'>
	                    </div>   
	                    <div class='col-sm-2'>
	                        <label for='ins_sum_insured'>Sum Insured (RM) <span style='color:red;'>*</span></label>
	                        <input name='ins_sum_insured[{{$risk_data->id}}]' class='form-control'>
	                    </div>
	                    <div class='col-sm-1 text-right'>                        
	                        <input class='btn btn-info btn_interest_insured' name='btn_interest_insured[{{$risk_data->id}}]' type='button' value='Add'>
	                    </div>
	                </div>
                	<div class='col-sm-12'>
	                    <table class='table table-sm' id='interest_table[{{$risk_data->id}}]'>
	                        <thead>
	                            <tr>
	                                <th>Item No.</th>
	                                <th class="text-center">Interest Description</th>
	                                <th class="text-right">Sum Insured (RM)</th>
	                                <th width="5%">&nbsp;</th>                                
	                            </tr>
	                        </thead>
	                        <tbody>  
	                        @foreach($interest_insured[$risk_data->id] as $ins_insured)
                                @foreach($ins_insured as $data)                                                         
                                    <tr>
                                        <td>{{$data->ii_item_no}}</td>
                                        <td>{{$data->ii_description}}</td>
                                        <td class="text-right">{{number_format($data->ii_sum_insured,2)}}</td>
                                        <td class="text-right">                                        	
				                            @can('interest_insured_edit')
				                                <span id="{{$data->id}}" data-toggle="modal" class="edit_data_interest" data-target="#editItemInterest"><i class="fas fa-edit"></i></span>
				                            @endcan

				                            @can('interest_insured_delete')
				                                   <span id="{{$data->id}}" data-toggle="modal" class="delete_data_ii" data-target="#deleteItemInsured"><i class="fas fa-trash-alt"></i></span>
				                            @endcan
                                        </td>
                                    </tr>
                                @endforeach  
                            @endforeach                            
	                        </tbody>
	                    </table>
	                </div>
	                <hr>
	                <label><b>Perils/Extensions/Clauses/Warranties/Memorandum</b></label>
	                <div class='row form-group'>
	                    <div class='col-sm-2'>
	                        <label for='ins_code'>Code<span style='color:red;'>*</span></label>
	                        <input name='ins_code[{{$risk_data->id}}]' class='form-control'>
	                    </div>
	                    <div class='col-sm-7'>
	                        <label for='ins_desc_perils'>Description<span style='color:red;'>*</span></label>
	                        <input name='ins_desc_perils[{{$risk_data->id}}]' class='form-control'>
	                    </div>   
	                    <div class='col-sm-2'>
	                        <label for='ins_rate'>Rate (%)<span style='color:red;'>*</span></label>
	                        <input name='ins_rate[{{$risk_data->id}}]' class='form-control'>
	                    </div>
	                    <div class='col-sm-1 text-right'>                        
	                        <input class='btn btn-info btn_perils' name='btn_perils[{{$risk_data->id}}]' type='button' value='Add'>
	                    </div>
	                </div>
	                <div class='col-sm-12'>
	                    <table class='table table-sm' id='perils_table[{{$risk_data->id}}]'>
	                        <thead>
	                            <tr>
	                                <th>Code</th>
	                                <th class="text-center">Description</th>
	                                <th class="text-right">Rate (%)</th>
	                                <th width="5%">&nbsp;</th>                                
	                            </tr>
	                        </thead>
	                        <tbody>   
	                        @foreach($perils[$risk_data->id] as $peril)
	                            @foreach($peril as $data)                                                         
	                                <tr>
	                                    <td>{{$data->prls_ref_no}}</td>
	                                    <td>{{$data->prls_description}}</td>
	                                    <td class="text-right">{{$data->prls_rate}}</td>
	                                    <td class="text-right">                                        	
				                            @can('perils_edit')
				                                <span id="{{$data->id}}" data-toggle="modal" class="edit_data_perils" data-target="#editItemPerils"><i class="fas fa-edit"></i></span>
				                            @endcan

				                            @can('perils_delete')
				                                   <span id="{{$data->id}}" data-toggle="modal" class="delete_data_perils" data-target="#deleteItemPerils"><i class="fas fa-trash-alt"></i></span>
				                            @endcan
                                        </td>
	                                </tr>
	                            @endforeach  
	                        @endforeach                          
	                        </tbody>
	                    </table>
	                </div>  
                </div>
                @endforeach
            </div>
            <!-- Interest insured!-->
            <!-- Perils!-->      
             <!-- save form-->
            <div style="text-align: center;">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.update') }}">
            </div>  	
        </form>
    </div>
</div>
<!-- Modal edit interest insured  -->
<div id="editItemInterest" class="modal fade">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Interest Insured</h4>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="update_interest_insured">                
                <input type="hidden" id="ins_id" name="ins_id" value="{{$insurance->id}}">
                <input type="hidden" id="ii_id" name="ii_id" value="">
                <div class="form-group row col-sm-12">
                    <label for="ii_item_no" class=" form-control-label">Item No.</label>
                    <input type="text" id="ii_item_no" name="ii_item_no" class="form-control">
                </div>
                <div class="form-group row col-sm-12">
                    <label for="ii_description" class=" form-control-label">Interest Description</label>
                    <input type="text" id="ii_description" name="ii_description" class="form-control">                    
                </div> 
                <div class="form-group row col-sm-12">
                    <label for="ii_sum_insured" class=" form-control-label">Sum Insured (RM)</label>
                    <input type="text" id="ii_sum_insured" name="ii_sum_insured" class="form-control">                    
                </div>                           
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                    <input type="text" id="prls_description" name="prls_description" class="form-control">                    
                </div> 
                <div class="form-group row col-sm-12">
                    <label for="prls_rate" class=" form-control-label">Rate (%)</label>
                    <input type="text" id="prls_rate" name="prls_rate" class="form-control">                    
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
<!-- Modal delete interest insured!-->
<div class="modal fade" id="deleteItemInsured">
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
            <button type="button" id="delete_record_ii" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

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
        //insurance id 
        var insurance_id = {!! json_encode($insurance->id) !!};
        //exisitng risk
        var risk_count = {!! json_encode($risk_count) !!};
        //get the highest tab index
        var highest_index = {!! json_encode($highest_index) !!};

        select_agent.data('select2').$selection.css('height', '35px');
        select_agent.data('select2').$selection.css('border', '1px solid #e4e7ea');     
        select_comp.data('select2').$selection.css('height', '35px');
        select_comp.data('select2').$selection.css('border', '1px solid #e4e7ea');    

        //get the default first row        
        tabCounter = highest_index+1;        
        // addTab();
        var dummyTabCount = risk_count +1;

        var tabs = $( "#tabs" ).tab();
        $("form[name=insuranceFormUpdate]").on( 'click', "input.btn_interest_insured", function(event) {
            var name = $(this).attr('name');
            var number_matches = name.match(/[0-9]+/g);
            var index = number_matches[0];    
            if($("input[name=ins_item_no\\["+index+"\\]]").val() == ''){
                alert('Item number is required!');
                $( "input[name=ins_item_no\\["+index+"\\]]" ).focus();
            } 
            else if($("input[name=ins_desc\\["+index+"\\]]").val() == ''){
                alert('Description is required!');
                $("input[name=ins_desc\\["+index+"\\]]").focus();
            } 
            else if($("input[name=ins_sum_insured\\["+index+"\\]]").val() == ''){
                alert('Sum insured is required!');
                $("input[name=ins_sum_insured\\["+index+"\\]]").focus();
            }
            else{
                addRowInterest(index);
            }       
                             
        });
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
        $('#insuranceFormUpdate').on('submit',function(event){           
            event.preventDefault();
            $("#interest_insured").val(JSON.stringify(TABLE_INTEREST_DATA));
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

        $('#update_interest_insured').on('submit', function(event){
            event.preventDefault();
            var data = $('#update_interest_insured').serialize();
            console.log(data);
            $.ajax({
                url: "/admin/interest_insured/update",
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

        //interest insured modal
        $(document).on('click', '.edit_data_interest', function(){
            var id = $(this).attr("id");
            $.ajax({
                url:"/admin/interest_insured/retrieve",
                method:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    id:id
                },
                success:function(data){
                console.log(data);                                                         
                    $('#ii_id').val(id);                   
                    $('#ii_item_no').val(data.ii_item_no);  
                    $('#ii_description').val(data.ii_description);     
                    $('#ii_sum_insured').val(data.ii_sum_insured);     
                    $('#editItemInterest').modal('show');
                }
            });
        }); 
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
                    $('#prls_ref_no').val(data.prls_ref_no);  
                    $('#prls_description').val(data.prls_description);     
                    $('#prls_rate').val(data.prls_rate);     
                    $('#editItemPerils').modal('show');
                }
            });
        }); 

        //delete item interest insured
        $(document).on('click', '.delete_data_ii', function(){
            var id = $(this).attr("id");            
            $('#delete_record_ii').data('id', id); //set the data attribute on the modal button
        });

        $( "#delete_record_ii" ).click( function() {
            var ID = $(this).data('id');
            $.ajax({
                url:"/admin/insurance/interest_insured/destroy",
                method:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    id:ID,
                    ins_id:insurance_id
                },
                success:function(response){
                    $('#deleteItemInsured').modal('hide');     
                    window.location=response.url;
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

        function addRowInterest(index){  
            var indexInterestObj = {};         
            var item_no = $("input[name=ins_item_no\\["+index+"\\]]").val();
            var desc = $("input[name=ins_desc\\["+index+"\\]]").val();
            var sum_insured = $("input[name=ins_sum_insured\\["+index+"\\]]").val();
            var currentInterestData = new interestData(item_no, desc, sum_insured);
            indexInterestObj[index] = currentInterestData;
            TABLE_INTEREST_DATA.push(indexInterestObj);
 
            var markup = "<tr><td>"+item_no+"</td><td>"+desc+"</td><td>"+sum_insured+"</td></tr>";
            $("#interest_table\\["+index+"\\] tbody").append(markup);
            
            //clear input fields after populated in the table
            $("input[name=ins_item_no\\["+index+"\\]]").val('');
            $("input[name=ins_desc\\["+index+"\\]]").val('');
            $("input[name=ins_sum_insured\\["+index+"\\]]").val('');                
        }
        function addRowPerils(index){   
            var indexPerilsObj = {};           
            var code = $("input[name=ins_code\\["+index+"\\]]").val();
            var desc = $("input[name=ins_desc_perils\\["+index+"\\]]").val();
            var rate = $("input[name=ins_rate\\["+index+"\\]]").val();
            var currentPerilsData = new perilsData(code, desc, rate);
            indexPerilsObj[index] = currentPerilsData;
            TABLE_PERILS_DATA.push(indexPerilsObj);

            var markup = "<tr><td>"+code+"</td><td>"+desc+"</td><td>"+rate+"</td></tr>";
            $("#perils_table\\["+index+"\\] tbody").append(markup);
            
            //clear input fields after populated in the table
            $("input[name=ins_code\\["+index+"\\]]").val('');
            $("input[name=ins_desc_perils\\["+index+"\\]]").val('');
            $("input[name=ins_rate\\["+index+"\\]]").val('');                
        }
        
        function interestData(item_no, desc, sum_insured){
            this.ins_item_no = item_no;
            this.ins_desc = desc;
            this.ins_sum_insured = sum_insured;
        } 

        function perilsData(code, desc, rate){
            this.ins_code = code;
            this.ins_desc_perils = desc;
            this.ins_rate = rate;
        } 


        
    });    
</script>
@endsection