@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Payroll
    </div>

    <div class="card-body">
        <!-- <form method="POST" action="{{ route("admin.payrolls.store") }}" enctype="multipart/form-data"> -->
        <form id="payrollForm" name="payrollForm">
            @csrf
            <input type='hidden' id='payrollDetailsHourly' name='payrollDetailsHourly' value=''> 
            <input type='hidden' id='payrollDetailsPicking' name='payrollDetailsPicking' value=''> 
            <div class="row">
                <div class="form-group col-sm-4">
                    <label class="required" for="emp_name">Employee Name</label>
                    <select name="emp_name" id="emp_name" class="form-control" >
                        <option value="">Select Employee</option>
                        @foreach($employee as $id => $emp)
                            <option value="{{ $emp->id }}">{{ strtoupper($emp->emp_name) }}</option>
                        @endforeach                   
                    </select>
                    @if($errors->has('emp_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('emp_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.first_name_helper') }}</span>
                </div>
                <div class="form-group col-sm-3">
                    <label class="required" for="payroll_date">Payroll Date</label>
                    <input class="form-control date" type="text" name="payroll_date" id="payroll_date" value="">    
                </div>
            </div>
            
            <div class="separator"><b>Pay Hourly</b></div>
            <br>
            <div class="row col-sm-12">
                <div class="form-group col-sm-2">
                    <label for="date">Date</label>
                    <input class="form-control date" type="text" name="date_hourly" id="date_hourly" value="">                    
                </div>
                <div class="form-group col-sm-2">
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
                <div class="form-group col-sm-2">
                    <label for="time_start">Time Start</label>
                    <input class="form-control {{ $errors->has('time_start') ? 'is-invalid' : '' }}" type="time" name="time_start" id="time_start" value="{{ old('time_start') }}">
                    @if($errors->has('total_hours'))
                        <div class="invalid-feedback">
                            {{ $errors->first('time_start') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div> 
                <div class="form-group col-sm-2">
                    <label for="time_end">Time End</label>
                    <input class="form-control {{ $errors->has('time_end') ? 'is-invalid' : '' }}" type="time" name="time_end" id="time_end" value="{{ old('time_end') }}">
                    @if($errors->has('total_hours'))
                        <div class="invalid-feedback">
                            {{ $errors->first('time_end') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div> 
                <div class="form-group col-sm-1">
                    <label for="rest_time">Rest Time (Min)</label>
                    <input class="form-control {{ $errors->has('rest_time') ? 'is-invalid' : '' }} allow_numeric" type="text" name="rest_time" id="rest_time" value="{{ old('rest_time') }}">
                    @if($errors->has('rest_time'))
                        <div class="invalid-feedback">
                            {{ $errors->first('rest_time') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div> 
                <div class="form-group col-sm-1">
                    <label for="total_hours">Total Hour(s)</label>
                    <input class="form-control {{ $errors->has('total_hours') ? 'is-invalid' : '' }}" type="text" name="total_hours" id="total_hours" value="{{ old('total_hours') }}">
                    @if($errors->has('total_hours'))
                        <div class="invalid-feedback">
                            {{ $errors->first('total_hours') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div>                
                <div class="form-group col-sm-1">
                    <label for="job_rate_hourly">Rate (AUD)</label>
                    <input class="form-control allow_decimal" type="text" name="job_rate_hourly" id="job_rate_hourly" value="" >                    
                </div>
                <div class="form-group col-sm-1" >
                    <button type="button" class="btn btn-success add_payroll_details_hourly" style="position:absolute; bottom:0;">Add</button>
                </div>
            </div>
            <div class='col-sm-12'>
                <table class='table table-sm' id='payroll_details_hourly'>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Job</th>                              
                            <th style='text-align:center;'>Time Start</th>        
                            <th style='text-align:center;'>Time End</th>     
                            <th style='text-align:center;'>Rest Time(min)</th>  
                            <th style='text-align:center;'>Total (Hours)</th>                         
                            <th style='text-align:center;'>Rate (AUD)</th>
                            <th style='text-align:right;'>Total (AUD)</th>    
                            <th>&nbsp;</th>                                
                        </tr>
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:right">Total Amount(AUD)</th>
                            <th style="text-align:right"><span id="gTotal">0.00</span></td>
                            <th></th>
                        </tr>                    
                    </tfoot>
                </table>
            </div>
            <br>
            <br>
            <div class="separator"><b>Fruit Picking</b></div>
            <br> 
            <div class="row col-sm-12">
                <div class="form-group col-sm-2">
                    <label for="date">Date</label>
                    <input class="form-control date" type="text" name="date_picking" id="date_picking" value="">                    
                </div>
                <div class="form-group col-sm-3">
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
                <div class="form-group col-sm-3">
                    <label for="total_bin">Total Bin(s)</label>
                    <input class="form-control {{ $errors->has('total_bin') ? 'is-invalid' : '' }} allow_decimal" type="text" name="total_bin" id="total_bin" value="{{ old('total_bin') }}">
                    @if($errors->has('total_bin'))
                        <div class="invalid-feedback">
                            {{ $errors->first('total_bin') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div>
                <div class="form-group col-sm-2">
                    <label for="job_rate_picking">Rate (AUD)</label>
                    <input class="form-control allow_decimal" type="text" name="job_rate_picking" id="job_rate_picking" value="">                    
                </div>
                <div class="form-group col-sm-2" >
                    <button type="button" class="btn btn-success add_payroll_details_picking" style="position:absolute; bottom:0;">Add</button>
                </div>
            </div>
            <div class='col-sm-12'>
                <table class='table table-sm' id='payroll_details_picking'>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Job</th>                            
                            <th style='text-align:center;'>Total (Bin)</th>
                            <th style='text-align:center;'>Rate (AUD)</th>
                            <th style='text-align:right;'>Total (AUD)</th>    
                            <th>&nbsp;</th>                                
                        </tr>
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:right">Total Amount(AUD)</th>
                            <th style="text-align:right"><span id="gTotalPicking">0.00</span></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br> 
            <hr>
            <div class='form-group col-sm-6'>
                <label for="total_amount">Total Amount (AUD)</label>
                <input class="form-control" type="text" name="total_amount" id="total_amount" value=""> 
            </div>
            <div class='form-group col-sm-6'>
                <label for="allowance">Bonus, Allowance, etc</label>
                <input class="form-control" type="text" name="allowance" id="allowance" value=""> 
            </div>
            <br>
            <br>
            <div class="separator"><b>Deduction</b></div>
            <br>  
            <div class='form-group'>
                <div class="col-sm-4">
                    <label for="deduction">Other (AUD)</label>
                    <input class="form-control" type="text" name="deduction" id="deduction" value="">                    
                </div>
                <br>
                <div class="col-sm-4">
                    <label for="emp_tax">Tax (AUD)</label>
                    <input class="form-control" type="text" name="emp_tax" id="emp_tax" value="">                    
                </div>
            </div>                         
            <div class="form-group text-center">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
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

        var markup = "<tr><td>"+date+"</td><td>"+job_name+"</td><td style='text-align:center;'>"+time_start+"</td><td style='text-align:center;'>"+time_end+"</td><td style='text-align:center;'>"+rest_time+"</td><td style='text-align:center;'>"+total_hours+"</td><td style='text-align:center;'>"+job_rate+"</td><td style='text-align:right;'>"+total+"</td>"
        +"<td style='text-align:center;'>"
            +"<a href='javascript:void(0);' style='font-size: 1em; color: Tomato;' class='remove_item'><i class='far fa-trash-alt'></i></a>"        
        +"</td></tr>";
        $("#payroll_details_hourly tbody").append(markup);
        
        CalculateTotal();

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

        var markup = "<tr><td>"+date+"</td><td>"+job_name+"</td><td style='text-align:center;'>"+total_bin+"</td><td style='text-align:center;'>"+job_rate+"</td><td style='text-align:right;'>"+total+"</td>"
        +"<td style='text-align:center;'><a href='javascript:void(0);' style='font-size: 1em; color: Tomato;' class='remove_item'><i class='far fa-trash-alt'></i></a></td>"     
        +"</tr>";
        $("#payroll_details_picking tbody").append(markup);
        
        CalculateTotalPicking();
        
        //clear input fields after populated in the table
        $("input[name=date_picking").val('');
        $("select[name=job_name_picking").val('');
        $("input[name=total_bin").val('');        
        $("input[name=job_rate_picking").val(''); 
    }                       
}

function CalculateTotal() {
    var grandT = 0;
    $("#payroll_details_hourly > tbody > tr").each(function () {
        var t3 = $(this).find('td').eq(7).html();
        if (!isNaN(t3)) {
            grandT += parseFloat(t3);
        }
    });
    $("#gTotal").html((Math.round((grandT) * 100) / 100).toFixed(2));
    //display total_amount - gTotal + gTotalPicking
    var gT = parseFloat($('#gTotal').text());
    var gT2 = parseFloat($('#gTotalPicking').text());
    console.log(gT+gT2);
    $('#total_amount').val((Math.round((gT+gT2) * 100) / 100).toFixed(2));
}

function CalculateTotalPicking() {
    var grandT = 0;
    $("#payroll_details_picking > tbody > tr").each(function () {
        var t3 = $(this).find('td').eq(4).html();
        if (!isNaN(t3)) {
            grandT += parseFloat(t3);
        }
    });
    $("#gTotalPicking").html((Math.round((grandT) * 100) / 100).toFixed(2));
    //display total_amount
    var gT = parseFloat($('#gTotal').text());
    var gT2 = parseFloat($('#gTotalPicking').text());
    console.log(gT+gT2);
    $('#total_amount').val((Math.round((gT+gT2) * 100) / 100).toFixed(2));
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
    $('#total_hours').val(hr_after_rest);         
        
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

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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


var TABLE_PAYROLL_DATA_HOURLY = [];
var TABLE_PAYROLL_DATA_PICKING = [];

$(function () {    
    var rate = 0;

    //validate input text 
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

    //remove row-data
    $("#payroll_details_hourly").on('click','.remove_item',function(){
        $(this).parent().parent().remove();
    });

    $("#payroll_details_picking").on('click','.remove_item',function(){
        $(this).parent().parent().remove();
    });
    
    $('#time_end').on('keyup keydown', function(){
        // calculateTime();
        calTime();
    });

    $('#rest_time').on('keyup', function(){
        var rest_time = $(this).val();
        $('#rest_time').val(rest_time);
        // calculateTime();
        calTime();
    });

    $(".add_payroll_details_hourly").on( 'click', function(event) {                                          
        addRowPayrollHourly();                 
    });

    $(".add_payroll_details_picking").on( 'click', function(event) {                                          
        addRowPayrollPicking();                 
    });

    $('#payrollForm').on('submit',function(event){           
        event.preventDefault();
        var emp_name = $("select[name=emp_name]").val();        
        if( !emp_name ){
            alert('Employee name is required!');  
            $("select[name=emp_name]").focus()      
        }else{
            $("#payrollDetailsHourly").val(JSON.stringify(TABLE_PAYROLL_DATA_HOURLY));
            $("#payrollDetailsPicking").val(JSON.stringify(TABLE_PAYROLL_DATA_PICKING));
            var payrollData = $('#payrollForm').serialize();
            $.ajax({
                url: "/admin/payrolls/add",
                type:"POST",               
                data:{
                    "_token": "{{ csrf_token() }}",
                    data: payrollData                    
                },                
                success:function(response){                
                    window.location=response.url;
                },
            });

        }
        
    });
});

</script>
@endsection