<?php

namespace App\Http\Controllers\Admin;

use App\InsuranceCompany;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInsuranceCompanyRequest;
use App\Http\Requests\StoreInsuranceCompanyRequest;
use App\Http\Requests\UpdateInsuranceCompanyRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InsuranceCompanyController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('insurance_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $insuranceCompany = InsuranceCompany::all();

        return view('admin.insuranceCompany.index', compact('insuranceCompany'));
    }

    public function create()
    {
        abort_if(Gate::denies('insurance_company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.insuranceCompany.create');
    }

    public function store(StoreInsuranceCompanyRequest $request)
    {
        $insuranceCompany = InsuranceCompany::create($request->all());

        return redirect()->route('admin.insuranceCompany.index');
    }

    public function edit(InsuranceCompany $insuranceCompany)
    {
        abort_if(Gate::denies('insurance_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.insuranceCompany.edit', compact('insuranceCompany'));
    }

    public function update(UpdateInsuranceCompanyRequest $request, InsuranceCompany $insuranceCompany)
    {
        $insuranceCompany->update($request->all());

        return redirect()->route('admin.insuranceCompany.index');
    }

    public function show(InsuranceCompany $insuranceCompany)
    {
        abort_if(Gate::denies('insurance_company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.insuranceCompany.show', compact('insuranceCompany'));
    }

    public function destroy(InsuranceCompany $insuranceCompany)
    {
        abort_if(Gate::denies('insurance_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $insuranceCompany->delete();

        return back();
    }

    public function massDestroy(MassDestroyInsuranceCompanyRequest $request)
    {
        InsuranceCompany::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
