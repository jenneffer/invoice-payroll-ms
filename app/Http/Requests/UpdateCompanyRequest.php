<?php

namespace App\Http\Requests;

use App\Company;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [            
            'comp_name'     => [
                'required',
            ],
            'comp_address'     => [
                'required',
            ],
        ];
    }
}
