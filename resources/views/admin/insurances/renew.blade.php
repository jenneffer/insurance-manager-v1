@extends('layouts.admin')
@section('content')
@can('insurance_renew')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{route('admin.insurances.renew_with_addition', $ins_id)}}">
                Renew with additional item insured    
            </a>
        </div>        
    </div>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-4">
            <a class="btn btn-success" href="{{route('admin.insurances.renew_without_addition', $ins_id)}}">
                Renew without additional item insured    
            </a>
        </div>
    </div>        
@endcan
@endsection

