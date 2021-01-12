@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Insurance Company
    </div>

    <div class="card-body">
        <form action="{{ route("admin.insuranceCompany.update", [$insuranceCompany->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')            
            <div class="form-group {{ $errors->has('ins_agent_code') ? 'has-error' : '' }}">
                <label for="ins_agent_code">Code*</label>
                <input type="text" id="ins_agent_code" name="ins_agent_code" class="form-control" value="{{ old('ins_agent_code', isset($insuranceCompany) ? $insuranceCompany->ins_agent_code : '') }}"  required>
                @if($errors->has('ins_agent_code'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ins_agent_code') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('ins_agent_desc') ? 'has-error' : '' }}">
                <label for="ins_agent_desc">Name</label>
                <input type="text" id="ins_agent_desc" name="ins_agent_desc" class="form-control" value="{{ old('ins_agent_desc', isset($insuranceCompany) ? $insuranceCompany->ins_agent_desc : '') }}">
                @if($errors->has('ins_agent_desc'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ins_agent_desc') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection