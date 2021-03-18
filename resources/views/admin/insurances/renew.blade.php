@extends('layouts.admin')
@section('content')
@can('insurance_renew')
    <div style="text-align: center;">
        <div style="margin-bottom: 10px;" >
            <div class="col-lg-12">
                <a class="btn btn-success btn-block btn-lg" href="{{route('admin.insurances.renew_with_addition', ['id'=>$ins_id, 'ins_details_id'=> $ins_details_id])}}">
                    Renew with additional item insured    
                </a>
            </div>        
        </div>
        <div style="margin-bottom: 10px;">
            <div class="col-lg-12">
                <a class="btn btn-success btn-block btn-lg" href="{{route('admin.insurances.renew_without_addition', ['id'=>$ins_id,'ins_details_id'=>$ins_details_id])}}">
                    Renew without additional item insured    
                </a>
            </div>
        </div>
    </div>        
@endcan
@endsection

