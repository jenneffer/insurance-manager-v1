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
        {{ trans('global.create') }} {{ trans('cruds.insurance.title_singular') }}
    </div>

    <div class="card-body">
        <!-- <form name='insuranceForm' id='insuranceForm' action="{{ route("admin.insurances.store") }}" method="POST" enctype="multipart/form-data"> -->
            <form name='insuranceForm' id='insuranceForm'>                           
            @csrf
            <input type='hidden' id='interest_insured' name='interest_insured' value=''>
            <input type='hidden' id='perils' name='perils' value=''> 
            <!-- Insurance basic info -->
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="title">Insurance Agent <span style="color:red;">*</span></label>
                    <select name="ins_agent" id="ins_agent" class="form-control select2" required="required">
                        @foreach($agent as $id => $ag)
                            <option value="{{ $id }}" >{{ $ag }}</option>
                        @endforeach
                    </select>   
                </div>
                <div class="col-sm-3 {{ $errors->has('company_id') ? 'has-error' : '' }}">
                    <label for="company">Policy Holder <span style="color:red;">*</span></label>
                    <select name="company_id" id="company_id" class="form-control select2" required="required">
                        @foreach($company as $id => $comp)
                            <option value="{{ $id }}" {{ (isset($insurance) && $insurance->comp ? $insurance->comp->id : old('company_id')) == $id ? 'selected' : '' }}>{{ $comp }}</option>
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
                    <input name="ins_class" id="ins_class" class="form-control" required="required">
                </div>
                <div class="col-sm-3">
                    <label for="ins_policy_no">Policy No. <span style="color:red;">*</span></label>
                    <input name="ins_policy_no" id="ins_policy_no" class="form-control" required="required">
                </div>                  
            </div>
            <div class="row form-group">
                 <div class="col-sm-6">
                    <label for="ins_correspond_address">Correspondence Address <span style="color:red;">*</span></label>
                    <input name="ins_correspond_address" id="ins_correspond_address" class="form-control" required="required">
                </div>
                <div class="col-sm-6">
                    <label for="ins_self_rating">Self Rating</label>
                    <input name="ins_self_rating" id="ins_self_rating" class="form-control">
                </div>                
            </div>
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="ins_date_start">Date start <span style="color:red;">*</span></label>
                    <input name="ins_date_start" id="ins_date_start" class="form-control date" required="required">
                </div>
                <div class="col-sm-3">
                    <label for="ins_date_end">Date end <span style="color:red;">*</span></label>
                    <input name="ins_date_end" id="ins_date_end" class="form-control date" required="required">
                </div>  
                <div class="col-sm-3">
                    <label for="ins_issuing_branch">Issuing Branch</label>
                    <input name="ins_issuing_branch" id="ins_issuing_branch" class="form-control" required="required">
                </div>
                <div class="col-sm-3">
                    <label for="ins_issuing_date">Issuing date</label>
                    <input name="ins_issuing_date" id="ins_issuing_date" class="form-control date" required="required">
                </div>                      
            </div>              
            <div class="row form-group">
                <div class="col-sm-3">
                    <label for="ins_gross_premium">Gross Premium </label>
                    <input name="ins_gross_premium" id="ins_gross_premium" class="form-control" required="required">
                </div>
                <div class="col-sm-3">
                    <label for="ins_service_tax">Service Tax</label>
                    <input name="ins_service_tax" id="ins_service_tax" class="form-control" required="required">
                </div>   
                <div class="col-sm-3">
                    <label for="ins_stamp_duty">Stamp Duty</label>
                    <input name="ins_stamp_duty" id="ins_stamp_duty" class="form-control" required="required">
                </div>
                <div class="col-sm-3">
                    <label for="ins_total_sum_insured">Total Sum Insured (RM) <span style="color:red;">*</span></label>
                    <input name="ins_total_sum_insured" id="ins_total_sum_insured" class="form-control" required="required">
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
                <li class="nav-item">
                    <button class="btn alt-btn-black alt-btn newTravelLegButton" type="button" >
                    <i class="fa fa-plus" aria-hidden="true" color="black"></i>
                    </button>
                </li>             
            </ul>
            <div class="tab-content" id="myTabContent">
                
            </div>
            
            <!-- save form-->
            <br>
            <div style="text-align: center;">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


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
        var select_company = $("#company_id").select2({
            selectOnClose: true
        });
        
        select_company.data('select2').$selection.css('height', '35px');
        select_company.data('select2').$selection.css('border', '1px solid #e4e7ea');    

        select_agent.data('select2').$selection.css('height', '35px');
        select_agent.data('select2').$selection.css('border', '1px solid #e4e7ea');  

        //get the default first row        
        tabCounter = 1;        
        addTab();

        var tabs = $( "#tabs" ).tab();
        $("form[name=insuranceForm]").on( 'click', "input.btn_interest_insured", function(event) {
            var name = $(this).attr('name');
            var number_matches = name.match(/[0-9]+/g);
            var index = number_matches[0];             
            addRowInterest(index);                             
        });

        $("form[name=insuranceForm]").on( 'click', "input.btn_perils", function(event) {
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
        $('#insuranceForm').on('submit',function(event){           
            event.preventDefault();
            $("#interest_insured").val(JSON.stringify(TABLE_INTEREST_DATA));
            $("#perils").val(JSON.stringify(TABLE_PERILS_DATA));
            var insuranceData = $('#insuranceForm').serialize();
            $.ajax({
                url: "/admin/insurance/add",
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

        function addTab(){            
            var id = "tab-" + tabCounter;
            var liTabId = tabCounter;

            $('#tabs a').removeClass('active');
            $('.tab-pane').removeClass('active');
            tabHTML = "<li class='nav-item'>";
            tabHTML += "<a class='nav-link active' data-toggle='tab' id='"+liTabId+"' href='#"+id+"' role='tab' aria-controls="+id+" aria-selected='true'>RISK "+tabCounter+"</a><span>x</span>";
            tabHTML += "</li>";
            lastLI = $('.newTravelLegButton').closest('li');
            $( tabHTML ).insertBefore( lastLI );
            var riskTab = <?= json_encode(utf8_encode($tabContent))?>;  
            var contentHTML = riskTab.replace( /{index}/g, tabCounter );       
            $('.tab-content').append( "<div class='tab-pane fade show active' id='"+id+"' role='tabpanel' aria-labelledby='"+liTabId+"'>"+contentHTML+"</div>" );            
            tabCounter++;           
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