<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPerilsRequest;
use App\Http\Requests\StorePerilsRequest;
use App\Http\Requests\UpdatePerilsRequest;
use App\Perils;
use Gate;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerilsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('perils_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $perils = Perils::all();

        return view('admin.perils.index', compact('perils'));
    }

    public function store(StorePerilsRequest $request)
    {
        $perils = perils::create($request->all());

        return redirect()->route('admin.perils.index');
    }

    public function edit(Perils $perils)
    {
        abort_if(Gate::denies('perils_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $perils = perils::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.perils.edit', compact('perils'));
    }

    public function update(UpdatePerilsRequest $request)
    {
        $perils->update($request->all());
        // return redirect()->route('admin.insurances.index');
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('perils_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $perils = DB::table('risk')
            ->join('additional_ins_item', 'additional_ins_item.risk_id', '=', 'risk.id')
            ->select('additional_ins_item.*')->where('ins_id', $request->id)->get();

        return response()->json($perils); 
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('perils_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::table('interest_insured')->where('id', $request->id)->delete();
        // $affected = DB::table('perils')->where('id', $request->id)->update([
        //     'deleted_at' => Carbon::now(),    
        // ]);      
        $affected = DB::table('additional_ins_item')->where('id', $request->id)->update([
            'deleted_at' => Carbon::now(),    
        ]); 
        return response()->json(['url'=>url('/admin/insurances/'.$request->ins_id.'/edit')]);
        // return back();
    }

    public function massDestroy(MassDestroyInterestInsuredRequest $request)
    {
        InterestInsured::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
