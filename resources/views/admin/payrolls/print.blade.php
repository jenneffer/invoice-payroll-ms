<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Payroll</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
        <style>
          body{
            margin:20px;
            background:#eee;
          }
          .grid {
              position: relative;
              width: 100%;
              padding:50px;
              background: #fff;
              color: #666666;
              border-radius: 2px;
              /* font-family: "Times New Roman", Times, serif; */
              font-size: 14px;
              font-weight: bold;
              box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
          }
          .form-item{
            clear: left;
          }          
          .amt_word{
            float: left;
            width: 150px;
            clear: right;
          }
          label {
            float: left;
            width: 130px;
            clear: right;
          }
          @media print {
            #printPageButton {
              display: none;
            }
          }
          
        </style>
    </head>
    <body>
        <div id="container">
          <div class="grid" id="invoice">
            <div class="form-group">
              <p>Linus Pty Ltd</p>
              <p>ABN No: 74642074575</p>
              <p>ACN No: 642074574</p>
            </div>
            <br>
            <div class="form-group row">
              <div class="col-sm-8">                
                <p>20 Jacobs Street</p>
                <p>WAIKERIE SA 5330</p>
              </div>                  
              <div class="col-sm-4">
                <p>&nbsp;</p>
                <p>PAYSLIP</p>
              </div>          
            </div>
            <div class="form-group row">    
              <div class="col-sm-8">
                <div class="form-item">
                    <label for="name">Employee</label>
                    <p>: {{$employee->emp_name}}</p>
                </div>
                <div class="form-item">
                    <label for="name">Email</label>
                    <p>: {{$employee->emp_email}}</p>
                </div>
              </div>              
              <div class="col-sm-4">
                <!-- <div class="form-item">
                    <label for="name">No.</label>
                    <p>: {{$payroll->id}}</p>
                </div> -->
                <div class="form-item">
                    <label for="name">Date</label>
                    <p>: {{date('d-m-Y', strtotime($payroll->created_at))}}</p>
                </div>
              </div> 
            </div>                           
            <br>
            <div class="form-group float-none">
              <p>Job details :</p>
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
            <div style="text-align:center;">
              <a class="btn btn-primary" id="printPageButton" href="#" onclick="printDiv('invoice')">
                Print
              </a> 
            </div>          
          </div> 
                
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

