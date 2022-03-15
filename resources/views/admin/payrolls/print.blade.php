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
            <div class="form-group" style="text-align: center;">
              <p style="font-size: 32px;">{{strtoupper('Linus Pty Ltd')}}</p>
              {{-- <p>ABN No: 74642074575</p>
              <p>ACN No: 642074574</p> --}}
            </div>
            <br>
            <div class="form-group row">
              {{-- <div class="col-sm-8">                
                <p>20 Jacobs Street</p>
                <p>WAIKERIE SA 5330</p>
              </div>                   --}}
              <div class="col-sm-4">
                <p>&nbsp;</p>
                <p style="font-size: 16px;">{{$employee->emp_name}}</p>
              </div>          
            </div>
            <div class="form-group row">    
              <div class="col-sm-7">
                {{-- <div class="form-item">
                    <label for="name">Employee</label>
                    <p>: {{$employee->emp_name}}</p>
                </div>
                <div class="form-item">
                    <label for="name">Email</label>
                    <p>: {{$employee->emp_email}}</p>
                </div> --}}
              </div>              
              <div class="col-sm-4">               
                <div class="form-item">
                    <label for="name">Date Paid </label>
                    <p style="text-align: right;"> {{date('d-m-Y', strtotime($payroll->payroll_date))}}</p>
                </div>
                <div class="form-item">
                    <label for="name">Pay Period Start </label>
                    <p style="text-align: right;"> {{date('d-m-Y', strtotime($min_date))}}</p>
                </div>
                <div class="form-item">
                  <label for="name">Pay Period End </label>
                  <p style="text-align: right;"> {{date('d-m-Y', strtotime($max_date))}}</p>
              </div>
              <div class="form-item">
                <label for="name">Employment Status </label>
                <p style="text-align: right;"> {{$employee->emp_status}}</p>
            </div>
              </div> 
            </div>                           
            <br>                                        
            <table class="table table-bordered table-striped">
            <thead>
                <th>Payment Type</th>
                <th>Description</th>
                <th class="text-center">Qty/Hrs</th>
                <th>Unit/Type/Desc</th>                
                <th class="text-center">Rate($)</th>
                <th class="text-right">Amount($)</th>
            </thead>
            <tbody>   
                @php
                    $salary = 0;
                    $total_salary = 0;
                @endphp             
                @foreach($job_details as $value)
                @php
                    $qty_hrs = $value['tot_hrs'] != 0 ? $value['tot_hrs'] : ""; 
                    $qty_bin = $value['tot_bin'] != 0 ? $value['tot_bin'] : ""; 
                    $desc_hrs = $value['description'] == 'hour' ? "Normal Hours" : "";
                    $desc_bin = $value['description'] == 'bin' ? "Bins" : "";
                    $salary = ($value['tot_hrs'] * $value['rate']) + ($value['tot_bin']*$value['rate']);
                    $total_salary += $salary;
                @endphp
                    <tr data-entry-id="">                                    
                        <td class="text-left">Pay Rate</td>
                        <td class="text-left">{{$desc_hrs}} {{$desc_bin}}</td>
                        <td class="text-center">{{$qty_hrs}} {{$qty_bin}}</td>
                        <td class="text-left">{{$value['job_name']}}</td>
                        <td class="text-center">{{$value['rate']}}</td>
                        <td class="text-right">{{number_format($salary, 2)}}</td>
                    </tr>
                @endforeach
                <tr data-entry-id="">                                    
                    <td>Super</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right"><b>Wages :</b></td>
                    <td class="text-right"><b>0</b></td>
                </tr>
                <tr>                        
                    <td colspan="5" class="text-right"><b>Super :</b></td>
                    <td class="text-right"><b>0</b></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right"><b>Gross :</b></td>
                    <td class="text-right"><b>0</b></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right"><b>Tax :</b></td>
                    <td class="text-right"><b>0</b></td>
                </tr>
                <tr>                        
                    <td colspan="5" class="text-right"><b>Net</b></td>
                    <td class="text-right"><b>{{number_format($total_salary,2)}}</b></td>
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

