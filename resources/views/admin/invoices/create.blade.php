@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.invoice.title_singular') }}
    </div>

    <div class="card-body">
        <!-- <form method="POST" action="{{ route("admin.invoices.store") }}" enctype="multipart/form-data"> -->
        <form id="invoiceForm" name="invoiceForm">              
        @csrf
        <input type="hidden" id="subTotal" name="subTotal" value="">
        <input type="hidden" id="gst" name="gst">
        <input type="hidden" id="superAmt" name="superAmt" value="0">
        <input type="hidden" id="farm_comp_super" name="farm_comp_super" value="">
        <input type="hidden" id="total_amount" name="total_amount" value="">  
        <input type="hidden" id="invoiceDetails" name="invoiceDetails" value=""> 
            <div class="row">
                <div class="form-group col-sm-4">
                    <label class="required" for="farm_company_id">Farm Company</label>
                    <select class="form-control select2 {{ $errors->has('farm_company') ? 'is-invalid' : '' }}" name="farm_company_id" id="farm_company_id" required>
                        @foreach($farm_company as $id => $fc)
                            <option value="{{ $id }}" {{ old('farm_company_id') == $id ? 'selected' : '' }}>{{ $fc }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('fc'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fc') }}
                        </div>
                    @endif
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="invoice_date">Invoice Date</label>
                    <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="invoice_date" id="invoice_date" value="{{ old('date') }}" required>
                    @if($errors->has('date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('date') }}
                        </div>
                    @endif
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="invoice_number">{{ trans('cruds.invoice.fields.invoice_number') }}</label>
                    <input class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}" type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $inv_str) }}" step="1" readonly>
                    @if($errors->has('invoice_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('invoice_number') }}
                        </div>
                    @endif                    
                </div>
            </div>
            <div class="row">                
                <div class="form-group col-sm-4">
                    <label class="required" for="bank">Bank</label>
                    <input class="form-control {{ $errors->has('bank') ? 'is-invalid' : '' }}" type="text" name="bank" id="bank" value="{{ old('bank', '') }}" required>
                    @if($errors->has('bank'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bank') }}
                        </div>
                    @endif
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="bsb">BSB</label>
                    <input class="form-control {{ $errors->has('bsb') ? 'is-invalid' : '' }}" type="text" name="bsb" id="bsb" value="{{ old('bsb', '') }}" required>
                    @if($errors->has('bsb'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bsb') }}
                        </div>
                    @endif
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="acc_no">Account Number</label>
                    <input class="form-control {{ $errors->has('acc_no') ? 'is-invalid' : '' }}" type="text" name="acc_no" id="acc_no" value="{{ old('acc_no', '') }}" required>
                    @if($errors->has('acc_no'))
                        <div class="invalid-feedback">
                            {{ $errors->first('acc_no') }}
                        </div>
                    @endif                    
                </div>
            </div>    
            <hr>  
            <div class="form-group">
                <p>Ref. (Work report from) :</p>
            </div>
            <div class="row col-sm-12">
                <div class="form-group col-sm-2">
                    <label for="inv_details_date">Date</label>
                    <input class="form-control date" type="text" name="inv_details_date" id="inv_details_date" value="">                    
                </div>                               
                <div class="form-group col-sm-6">
                    <label for="description">Description/Block</label>
                    <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('total_bin') }}">
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.email_helper') }}</span>
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount_charged">Amount Charged (AUD)</label>
                    <input class="form-control allow_decimal" type="text" name="amount_charged" id="amount_charged" value="">                    
                </div>
                <div class="form-group col-sm-2" >
                    <button type="button" class="btn btn-success add_invoice_details" style="position:absolute; bottom:0;">Add</button>
                </div>
            </div>
            <div class='col-sm-12'>
                <table class='table table-sm' id='invoice_details'>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description/Block</th>                                                        
                            <th style='text-align:right;'>Amount Charged (AUD)</th>    
                            <th>&nbsp;</th>                                
                        </tr>
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>                        
                    </tfoot>
                </table>
                <hr>
                <table class='table table-sm table-borderless'>
                    <tr>
                        <th colspan="2" style="text-align:right">Sub Total (AUD)</th>
                        <th style="text-align:right"><span id="sTotal">0.00</span></th>
                        <th></th>
                    </tr>
                    <tr id="super_tr">
                        <th colspan="2" style="text-align:right">Super (9.5%)</th>
                        <th style="text-align:right"><span id="superAmount">0.00</span></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right">GST (10%)</th>
                        <th style="text-align:right"><span id="gstAmount">0.00</span></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right">Total (AUD)</th>
                        <th style="text-align:right"><span id="gTotal">0.00</span></th>
                        <th></th>
                    </tr>
                </table>
            </div>
            <br>    
            <div class="form-group" style="text-align:center;">
                <button class="btn btn-danger"  type="submit">
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

var TABLE_INVOICE_DATA = [];

$(function () { 
    //check if the farm company is super
    $("#super_tr").hide();    
    $("#farm_company_id").on("change", function(){
        farm_id = $(this).val();
        $.ajax({
            url: "/admin/farm_companies/get_super",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                id: farm_id                    
            },                
            success:function(response){  
                $("#farm_comp_super").val(response);      
                if(response==1){
                    $("#super_tr").show();
                }else{
                    $("#super_tr").hide();
                }
            },
        });
    });
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

    $(".add_invoice_details").on( 'click', function(event) {                                                  
        addRowInvoiceDetails();                 
    });

    //remove row-data
    $("#invoice_details").on('click','.remove_item',function(){
        $(this).parent().parent().remove();
    });

    //submit form
    $('#invoiceForm').on('submit',function(event){           
        event.preventDefault();
        $("#invoiceDetails").val(JSON.stringify(TABLE_INVOICE_DATA));       
        var invoiceData = $('#invoiceForm').serialize();
        $.ajax({
            url: "/admin/invoices/add",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                data: invoiceData                    
            },                
            success:function(response){                
                window.location=response.url;
            },
        });
    });
});

function addRowInvoiceDetails(){
    var date = $("input[name=inv_details_date]").val();
    var desc = $("input[name=description]").val();
    var amt_charged = $("input[name=amount_charged]").val();      

    if(date == ''){
        alert('Date is required!');              
    }
    else if(desc == ''){
        alert('Description is required!');
        $( "#description" ).focus();
    }
    else if(amt_charged == ''){
        alert('Amount charged is required!');
        $( "#amount_charged" ).focus();
    }
    else{         
        var currentInvoiceData = new InvoiceData(date, desc, amt_charged);
        TABLE_INVOICE_DATA.push(currentInvoiceData);

        var markup = "<tr><td>"+date+"</td><td>"+desc+"</td><td style='text-align:right;'>"+amt_charged+"</td>"
        +"<td style='text-align:center;'>"
            +"<a href='javascript:void(0);' style='font-size: 1em; color: Tomato;' class='remove_item'><i class='far fa-trash-alt'></i></a>"        
        +"</td></tr>";
        $("#invoice_details tbody").append(markup);
        
        CalculateTotal();
        
        //clear input fields after populated in the table
        $("input[name=inv_details_date").val('');
        $("input[name=description").val('');
        $("input[name=amount_charged").val('');           
    }      
}
function InvoiceData(date, desc, amt_charged){
    this.inv_details_date = date;
    this.description = desc;
    this.amount_charged = amt_charged;    
} 

function CalculateTotal() {    
    var grandT = 0;
    var sTotal = 0;
    var gst = 0;
    var superAmt = 0;
    var farm_comp_super = $("#farm_comp_super").val();
    $("#invoice_details > tbody > tr").each(function () {
        var t3 = $(this).find('td').eq(2).html();
        if (!isNaN(t3)) {
            sTotal += parseFloat(t3);
        }
    });
    $("#sTotal").html(sTotal);
    //display total_amount - sTotal + gst
    var sT = parseFloat($('#sTotal').text());
    $('#subTotal').val(sT);
    //calculate super if exist
    if(farm_comp_super == 1){
        superAmt = sT*0.095;
        $("#superAmount").html(superAmt);
        $('#superAmt').val(superAmt);
    }    
    //calculate gst 10%
    gst = sT*0.1;
    $("#gstAmount").html(gst);
    $('#gst').val(gst);
    $('#gTotal').html((Math.round((sT+gst+superAmt) * 100) / 100).toFixed(2));
    var gTotal = parseFloat($('#gTotal').text());
    $('#total_amount').val(gTotal);
}
</script>
@endsection