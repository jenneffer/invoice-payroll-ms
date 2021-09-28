@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show Farm Company
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
                            {{ $farm_company->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $farm_company->comp_name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Address
                        </th>
                        <td>
                            {{ $farm_company->comp_address}}
                        </td>
                    </tr> 
                    <tr>
                        <th>
                            ABN No
                        </th>
                        <td>
                            {{ $farm_company->abn_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Contact Person
                        </th>
                        <td>
                            {{ $farm_company->contact_person}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Contact No
                        </th>
                        <td>
                            {{ $farm_company->contact_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $farm_company->email}}
                        </td>
                    </tr> 
                    <tr>
                        <th>
                            Super
                        </th>
                        <td>
                            {{ ($farm_company->super == 1) ? "Super ( 9.5% )" : "No"}}
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