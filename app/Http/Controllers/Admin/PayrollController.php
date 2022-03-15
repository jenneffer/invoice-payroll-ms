<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStudentRequest;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Requests\UpdatePayrollRequest;
use Illuminate\Support\Facades\Mail; 
use App\Mail\Email;
use App\Payroll;
use App\PayrollDetails;
use App\Employee;
use App\Job;
use Gate;
use DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayrollController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $payroll = DB::table('payroll')
        //             ->join('employee', 'employee.id', '=', 'payroll.emp_id')
        //             ->select('payroll.*', 'employee.emp_name', 'employee.emp_email')
        //             ->where('payroll.deleted_at', NULL)
        //             ->get();

        $payroll = Payroll::whereNull('deleted_at')->get();

        return view('admin.payrolls.index', compact('payroll'));
    }

    public function filter_daterange(Request $request){
        $from = $request->searchByFromdate;
        $to = $request->searchByTodate;
        if(request()->ajax()){
            $payroll = DB::table('payroll')
            ->join('employee', 'employee.id', '=', 'payroll.emp_id')
            ->select('payroll.*', 'employee.emp_name', 'employee.emp_email',DB::raw('((total_salary+allowance)-(deduction+emp_tax)) as payable_salary'), DB::raw('(SELECT min(DATE) FROM payroll_details WHERE payroll_id=payroll.id) as start_date'),DB::raw('(SELECT max(DATE) FROM payroll_details WHERE payroll_id=payroll.id) as end_date'))
            ->where('payroll.deleted_at', NULL)
            // ->whereBetween('payroll_date', array($from, $to))
            ->having('start_date','>=', $from)
            ->having('end_date','<=', $to)
            ->get();

            // $payroll = DB::select("SELECT p.*, e.emp_name, e.emp_email,
            //                 (SELECT min(DATE) FROM payroll_details WHERE payroll_id=p.id)AS sdate ,
            //                 (SELECT max(DATE) FROM payroll_details WHERE payroll_id=p.id)AS edate FROM payroll p
            //                 INNER JOIN employee e ON p.emp_id = e.id
            //                 HAVING sdate>='$from' AND edate <= '$to'
            //             ");
                        
            return datatables()->of($payroll)  
                    ->addIndexColumn()                       
                    ->addColumn('print', function($row){
                        return $this->getPrintColumn($row);
                    })
                    ->addColumn('start_date', function($row){
                        return $this->getStartDate($row);
                    })
                    ->addColumn('total_salary', function($row){
                        return number_format($row->total_salary,2);
                    })
                    ->addColumn('emp_status', function($row){
                        return 'Part-time';
                    })
                    ->addColumn('end_date', function($row){
                        return $this->getEndDate($row);
                    })                    
                    ->rawColumns(['action','print'])
                    ->make(true);
        }
        
    }
    protected function getStartDate($data): string 
    {
        $result = PayrollDetails::where('payroll_id', $data->id)->get();
        $p_date = [];
        foreach($result as $res){
            $p_date[] = $res->date;
        }

        usort($p_date, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });

        $min_date = $p_date[0];
        

        return $min_date;
    }
    protected function getEndDate($data): string 
    {
        $result = PayrollDetails::where('payroll_id', $data->id)->get();
        $p_date = [];
        foreach($result as $res){
            $p_date[] = $res->date;
        }

        usort($p_date, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
       
        $max_date = $p_date[count($p_date) - 1];

        return $max_date;
    }
    protected function getPrintColumn($data): string
    {
        // $printUrl = route('admin.payrolls.print',$data->id);  
        $printUrl = route('admin.payrolls.print',$data->id);        
        return "<a href='$printUrl' target='_blank' title='Print'>
                <i class='fas fa-print fa-2x'></i>
                </a>";
    }

    protected function getActionColumn($data): string
    {
        $showUrl = route('admin.payrolls.show', $data->id);
        $editUrl = route('admin.payrolls.edit', $data->id);
        $deleteUrl = route('admin.payrolls.destroy', $data->id);
        return "<a class='btn btn-xs btn-primary' data-value='$data->id' href='$showUrl'>View</a> 
                <a class='btn btn-xs btn-info' data-value='$data->id' href='$editUrl'>Edit</a>
                <form action='$deleteUrl' method='POST' onsubmit='return confirm('Are you sure?');' style='display: inline-block;'>
                    <input type='hidden' name='_method' value='DELETE'>
                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                    <input type='submit' class='btn btn-xs btn-danger' value='Delete'>
                </form>";
    }

    public function create()
    {
        // abort_if(Gate::denies('student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $employee = Employee::all();
        $job = Job::all();

        return view('admin.payrolls.create', compact('employee','job'));
    }

    public function store(StorePayrollRequest $request)
    {
        // $payroll = Payroll::create($request->all());
        $data = $request->all(); //form data        
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data            
        $token = $param['_token'];
        $empID = $param['emp_name'];
        $payroll_date = $param['payroll_date'];
        $totalAmount = $param['total_amount'];
        $allowance = !empty($param['allowance']) ? $param['allowance'] : 0;
        $deduction = !empty($param['deduction']) ? $param['deduction'] : 0;
        $emp_tax = !empty($param['emp_tax']) ? $param['emp_tax'] : 0;
        
        //insert into payroll table
        $payrollID = DB::table('payroll')->insertGetId([
            'emp_id' => $empID,
            'total_salary' => $totalAmount,
            'deduction' => $deduction,
            'emp_tax' => $emp_tax,
            'allowance' => $allowance,
            'payroll_date' => $payroll_date,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        
        //insert into payroll details
        $payrollDetailsHourly = json_decode($param['payrollDetailsHourly']);    

        foreach($payrollDetailsHourly as $ph){            
            $total = ((double)$ph->total_hours * (double)$ph->job_rate);            
            $payrollDataH = array(
                'payroll_id' => $payrollID,
                'job_id' => $ph->job_name,
                'total_hours' => $ph->total_hours,
                'time_start' => $ph->time_start,
                'time_end' => $ph->time_end,
                'time_rest' => $ph->rest_time,
                'rate' => $ph->job_rate,
                'date' => $ph->date,    
                'total' => $total
            );
            
            PayrollDetails::create($payrollDataH);   
        }

        $payrollDetailsPicking = json_decode($param['payrollDetailsPicking']);        
        foreach($payrollDetailsPicking as $pp){
            $total = ((double)$pp->total_bin * (double)$pp->job_rate);            
            $payrollDataP = array(
                'payroll_id' => $payrollID,
                'job_id' => $pp->job_name,                
                'total_bin' => $pp->total_bin,
                'rate' => $pp->job_rate,
                'date' => $pp->date,    
                'total' => $total
            );
            
            PayrollDetails::create($payrollDataP);   
        }

        // return redirect()->route('admin.payrolls.index');
        return response()->json(['url'=>url('/admin/payrolls')]);
    }

    public function edit(Payroll $payroll)
    {
        // abort_if(Gate::denies('student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $employee_data = DB::table('payroll')
                    ->join('employee', 'employee.id', '=', 'payroll.emp_id')           
                    ->select('payroll.*', 'employee.emp_name', 'employee.emp_email')  
                    ->where('payroll.id', $payroll->id)                              
                    ->first();
    
        $payroll_details = DB::table('payroll_details')->where('payroll_id', $payroll->id)->where('deleted_at', NULL)->get();
        $job = Job::all();

        return view('admin.payrolls.edit',  compact('payroll','employee_data','payroll_details','job'));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll)
    {        
        // $payroll->update($request->all());
        $data = $request->all(); //form data 
        $payroll_id = $data['id'];   
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
        
        $payrollDetailsHourly = json_decode($param['payrollDetailsHourly']);
        
        foreach($payrollDetailsHourly as $ph){            
            $total = ((double)$ph->total_hours * (double)$ph->job_rate);            
            $payrollDataH = array(
                'payroll_id' => $payroll_id,
                'job_id' => $ph->job_name,
                'total_hours' => $ph->total_hours,
                'time_start' => $ph->time_start,
                'time_end' => $ph->time_end,
                'time_rest' => $ph->rest_time,
                'rate' => $ph->job_rate,
                'date' => $ph->date,    
                'total' => $total
            );
            
            PayrollDetails::create($payrollDataH);   
        }

        $payrollDetailsPicking = json_decode($param['payrollDetailsPicking']);        
        foreach($payrollDetailsPicking as $pp){
            $total = ((double)$pp->total_bin * (double)$pp->job_rate);            
            $payrollDataP = array(
                'payroll_id' => $payroll_id,
                'job_id' => $pp->job_name,                
                'total_bin' => $pp->total_bin,
                'rate' => $pp->job_rate,
                'date' => $pp->date,    
                'total' => $total
            );
            
            PayrollDetails::create($payrollDataP);   
        }

        $total_salary = $param['total_salary'];
        $payroll_date = $param['payroll_date'];
        $allowance = $param['allowance'];
        $deduction = $param['deduction'];
        $emp_tax = $param['emp_tax'];

        $affected = DB::table('payroll')
              ->where('id', $payroll_id)
              ->update([
                  'total_salary' => $total_salary,
                  'deduction' => $deduction,
                  'emp_tax' => $emp_tax,
                  'payroll_date' => $payroll_date,
                  'allowance' => $allowance,
                  'updated_at' => Carbon::now()->toDateTimeString()
                ]);
        // return redirect()->route('admin.payrolls.index');
        return response()->json(['url'=>url('/admin/payrolls')]);

    }

    public function show(Payroll $payroll)
    {            
        $payroll->load('payroll_details');    
        $payroll->load('employee');
        // abort_if(Gate::denies('student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $employee_data = DB::table('payroll')
            ->join('employee', 'employee.id', '=', 'payroll.emp_id')           
            ->select('payroll.*', 'employee.emp_name', 'employee.emp_email')  
            ->where('payroll.id', $payroll->id)          
            ->first();        
        // $payroll_details = PayrollDetails::where('payroll_id', $payroll->id)->get();
        return view('admin.payrolls.show', compact('payroll','employee_data'));
    }

    public function destroy(Request $request, $id)
    {
        // abort_if(Gate::denies('student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $affected = DB::table('payroll')->where('id', $id)->update([
            'deleted_at' => Carbon::now(),    
        ]);         
        return back();

    }

    public function destroy_details(Request $request)
    {
        // abort_if(Gate::denies('student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $affected = DB::table('payroll_details')->where('id', $request->id)->update([
            'deleted_at' => Carbon::now(),    
        ]); 
        return response()->json(['url'=>url('/admin/payrolls/'.$request->payroll_id.'/edit')]);
        // return back();

    }

    public function massDestroy(MassDestroyPayrollRequest $request)
    {
        Payroll::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function retrieve(Request $request)
    {
        $payroll_details_id = $request->id;
        $payroll_details = DB::table('payroll_details')->find($payroll_details_id);

        return response()->json($payroll_details);

    }

    public function update_details(Request $request){
        $data = $request->all(); //form data         
        $param = array();
        $payroll_id = $data['id'];
        parse_str($data['data'], $param); //unserialize jquery string data 
        if(!empty($param)){
            $payroll_details_id = $param['payroll_details_id'];
            $date = $param['date'];
            $total = $param['total'];            
            $time_start = !empty($param['time_start']) ? $param['time_start'] : "00:00:00";
            $time_end = !empty($param['time_end']) ? $param['time_end'] : "00:00:00";
            $rest_time = !empty($param['rest_time']) ? $param['rest_time'] : 0;
            $total_hours = !empty($param['total_hours']) ? $param['total_hours'] : 0;
            $total_bin = !empty($param['total_bin']) ? $param['total_bin'] : 0;
            $job_rate = $param['job_rate'];

            $affected = DB::table('payroll_details')
            ->where('id', $payroll_details_id)
            ->update([
                'date' => $date,
                'total_hours' => $total_hours,
                'total_bin' => $total_bin,
                'rate' => $job_rate,
                'total' => $total,
                'time_start' => $time_start,
                'time_end' => $time_end,
                'time_rest' => $rest_time,
                'updated_at' => date('Y-m-d h:i:s')
            ]);

        } 
        
        // return redirect()->route('admin.payrolls.index');
        return response()->json(['url'=>url('/admin/payrolls/'.$payroll_id.'/edit')]);

    }

    public function print_payroll($id)
    {
        $payroll = Payroll::find($id);
        $employee = DB::table('employee')->where('id',$payroll->emp_id)->first();
        // $moneyText = $this->numberTowords1($invoice->total_amount);
        $payroll_details = PayrollDetails::where('payroll_id', $id)->get();     
        $p_date = [];  
        $job = [];   
        foreach($payroll_details as $pd){
            // var_dump($pd);
            $p_date[] = $pd->date;
            // $job_details[$pd->job->id] = array(
            //     'job_name' => $pd->job->job_name,
            //     'description' => $pd->job->job_pay_method,
            //     'tot_hrs' => ($job[$pd->job->id]['tot_hrs'] ?? 0) + $pd->total_hours,
            //     'tot_bin' => ($job[$pd->job->id]['tot_bin'] ?? 0) + $pd->total_bin,
            //     'rate' => $pd->rate 
            // );
            $job[$pd->job->id][] = array(
                'job_name' => $pd->job->job_name,
                'description' => $pd->job->job_pay_method,
                'tot_hrs' => number_format($pd->total_hours,2),
                'tot_bin' => $pd->total_bin,
                'rate' => $pd->rate 
            );
        }
        $job_details = [];
        foreach ($job as $key => $value) {                        
            foreach ($value as $val) {
                if(isset($job_details[$key]['tot_hrs'])){
                    $job_details[$key]['tot_hrs'] += $val['tot_hrs'];
                }else{
                    $job_details[$key]['tot_hrs'] = $val['tot_hrs'];
                }

                if(isset($job_details[$key]['tot_bin'])){
                    $job_details[$key]['tot_bin'] += $val['tot_bin'];
                }else{
                    $job_details[$key]['tot_bin'] = $val['tot_bin'];
                }

                $job_details[$key]['job_name'] = $val['job_name'];
                $job_details[$key]['description'] = $val['description'];
                $job_details[$key]['rate'] = $val['rate'];
            }
        }
        
        usort($p_date, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });  
        
        $min_date = $p_date[0];
        $max_date = $p_date[count($p_date) - 1];
        $total_salary = $payroll->total_salary;
        $pdf = PDF::loadView('mails.payslip', compact('payroll','payroll_details','employee','min_date','max_date','job_details'));
        return $pdf->stream($employee->emp_name.'_'.$payroll->payroll_date.'.pdf');
        // return view('admin.payrolls.print', compact('payroll','payroll_details','employee','min_date','max_date','job_details'));

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

    public function send_email(Request $request)
    {
        $id = $request->id;
        $payroll = Payroll::find($id);
        $employee = DB::table('employee')->where('id',$payroll->emp_id)->first();    
        $payroll_details = PayrollDetails::where('payroll_id', $id)->get();     
        //sent email to administrator/moderator of the submission
        $objDemo = new \stdClass();                        
        $objDemo->receiver = $employee->emp_email;
        // $objDemo->receiver = 'j.jennefferj@gmail.com';
        $objDemo->receiverName = $employee->emp_name;
        $objDemo->payroll = $payroll;
        $objDemo->payroll_details = $payroll_details;
        $objDemo->employee = $employee;        
        $objDemo->sender = 'payslip-info@linusaussie.link';
        
        Mail::send(new Email($objDemo));

        $response = [];
        //check for failure
        if (Mail::failures()) {
            $response = array('msg' => 'Email failed to send!');
        }else{
            //update email_sent column in payroll table
            $payrollUpdate = Payroll::find($id);
            $payrollUpdate->email_sent = 1;
            $payrollUpdate->save();
            $response = array('msg' => 'Email successfully sent!');
        }

        return response()->json($response);
        
    }

}
