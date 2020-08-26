<?php

namespace App\Http\Requests;

use App\Permission;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInterestInsuredRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('interest_insured_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ii_id'   => 'required|array',
            'ii_ids.*' => 'exists:interest_insured,ii_id',
        ];
    }
}