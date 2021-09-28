<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Notifications\MonthlyMemberNotification;
use App\Invoice;
use App\FarmCompany;
use App\InvoiceDetails;
use App\Company;
use Gate;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class InvoicesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('invoice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoices = Invoice::all();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        // abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoice_yr = date('Y');
        $alphabetical = 'B';
        $numeric = $this->getLastInvoiceNumber(); //increment by 1
        
        $inv_str = $invoice_yr.$alphabetical.$numeric;
        
        $farm_company = FarmCompany::all()->pluck('comp_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.invoices.create', compact('farm_company','inv_str'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        // $invoice = Invoice::create($request->all());
        $data = $request->all(); //form data        
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
        $token = $param['_token'];
        $subTotal = $param['subTotal'];
        $superAmt = $param['superAmt'];
        $gst = $param['gst'];
        $total_amount = $param['total_amount'];
        $farm_company_id = $param['farm_company_id'];
        $invoice_date = $param['invoice_date'];
        $invoice_number = $param['invoice_number'];
        $bank = $param['bank'];
        $bsb = $param['bsb'];
        $acc_no = $param['acc_no'];

        //insert into invoices table
        $invoiceID = DB::table('invoices')->insertGetId([
            'farm_comp_id' => $farm_company_id,
            'date' => $invoice_date,
            'invoice_number' => $invoice_number,
            'acc_no' => $acc_no,
            'bank' => $bank,
            'bsb' => $bsb,
            'total_amount' => $total_amount,
            'gst' => $gst,
            'super_amount' => $superAmt,
            'sub_total' => $subTotal,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        //update the last_invoice_no in company table
        DB::table('company')
            ->where('id',1)
            ->update([
                'last_invoice_no' => DB::raw('last_invoice_no+1'),
                'updated_at' => Carbon::now()
            ]);
        
        //insert into invoice_details table
        $invoiceDetails = json_decode($param['invoiceDetails']);   
        foreach($invoiceDetails as $invoice){                                  
            
            DB::table('invoice_details')->insert([
                'invoice_id' => $invoiceID,
                'date' => $invoice->inv_details_date,
                'description' => $invoice->description,
                'amount_charged' => $invoice->amount_charged,
                'created_at' => Carbon::now()->toDateTimeString()
            ]);
              
        } 
        // return redirect()->route('admin.invoices.index');
        return response()->json(['url'=>url('/admin/invoices')]);

    }

    public function edit(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $students = Student::all()->pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invoice->load('invoice_details');
        $invoice->load('farm_company');
        $farm_company = FarmCompany::all()->pluck('comp_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.invoices.edit', compact('invoice','farm_company'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        // $invoice->update($request->all());
        $data = $request->all(); //form data 
        $invoice_id = $data['id'];   
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
        $subTotal = $param['subTotal'];
        $superAmt = $param['superAmt'];
        $gst = $param['gst'];
        $total_amount = $param['total_amount'];
        $farm_company_id = $param['farm_company_id'];
        $invoice_date = $param['invoice_date'];
        $invoice_number = $param['invoice_number'];
        $bank = $param['bank'];
        $bsb = $param['bsb'];
        $acc_no = $param['acc_no'];
        
        $invoice = Invoice::find($invoice_id);        
        $invoice->farm_comp_id =$farm_company_id;
        $invoice->date = $invoice_date;
        $invoice->invoice_number = $invoice_number;
        $invoice->acc_no = $acc_no;
        $invoice->bank = $bank;
        $invoice->bsb = $bsb;
        $invoice->total_amount = $total_amount;
        $invoice->gst = $gst;
        $invoice->super_amount = $superAmt;
        $invoice->sub_total = $subTotal;
        $invoice->save();
        return response()->json(['url'=>url('/admin/invoices')]);

        // return redirect()->route('admin.invoices.index');

    }

    public function show(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice->load('invoice_details');
        $invoice->load('farm_company');
        
        return view('admin.invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice->delete();

        return back();

    }

    public function massDestroy(MassDestroyInvoiceRequest $request)
    {
        Invoice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function togglePaid(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_toggle_paid'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paidAt = ($invoice->paid_at == null) ? now() : null;

        $invoice->paid_at = $paidAt;
        $invoice->save();

        return redirect()->back();
    }

    public function resend(Invoice $invoice)
    {
        $student = $invoice->student;

        Notification::route('mail', $student->email)->notify(new MonthlyMemberNotification($student, $invoice));

        return redirect()->back();
    }

    public function retrieve(Request $request)
    {
        $inv_details_id = $request->id;
        $invoice_details = InvoiceDetails::find($inv_details_id);

        return response()->json($invoice_details);

    }

    public function destroy_details(Request $request)
    {
        $inv_details = InvoiceDetails::find($request->id);
        $inv_details->delete();

        return response()->json(['url'=>url('/admin/invoices/'.$request->invoice_id.'/edit')]);

    }

    public function update_details(Request $request){
        $data = $request->all(); //form data         
        $param = array();
        $inv_id = $data['id'];
        parse_str($data['data'], $param); //unserialize jquery string data 
        if(!empty($param)){
            $inv_details_id = $param['invoice_details_id'];
            $date = $param['date'];
            $description = $param['description'];            
            $amount_charged = $param['amount_charged'];

            $inv_details = InvoiceDetails::find($inv_details_id);

            $inv_details->date = $date;
            $inv_details->description = $description;
            $inv_details->amount_charged = $amount_charged;

            $inv_details->save();

        } 
        
        // return redirect()->route('admin.payrolls.index');
        return response()->json(['url'=>url('/admin/invoices/'.$inv_id.'/edit')]);

    }
    public function print_invoice($id)
    {
        $invoice = Invoice::find($id);
        $invoice->load('farm_company');

        $moneyText = $this->numberTowords1($invoice->total_amount);
        $inv_details = InvoiceDetails::where('invoice_id', $id)->get();     
        
        return view('admin.invoices.print', compact('invoice','inv_details','moneyText'));

    }

    public static function getLastInvoiceNumber(){
        $rst = DB::table('company')->where('id',1)->first();
        $last_inv_no = (int)$rst->last_invoice_no + 1;//increment by 1
        return $last_inv_no;
    }
    public static function numberTowords1($num){

        $ones = array(
            0 =>"",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            "014" => "FOURTEEN"
        );
        $tens = array( 
            0 => "",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY", 
            4 => "FORTY", 
            5 => "FIFTY", 
            6 => "SIXTY", 
            7 => "SEVENTY", 
            8 => "EIGHTY", 
            9 => "NINETY" 
        ); 
        $hundreds = array( 
            "HUNDRED", 
            "THOUSAND", 
            "MILLION", 
            "BILLION", 
            "TRILLION", 
            "QUARDRILLION" 
        ); /*limit t quadrillion */
        $num = number_format($num,2,".",","); 
        $num_arr = explode(".",$num); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        krsort($whole_arr,1); 
        $rettxt = "";         
        foreach($whole_arr as $key => $i){
            
            while(substr($i,0,1)=="0")
            $i=substr($i,1,5);
            if($i < 20){ 
                /* echo "getting:".$i; */
                $rettxt .= $ones[$i]; 
            }elseif($i < 100){ 
                if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
                if(substr($i,1,1)!="0") $rettxt .= "-".$ones[substr($i,1,1)]; 
            }else{ 
                if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
                if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
                if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
            } 
            if($key > 0){ 
                $rettxt .= " ".$hundreds[$key]." "; 
            }   
            
            
        } 
        $rettxt .=" DOLLARS";
        if($decnum > 0){
            $rettxt .= " AND ";
            if($decnum < 20){
                $rettxt .= $ones[$decnum];                
                $rettxt .= " CENTS";
            }elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];                
                $rettxt .= " ".$ones[substr($decnum,1,1)];
                $rettxt .= " CENTS";
            }
        }
        return $rettxt;
    }
}
