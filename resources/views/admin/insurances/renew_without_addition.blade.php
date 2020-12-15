@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.renew') }} Insurance without Additional Risk 
    </div>
    <div class="card-body">
        <form name='InsuranceRenewalForm' id='InsuranceRenewalForm'>
            @csrf            
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_policy_no">Policy No. <span style="color:red;">*</span></label>
                    <input name="ins_policy_no" id="ins_policy_no" class="form-control" required="required">
                </div> 
                <div class="col-sm-6">
                    <label for="ins_total_sum_insured">Total Sum Insured (RM) <span style="color:red;">*</span></label>
                    <input name="ins_total_sum_insured" id="ins_total_sum_insured" class="form-control" required="required">
                </div> 
            </div> 
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_date_start">Date start <span style="color:red;">*</span></label>
                    <input name="ins_date_start" id="ins_date_start" class="form-control date" required="required">
                </div>
                <div class="col-sm-6">
                    <label for="ins_date_end">Date end <span style="color:red;">*</span></label>
                    <input name="ins_date_end" id="ins_date_end" class="form-control date" required="required">
                </div>
            </div> 
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="ins_remark">Remarks</label>
                    <textarea name="ins_remark" id="ins_remark" class="form-control" rows="2" cols="40"></textarea>
                </div>
            </div>               
            <br>
            <div class="d-flex justify-content-center">
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
                &nbsp;&nbsp;&nbsp;
                <a class="btn btn-default" href="{{url()->previous()}}">
                    Go Back   
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript"> 
    $(document).ready(function(){    
        //submit form
        $('#InsuranceRenewalForm').on('submit',function(event){           
            event.preventDefault();
            var insuranceData = $('#InsuranceRenewalForm').serialize();    
            var insurance_id = {!! json_encode($ins_id) !!};        
            $.ajax({
                url: "/admin/insurance/update_renewal",
                type:"POST",               
                data:{
                    "_token": "{{ csrf_token() }}",
                    data: insuranceData,
                    ins_id: insurance_id                    
                },                
                success:function(response){
                    window.location=response.url;
                },
            });
        });    
        
    });    
</script>
@endsection