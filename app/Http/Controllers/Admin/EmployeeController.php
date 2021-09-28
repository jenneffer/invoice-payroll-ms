<?php
namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee = Employee::all();
        
        return view('admin.employees.index', compact('employee'));
    }

    public function create()
    {
        // abort_if(Gate::denies('company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->all());

        return redirect()->route('admin.employees.index');
    }

    public function edit(Employee $employee)
    {
        // abort_if(Gate::denies('company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->load('created_by');

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());

        return redirect()->route('admin.employees.index');
    }

    public function show(Employee $employee)
    {
        // abort_if(Gate::denies('company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->load('created_by');

        return view('admin.employees.show', compact('employee'));
    }

    public function destroy(Employee $employee)
    {
        // abort_if(Gate::denies('company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmployeeRequest $request)
    {
        Employee::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public static function getEmployeeName($emp_id){
        $results = Employee::where(['id' => $emp_id])->first();
        return $results;
    }
}
