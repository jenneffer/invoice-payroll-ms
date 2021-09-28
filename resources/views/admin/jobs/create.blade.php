@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Job
    </div>

    <div class="card-body">
        <form action="{{ route("admin.jobs.store") }}" method="POST" enctype="multipart/form-data">
            @csrf            
            <div class="form-group {{ $errors->has('job_name') ? 'has-error' : '' }}">
                <label for="job_name">Job Name <span style="color:red;">*</span></label>
                <input type="text" id="job_name" name="job_name" class="form-control" value="{{ old('job_name', isset($jobs) ? $jobs->job_name : '') }}" required>
                @if($errors->has('job_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('job_name') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('job_pay_method') ? 'has-error' : '' }}">
                <label for="job_pay_method">Payment Method <span style="color:red;">*</span></label>
                <select class="form-control" name="job_pay_method" id="job_pay_method">
                    <option value="bin">Bin(s)</option>
                    <option value="hour">Hour(s)</option>                    
                </select>
                @if($errors->has('job_pay_method'))
                    <em class="invalid-feedback">
                        {{ $errors->first('job_pay_method') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>                                                
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection