@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Edit Payroll
    </div>
    <div class="card-body">
        <!-- <form method="POST" action="{{ route("admin.payrolls.update", [$payroll->id]) }}" enctype="multipart/form-data"> -->
        <form id="payrollFormUpdate" name="payrollFormUpdate">
            @method('PUT')
            @csrf
            <input type='hidden' id='payrollDetailsHourly' name='payrollDetailsHourly' value=''> 
            <input type='hidden' id='payrollDetailsPicking' name='payrollDetailsPicking' value=''> 
            <input type="hidden" id="total_salary" name="total_salary" value="">
            <div class="separator"><b>Employee Details</b></div>
            <br>
            <div class="row form-group">
                <div class="col-sm-1">     
                    <label>Employee Name</label>
                </div>
                <div class="col-sm-6">                    
                    : {{$employee_data->emp_name}}
                </div>                                
            </div>
            <div class="row form-group">
                <div class="col-sm-1">
                    <label>Email</label>
                </div>
                <div class="col-sm-6">                    
                    : {{$employee_data->emp_email}}
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-1">
                    <label>Payroll Date</label>
                </div>                
                <div class="col-sm-2">                    
                    <input type="text" id="created_at" name="created_at" class="form-control date" value="{{ old('created_at', isset($employee_data) ? $employee_data->created_at : '') }}"  required>
                    @if($errors->has('created_at'))
                        <em class="invalid-feedback">
                            {{ $errors->first('created_at') }}
                        </em>
                    @endif
                    <p class="helper-block"></p>
                </div>
            </div>  
            <div>
                <div style="width: 800px; float: left;">
                    <div class="separator"><b>Pay Hourly</b></div>
                    <br>
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-3">
                            <label for="date">Date</label>
                            <input class="form-control date" type="text" name="date_hourly" id="date_hourly" value="">                    
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="required" for="job_name_hourly">Job type</label>
                            <input type="hidden" name="jobNameHourly" id="jobNameHourly" value="">     
                            <select class="form-control" name="job_name_hourly" id="job_name_hourly">
                                <option value="">-Please Select Job-</option>
                                @foreach($job as $id => $j)
                                @if($j->job_pay_method == 'hour')
                                    <option value="{{ $j->id }}">{{ strtoupper($j->job_name) }}</option>
                                @endif
                                @endforeach                   
                            </select>
                            @if($errors->has('job_name_hourly'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('job_name_hourly') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.last_name_helper') }}</span>
                        </div>  
                        <div class="form-group col-sm-3">
                            <label for="time_start">Time Start</label>
                            <input class="form-control {{ $errors->has('time_start') ? 'is-invalid' : '' }}" type="time" name="time_start" id="time_start" value="{{ old('time_start') }}">
                            @if($errors->has('total_hours'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('time_start') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                        </div> 
                        <div class="form-group col-sm-3">
                            <label for="time_end">Time End</label>
                            <input class="form-control {{ $errors->has('time_end') ? 'is-invalid' : '' }}" type="time" name="time_end" id="time_end" value="{{ old('time_end') }}">
                            @if($errors->has('total_hours'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('time_end') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                        </div>                      
                    </div>
                    <div class="row col-sm-12">
                                                 
                    </div>
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-3">
                            <label for="rest_time">Rest Time (Min)</label>
                            <input class="form-control {{ $errors->has('rest_time') ? 'is-invalid' : '' }} allow_numeric" type="text" name="rest_time" id="rest_time" value="{{ old('rest_time') }}">
                            @if($errors->has('rest_time'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('rest_time') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                        </div> 
                        <div class="form-group col-sm-3">
                            <label for="total_hours">Total Hour(s)</label>
                            <input class="form-control {{ $errors->has('total_hours') ? 'is-invalid' : '' }}" type="text" name="total_hours" id="total_hours" value="{{ old('total_hours') }}">
                            @if($errors->has('total_hours'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('total_hours') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                        </div>                
                        <div class="form-group col-sm-3">
                            <label for="job_rate_hourly">Rate (AUD)</label>
                            <input class="form-control allow_decimal" type="text" name="job_rate_hourly" id="job_rate_hourly" value="" >                    
                        </div>
                        <div class="form-group col-sm-3" >
                            <button type="button" class="btn btn-success add_payroll_details_hourly" style="position:absolute; bottom:0;">Add</button>
                        </div>
                    </div>                   
                    <div class="separator"></div>
                </div>
                <div style="margin-left: 850px;">
                    <div class="separator"><b>Fruit Picking</b></div>
                    <br> 
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-4">
                            <label for="date">Date</label>
                            <input class="form-control date" type="text" name="date_picking" id="date_picking" value="">                    
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="required" for="job_name_picking">Job type</label>
                            <input type="hidden" name="jobNamePicking" id="jobNamePicking" value="">     
                            <select class="form-control" name="job_name_picking" id="job_name_picking">
                                <option value="">-Please Select Job-</option>
                                @foreach($job as $id => $j)
                                @if($j->job_pay_method == 'bin')
                                    <option value="{{ $j->id }}">{{ strtoupper($j->job_name) }}</option>
                                @endif
                                @endforeach                   
                            </select>
                            @if($errors->has('job_name_picking'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('job_name_picking') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.last_name_helper') }}</span>
                        </div>                                        
                    </div> 
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-4">
                            <label for="total_bin">Total Bin(s)</label>
                            <input class="form-control {{ $errors->has('total_bin') ? 'is-invalid' : '' }}" type="text" name="total_bin" id="total_bin" value="{{ old('total_bin') }}">
                            @if($errors->has('total_bin'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('total_bin') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="job_rate_picking">Rate (AUD)</label>
                            <input class="form-control allow_decimal" type="text" name="job_rate_picking" id="job_rate_picking" value="">                    
                        </div>
                        <div class="form-group col-sm-2" >
                            <button type="button" class="btn btn-success add_payroll_details_picking" style="position:absolute; bottom:0;">Add</button>
                        </div>
                    </div>
                    <div class="separator"></div>
                </div>
            </div>     
            <br><br>               
            <div class="separator"><b>Job Details</b></div>
            <br>
            <table id="job_details" class="table table-bordered table-striped">
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
                    <th class="text-center">Modify</th>
                </thead>
                <tbody>
                    @php
                    $total_salary = 0
                    @endphp
                    @foreach($payroll_details as $p)
                        @php
                        $total_salary += $p->total;
                        @endphp
                        <tr data-entry-id="{{$p->id}}">
                            <td>{{ $p->date ?? '' }}</td>
                            <td>{{ App\Job::getJobName($p->job_id) ?? '' }}</td>
                            <td>{{ $p->time_start ?? '' }}</td>
                            <td>{{ $p->time_end ?? '' }}</td>
                            <td class="text-center">{{ $p->time_rest ?? '' }}</td>
                            <td class="text-center">{{ $p->total_hours ?? '' }}</td>
                            <td class="text-center">{{ $p->total_bin ?? '' }}</td>
                            <td class="text-center">{{ number_format($p->rate,2) ?? '' }}</td>
                            <td class="text-right">{{ $p->total ?? '' }}</td>
                            <td class="text-center">
                                <span id="{{$p->id}}" data-toggle="modal" class="edit_data_payroll" data-target="#editItemPayroll"><i class="fas fa-edit"></i></span>
                                <span id="{{$p->id}}" data-toggle="modal" class="delete_data_payroll" data-target="#deleteItemPayroll"><i class="fas fa-trash-alt"></i></span>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="8" class="text-right">Total (AUD)</td>
                        <td class="text-right"><span id="total_aud">0.00</span></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right">Bonus/Allowance etc (AUD)</td>
                        <td class="text-right"><input style="text-align:right;" type="text" id="allowance" name="allowance" class="form-control" value="{{ old('allowance', isset($employee_data) ? $employee_data->allowance : '') }}"></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right">Deduction Other (AUD)</td>
                        <td class="text-right"><input style="text-align:right;" type="text" id="deduction" name="deduction" class="form-control" value="{{ old('deduction', isset($employee_data) ? $employee_data->deduction : '') }}"></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right">Deduction Tax (AUD)</td>
                        <td class="text-right"><input style="text-align:right;" type="text" id="emp_tax" name="emp_tax" class="form-control" value="{{ old('emp_tax', isset($employee_data) ? $employee_data->emp_tax : '') }}"></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right">Grand Total (AUD)</td>
                        <td class="text-right"><span id="gTotal">{{ number_format(($total_salary + $employee_data->allowance) - ($employee_data->deduction+$employee_data->emp_tax),2)}}</span></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <div class="row col-sm-12">
                    <a class="btn btn-default" href="{{ route('admin.payrolls.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit payroll details  -->
<div id="editItemPayroll" class="modal fade">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Payroll Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="update_payroll_details">                                
                <input type="hidden" id="total" name="total" value="">
                <input type="hidden" id="payroll_details_id" name="payroll_details_id" value="">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input class="form-control date" type="text" name="date" id="date" value="">                    
                </div>
                <div class="form-group">
                    <label class="required" for="job_name">Job type</label>
                    <input type="hidden" name="jobName" id="jobName" value="">     
                    <select class="form-control" name="job_name" id="job_name" disabled>
                        <option value="">-Please Select Job-</option>
                        @foreach($job as $id => $j)
                            <option value="{{ $j->id }}">{{ strtoupper($j->job_name) }}</option>
                        @endforeach                   
                    </select>
                    @if($errors->has('job_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('job_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.last_name_helper') }}</span>
                </div>
                <div class="row form-group hours_div">
                    <div class="form-group col-sm-3">
                        <label for="time_start">Time Start</label>
                        <input class="form-control {{ $errors->has('time_start') ? 'is-invalid' : '' }}" type="time" name="time_start" id="time_start" value="{{ old('time_start') }}">
                        @if($errors->has('total_hours'))
                            <div class="invalid-feedback">
                                {{ $errors->first('time_start') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                    </div> 
                    <div class="form-group col-sm-3">
                        <label for="time_end">Time End</label>
                        <input class="form-control {{ $errors->has('time_end') ? 'is-invalid' : '' }}" type="time" name="time_end" id="time_end" value="{{ old('time_end') }}">
                        @if($errors->has('total_hours'))
                            <div class="invalid-feedback">
                                {{ $errors->first('time_end') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="rest_time">Rest Time (Min)</label>
                        <input class="form-control {{ $errors->has('rest_time') ? 'is-invalid' : '' }} allow_numeric" type="text" name="rest_time" id="rest_time" value="{{ old('time_rest') }}">
                        @if($errors->has('rest_time'))
                            <div class="invalid-feedback">
                                {{ $errors->first('rest_time') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                    </div>  
                </div>
                <div class="form-group hours_div">
                    <label for="total_hours">Total Hour(s)</label>
                    <input class="form-control {{ $errors->has('total_hours') ? 'is-invalid' : '' }} allow_decimal" type="text" name="total_hours" id="total_hours" value="{{ old('total_hours') }}">
                    @if($errors->has('total_hours'))
                        <div class="invalid-feedback">
                            {{ $errors->first('total_hours') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div>
                <div class="form-group bin_div">
                    <label for="total_bin">Total Bin(s)</label>
                    <input class="form-control {{ $errors->has('total_bin') ? 'is-invalid' : '' }} allow_decimal" type="text" name="total_bin" id="total_bin" value="{{ old('total_bin') }}">
                    @if($errors->has('total_bin'))
                        <div class="invalid-feedback">
                            {{ $errors->first('total_bin') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="job_rate">Rate (AUD)</label>
                    <input class="form-control allow_decimal" type="text" name="job_rate" id="job_rate" value="">                    
                </div>   
                <div class="form-group">
                    <label for="totalSalary">Total (AUD) : </label>
                    <span id="totalSalary">&nbsp;&nbsp;</span>
                </div>                      
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary update_data ">Update</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal delete payroll details!-->
<div class="modal fade" id="deleteItemPayroll">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel">Delete Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                   Are you sure you want to delete?
               </p>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" id="delete_record_payroll_details" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
var TABLE_PAYROLL_DATA_HOURLY = [];
var TABLE_PAYROLL_DATA_PICKING = [];

function getColumnTotal(){
    var grandT = 0;
    $("#job_details > tbody > tr").each(function () {
        var t3 = $(this).find('td').eq(8).html();
        console.log(!isNaN(t3));
        if (!isNaN(t3)) {
            grandT += parseFloat(t3);
        }
    });
    $('#total_salary').val(grandT);
    return grandT;
}

$(document).ready(function(){      
    //validate input text 
    var total = getColumnTotal();
    
    $('#total_aud').text((Math.round(total * 100) / 100).toFixed(2));    
    $(".allow_decimal").on("keypress keyup",function (event) {           
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(".allow_numeric").on("keypress keyup",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $('#allowance').on('keypress keyup', function(){
        var allowance = $(this).val();
        var deduction = $('#deduction').val();
        var emp_tax = $('#emp_tax').val();       
        CalculateTotal(total, allowance, deduction, emp_tax);
    });

    $('#deduction').on('keypress keyup', function(){
        var deduction = $(this).val();
        var allowance = $('#allowance').val();
        var emp_tax = $('#emp_tax').val();
        CalculateTotal(total, allowance, deduction, emp_tax);
    });

    $('#emp_tax').on('keypress keyup', function(){
        var emp_tax = $(this).val();
        var allowance = $('#allowance').val();
        var deduction = $('#deduction').val();
        CalculateTotal(total, allowance, deduction, emp_tax);
    });

    $('#time_end').on('keyup keydown', function(){
        // calculateTime();
        calTime();
    });

    $('#total_hours').on('keypress keyup', function(){
        var val = $(this).val();
        //calculate new total
        var job_rate = $('#job_rate').val();
        calNewTotal(val, job_rate); 

    });

    $('#total_bin').on('keypress keyup', function(){
        var val = $(this).val();
        //calculate new total
        var job_rate = $('#job_rate').val();
        calNewTotal(val, job_rate); 

    });

    $('#job_rate').on('keypress keyup', function(){
        var val = $(this).val();
        //calculate new total
        var total_hours = $('#total_hours').val();
        var total_bin = $('#total_bin').val();
        var total = parseFloat((total_hours*1) + (total_bin*1));

        calNewTotal(total, val); 
    });
    $('#rest_time').on('keyup', function(){
        var rest_time = $(this).val();
        $('#rest_time').val(rest_time);
        // calculateTime();
        calTime();
    });
    
    $("#job_details").on('click','.remove_item',function(){
        $(this).parent().parent().remove();
        CalcTotal();
    });
    
    $(document).on('click', '.edit_data_payroll', function(){
        var id = $(this).attr("id");               
        //hide total bin if its hourly    
        $('#payroll_details_id').val(id);
        $.ajax({
            url:"/admin/payrolls/retrieve",
            method:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                id:id                
            },
            success:function(data){
                console.log(data);
                rate = data.rate;
                total_bin = (data.total_bin == '' || data.total_bin == null ) ? 0 : data.total_bin;
                total_hours = (data.total_hours == '' || data.total_hours == null) ? 0 : data.total_hours;
                if(total_bin == 0){
                    $('.bin_div').hide();
                }else{
                    $('.bin_div').show();
                }                
                if(total_hours == 0){                
                    $('.hours_div').hide();
                }else{
                    $('.hours_div').show();
                }
                $('#total').val(data.total);
                $('#totalSalary').text(numberWithCommas(data.total));
                $('#date').val(data.date);                   
                $('#job_name').val(data.job_id); 
                $('#time_start').val(data.time_start);  
                $('#time_end').val(data.time_end);  
                $('#rest_time').val(data.time_rest);   
                $('#total_hours').val(data.total_hours);     
                $('#total_bin').val(parseFloat(data.total_bin));     
                $('#job_rate').val(data.rate);     
                $('#editItemPayroll').modal('show');
            }
        });
    }); 

    $(document).on('click', '.delete_data_payroll', function(){
        var id = $(this).attr("id");                
        $('#delete_record_payroll_details').data('id', id); //set the data attribute on the modal button
    });

    $( "#delete_record_payroll_details" ).click( function() {
        var ID = $(this).data('id');               
        $.ajax({
            url:"/admin/payrolls/details/destroy",
            method:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                id:ID,  
                payroll_id: '<?= json_encode($payroll->id); ?>'               
            },
            success:function(response){
                $('#deleteItemPayroll').modal('hide');     
                window.location=response.url;
            }
        });
    });

    $('#payrollFormUpdate').on('submit',function(event){           
        event.preventDefault();        
        $("#payrollDetailsHourly").val(JSON.stringify(TABLE_PAYROLL_DATA_HOURLY));
        $("#payrollDetailsPicking").val(JSON.stringify(TABLE_PAYROLL_DATA_PICKING));
        var payrollData = $('#payrollFormUpdate').serialize();
        $.ajax({
            url: "/admin/payrolls/update",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                data: payrollData,
                id: '<?= json_decode($payroll->id)?>'                 
            },                
            success:function(response){                
                window.location=response.url;
            },
        });
    });

    //update modal
    $('#update_payroll_details').on('submit',function(event){           
        event.preventDefault();
        var payrollDetails = $('#update_payroll_details').serialize();
        $.ajax({
            url: "/admin/payrolls/details/update",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                data: payrollDetails,  
                id: <?=json_decode($payroll->id)?>                              
            },                
            success:function(response){                
                window.location=response.url;
            },
        });
    });

    $(".add_payroll_details_hourly").on( 'click', function(event) {                                          
        addRowPayrollHourly();                 
    });

    $(".add_payroll_details_picking").on( 'click', function(event) {                                          
        addRowPayrollPicking();                 
    });
});

function addRowPayrollHourly(){             
    var date = $("input[name=date_hourly]").val();
    var job_name = $("select[name=job_name_hourly]").val();
    var total_hours = $("input[name=total_hours]").val();
    var time_start = $("input[name=time_start]").val();
    var time_end = $("input[name=time_end]").val();
    var rest_time = $("input[name=rest_time]").val();
    var job_rate = $("input[name=job_rate_hourly]").val();   

    if(date == ''){
        alert('Date is required!');              
    }
    else if(job_name == ''){
        alert('Job name is required!');
        $( "#job_name_hourly" ).focus();
    }
    else if(total_hours == ''){
        alert('Total hours is required!')
        $( "#total_hours" ).focus();
    } 
    else if(time_start == ''){
        alert('Time start is required!')
        $( "#time_start" ).focus();
    } 
    else if(time_end == ''){
        alert('Time end is required!')
        $( "#time_end" ).focus();
    }    
    else if(job_rate == ''){
        alert('Job rate is required!');
        $( "#job_rate_hourly" ).focus();
    }
    else{
        var total = (Math.round((total_hours * job_rate) * 100) / 100).toFixed(2);    
        var currentPayrollData = new payrollData(date, job_name, total_hours, time_start, time_end, rest_time, job_rate);
        TABLE_PAYROLL_DATA_HOURLY.push(currentPayrollData);

        var markup = "<tr><td>"+date+"</td><td>"+job_name+"</td><td style='text-align:center;'>"+time_start+"</td><td style='text-align:center;'>"+time_end+"</td><td style='text-align:center;'>"+rest_time+"</td><td style='text-align:center;'>"+total_hours+"</td><td>&nbsp;</td><td style='text-align:center;'>"+job_rate+"</td><td style='text-align:right;'>"+total+"</td>"
        +"<td style='text-align:center;'>"
            +"<a href='javascript:void(0);' style='font-size: 1em; color: Tomato;' class='remove_item'><i class='far fa-trash-alt'></i></a>"        
        +"</td></tr>";
        $("#job_details tbody").prepend(markup);
        
        CalcTotal();

        //clear input fields after populated in the table
        $("input[name=date_hourly").val('');
        $("input[select=job_name_hourly").val('');
        $("input[name=total_hours").val('');        
        $("input[name=job_rate_hourly").val('');    
        $("input[name=time_start").val('');    
        $("input[name=time_end").val('');    
        $("input[name=rest_time").val('');   
    }             
}
function addRowPayrollPicking(){  

    var date = $("input[name=date_picking]").val();
    var total_bin = $("input[name=total_bin]").val();
    var job_name = $("select[name=job_name_picking]").val();    
    var job_rate = $("input[name=job_rate_picking]").val();
    
    if(date == ''){
        alert('Date is required!');        
    }
    else if(job_name == ''){
        alert('Job name is required!');
        $( "#job_name_picking" ).focus();
    }
    else if(total_bin == ''){
        alert('Total bin is required!')
        $( "#total_bin" ).focus();
    }    
    else if(job_rate == ''){
        alert('Job rate is required!');
        $( "#job_rate_picking" ).focus();
    }
    else{
        var total = (total_bin * job_rate);
        var currentPayrollData = new payrollData2(date, job_name, total_bin, job_rate);
        TABLE_PAYROLL_DATA_PICKING.push(currentPayrollData);

        var markup = "<tr><td>"+date+"</td><td>"+job_name+"</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style='text-align:center;'>"+total_bin+"</td><td style='text-align:center;'>"+job_rate+"</td><td style='text-align:right;'>"+total+"</td>"
        +"<td style='text-align:center;'><a href='javascript:void(0);' style='font-size: 1em; color: Tomato;' class='remove_item'><i class='far fa-trash-alt'></i></a></td>"     
        +"</tr>";
        $("#job_details tbody").prepend(markup);
        
        // CalculateTotalPicking();
        CalcTotal();
        //clear input fields after populated in the table
        $("input[name=date_picking").val('');
        $("select[name=job_name_picking").val('');
        $("input[name=total_bin").val('');        
        $("input[name=job_rate_picking").val(''); 
    }                       
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function calculateTime() {
    //get values
    var valuestart = $("input[name='time_start']").val();
    var valuestop = $("input[name='time_end']").val();
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    //create date format          
    var timeStart = new Date(today+" " + valuestart).getHours();
    var timeEnd = new Date(today+" " + valuestop).getHours();
    
    var hourDiff = timeEnd - timeStart;    
    var rest_time = $('#rest_time').val();
    if(rest_time == '') rest_time = 0;
    //convert rest time minutes into hour
    var hr_rt = parseInt(rest_time)/60;  
    
    //total hour after rest
    hr_after_rest = parseFloat(hourDiff) - parseFloat(hr_rt);
    $('#total_hours').val(hr_after_rest.toFixed(2));        

    //calculate new total
    var job_rate = $('#job_rate').val();
    calNewTotal(hr_after_rest, job_rate); 
        
}
function calTime(){
    var valuestart = $("input[name='time_start']").val();
    var valuestop = $("input[name='time_end']").val();

    if (valuestart != "" && valuestop != "") {
        var tStart = parseTime(valuestart);
        var tStop = parseTime(valuestop);

        diff_time = (tStop - tStart)/(1000*60);
    }
    else {
        diff_time = "";
    }
    timeConvert(diff_time);
}
function parseTime(cTime){
    if (cTime == '') return null;
    var d = new Date();
    var time = cTime.match(/(\d+)(:(\d\d))?\s*(p?)/);
    d.setHours( parseInt(time[1]) + ( ( parseInt(time[1]) < 12 && time[4] ) ? 12 : 0) );
    d.setMinutes( parseInt(time[3]) || 0 );
    d.setSeconds(0, 0);
    return d;
}
function timeConvert(n) {
    var num = n;
    var hours = (num / 60);    
    
    var rest_time = $('#rest_time').val();
    if(rest_time == '') rest_time = 0;
    //convert rest time minutes into hour
    var hr_rt = parseInt(rest_time)/60;  
    
    //total hour after rest
    hr_after_rest = parseFloat(hours) - parseFloat(hr_rt);
    $('#total_hours').val(hr_after_rest); 
}
function calNewTotal(hr_after_rest, job_rate){
    var total = parseFloat(hr_after_rest*1) * parseFloat(job_rate*1);
    $('#totalSalary').text(total.toFixed(2));
    $('#total').val(total);
}

function CalculateTotal(total, allowance, deduction, emp_tax) {
    total = $('#total_aud').html();
    var gTotal = (parseFloat(total*1)+parseFloat(allowance*1)) - parseFloat(deduction*1 + emp_tax*1);
    $('#gTotal').text((Math.round(gTotal * 100) / 100).toFixed(2));
}
function CalcTotal() {
    var grandT = 0;
    $("#job_details > tbody > tr").each(function () {
        var t3 = $(this).find('td').eq(8).html();
        if (!isNaN(t3)) {
            grandT += parseFloat(t3);
        }
    });
    $("#total_aud").html((Math.round((grandT) * 100) / 100).toFixed(2));
    $('#total_salary').val(grandT);
    var allowance = $('#allowance').val();
    var deduction = $('#deduction').val();
    var emp_tax = $('#emp_tax').val();
    CalculateTotal(grandT, allowance, deduction, emp_tax);
}
function payrollData(date, job_name, total_hours, time_start, time_end, rest_time, job_rate){
    this.date = date;
    this.job_name = job_name;
    this.total_hours = total_hours;
    this.time_start = time_start;
    this.time_end = time_end;
    this.rest_time = rest_time;
    this.job_rate = job_rate;    
} 
function payrollData2(date, job_name, total_bin, job_rate){
    this.date = date;
    this.job_name = job_name;
    this.total_bin = total_bin;
    this.job_rate = job_rate;    
} 
</script>
@endsection