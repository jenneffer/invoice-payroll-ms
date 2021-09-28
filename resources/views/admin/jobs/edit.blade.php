@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Job
    </div>

    <div class="card-body">
        <form action="{{ route("admin.jobs.update", [$job->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')            
            <div class="form-group {{ $errors->has('job_name') ? 'has-error' : '' }}">
                <label for="job_name">Code*</label>
                <input type="text" id="job_name" name="job_name" class="form-control" value="{{ old('job_name', isset($job) ? $job->job_name : '') }}"  required>
                @if($errors->has('job_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('job_name') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('job_pay_method') ? 'has-error' : '' }}">
                <label for="job_pay_method">Pay Method</label>
                <!-- <input type="text" id="job_pay_method" name="job_pay_method" class="form-control" value="{{ old('job_pay_method', isset($job) ? $job->job_pay_method : '') }}"> -->
                <select class="form-control" name="job_pay_method" id="job_pay_method">
                    <option value="bin" {!! old('job_pay_method', $job->job_pay_method) == 'bin' ? 'selected="selected"' : '' !!}> Bin(s)</option>
                    <option value="hour" {!! old('job_pay_method', $job->job_pay_method) == 'hour' ? 'selected="selected"' : '' !!}> Hour(s)</option>                    
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