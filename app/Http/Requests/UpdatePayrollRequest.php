<?php

namespace App\Http\Requests;

use App\Payroll;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePayrollRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [            
           
        ];

    }
}
