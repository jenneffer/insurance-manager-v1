@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.insurance.title') }}
    </div>

    <div class="card-body insurance-font">  
        <div id="printableArea" class="mb-2">
            
            <div class="content">
                <div class="header text-center">
                    <p><b>PAYMENT REQUISITION FORM</b></p>                
                </div>
                <div class="row col-sm-12">
                    <p>Company/Owner : </p>
                    <p>&nbsp;<b>{{strtoupper($payment->compDesc)}}</b></p>
                </div>   
                <div class="row col-sm-12">
                    <div class="row col-sm-4">
                        <p>To : </p>
                        <p>&nbsp;<b>{{strtoupper($payment->ins_agent_desc)}}</b></p>
                    </div>      
                    <div class="row col-sm-4">
                        <p>Serial No : </p>
                        <hr style="width:60%;border:1px solid black;">
                    </div> 
                    <div class="row col-sm-4">
                        <p>Date : </p>
                        <p>&nbsp;<b>{{date('d-m-Y', strtotime($payment->created_at))}}</b></p>
                    </div>
                </div>
                <div class="row col-sm-12">
                    <p> We append below the details list of payment required to be paid on </p>
                    <p>&nbsp;<b>{{date('d-m-Y', strtotime($payment->payment_date))}}</b></p>
                </div>          
            </div>
            <table class="table-content" style="width: 100%">
            <tr>
                <th class="table-content text-center" width="5%">No.</th>
                <th class="table-content text-center" width="45%">Particulars</th>
                <th class="table-content text-center" width="20%">Total (RM)</th>
                <th class="table-content text-center" width="30%">Remark</th>
            </tr>
            <tr>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">Being Payment For Insurance Renewal</td>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">Classification : {{$payment->ins_class}}</td>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-content text-center">1</td>
                <td class="table-content">Policy No. : {{$payment->policy_no}}</td>
                <td class="table-content text-right">{{number_format($payment->paid_amount,2)}}</td>
                <td class="table-content">{{$payment->remark}}</td>
            </tr>
            <tr>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">Period of Insurance : {{$payment->date_start}} to {{$payment->date_end}}</td>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">Location : {{$payment->risk_location}}</td>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">Property Insured : {{$payment->risk_description}}</td>
                <td class="table-content">&nbsp;</td>
                <td class="table-content">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-content text-center"><b>Total</b></td>
                <td class="table-content"><b>{{$moneyText}} only</b></td>
                <td class="table-content text-right"><b>{{number_format($payment->paid_amount,2)}}</b></td>
                <td class="table-content">&nbsp;</td>
            </tr>
                        
            </table>
            <table class="table-1" style="width: 100%">
            <tr>
                <td class="table-1 text-center">&nbsp;</td>
                <td class="table-1 text-center">&nbsp;</td>
                <td class="table-1 text-center">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-1 text-center" style="width:33%;">Requested By</td>
                <td class="table-1 text-center" style="width:33%;">Verified By</td>
                <td class="table-1 text-center" style="width:33%;">Processed By</td>
            </tr>
            <tr>
                <td class="table-1">&nbsp;</td>
                <td class="table-1">&nbsp;</td>
                <td class="table-1">&nbsp;</td>
            </tr>
            <tr>
                <td class="table-1 text-center border-dark"><hr style="width:80%;border:1px solid black;"></td>
                <td class="table-1 text-center border-dark"><hr style="width:80%;border:1px solid black;"/></td>
                <td class="table-1 text-center border-dark"><hr style="width:80%;border:1px solid black;"></td>
            </tr>
            <tr>
                <td class="table-1 text-center">{{$user->name}}</td>
                <td class="table-1 text-center">Department In-Charge</td>
                <td class="table-1 text-center">Acc. Personnel</td>
            </tr>
            </table>            
        </div>      
        <div style="text-align: center;">
            <a style="margin-top:20px;width:10%;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
            &nbsp;&nbsp;&nbsp;
            <a style="margin-top:20px; width:10%;color:white;" class="btn btn-primary" onclick="printDiv('printableArea')">
                Print
            </a>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    function printDiv(divName){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
@endsection

