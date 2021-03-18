<?php

namespace App\Http\Requests;

use App\InsuranceCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreInsuranceCompanyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('insurance_company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [            
            'ins_agent_code'     => [
                'required',
            ],
            'ins_agent_desc'     => [
                'required',
            ],
        ];
    }
}
