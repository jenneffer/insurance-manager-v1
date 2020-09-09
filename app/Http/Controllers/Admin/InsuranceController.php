<?php

namespace App\Http\Controllers\Admin;

use App\Insurance;
use App\Risk;
use App\InterestInsured;
use App\Perils;
use App\ExpenseCategory;
use App\Company;
use App\Agent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInsuranceRequest;
use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InsuranceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('insurance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $insurances = Insurance::all();        
        return view('admin.insurances.index', compact('insurances'));
    }

    public function create()
    {
        abort_if(Gate::denies('insurance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company = Company::all()->pluck('compDesc', 'id')->prepend(trans('global.pleaseSelect'), '');   

        $agent = Agent::all()->pluck('agentDesc', 'id')->prepend(trans('global.pleaseSelect'), '');      

        return view('admin.insurances.create', compact('company','agent'));
    }

    public function store(StoreInsuranceRequest $request)
    {
        $data = $request->all(); //form data
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  

        //insert into insurance table       
        $token = $param['_token'];
        $insurance_table = [
            'ins_agent' =>$param['ins_agent'],
            'ins_company' =>$param['company_id'],
            'ins_class' =>$param['ins_class'], 
            'ins_policy_no' =>$param['ins_policy_no'], 
            'ins_correspond_address' =>$param['ins_correspond_address'], 
            'ins_date_start' =>$param['ins_date_start'], 
            'ins_date_end' =>$param['ins_date_end'],
            'ins_issuing_branch' =>$param['ins_issuing_branch'], 
            'ins_issuing_date' =>$param['ins_issuing_date'], 
            'ins_gross_premium' =>$param['ins_gross_premium'],
            'ins_service_tax' =>$param['ins_service_tax'], 
            'ins_stamp_duty' =>$param['ins_stamp_duty'], 
            'ins_total_sum_insured' =>$param['ins_total_sum_insured'], 
            'ins_self_rating' => $param['ins_self_rating'],
            'ins_remark' => $param['ins_remark']
        ];
        //insert into insurance table
        $insurance = Insurance::create($insurance_table);
        //get the last insert id
        $lastInsertID = $insurance->id; 
        
        //risk tabs array
        $risk_location = $param['risk_location'];
        $risk_address = $param['risk_address'];
        $risk_count = count($risk_address);
        $risk_description = $param['risk_description'];
        $risk_construction_code = $param['risk_construction_code'];

        for ($i=1; $i <=$risk_count ; $i++) {
            $risk_data = array (
                'ins_id' => $lastInsertID,
                'risk_riskno' => $i,
                'risk_location' => $risk_location[$i],
                'risk_address' => $risk_address[$i],
                'risk_description' => $risk_description[$i],
                'risk_construction_code' => $risk_construction_code[$i]
            );   
            //insert into risk table
            $risk = Risk::create($risk_data);  
            $riskLastInsertID = $risk->id;
            //need to parse JSON
            $interest_insured = json_decode($param['interest_insured']);                 
            foreach ($interest_insured as $key => $value) {
                # code...
                foreach ($value as $risk_tab_id => $risk_data_ii) {
                    # code...
                    if($i == $risk_tab_id ){
                        $interestInsuredData = array(
                            'risk_id' => $riskLastInsertID,
                            'ii_item_no' => $risk_data_ii->ins_item_no,
                            'ii_description' => $risk_data_ii->ins_desc,
                            'ii_sum_insured' => $risk_data_ii->ins_sum_insured,
                        );

                        $interestInsured = InterestInsured::create($interestInsuredData);    
                    }
                    
                }
            }
            $perils = json_decode($param['perils']);  
            foreach ($perils as $key => $value) {
                # code...
                foreach ($value as $risk_tab_id => $risk_data_perils) {
                    # code...
                    if($i == $risk_tab_id ){
                        $perilsData = array(
                            'risk_id' => $riskLastInsertID,
                            'prls_ref_no' => $risk_data_perils->ins_code,
                            'prls_description' => $risk_data_perils->ins_desc_perils,
                            'prls_rate' => $risk_data_perils->ins_rate,
                        );

                        $perils = Perils::create($perilsData);    
                    }
                    
                }
            }  
        }

        // return redirect()->route('admin.insurances.index');
        return response()->json(['url'=>url('/admin/insurances')]);
    }

    public function edit(Insurance $insurance)
    {
        abort_if(Gate::denies('insurance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company = Company::all()->pluck('compDesc', 'id')->prepend(trans('global.pleaseSelect'), '');
        $agent = Agent::all()->pluck('agentDesc', 'id')->prepend(trans('global.pleaseSelect'), '');

        $insurance->load('company', 'created_by');
        $insurance->load('agent', 'created_by');
        $risk = DB::table('risk')->where('ins_id', '=', $insurance->id)->get();
                 
        $interest_insured = [];
        $perils = [];
        $arr_risk_id = [];
        foreach ($risk as $key => $value) {
            $risk_id = $value->id;
            $arr_risk_id[] = $risk_id;
            $interest_insured[$risk_id][] = DB::table('interest_insured')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
            $perils[$risk_id][] = DB::table('perils')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
        }
         $risk_count = count($arr_risk_id);   
         //get the lowest and highest tab index  
         $lowest_index = min($arr_risk_id);
         $highest_index = max($arr_risk_id);
        return view('admin.insurances.edit', compact('company','agent', 'insurance','risk','perils','interest_insured','risk_count','lowest_index','highest_index'));
    }

    public function update(UpdateInsuranceRequest $request, Insurance $insurance)
    {
        $data = $request->all();
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
        $ins_id = $param['ins_id'];        
        $insurance_table = [
            'ins_agent' =>$param['ins_agent'],
            'ins_company' =>$param['company_id'],
            'ins_class' =>$param['ins_class'], 
            'ins_policy_no' =>$param['ins_policy_no'], 
            'ins_correspond_address' =>$param['ins_correspond_address'], 
            'ins_date_start' =>$param['ins_date_start'], 
            'ins_date_end' =>$param['ins_date_end'],
            'ins_issuing_branch' =>$param['ins_issuing_branch'], 
            'ins_issuing_date' =>$param['ins_issuing_date'], 
            'ins_gross_premium' =>$param['ins_gross_premium'],
            'ins_service_tax' =>$param['ins_service_tax'], 
            'ins_stamp_duty' =>$param['ins_stamp_duty'], 
            'ins_total_sum_insured' =>$param['ins_total_sum_insured'], 
            'updated_at' => date('Y-m-d')
        ];
        //update insurance table
        $affected_ins = DB::table('insurances')
                      ->where('id', $ins_id)
                      ->update($insurance_table);
        
        //risk tabs array
        $risk_location = $param['risk_location'];
        $risk_address = $param['risk_address'];
        $risk_count = count($risk_address);
        $risk_description = $param['risk_description'];
        $risk_construction_code = $param['risk_construction_code'];
        $i = 0;
        foreach ($risk_address as $risk_id => $value) {
            # code...
            $i++;
            $risk_data = [
                    'ins_id' => $ins_id,
                    'risk_riskno' => $i,
                    'risk_location' => $risk_location[$risk_id],
                    'risk_address' => $risk_address[$risk_id],
                    'risk_description' => $risk_description[$risk_id],
                    'risk_construction_code' => $risk_construction_code[$risk_id]
                ];  
                //The updateOrInsert method will first attempt to locate a matching database record using the first argument's column and value pairs. If the record exists, it will be updated with the values in the second argument. If the record can not be found, a new record will be inserted with the merged attributes of both arguments
                $affected_risk = DB::table('risk')->updateOrInsert( ['id' => $risk_id], $risk_data );

                //need to parse JSON - insert newly added 
                $interest_insured = json_decode($param['interest_insured']);  

                if(!empty($interest_insured)){
                    foreach ($interest_insured as $key => $value) {
                    # code...
                        foreach ($value as $risk_tab_id => $risk_data_ii) {
                            # code... 
                            if($risk_id == $risk_tab_id ){
                                $interestInsuredData = array(
                                    'risk_id' => $risk_id,
                                    'ii_item_no' => $risk_data_ii->ins_item_no,
                                    'ii_description' => $risk_data_ii->ins_desc,
                                    'ii_sum_insured' => $risk_data_ii->ins_sum_insured,
                                );

                                $interestInsured = InterestInsured::create($interestInsuredData);    
                            }
                            
                        }
                    }

                }               

                $perils = json_decode($param['perils']);  
                if(!empty($perils)){
                    foreach ($perils as $key => $value) {
                    # code...
                        foreach ($value as $risk_tab_id => $risk_data_perils) {
                            # code...
                            if($risk_id == $risk_tab_id ){
                                $perilsData = array(
                                    'risk_id' => $risk_id,
                                    'prls_ref_no' => $risk_data_perils->ins_code,
                                    'prls_description' => $risk_data_perils->ins_desc_perils,
                                    'prls_rate' => $risk_data_perils->ins_rate,
                                );

                                $perils = Perils::create($perilsData);    
                            }
                            
                        }
                    }
                }
            }

        return response()->json(['url'=>url('/admin/insurances')]);
    }

    public function show(Insurance $insurance)
    {
        abort_if(Gate::denies('insurance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $insurance->load('company', 'created_by');
        
        //get risk
        $risk = DB::table('risk')->where('ins_id', '=', $insurance->id)->get();
        $interest_insured = [];
        $perils = [];
        foreach ($risk as $key => $value) {
            $risk_id = $value->id;
            $interest_insured[$risk_id][] = DB::table('interest_insured')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
            $perils[$risk_id][] = DB::table('perils')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
        }
        $insurance['risk'] = $risk;
        $insurance['interest_insured'] = $interest_insured;
        $insurance['perils'] = $perils;

        return view('admin.insurances.show',compact('insurance'));
    }

    public function destroy(Insurance $insurance)
    {
        abort_if(Gate::denies('insurance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $insurance->delete();

        return back();
    }

    public function massDestroy(MassDestroyInsuranceRequest $request)
    {
        Insurance::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function showPerils(Request $request)
    {
        $perils = DB::table('perils')->where('id', $request->id)->first();
        return response()->json($perils);

    }

    public function showInterestInsured(Request $request)
    {
        $interestInsured = DB::table('interest_insured')->where('id', $request->id)->first();
        return response()->json($interestInsured);     
    }

    public function updateInterestInsured(Request $request)
    {
        $data = $request->all(); //form data
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
        $ins_id = $param['ins_id'];
        $id = $param['id'];
        $item_no = $param['ii_item_no'];
        $desc = $param['ii_description'];
        $sum_insured = $param['ii_sum_insured'];

        $affected = DB::table('interest_insured')->where('id', $id)->update([
                    'ii_item_no' => $item_no, 
                    'ii_description' => $desc,
                    'ii_sum_insured' => $sum_insured
                ]);    

        return response()->json(['url'=>url('/admin/insurances/'.$ins_id.'/edit')]);
    }

    public function updatePerils(Request $request)
    {
        $data = $request->all(); //form data
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
        $ins_id = $param['ins_id'];
        $id = $param['prls_id'];
        $code = $param['prls_ref_no'];
        $desc = $param['prls_description'];
        $rate = $param['prls_rate'];

        $affected = DB::table('perils')->where('prls_id', $id)->update([
                    'prls_ref_no' => $code, 
                    'prls_description' => $desc,
                    'prls_rate' => $rate
                ]);    

        return response()->json(['url'=>url('/admin/insurances/'.$ins_id.'/edit')]);
    }
}
