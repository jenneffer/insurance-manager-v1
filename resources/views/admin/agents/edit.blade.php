@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Agent
    </div>

    <div class="card-body">
        <form action="{{ route("admin.agents.update", [$agent->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')            
            <div class="form-group {{ $errors->has('agentCode') ? 'has-error' : '' }}">
                <label for="agentCode">Code*</label>
                <input type="text" id="agentCode" name="agentCode" class="form-control" value="{{ old('agentCode', isset($agent) ? $agent->agentCode : '') }}"  required>
                @if($errors->has('agentCode'))
                    <em class="invalid-feedback">
                        {{ $errors->first('agentCode') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('agentDesc') ? 'has-error' : '' }}">
                <label for="agentDesc">Name</label>
                <input type="text" id="agentDesc" name="agentDesc" class="form-control" value="{{ old('agentDesc', isset($agent) ? $agent->agentDesc : '') }}">
                @if($errors->has('agentDesc'))
                    <em class="invalid-feedback">
                        {{ $errors->first('agentDesc') }}
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