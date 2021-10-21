@extends('layouts.admin')
@section('content')
@section('styles')
<style>
    .form-item{
        clear: left;
    } 
    .emp_label{
        float: left;
        width: 150px;
        clear: right;
    }
</style>
@endsection
<div class="card">
    <div class="card-header">
        Show Payroll
    </div>

    <div class="card-body">
        <div class="form-group">
            <!-- <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.payrolls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div> -->
            <div class="form-item">
                <label class="emp_label">Employee Name</label>
                <p>:&nbsp;{{$employee_data->emp_name}}</p>
            </div>
            <div class="form-item">
                <label class="emp_label">Date</label>
                <p>:&nbsp;{{$employee_data->payroll_date}}</p>
            </div>
            <div class="form-item">
                <label class="emp_label">Email</label>
                <p>:&nbsp;{{$employee_data->emp_email}}</p>
            </div>
            
            <div class="form-group">
                Job Details:
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <th>Date</th>
                    <th>Job</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <th class="text-center">Rest Time(Min)</th>
                    <th class="text-center">Total(Hours)</th>
                    <th class="text-center">Total(Bin)</th>
                    <th class="text-center">Rate(AUD)</th>
                    <th class="text-right">Total(AUD)</th>
                </thead>
                <tbody>
                    @php
                    $total_salary = 0
                    @endphp
                    @foreach($payroll->payroll_details as $p)
                        @php
                        $total_salary += $p->total;
                        @endphp
                        <tr data-entry-id="{{$p->id}}">
                            <td>{{ date('d-m-Y', strtotime($p->date)) ?? '' }}</td>
                            <td>{{ App\Job::getJobName($p->job_id) ?? '' }}</td>
                            <td>{{ $p->time_start ?? '' }}</td>
                            <td>{{ $p->time_end ?? '' }}</td>
                            <td class="text-center">{{ $p->time_rest ?? '' }}</td>
                            <td class="text-center">{{ $p->total_hours ?? '' }}</td>
                            <td class="text-center">{{ $p->total_bin ?? '' }}</td>
                            <td class="text-center">{{ number_format($p->rate,2) ?? '' }}</td>
                            <td class="text-right">{{ number_format($p->total,2) ?? '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="8" class="text-right"><b>Total (AUD)</b></td>
                        <td class="text-right"><b>{{ number_format($total_salary,2) }}</b></td>
                    </tr>
                    <tr>                        
                        <td colspan="8" class="text-right"><b>Bonus/Allowance etc (AUD)</b></td>
                        <td class="text-right"><b>+ {{ number_format($employee_data->allowance,2) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right"><b>Deduction Others (AUD)</b></td>
                        <td class="text-right"><b>- {{ number_format($employee_data->deduction,2) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right"><b>Deduction Tax (AUD)</b></td>
                        <td class="text-right"><b>- {{ number_format($employee_data->emp_tax,2) }}</b></td>
                    </tr>
                    <tr>                        
                        <td colspan="8" class="text-right"><b>Grandtotal (AUD)</b></td>
                        <td class="text-right"><b>{{ number_format((($total_salary+$employee_data->allowance) - ($employee_data->deduction + $employee_data->emp_tax)),2) }}</b></td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.payrolls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection