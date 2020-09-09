<?php

namespace App\Http\Requests;

use App\Attachment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAttachmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('attachment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:attachments,id',
        ];
    }
}
