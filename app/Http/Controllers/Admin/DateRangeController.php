<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStudentRequest;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Requests\UpdatePayrollRequest;
use App\Payroll;
use App\PayrollDetails;
use App\Employee;
use App\Job;
use Gate;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DateRangeController extends Controller
{
    function index(Request $request)
    {
        var_dump($request);
     if(request()->ajax())
     {
      if(!empty($request->from_date))
      {
       $data = DB::table('payroll')
        ->join('employee', 'employee.id', '=', 'payroll.emp_id')
        ->select('payroll.*', 'employee.emp_name', 'employee.emp_email')
        ->where('payroll.deleted_at', NULL)
         ->whereBetween('inv_date', array($request->from_date, $request->to_date))
         ->get();
      }
      else
      {
       $data = DB::table('payroll')
         ->get();
      }
      return datatables()->of($data)->make(true);
     }
     return view('admin.payrolls.index');
    }
}

?>
