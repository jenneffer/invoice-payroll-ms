@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.invoice.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <!-- <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.payrolls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div> -->
            <div class="form-group row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Farm Company:</label>
                        {{$invoice->farm_company->comp_name}}
                    </div>
                    <div class="form-group">
                        <label>Address :</label>
                        {{$invoice->farm_company->comp_address}}
                    </div>
                    <div class="form-group">
                        <label>Contact Person :</label>
                        {{$invoice->farm_company->contact_person}}
                    </div>
                    <div class="form-group">
                        <label>Mobile No :</label>
                        {{$invoice->farm_company->contact_no}}
                    </div>
                    <div class="form-group">
                        <label>Email :</label>
                        {{$invoice->farm_company->email}}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Invoice Date :</label>
                        {{date('d-m-Y', strtotime($invoice->date))}}
                    </div>
                    <div class="form-group">
                        <label>Invoice No :</label>
                        {{$invoice->invoice_number}}
                    </div>  
                    <div class="form-group">
                        <label>Bank :</label>
                        {{$invoice->bank}}
                    </div>
                    <div class="form-group">
                        <label>BSB :</label>
                        {{$invoice->bsb}}
                    </div>
                    <div class="form-group">
                        <label>Account No :</label>
                        {{$invoice->acc_no}}
                    </div>                             
                </div>
                <br>
            </div>   
            <div class="form-group float-none">
                <p>Ref. (Work report from) :</p>
            </div>                                         
            <table class="table table-bordered table-striped">
                <thead>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-right">Amount Charged(AUD)</th>                    
                </thead>
                <tbody>
                    @foreach($invoice->invoice_details as $inv)                        
                        <tr data-entry-id="{{$inv->id}}">
                            <td>{{ date('d-m-Y', strtotime($inv->date)) ?? '' }}</td>
                            <td>{{ $inv->description ?? '' }}</td>
                            <td class="text-right">{{ number_format($inv->amount_charged,2) ?? '' }}</td>                            
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right"><b>Sub Total (AUD)</b></td>
                        <td class="text-right"><b>{{ $invoice->sub_total }}</b></td>
                    </tr>
                    @if($invoice->super_amount != 0 || !empty($invoice->super_amount))
                    <tr>
                        <td colspan="2" class="text-right"><b>Super (9.5%)</b></td>
                        <td class="text-right"><b>{{ $invoice->super_amount }}</b></td>
                    </tr>
                    @endif
                    <tr>                        
                        <td colspan="2" class="text-right"><b>GST (10%)</b></td>
                        <td class="text-right"><b>{{ $invoice->gst }}</b></td>
                    </tr>
                    <tr>                        
                        <td colspan="2" class="text-right"><b>Total Amount (AUD)</b></td>
                        <td class="text-right"><b>{{ number_format($invoice->total_amount,2) }}</b></td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group" style="text-align:center;">
                <a class="btn btn-default" href="{{ route('admin.invoices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>                
                <!-- <a class="btn btn-success" href="">
                    Send Email
                </a> -->
            </div>
        </div>
    </div>
</div>



@endsection