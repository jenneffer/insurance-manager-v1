<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInterestInsuredRequest;
use App\Http\Requests\StoreInterestInsuredRequest;
use App\Http\Requests\UpdateInterestInsuredRequest;
use App\InterestInsured;
use Gate;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InterestInsuredController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('interest_insured_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interestInsured = InterestInsured::all();

        return view('admin.interestInsured.index', compact('interestInsured'));
    }

    public function store(StoreInterestInsuredRequest $request)
    {
        $interestInsured = InterestInsured::create($request->all());

        return redirect()->route('admin.interestInsured.index');
    }

    public function edit(InterestInsured $interestInsured)
    {
        abort_if(Gate::denies('interestInsured_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interestInsured = interestInsured::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.interestInsured.edit', compact('interestInsured'));
    }

    public function update(UpdateInterestInsuredRequest $request)
    {
        $interestInsured->update($request->all());
        // return redirect()->route('admin.insurances.index');
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('interest_insured_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interestInsured = DB::table('risk')
            ->join('interest_insured', 'interest_insured.risk_id', '=', 'risk.id')
            ->select('interest_insured.*')->where('ins_id', $request->id)->get();

        return response()->json($interestInsured); 
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('interest_insured_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::table('interest_insured')->where('id', $request->id)->delete();
        $affected = DB::table('interest_insured')->where('id', $request->id)->update([
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
