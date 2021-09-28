<?php
namespace App\Http\Controllers\Admin;

use App\FarmCompany;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFarmCompanyRequest;
use App\Http\Requests\StoreFarmCompanyRequest;
use App\Http\Requests\UpdateFarmCompanyRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FarmCompanyController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company = FarmCompany::all();
        
        return view('admin.farm_companies.index', compact('company'));
    }

    public function create()
    {
        // abort_if(Gate::denies('company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.farm_companies.create');
    }

    public function store(StoreFarmCompanyRequest $request)
    {
        $farm_company = FarmCompany::create($request->all());

        return redirect()->route('admin.farm_companies.index');
    }

    public function edit(FarmCompany $farm_company)
    {
        // abort_if(Gate::denies('company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $farm_company->load('created_by');
        $checked = "";
        if($farm_company->super == 1) $checked = "checked";

        return view('admin.farm_companies.edit', compact('farm_company', 'checked'));
    }

    public function update(UpdateFarmCompanyRequest $request, FarmCompany $farm_company)
    {                
        $farm_company->comp_name = isset($request->comp_name) ? $request->comp_name : '';
        $farm_company->comp_address = isset($request->comp_address) ? $request->comp_address : '';
        $farm_company->abn_no = isset($request->abn_no) ? $request->abn_no : '';
        $farm_company->contact_person = isset($request->contact_person) ? $request->contact_person : '';
        $farm_company->contact_no = isset($request->contact_no) ? $request->contact_no : '';
        $farm_company->email = isset($request->email) ? $request->email : '';
        $farm_company->super = isset($request->super) ? 1 : 0;
        $farm_company->save();
        // $farm_company->update($request->all());

        return redirect()->route('admin.farm_companies.index');
    }

    public function show(FarmCompany $farm_company)
    {
        // abort_if(Gate::denies('company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $farm_company->load('created_by');        
        return view('admin.farm_companies.show', compact('farm_company'));
    }

    public function destroy(FarmCompany $farm_company)
    {
        // abort_if(Gate::denies('company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $farm_company->delete();

        return back();
    }

    public function massDestroy(MassDestroyFarmCompanyRequest $request)
    {
        FarmCompany::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public static function getCompanyName($comp_id){
        $results = FarmCompany::where(['id' => $comp_id])->first();
        return $results;
    }

    public function getSuper(Request $request){
        $results = FarmCompany::where(['id' => $request->id])->pluck('super')->first();
        return $results;
    } 
}
