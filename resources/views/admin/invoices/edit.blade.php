@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Invoice
    </div>

    <div class="card-body">
        <!-- <form method="POST" action="{{ route("admin.invoices.update", [$invoice->id]) }}" enctype="multipart/form-data"> -->
            <form id="invoiceFormUpdate" name="invoiceFormUpdate">  
            @method('PUT')
            @csrf
            <input type="hidden" id="subTotal" name="subTotal" value="">
            <input type="hidden" id="gst" name="gst">
            <input type="hidden" id="superAmt" name="superAmt" value="0">
            <input type="hidden" id="total_amount" name="total_amount" value="">              
            <div class="row">
                <div class="form-group col-sm-4">
                    <label class="required" for="farm_company_id">Farm Company</label>
                    <select class="form-control select2 {{ $errors->has('farm_company') ? 'is-invalid' : '' }}" name="farm_company_id" id="farm_company_id" required>
                        @foreach($farm_company as $id => $fc)
                            <!-- <option value="{{ $id }}" {{ old('farm_company_id') == $id ? 'selected' : '' }}>{{ $fc }}</option> -->
                            <option value="{{ $id }}" {{ ($invoice->farm_company ? $invoice->farm_company->id : old('farm_company_id')) == $id ? 'selected' : '' }}>{{ $fc }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('farm_company'))
                        <div class="invalid-feedback">
                            {{ $errors->first('farm_company') }}
                        </div>
                    @endif                    
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="invoice_date">Invoice Date</label>
                    <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="invoice_date" id="invoice_date" value="{{ old('date',$invoice->date) }}" required>
                    @if($errors->has('date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('date') }}
                        </div>
                    @endif                    
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="invoice_number">{{ trans('cruds.invoice.fields.invoice_number') }}</label>
                    <input class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}" type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
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
                    <input class="form-control {{ $errors->has('bank') ? 'is-invalid' : '' }}" type="text" name="bank" id="bank" value="{{ old('bank', $invoice->bank) }}" required>
                    @if($errors->has('bank'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bank') }}
                        </div>
                    @endif                    
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="bsb">BSB</label>
                    <input class="form-control {{ $errors->has('bsb') ? 'is-invalid' : '' }}" type="text" name="bsb" id="bsb" value="{{ old('bsb', $invoice->bsb) }}" required>
                    @if($errors->has('bsb'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bsb') }}
                        </div>
                    @endif                    
                </div>
                <div class="form-group col-sm-4">
                    <label class="required" for="acc_no">Account Number</label>
                    <input class="form-control {{ $errors->has('acc_no') ? 'is-invalid' : '' }}" type="text" name="acc_no" id="acc_no" value="{{ old('acc_no', $invoice->acc_no) }}" required>
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
            <table class="table table-bordered table-striped" id="inv_details_table">
                <thead>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-right">Amount Charged (AUD)</th>                    
                    <th class="text-center">Modify</th>
                </thead>
                <tbody>                    
                    @foreach($invoice->invoice_details as $inv_details)                        
                        <tr data-entry-id="{{$inv_details->id}}">
                            <td>{{ date('d-m-Y', strtotime($inv_details->date)) ?? '' }}</td>
                            <td>{{ $inv_details->description ?? '' }}</td>                            
                            <td class="text-right">{{ $inv_details->amount_charged ?? '' }}</td>
                            <td class="text-center">
                                <span id="{{$inv_details->id}}" data-toggle="modal" class="edit_data_invoice" data-target="#editInvoiceItem" style="color: #20a8d8;" title="Edit"><i class="fas fa-edit"></i></span>
                                <span id="{{$inv_details->id}}" data-toggle="modal" class="delete_data_invoice" data-target="#deleteInvoiceItem" style="color: Tomato;"  title="Delete"><i class="fas fa-trash-alt"></i></span>
                            </td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
            <table class='table table-borderless'>
                <tr>
                    <td colspan="2" class="text-right"><b>Sub Total (AUD)</b></td>
                    <td class="text-right"><span id="subtotal_amt">0.00</span></td>
                    <td>&nbsp;</td>
                </tr>
                @if($invoice->farm_company->super == 1)
                <tr id="super_tr">
                    <td colspan="2" class="text-right"><b>Super (9.5%)</b></td>
                    <td class="text-right"><span id="super_amt">0.00</span></td>
                    <td>&nbsp;</td>
                </tr>
                @endif
                <tr>                        
                    <td colspan="2" class="text-right"><b>GST (10%)</b></td>
                    <td class="text-right"><span id="gst_amt">0.00</span></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>                        
                    <td colspan="2" class="text-right"><b>Total Amount (AUD)</b></td>
                    <td class="text-right"><span id="total_amt">0.00</span></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <div class="form-group" style="text-align:center;">
                <a class="btn btn-default" href="{{ route('admin.invoices.index') }}">
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
<div id="editInvoiceItem" class="modal fade">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Invoice Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="update_invoice_details">                                
                <input type="hidden" id="invoice_details_id" name="invoice_details_id" value="">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input class="form-control date" type="text" name="date" id="date" value="">                    
                </div>                                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" type="text" name="description" id="description" value=""></textarea>                    
                </div>   
                <div class="form-group">
                    <label for="amount_charged">Amount Charged (AUD) : </label>
                    <input class="form-control" type="text" name="amount_charged" id="amount_charged" value="">          
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
<div class="modal fade" id="deleteInvoiceItem">
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
            <button type="button" id="delete_inv_details" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){ 
    //calculate subtotal,total amount and gst
    CalculateTotal();
    //retrieve
    $(document).on('click', '.edit_data_invoice', function(){
        var id = $(this).attr("id");               
        
        $('#invoice_details_id').val(id);
        $.ajax({
            url:"/admin/invoices/retrieve",
            method:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                id:id                
            },
            success:function(data){   
                console.log(data); 
                inv_date = formatDate(data.date);           
                $('#date').val(inv_date);
                $('#description').val(data.description);                   
                $('#amount_charged').val(data.amount_charged); 
    
                $('#editInvoiceItem').modal('show');
            }
        });
    }); 

    //delete
    $(document).on('click', '.delete_data_invoice', function(){
        var id = $(this).attr("id");                
        $('#delete_inv_details').data('id', id); //set the data attribute on the modal button
    });

    $( "#delete_inv_details" ).click( function() {
        var ID = $(this).data('id');               
        $.ajax({
            url:"/admin/invoices/details/destroy",
            method:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                id:ID,  
                invoice_id: '<?= json_encode($invoice->id); ?>'               
            },
            success:function(response){
                $('#deleteInvoiceItem').modal('hide');     
                window.location=response.url;
            }
        });
    });

    //update modal    
    $('#update_invoice_details').on('submit',function(event){           
        event.preventDefault();
        var invoiceDetails = $('#update_invoice_details').serialize();
        $.ajax({
            url: "/admin/invoices/details/update",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                data: invoiceDetails,  
                id: <?=json_decode($invoice->id)?>                              
            },                
            success:function(response){                
                window.location=response.url;
            },
        });
    });

    //update form
    $('#invoiceFormUpdate').on('submit',function(event){           
        event.preventDefault();
        var invoiceData = $('#invoiceFormUpdate').serialize();
        console.log(invoiceData);
        $.ajax({
            url: "/admin/invoices/update",
            type:"POST",               
            data:{
                "_token": "{{ csrf_token() }}",
                data: invoiceData,
                id: '<?= json_decode($invoice->id)?>'                 
            },                
            success:function(response){                
                window.location=response.url;
            },
        });
    });
});
function CalculateTotal() {
    var grandT = 0;
    var sTotal = 0;
    var gst = 0;
    var super_amt = 0;
    var farm_com_super = "<?= $invoice->farm_company->super; ?>";
    var superAmount = "<?= $invoice->super_amount; ?>";
    $("#inv_details_table > tbody > tr").each(function () {
        var t3 = $(this).find('td').eq(2).html();        
        if (!isNaN(t3)) {            
            sTotal += parseFloat(t3);
        }
    });
    $("#subtotal_amt").html(sTotal);
    //display total_amount - sTotal + gst
    var sT = parseFloat($('#subtotal_amt').text());
    $('#subTotal').val(sT);
    //calculate super 9.5% if farm company super
    if(farm_com_super == 1 && superAmount !=0){
        super_amt = sT*0.095;
        $("#super_amt").html(super_amt);
        $('#superAmt').val(super_amt);
    }    
    //calculate gst 10%
    gst = sT*0.1;
    $("#gst_amt").html(gst);
    $('#gst').val(gst);

    $('#total_amt').html((Math.round((sT+gst+super_amt) * 100) / 100).toFixed(2));
    var gTotal = parseFloat($('#total_amt').text());
    $('#total_amount').val(gTotal);
}
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}
</script>
@endsection