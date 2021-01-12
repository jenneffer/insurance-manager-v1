<?php

namespace App\Http\Requests;

use App\InsuranceCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInsuranceCompanyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('insurance_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:insurance_company,id',
        ];
    }
}
