<?php
namespace App\Http\Controllers\Admin;

use App\Job;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyJobRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jobs = Job::all();
        
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        // abort_if(Gate::denies('company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.jobs.create');
    }

    public function store(StoreJobRequest $request)
    {
        $jobs = Job::create($request->all());

        return redirect()->route('admin.jobs.index');
    }

    public function edit(Job $job)
    {        
        // abort_if(Gate::denies('company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $job->load('created_by');

        return view('admin.jobs.edit', compact('job'));
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
        $job->update($request->all());

        return redirect()->route('admin.jobs.index');
    }

    public function show(Job $job)
    {
        // abort_if(Gate::denies('company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $job->load('created_by');

        return view('admin.jobs.show', compact('job'));
    }

    public function destroy(Job $job)
    {
        // abort_if(Gate::denies('company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $job->delete();

        return back();
    }

    public function massDestroy(MassDestroyJobRequest $request)
    {
        Job::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function get_job_info(Request $request){
        $job = Job::find($request->id);

        return response()->json(['success' => true, $job]);
    }

}
