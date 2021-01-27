<?php

namespace App\Http\Controllers\Admin;

use App\Risk;
use App\Http\Controllers\Controller;
// use App\Http\Requests\MassDestroyExpenseCategoryRequest;
// use App\Http\Requests\StoreExpenseCategoryRequest;
// use App\Http\Requests\UpdateExpenseCategoryRequest;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RiskController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('risk_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $risk = Risk::all();

        return view('admin.risk.index', compact('risk'));
    }

    public function create()
    {
        abort_if(Gate::denies('risk_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.risk.create');
    }

    public function store(StoreRiskRequest $request)
    {
        $risk = Risk::create($request->all());

        return redirect()->route('admin.risk.index');
    }

    public function edit(Risk $risk)
    {
        abort_if(Gate::denies('risk_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $risk->load('created_by');

        return view('admin.risk.edit', compact('risk'));
    }

    public function update(UpdateRiskRequest $request, Risk $risk)
    {
        $risk->update($request->all());

        return redirect()->route('admin.risk.index');
    }

    public function show(Risk $risk)
    {
        abort_if(Gate::denies('risk_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $risk->load('created_by');

        return view('admin.risk.show', compact('risk'));
    }

    public function destroy(Risk $risk)
    {
        abort_if(Gate::denies('risk_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $risk->delete();

        return back();
    }

    public function massDestroy(MassDestroyRiskRequest $request)
    {
        Risk::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getLastId(){
        $last_insert_id =  DB::table('risk')->orderBy('id', 'DESC')->first();
        $id = $last_insert_id->id;
        return $id;
    }
}
