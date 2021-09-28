<?php

namespace App\Http\Requests;

use App\FarmCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreFarmCompanyRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
