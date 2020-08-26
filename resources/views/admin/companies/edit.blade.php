@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.expense.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.companies.update", [$company->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')            
            <div class="form-group {{ $errors->has('compCode') ? 'has-error' : '' }}">
                <label for="compCode">Code*</label>
                <input type="text" id="compCode" name="compCode" class="form-control" value="{{ old('compCode', isset($company) ? $company->compCode : '') }}"  required>
                @if($errors->has('compCode'))
                    <em class="invalid-feedback">
                        {{ $errors->first('compCode') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('compDesc') ? 'has-error' : '' }}">
                <label for="compDesc">Name</label>
                <input type="text" id="compDesc" name="compDesc" class="form-control" value="{{ old('compDesc', isset($company) ? $company->compDesc : '') }}">
                @if($errors->has('compDesc'))
                    <em class="invalid-feedback">
                        {{ $errors->first('compDesc') }}
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