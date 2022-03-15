<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Payroll</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
        <style>
            body {
                font-family: sans-serif;
                font-size: 14px;
                margin: 10px;
            }
            #employee_details td {
                border:none;
                padding:5px;
            }
            #payslip_details th, td{
                border-collapse: collapse;
                border:solid;
                border-right:solid ;
                border-left:solid;
                border-top:solid;
                border-bottom:solid;
                border-width:1px;
                border-color:#c7c6c6;
                padding:8px;
            } 
            @page { margin: 5px; }
      </style>
    </head>
    <body>        
        <div class="container">
            <div class="form-group">                
                <h5><b>{{strtoupper('Linus Pty Ltd')}}</b></h5>  
                <p>ABN No: 74642074575</p>
                <p>ACN No: 642074574</p>
            </div>
            <div class="form-group">                
                <p>20 Jacobs Street</p>
                <p>WAIKERIE SA 5330</p>
            </div>
            <table class="table" id="employee_details" width="100%">
                <tr>   
                    <td>Employee</td>                                  
                    <td><b>{{$employee->emp_name}}</b></td>
                    <td style="text-align: right;">Date</td>
                    <td style="text-align: right; width:20%;"><b>: {{date('d/m/Y', strtotime($payroll->payroll_date))}}<b></td>                         
                </tr>
                <tr>          
                    <td>Email</td>                               
                    <td><b>{{$employee->emp_email}}</b></td> 
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>                   
                </tr>                               
            </table> 
            <br>         
            <table class="table" id="payslip_details" width="100%">     
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Job</th>
                        <th>Time Start</th>
                        <th>Time End</th>
                        <th class="text-center">Rest Time(Min)</th>
                        <th class="text-center">Total(Hours)</th>
                        <th class="text-center">Total(Bin)</th>
                        <th class="text-center">Rate(AUD)</th>
                        <th class="text-right">Total(AUD)</th>
                    </tr>
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
                        <td class="text-right"><b>+ {{ number_format($payroll->allowance,2) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right"><b>Deduction Other(AUD)</b></td>
                        <td class="text-right"><b>- {{ number_format($payroll->deduction,2) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right"><b>Deduction Tax(AUD)</b></td>
                        <td class="text-right"><b>- {{ number_format($payroll->emp_tax,2) }}</b></td>
                    </tr>
                    <tr>                        
                        <td colspan="8" class="text-right"><b>Grandtotal (AUD)</b></td>
                        <td class="text-right"><b>{{ number_format((($total_salary+$payroll->allowance) - ($payroll->deduction+$payroll->emp_tax)),2) }}</b></td>
                    </tr>
                </tbody>
            </table>                       
        </div>
    </body>
</html>
<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>

