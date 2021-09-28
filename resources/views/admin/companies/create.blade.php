@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Company
    </div>

    <div class="card-body">
        <form action="{{ route("admin.companies.store") }}" method="POST" enctype="multipart/form-data">
            @csrf            
            <div class="form-group {{ $errors->has('comp_name') ? 'has-error' : '' }}">
                <label for="comp_name">Company Name <span style="color:red;">*</span></label>
                <input type="text" id="comp_name" name="comp_name" class="form-control" value="{{ old('comp_name', isset($company) ? $company->comp_name : '') }}" required>
                @if($errors->has('comp_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comp_name') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('comp_address') ? 'has-error' : '' }}">
                <label for="comp_address">Company Address <span style="color:red;">*</span></label>
                <input type="text" id="comp_address" name="comp_address" class="form-control" value="{{ old('comp_address', isset($company) ? $company->comp_address : '') }}" required>
                @if($errors->has('comp_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comp_address') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>  
            <div class="form-group {{ $errors->has('abn_no') ? 'has-error' : '' }}">
                <label for="abn_no">ABN No</label>
                <input type="text" id="abn_no" name="abn_no" class="form-control" value="{{ old('abn_no', isset($company) ? $company->abn_no : '') }}" required>
                @if($errors->has('abn_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('abn_no') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('acn_no') ? 'has-error' : '' }}">
                <label for="acn_no">ACN No</label>
                <input type="text" id="acn_no" name="acn_no" class="form-control" value="{{ old('acn_no', isset($company) ? $company->acn_no : '') }}" required>
                @if($errors->has('acn_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('acn_no') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('contact_person') ? 'has-error' : '' }}">
                <label for="contact_person">Contact Person</label>
                <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ old('contact_person', isset($company) ? $company->contact_person : '') }}" required>
                @if($errors->has('contact_person'))
                    <em class="invalid-feedback">
                        {{ $errors->first('contact_person') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('contact_no') ? 'has-error' : '' }}">
                <label for="contact_no">Contact No</label>
                <input type="text" id="contact_no" name="contact_no" class="form-control" value="{{ old('contact_no', isset($company) ? $company->contact_no : '') }}" required>
                @if($errors->has('contact_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('contact_no') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($company) ? $company->email : '') }}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block"></p>
            </div>  
            <div class="form-group {{ $errors->has('last_invoice_no') ? 'has-error' : '' }}">
                <label for="last_invoice_no">Last Invoice No <span style="color:red;">*</span></label>
                <input type="number" id="last_invoice_no" name="last_invoice_no" class="form-control" value="{{ old('last_invoice_no', isset($company) ? $company->last_invoice_no : '') }}" required>
                @if($errors->has('last_invoice_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('last_invoice_no') }}
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