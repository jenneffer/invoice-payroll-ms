<?php
namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company = Company::all();
        
        return view('admin.companies.index', compact('company'));
    }

    public function create()
    {
        // abort_if(Gate::denies('company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create($request->all());

        return redirect()->route('admin.companies.index');
    }

    public function edit(Company $company)
    {
        // abort_if(Gate::denies('company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company->load('created_by');

        return view('admin.companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->all());

        return redirect()->route('admin.companies.index');
    }

    public function show(Company $company)
    {
        // abort_if(Gate::denies('company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company->load('created_by');

        return view('admin.companies.show', compact('company'));
    }

    public function destroy(Company $company)
    {
        // abort_if(Gate::denies('company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company->delete();

        return back();
    }

    public function massDestroy(MassDestroyCompanyRequest $request)
    {
        Company::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public static function getCompanyName($comp_id){
        $results = Company::where(['id' => $comp_id])->first();
        return $results;
    }
}
