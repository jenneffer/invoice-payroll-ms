<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet"/>

        <title>{{strtoupper('Linus Pty Ltd')}}</title>
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
            @page { margin: 0px; }

        </style>
    </head>    
    <body>        
        <div class="container">
            <div class="form-group">
                <h3 style="text-align: center;"><b>{{strtoupper('Linus Pty Ltd')}}</b></h3>  
            </div>
            <br>
            <table class="table" id="employee_details" width="100%">
                <tr>                                    
                    <td colspan="6"><b>{{$employee->emp_name}}</b></td>                    
                </tr>
                <tr>   
                    <td colspan="5" style="text-align: right;">Date Paid</td>                                 
                    <td style="text-align: right; width:20%;">{{date('d/m/Y', strtotime($payroll->payroll_date))}}</td>                    
                </tr>
                <tr>     
                    <td colspan="5" style="text-align: right;">Pay Period Start</td>                                    
                    <td style="text-align: right;">{{date('d/m/Y', strtotime($min_date))}}</td>                    
                </tr>
                <tr>      
                    <td colspan="5" style="text-align: right;">Pay Period End</td>                                   
                    <td style="text-align: right;">{{date('d/m/Y', strtotime($max_date))}}</td>                    
                </tr>
                <tr>    
                    <td colspan="5" style="text-align: right;">Employment Status</td>                                    
                    <td style="text-align: right;">{{$employee->emp_status}}</td>                    
                </tr>
            </table>          
            <table class="table" id="payslip_details" width="100%">     
                <thead>
                    <tr>
                        <th>Payment Type</th>
                        <th>Description</th>
                        <th class="text-center">Qty/Hrs</th>
                        <th>Unit/Type/Desc</th>                
                        <th class="text-center">Rate($)</th>
                        <th class="text-right">Amount($)</th>
                    </tr>
                </thead>           
                <tbody>
                    @php
                        $total_salary = 0;
                        $tr_body = [];
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
                    <tr>                                    
                        <td class="text-left">Pay Rate</td>
                        <td class="text-left">{{$desc_hrs}} {{$desc_bin}}</td>
                        <td class="text-center">{{$qty_hrs}} {{$qty_bin}}</td>
                        <td class="text-left">{{$value['job_name']}}</td>
                        <td class="text-center">{{$value['rate']}}</td>
                        <td class="text-right">{{number_format($salary, 2)}}</td>
                    </tr>
                    @endforeach
                    <tr>                                    
                        <td>Super</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Wages :</td>
                        <td class="text-right">0</td>
                    </tr>
                    <tr>                        
                        <td colspan="5" class="text-right">Super :</td>
                        <td class="text-right">0</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Gross :</td>
                        <td class="text-right">0</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Tax :</td>
                        <td class="text-right">0</td>
                    </tr>
                    <tr>                        
                        <td colspan="5" class="text-right"><b>Net</b></td>
                        <td class="text-right"><b>{{number_format($total_salary,2)}}</b></td>
                    </tr>
                </tbody>
            </table>                       
        </div>
    </body>
</html>