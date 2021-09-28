@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show Company
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $company->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $company->comp_name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Address
                        </th>
                        <td>
                            {{ $company->comp_address}}
                        </td>
                    </tr> 
                    <tr>
                        <th>
                            ABN No
                        </th>
                        <td>
                            {{ $company->abn_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            ACN No
                        </th>
                        <td>
                            {{ $company->acn_no}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Contact Person
                        </th>
                        <td>
                            {{ $company->contact_person}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Contact No
                        </th>
                        <td>
                            {{ $company->contact_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $company->email}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Last Invoice No.
                        </th>
                        <td>
                            {{ $company->last_invoice_no}}
                        </td>
                    </tr>                   
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection