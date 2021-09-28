<?php

namespace App\Http\Requests;

use App\Employee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [            
            'emp_name'     => [
                'required',
            ],
            'emp_email'     => [
                'required',
            ],
        ];
    }
}
