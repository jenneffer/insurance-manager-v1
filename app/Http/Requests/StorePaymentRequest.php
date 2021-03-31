<?php

namespace App\Http\Requests;

use App\Payment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePaymentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [                        
            'paid_amount' => [
                'required',
            ],
            'payment_date' => [
                'required',
            ],
            'payment_mode' => [
                'required',
            ],
        ];
    }
}
