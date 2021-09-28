@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Farm Company
    </div>

    <div class="card-body">
        <form action="{{ route("admin.farm_companies.store") }}" method="POST" enctype="multipart/form-data">
            @csrf            
            <div class="form-group {{ $errors->has('comp_name') ? 'has-error' : '' }}">
                <label for="comp_name">Farm Company Name <span style="color:red;">*</span></label>
                <input type="text" id="comp_name" name="comp_name" class="form-control" value="{{ old('comp_name', isset($farm_company) ? $farm_company->comp_name : '') }}" required>
                @if($errors->has('comp_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comp_name') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('comp_address') ? 'has-error' : '' }}">
                <label for="comp_address">Farm Company Address <span style="color:red;">*</span></label>
                <input type="text" id="comp_address" name="comp_address" class="form-control" value="{{ old('comp_address', isset($farm_company) ? $farm_company->comp_address : '') }}" required>
                @if($errors->has('comp_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comp_address') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>  
            <div class="form-group {{ $errors->has('abn_no') ? 'has-error' : '' }}">
                <label for="abn_no">ABN No</label>
                <input type="text" id="abn_no" name="abn_no" class="form-control" value="{{ old('abn_no', isset($farm_company) ? $farm_company->abn_no : '') }}" required>
                @if($errors->has('abn_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('abn_no') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('contact_person') ? 'has-error' : '' }}">
                <label for="contact_person">Contact Person</label>
                <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ old('contact_person', isset($farm_company) ? $farm_company->contact_person : '') }}" required>
                @if($errors->has('contact_person'))
                    <em class="invalid-feedback">
                        {{ $errors->first('contact_person') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('contact_no') ? 'has-error' : '' }}">
                <label for="contact_no">Contact No</label>
                <input type="text" id="contact_no" name="contact_no" class="form-control" value="{{ old('contact_no', isset($farm_company) ? $farm_company->contact_no : '') }}" required>
                @if($errors->has('contact_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('contact_no') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($farm_company) ? $farm_company->email : '') }}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div> 
            <div class="form-group {{ $errors->has('super') ? 'has-error' : '' }}">                            
                <label for="super">Super</label> &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="super" name="super" value="1">
                @if($errors->has('super'))
                    <em class="invalid-feedback">
                        {{ $errors->first('super') }}
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