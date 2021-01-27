@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.insurance.title') }}
    </div>

    <div class="card-body insurance-font">
        <ul class="nav nav-tabs" id="tabs" role="tablist">  

            @foreach($insurance->ins_details as $key => $ins_data)  
            <li class="nav-item">
                <a class='nav-link {{ $loop->last ? "active" : "" }}' data-toggle='tab' id='{{$key}}' href='#tab-{{$key}}' role='tab' aria-controls="+id+" aria-selected='true'>YEAR {{$key}}</a>
            </li>   
            @endforeach                          
        </ul>
        <div class="tab-content" id="myTabContent">
                @foreach($insurance->ins_details as $year => $ins_data)   
                <div class='tab-pane fade show {{ $loop->last ? "active" : "" }}' id='tab-{{$year}}' role='tabpanel' aria-labelledby='{{$year}}'>    
                    <br>
                    <div class="mb-2">
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-6 row">
                                <div class="col-sm-4"><strong>Insured / Policyholder</strong></div>
                                <div class="col-sm-1 text-right">:</div>
                                <div class="col-sm-6">{{$insurance->company->compDesc}}</div>
                            </div>
                            <div class="col-sm-6 row">
                                <div class="col-sm-4"><strong>Agent</strong></div>
                                <div class="col-sm-1 text-right">:</div>
                                <div class="col-sm-6">{{$insurance->agent->agentDesc}}</div>
                            </div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-6 row">
                                <div class="col-sm-4"><strong>Correspondence Address</strong></div>
                                <div class="col-sm-1 text-right">:</div>
                                <div class="col-sm-6">{{$insurance->ins_correspond_address}}</div>
                            </div>
                            <div class="col-sm-6 row">
                                <div class="col-sm-4"><strong>Insurance Company</strong></div>
                                <div class="col-sm-1 text-right">:</div>
                                <div class="col-sm-6">{{$insurance->insurance_company->ins_agent_desc}}</div>
                            </div>
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
                            <div class="col-sm-2 text-right"> {{number_format($ins_data['gross_premium'],2)}}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Policy No.</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3"> {{$ins_data['policy_no']}}</div>
                            <div class="col-sm-3"><strong>Service Tax</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-2 text-right"> {{number_format($ins_data['service_tax'],2)}}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Period of Insurance</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3"><b>From</b> {{$ins_data['date_start']}} <b>To</b> {{$ins_data['date_end']}}</div>
                            <div class="col-sm-3"><strong>Stamp Duty</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-2 text-right"> {{number_format($ins_data['stamp_duty'],2)}}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Issuing Branch</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3">{{$insurance->ins_issuing_branch}}</div>
                            <div class="col-sm-3"><strong>Total Premium</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-2 text-right">{{number_format($ins_data['gross_premium'] + $ins_data['service_tax'] + $ins_data['stamp_duty'],2) }}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Issuing Date</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3">{{$insurance->ins_issuing_date}}</div>
                            <div class="col-sm-3"><strong>Amount Payable (Rounded)</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-2 text-right"> {{number_format($ins_data['gross_premium'] + $ins_data['service_tax'] + $ins_data['stamp_duty'],2) }}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Total Sum Insured (RM)</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3"> {{ number_format($ins_data['sum_insured'],2)}}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Self Rating</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3"> {{ number_format(floatval($ins_data['self_rating']),5)}}</div>
                        </div>
                        <div class="form-group row col-sm-12">
                            <div class="col-sm-2"><strong>Remarks</strong></div>
                            <div class="col-sm-1 text-right">:</div>
                            <div class="col-sm-3"> {{ $ins_data['remark']}}</div>
                        </div>
                        <hr>
                        <!-- Show the policy risk based on the renewal year. Please refer to renewal_item_controller table !-->
                        @foreach($insurance->risk as $key => $risk) 
                        @if($risk->year == $year)              
                            <div class="form-group col-sm-12">
                                <div class="col-sm-12" style="background-color: #b0e8f7;">
                                    <label><strong>Risk No. {{$risk->risk_riskno}}</strong></label> 
                                </div>
                                <div class="col-sm-2"><strong>Situation of Risk</strong></div>
                                <div class="col-sm-3">{{$risk->risk_location}}<br><span>{{$risk->risk_address}}</span></div>
                            </div> 
                            <div class="form-group col-sm-12">
                                <div class="col-sm-6"><strong>Properties Insured</strong></div>
                                <div class="col-sm-6">{{$risk->risk_description}}</span></div>
                            </div>                     
                            <div class="form-group col-sm-12">
                                <label><b>Additional Item(s)</b></label>
                                <table class="table-sm table table-condensed">
                                    <thead>
                                        <th width="5%">Item No.</th>
                                        <th width="60%" class="text-center">Description</th>
                                        <th width="10%" class="text-right">Rate (%)</th>
                                        <th width="25%" class="text-right">Sum Insured (RM)</th>
                                    </thead>
                                    <tbody>
                                        @foreach($insurance->perils[$risk->id] as $perils)
                                            @foreach($perils as $key => $data)  
                                                @foreach($data as $dat)   
                                                    @if($key == $year)                                         
                                                    <tr>
                                                        <td>{{$dat->ref_no}}</td>
                                                        <td>{{$dat->description}}</td>
                                                        <td class="text-right">{{$dat->rate}}</td>
                                                        <td class="text-right">{{number_format($dat->sum_insured,2)}}</td>                         
                                                    </tr>
                                                    @endif
                                                @endforeach
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
                        @endif                                                   
                        @endforeach
                        <!-- Interest Insured!-->
                        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
    </div>
</div>
@endsection
