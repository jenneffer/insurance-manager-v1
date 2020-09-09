@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.attachment.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.attachments.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">Policy No*</label>
                <select name="policy_id" id="policy_id" class="form-control select2" required="required">
                    @foreach($insurance_policy as $id => $data)
                        <option value="{{ $id }}"}}>{{ $data }}</option>
                    @endforeach
                </select>
                @if($errors->has('title'))
                    <em class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </em>
                @endif                
            </div>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="myFile" name="myFile[]" multiple aria-describedby="myFile">
                    <label class="custom-file-label" for="myInput">Choose file</label>
                </div>                
            </div>
            <br>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){   
        //initialize select
        var select_policy = $("#policy_id").select2({
            selectOnClose: true
        }); 
        select_policy.data('select2').$selection.css('height', '35px');
        select_policy.data('select2').$selection.css('border', '1px solid #e4e7ea');  
        //display input file name
        document.querySelector('.custom-file-input').addEventListener('change',function(e){
            var fileName = document.getElementById("myFile").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = fileName
        });
    });
</script>
@endsection
