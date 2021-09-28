@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show Employee
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
                            {{ $employee->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $employee->emp_name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $employee->emp_email}}
                        </td>
                    </tr> 
                    <tr>
                        <th>
                            Date of Join
                        </th>
                        <td>
                            {{ $employee->emp_doj }}
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