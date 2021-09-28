<?php

namespace App\Http\Requests;

use App\Invoice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            // 'farm_comp_id'     => [
            //     'required',
            //     'integer'
            // ],
            // 'date'    => [
            //     'required',
            //     'date_format:' . config('panel.date_format')
            // ],            
            // 'invoice_number' => [
            //     'required',                
            // ],
            // 'acc_no' => [
            //     'required'
            // ],
            // 'bank' => [
            //     'required'
            // ],
            // 'bsb' => [
            //     'required'
            // ],                        
        ];

    }
}
