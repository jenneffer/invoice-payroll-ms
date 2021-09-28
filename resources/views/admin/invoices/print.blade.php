<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
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
                <p>INVOICE</p>
              </div>          
            </div>
            <div class="form-group row">    
              <div class="col-sm-8">
                <div class="form-item">
                    <label for="name">Contact Person</label>
                    <p>: Linus Lazarus</p>
                </div>
                <div class="form-item">
                    <label for="name">Mobile No.</label>
                    <p>: 0404223669</p>
                </div>
                <div class="form-item">
                    <label for="name">Email</label>
                    <p>: linusaaron82@gmail.com</p>
                </div>
              </div>              
              <div class="col-sm-4">
                <div class="form-item">
                    <label for="name">No.</label>
                    <p>: {{$invoice->invoice_number}}</p>
                </div>
                <div class="form-item">
                    <label for="name">Date</label>
                    <p>: {{date('d-m-Y', strtotime($invoice->date))}}</p>
                </div>
              </div> 
            </div>
            <div class="form-group">
                <div class="form-item">
                    <label>Company</label>
                    :&nbsp;{{$invoice->farm_company->comp_name}}
                </div>
                <div class="form-item">
                    <label>ABN</label>
                    :&nbsp;{{$invoice->farm_company->abn_no}}
                </div>
                <div class="form-item">
                    <label>Address</label>
                    :&nbsp;{{$invoice->farm_company->comp_address}}
                </div>                
            </div> 
            <br>
            <div class="form-group">
              <div class="form-item">
                    <label>Contact Person</label>
                    :&nbsp;{{$invoice->farm_company->contact_person}}
                </div>
                <div class="form-item">
                    <label>Mobile No</label>
                    :&nbsp;{{$invoice->farm_company->contact_no}}
                </div>
                <div class="form-item">
                    <label>Email</label>
                    :&nbsp;{{$invoice->farm_company->email}}
                </div>
            </div>  
            <br>
            <div class="form-group float-none">
              <p>Ref. (Work report from) :</p>
            </div>                                         
            <table class="table table-bordered table-striped">
              <thead>
                  <th>Date</th>
                  <th>Description</th>
                  <th class="text-right">Amount Charged(AUD)</th>                    
              </thead>
              <tbody>
                  @foreach($inv_details as $inv)                        
                      <tr data-entry-id="{{$inv->id}}">
                          <td>{{ date('d-m-Y', strtotime($inv->date)) ?? '' }}</td>
                          <td>{{ $inv->description ?? '' }}</td>
                          <td class="text-right">{{ number_format($inv->amount_charged,2) ?? '' }}</td>                            
                      </tr>
                  @endforeach
                  <tr>
                      <td colspan="2" class="text-right"><b>Sub Total (AUD)</b></td>
                      <td class="text-right"><b>${{ number_format($invoice->sub_total,2) }}</b></td>
                  </tr>
                  @if($invoice->farm_company->super == 1)
                  <tr>
                      <td colspan="2" class="text-right"><b>Super (9.5%)</b></td>
                      <td class="text-right"><b>${{ $invoice->super_amount }}</b></td>
                  </tr>
                  @endif
                  <tr>                        
                      <td colspan="2" class="text-right"><b>GST (10%)</b></td>
                      <td class="text-right"><b>${{ $invoice->gst }}</b></td>
                  </tr>
                  <tr>                        
                      <td colspan="2" class="text-right"><b>Total Amount (AUD)</b></td>
                      <td class="text-right"><b>${{ number_format($invoice->total_amount,2) }}</b></td>
                  </tr>
              </tbody>
            </table>
            <div class="form-group">
              <div class="form-item">
                  <label class="amt_word">(Amount in words)</label>
                  :&nbsp;{{$moneyText}}
              </div>
              <div class="form-item">
                  <label class="amt_word">Note</label>
                  :&nbsp;Payment due or receipt of this invoice
              </div>
              <div class="form-item">
                  <label class="amt_word">Bank</label>
                  :&nbsp;{{$invoice->bank}}
              </div>              
            </div>
            <div class="row">
                <div class="form-item col-sm-6">
                    <label class="amt_word">BSB</label>
                    :&nbsp;{{$invoice->bsb}}
                </div>
                <div class="form-item col-sm-6">
                    <label class="amt_word">ACC. No</label>
                    :&nbsp;{{$invoice->acc_no}}
                </div>
            </div>
            <div style="text-align:right;">
              <p><i>Thank you for your business.</i></p>
            </div> 
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

