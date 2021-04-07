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
                    <p>&nbsp;<b>{{strtoupper($construtedData['company'])}}</b></p>
                </div>   
                <br>
                <div class="row col-sm-12">
                    <div class="row col-sm-4">
                        <div>To : </div>
                        <div>&nbsp;<b>{{strtoupper($construtedData['pay_to'])}}</b></div>
                    </div>      
                    <div class="row col-sm-4">
                        <div>Serial No : </div>
                        <hr style="width:60%;border:1px solid black;">
                    </div> 
                    <div class="row col-sm-4" style="text-align:right;">
                        <div>Date : </div>
                        <div>&nbsp;<b>{{date('d-m-Y', strtotime($construtedData['created_date']))}}</b></div>
                    </div>
                </div>
                <div class="row col-sm-12">
                    <p> We append below the details list of payment required to be paid on </p>
                    <p>&nbsp;<b>{{date('d-m-Y', strtotime($construtedData['payment_date']))}}</b></p>
                </div>          
            </div>
            <table class="table-content" style="width: 100%">
            <tr>
                <th class="table-content text-center" width="5%">No.</th>
                <th class="table-content text-center" width="50%">Particulars</th>
                <th class="table-content text-center" width="10%">Total (RM)</th>
                <!-- <th class="table-content text-center" width="30%">Remark</th> -->
            </tr>
            <tr>
                <td class="table-content"></td>
                <td class="table-content">Being Payment For Insurance Renewal</td>
                <td class="table-content">&nbsp;</td>
                <!-- <td class="table-content">&nbsp;</td> -->
            </tr>
            @php
            $count = 0;
            $gtotal = 0;
            @endphp
            @foreach($construtedData['insurance_details'] as $data)
            @php
            $count++;
            $gtotal +=$data['sum_insured'];
            @endphp
            <tr>
                <td class="table-content text-center">{{$count}}.</td>
                <td class="table-content">
                    <span>Classification : {{$data['ins_class']}}</span><br>
                    <span>Policy No. : {{$data['policy_no']}}</span><br>
                    <span>Period of Insurance : {{$data['date_start']}} to {{$data['date_end']}}</span><br>
                    <span>Location : {{$data['location']}}</span><br>
                    <span>Property Insured : {{$data['properties_insured']}}</span><br>
                </td>
                <td class="table-content text-right">{{number_format($data['sum_insured'],2)}}</td>
                <!-- <td class="table-content">&nbsp;</td> -->
            </tr>            
            @endforeach 
            <tr>
                <td></td>
                <th class="table-content text-right">Total Sum Insured (RM)</th>
                <th class="table-content text-right">{{number_format($gtotal,2)}}</th>
            </tr>    
            <tr>
                <th class="table-content text-center">Total</th>
                <th class="table-content">{{$moneyText}} Only</th>
                <th class="table-content text-right">{{number_format($construtedData['paid_amount'],2)}}</th>
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

