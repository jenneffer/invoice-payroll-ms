<?php

namespace App\Http\Requests;

use App\Payroll;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePayrollRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            // 'employee_id'      => [
            //     'required'],
            // 'job_id'       => [
            //     'required'],
            // 'total_hours'           => [
            //     'required'],
            // 'total_bin' => [
            //     'required'],
            // 'date' => [
            //     'required'],
        ];

    }
}
