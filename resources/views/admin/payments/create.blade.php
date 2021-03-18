@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Payment for <b>{{$policy_no}}</b>
    </div>

    <div class="card-body">
        <form action="{{ route("admin.payments.store") }}" method="POST" enctype="multipart/form-data">
            @csrf  
            <input type="hidden" name="insurance_id" value="{{$insurance_id}}">         
            <input type="hidden" name="insurance_details_id" value="{{$ins_details_id}}">       
            <input type="hidden" name="policy_no" value="{{$policy_no}}">         
            <div class="form-group">
                <label for="paid_amount">Paid amount (RM) *</label>
                <input type="text" id="paid_amount" name="paid_amount" class="form-control" required>                
                <p class="helper-block"></p>
            </div>
            <div class="form-group">
                <label for="payment_date">Payment date *</label>
                <input id="payment_date" name="payment_date" class="form-control date" required>
                <p class="helper-block"></p>
            </div>
            <div class="form-group">
                <label for="payment_mode">Payment mode *</label>
                <select name="payment_mode" id="payment_mode" class="form-control" required="required">                    
                    <option value="payment_company">Payment Company</option>
                    <option value="cash_individual">Cash Individual</option>
                </select>  
                <p class="helper-block"></p>
            </div>  
            <div class="form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control" rows="2" cols="40"></textarea>
                <p class="helper-block"></p>
            </div>
            <div style="text-align: center;">
                <a class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
                &nbsp;&nbsp;&nbsp;
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>          
        </form>
    </div>
</div>
@endsection