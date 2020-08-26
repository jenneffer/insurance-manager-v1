@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.insurance.title') }}
    </div>

    <div class="card-body insurance-font">
        <div class="mb-2">
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Insured / Policyholder</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-9">{{$insurance->company->compDesc}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Correspondence Address</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-9">{{$insurance->ins_correspond_address}}</div>
            </div>
            <hr>
            <div class="form-group row col-sm-12">
                <div class="col-sm-3">&nbsp;</div>
                <div class="col-sm-3">&nbsp;</div>
                <div class="col-sm-3">&nbsp;</div>
                <div class="col-sm-3 text-right"><strong>RM</strong></div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Product</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3"> {{$insurance->ins_class}}</div>
                <div class="col-sm-3"><strong>Gross Premium</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-2 text-right"> {{number_format($insurance->ins_gross_premium,2)}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Policy No.</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3"> {{$insurance->ins_policy_no}}</div>
                <div class="col-sm-3"><strong>Service Tax</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-2 text-right"> {{number_format($insurance->ins_service_tax,2)}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Period of Insurance</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3"><b>From</b> {{$insurance->ins_date_start}} <b>To</b> {{$insurance->ins_date_end}}</div>
                <div class="col-sm-3"><strong>Stamp Duty</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-2 text-right"> {{number_format($insurance->ins_stamp_duty,2)}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Issuing Branch</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3">{{$insurance->ins_issuing_branch}}</div>
                <div class="col-sm-3"><strong>Total Premium</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-2 text-right">{{number_format($insurance->ins_gross_premium + $insurance->ins_service_tax + $insurance->ins_stamp_duty,2) }}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Issuing Date</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3">{{$insurance->ins_issuing_date}}</div>
                <div class="col-sm-3"><strong>Amount Payable (Rounded)</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-2 text-right"> {{number_format($insurance->ins_gross_premium + $insurance->ins_service_tax + $insurance->ins_stamp_duty,2)}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Total Sum Insured (RM)</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3"> {{ number_format($insurance->ins_total_sum_insured,2)}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Self Rating</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3"> {{ $insurance->ins_self_rating}}</div>
            </div>
            <div class="form-group row col-sm-12">
                <div class="col-sm-2"><strong>Remarks</strong></div>
                <div class="col-sm-1 text-right">:</div>
                <div class="col-sm-3"> {{ $insurance->ins_remark}}</div>
            </div>
            <hr>
            <!-- Show the policy risk !-->
            @foreach($insurance->risk as $key => $risk)                
                <div class="form-group col-sm-12">
                    <label style="background-color: #b0e8f7;"><strong>Risk No. {{$risk->risk_riskno}}</strong></label> 
                    <div class="col-sm-2"><strong>Situation of Risk</strong></div>
                    <div class="col-sm-3">{{$risk->risk_location}}<br><span>{{$risk->risk_address}}</span></div>
                </div> 
                <div class="form-group col-sm-12">
                    <div class="col-sm-6"><strong>Occupation / Business Trade Code & Description</strong></div>
                    <div class="col-sm-6">{{$risk->risk_description}}</span></div>
                </div>   
                <div class="form-group col-sm-12">
                    <label><b>Interest Insured</b></label>
                    <table class="table-sm table table-condensed">
                        <thead>
                            <th>Item No.</th>
                            <th class="text-center">Interest Description</th>
                            <th class="text-right">Sum Insured (RM)</th>
                        </thead>
                        <tbody>
                            @foreach($insurance->interest_insured[$risk->id] as $ins_insured)
                                @foreach($ins_insured as $data)                                                         
                                    <tr>
                                        <td>{{$data->ii_item_no}}</td>
                                        <td>{{$data->ii_description}}</td>
                                        <td class="text-right">{{number_format($data->ii_sum_insured,2)}}</td>
                                    </tr>
                                @endforeach  
                            @endforeach                                                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>                            
                        </tfoot>
                    </table>    
                </div>   
                <div class="form-group col-sm-12">
                    <label><b>Subject to the following Perils/Extension/Clauses/Warranties/ Memorandum applicable to all sections</b></label>
                    <table class="table-sm table table-condensed">
                        <thead>
                            <th>Code</th>
                            <th class="text-center">Description</th>
                            <th class="text-right">Rate (%)</th>
                        </thead>
                        <tbody>
                            @foreach($insurance->perils[$risk->id] as $perils)
                                @foreach($perils as $data)                                                         
                                    <tr>
                                        <td>{{$data->prls_ref_no}}</td>
                                        <td>{{$data->prls_description}}</td>
                                        <td class="text-right">{{$data->prls_rate}}</td>
                                    </tr>
                                @endforeach  
                            @endforeach                                                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>                            
                        </tfoot>
                    </table>    
                </div>   
                <hr>                                                       
            @endforeach
            <!-- Interest Insured!-->
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection
