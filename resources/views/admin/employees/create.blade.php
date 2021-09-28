@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Employee
    </div>

    <div class="card-body">
        <form action="{{ route("admin.employees.store") }}" method="POST" enctype="multipart/form-data">
            @csrf            
            <div class="form-group {{ $errors->has('emp_name') ? 'has-error' : '' }}">
                <label for="emp_name">Employee Name <span style="color:red;">*</span></label>
                <input type="text" id="emp_name" name="emp_name" class="form-control" value="{{ old('emp_name', isset($employee) ? $employee->emp_name : '') }}" required>
                @if($errors->has('emp_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('emp_name') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('emp_email') ? 'has-error' : '' }}">
                <label for="emp_email">Employee Email <span style="color:red;">*</span></label>
                <input type="text" id="emp_email" name="emp_email" class="form-control" value="{{ old('emp_email', isset($employee) ? $employee->emp_email : '') }}" required>
                @if($errors->has('emp_email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('emp_email') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>         
            <div class="form-group {{ $errors->has('emp_doj') ? 'has-error' : '' }}">
                <label for="emp_doj">Date of Join</label>
                <input class="form-control date {{ $errors->has('emp_doj') ? 'is-invalid' : '' }}" type="text" name="emp_doj" id="emp_doj" value="{{ old('emp_doj') }}">
                @if($errors->has('emp_doj'))
                    <em class="invalid-feedback">
                        {{ $errors->first('emp_doj') }}
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