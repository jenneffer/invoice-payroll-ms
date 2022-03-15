<head>
	<title>{{strtoupper('Linus Pty Ltd')}}</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
</head>
<div id="container">
    <div class="grid" id="invoice">
      <div class="form-group" style="text-align: center;">
        <p style="font-size: 32px;">{{strtoupper('Linus Pty Ltd')}}</p>

      </div>
      <br>                           
      <table>
        <tr>
          <td>{{$employee->emp_name}}</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Date Paid </td>
          <td> {{date('d-m-Y', strtotime($payroll->payroll_date))}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Pay Period Start</td>
          <td> {{date('d-m-Y', strtotime($min_date))}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Pay Period End  </td>
          <td> {{date('d-m-Y', strtotime($max_date))}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Employment Status </td>
          <td> {{$employee->emp_status}}</td>
        </tr>
      </table>               
      <table>
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
          {{-- @foreach($job_details as $value)
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
          @endforeach --}}
          <tr>                                    
            <td>Super</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
          <tr>                                    
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
    </div>           
</div>