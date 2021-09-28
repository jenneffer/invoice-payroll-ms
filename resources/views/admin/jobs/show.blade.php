@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show Job
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
                            {{ $job->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $job->job_name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Payment Method
                        </th>
                        <td>
                            {{ $job->job_pay_method}}
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