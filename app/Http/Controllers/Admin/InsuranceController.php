<?php

namespace App\Http\Controllers\Admin;

use App\Insurance;
use App\InsuranceCompany;
use App\Risk;
use App\InterestInsured;
use App\Perils;
use App\Company;
use App\Agent;
use App\InsuranceDetails;
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
        $insurances = InsuranceDetails::inactive()->latest('date_start')->get();
        // $insurances = InsuranceDetails::all();

        return view('admin.insurances.index', compact('insurances'));
    }

    public function create()
    {
        abort_if(Gate::denies('insurance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company = Company::all()->pluck('compDesc', 'id')->prepend(trans('global.pleaseSelect'), '');   

        $agent = Agent::all()->pluck('agentDesc', 'id')->prepend(trans('global.pleaseSelect'), '');      

        $insuranceCompany = InsuranceCompany::all()->pluck('ins_agent_desc', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.insurances.create', compact('company','agent','insuranceCompany'));
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
            'ins_correspond_address' =>$param['ins_correspond_address'],             
            'ins_issuing_branch' =>$param['ins_issuing_branch'], 
            'ins_issuing_date' =>$param['ins_issuing_date'],             
            'ins_mortgagee' => $param['ins_mortgagee'], 
            'insurance_comp_id' => $param['insurance_comp_id']           
        ];
        //insert into insurance table
        $insurance = Insurance::create($insurance_table);
        
        //get the last insert id
        $lastInsertID = $insurance->id; 

        // insert into insurance details table
        $insurance_details_table = [
            'insurance_id' => $lastInsertID,
            'policy_no' =>$param['ins_policy_no'], 
            'date_start' =>$param['ins_date_start'], 
            'date_end' =>$param['ins_date_end'],
            'gross_premium' =>$param['ins_gross_premium'],
            'service_tax' =>$param['ins_service_tax'], 
            'stamp_duty' =>$param['ins_stamp_duty'], 
            'sum_insured' =>$param['ins_total_sum_insured'] == '' ? 0 : $param['ins_total_sum_insured'], 
            'self_rating' => $param['ins_self_rating'],
            'excess' => $param['ins_excess'],
            'remark' => $param['ins_remark']
        ];
        $insurance_details = InsuranceDetails::create($insurance_details_table);

        //risk tabs array
        $risk_location = $param['risk_location'];
        $risk_address = $param['risk_address'];
        $risk_count = count($risk_address);
        $risk_description = $param['properties_insured'];
        
        for ($i=1; $i <=$risk_count ; $i++) {            
            $risk_data = array (
                'ins_id' => $insurance_details->insurance_id,
                'risk_riskno' => $i,
                'risk_location' => $risk_location[$i],
                'risk_address' => $risk_address[$i],
                'risk_description' => $risk_description[$i],                
            );   
            //insert into risk table
            $risk = Risk::create($risk_data);  
            $riskLastInsertID = $risk->id;
            //need to parse JSON
            // $interest_insured = json_decode($param['interest_insured']);                 
            // foreach ($interest_insured as $key => $value) {
            //     # code...
            //     foreach ($value as $risk_tab_id => $risk_data_ii) {
            //         # code...
            //         if($i == $risk_tab_id ){
            //             $interestInsuredData = array(
            //                 'risk_id' => $riskLastInsertID,
            //                 'ii_item_no' => $risk_data_ii->ins_item_no,
            //                 'ii_description' => $risk_data_ii->ins_desc,
            //                 'ii_sum_insured' => $risk_data_ii->ins_sum_insured,
            //             );

            //             $interestInsured = InterestInsured::create($interestInsuredData);    
            //         }
                    
            //     }
            // }
            $additional_items_id = [];
            $perils = json_decode($param['perils']);  
            foreach ($perils as $key => $value) {
                # code...
               
                foreach ($value as $risk_tab_id => $risk_data_perils) {
                    # code...
                    if($i == $risk_tab_id ){
                        $perilsData = array(
                            'risk_id' => $riskLastInsertID,
                            'ref_no' => $risk_data_perils->ins_code,
                            'description' => $risk_data_perils->ins_desc_perils,
                            'rate' => $risk_data_perils->ins_rate,
                            'sum_insured' => $risk_data_perils->ins_sum_insured,
                            'policy_no' => $param['ins_policy_no'] 
                        );
                        
                        $perils = Perils::create($perilsData);    
                        //kumpul id utk dimasukkan dalam table renewal_item_controller based on the current year
                        //get the last insert id
                        $perilslastInsertID = $perils->id;                         
                        $additional_items_id[$riskLastInsertID][] = $perilslastInsertID;
                    }                    
                }
            }  
            //insert into renewal_item_controller
            //implode additional_items_id & risk_tab_id
            $items_id = implode(",", $additional_items_id[$riskLastInsertID]);
            //get year of date start
            $ds_year = date('Y', strtotime($param['ins_date_start']));
            DB::table('renewal_item_controller')->insert([
                'ins_id' => $insurance_details->insurance_id,
                'year' =>$ds_year,
                'risk_id' => $riskLastInsertID,
                'item_id' => $items_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // return redirect()->route('admin.insurances.index');
        return response()->json(['url'=>url('/admin/insurances')]);
    }

    public function edit(Insurance $insurance)
    {        

        abort_if(Gate::denies('insurance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company = Company::all()->pluck('compDesc', 'id')->prepend(trans('global.pleaseSelect'), '');
        $agent = Agent::all()->pluck('agentDesc', 'id')->prepend(trans('global.pleaseSelect'), '');
        $insuranceCompany = InsuranceCompany::all()->pluck('ins_agent_desc','id')->prepend(trans('global.pleaseSelect'), '');

        $insurance->load('company', 'created_by');
        $insurance->load('agent', 'created_by');        
        $insurance->load('insurance_details', 'created_by');

        $risk = DB::table('risk')->where('ins_id', '=', $insurance->id)->get();     
        // $interest_insured = [];
        $perils = [];
        $arr_risk_id = [];

        $ins_details = [];//form array by year
        $current_year = date("Y");
        foreach ($insurance->insurance_details as $key => $value) {
            # code...
            $year = date('Y', strtotime($value->date_start));
            $ins_details[$year] = array(
                'id' => $value->id,
                'insurance_id' => $value->insurance_id,
                'policy_no' => $value->policy_no, 
                'self_rating' => $value->self_rating,
                'excess' => $value->excess,
                'remark' => $value->remark,
                'sum_insured' => $value->sum_insured,
                'gross_premium' => $value->gross_premium,
                'service_tax' => $value->service_tax,
                'stamp_duty' => $value->stamp_duty,
                'date_start' => $value->date_start,
                'date_end' => $value->date_end,  
                'policy_status' => $value->policy_status,  
            );
                
        }   
        // foreach ($risk as $key => $value) {

        //     $risk_id = $value->id;
        //     $arr_risk_id[] = $risk_id;
        //     // $interest_insured[$risk_id][] = DB::table('interest_insured')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
        //     $perils[$risk_id][] = DB::table('additional_ins_item')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
            
        // }
        $perils = [];        
        foreach ($risk as $key => $value) { 
                   
            $risk_id = $value->id;
            
            $arr_risk_id[] = $risk_id;
            $renewal_item_c = DB::table('renewal_item_controller')
                                    ->where('risk_id', '=', $risk_id)
                                    ->get();

            $perils_item = [];
            foreach($renewal_item_c as $items){
                $item_id = explode(",",$items->item_id);
                //form items array
                $perils_item[$items->year] = DB::table('additional_ins_item')->where('risk_id','=', $risk_id)->whereIn('id',$item_id)->whereNull('deleted_at')->get();
                
            }  
                                          
            $perils[$risk_id][] = $perils_item;
        }         
        $risk_count = count($arr_risk_id);   

        //policy status array
        $arr_policy_status = array(
            'inactive' => 'Inactive',
            'active' => 'Active',
            'on_hold' => 'On Hold',
            'cancelled' => 'Cancelled'
        );
        //get the lowest and highest tab index  

        return view('admin.insurances.edit', compact('company','agent','insurance','ins_details','current_year','risk','perils','risk_count', 'insuranceCompany','arr_policy_status'));
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
            'ins_correspond_address' =>$param['ins_correspond_address'],  
            'ins_issuing_branch' =>$param['ins_issuing_branch'], 
            'ins_issuing_date' =>$param['ins_issuing_date'] == ''? NULL : $param['ins_issuing_date'], 
            'insurance_comp_id' =>$param['insurance_comp_id'],
            'updated_at' => date("Y-m-d H:i:s"),
            
        ];

        //update insurance table
        $affected_ins = DB::table('insurances')
                      ->where('id', $ins_id)
                      ->update($insurance_table);
        
        //update insurance details table
        $arr_ins_details_id = $param['ins_details_id'];
        $arr_policy_no = $param['policy_no'];
        $arr_date_start = $param['date_start'];
        $arr_date_end = $param['date_end'];
        $arr_sum_insured = $param['sum_insured'];
        $arr_gross_premium = $param['gross_premium'];
        $arr_service_tax = $param['service_tax'];
        $arr_stamp_duty = $param['stamp_duty'];
        $arr_rate = $param['rate'];
        $arr_remark = $param['remark'];
        $arr_excess = $param['excess'];
        $policy_status = $param['policy_status'];
        foreach ($arr_ins_details_id as $key => $value) {
            # code...
            $insurance_details = [
                'policy_no' => $arr_policy_no[$key],
                'date_start' => $arr_date_start[$key],
                'date_end' => $arr_date_end[$key],
                'sum_insured' => str_replace(",", "", $arr_sum_insured[$key]),
                'gross_premium' => str_replace(",", "", $arr_gross_premium[$key]),
                'service_tax' => str_replace(",", "", $arr_service_tax[$key]),
                'stamp_duty' => str_replace(",", "", $arr_stamp_duty[$key]),
                'self_rating' => $arr_rate[$key],
                'remark' => $arr_remark[$key],
                'excess' => $arr_excess[$key],
                'policy_status' => $policy_status[$key]
            ];
            
            $affected_insurance_details = DB::table('insurance_details')
                                        ->where('id', $value)
                                        ->update($insurance_details);
        }

        //risk tabs array
        $risk_location = $param['risk_location'];
        $risk_address = $param['risk_address'];
        $risk_count = count($risk_address);
        $risk_description = $param['risk_description'];        
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
                ];  
                //The updateOrInsert method will first attempt to locate a matching database record using the first argument's column and value pairs. If the record exists, it will be updated with the values in the second argument. If the record can not be found, a new record will be inserted with the merged attributes of both arguments
                $affected_risk = DB::table('risk')->updateOrInsert( ['id' => $risk_id], $risk_data );

                //need to parse JSON - insert newly added 
                // $interest_insured = json_decode($param['interest_insured']);  

                // if(!empty($interest_insured)){
                //     foreach ($interest_insured as $key => $value) {
                //     # code...
                //         foreach ($value as $risk_tab_id => $risk_data_ii) {
                //             # code... 
                //             if($risk_id == $risk_tab_id ){
                //                 $interestInsuredData = array(
                //                     'risk_id' => $risk_id,
                //                     'ii_item_no' => $risk_data_ii->ins_item_no,
                //                     'ii_description' => $risk_data_ii->ins_desc,
                //                     'ii_sum_insured' => $risk_data_ii->ins_sum_insured,
                //                 );

                //                 $interestInsured = InterestInsured::create($interestInsuredData);    
                //             }
                            
                //         }
                //     }

                // }               

                $perils = json_decode($param['perils']);  
                if(!empty($perils)){
                    foreach ($perils as $key => $value) {
                    # code...
                        foreach ($value as $risk_tab_id => $risk_data_perils) {
                            # code...
                            if($risk_id == $risk_tab_id ){
                                $perilsData = array(
                                    'risk_id' => $risk_id,
                                    'ref_no' => $risk_data_perils->ins_code,
                                    'description' => $risk_data_perils->ins_desc_perils,
                                    'rate' => $risk_data_perils->ins_rate,
                                    'sum_insured' => $risk_data_perils->ins_sum_insured,
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
        $insurance->load('insurance_details', 'created_by');
        $ins_details = [];//form array by year
        foreach ($insurance->insurance_details as $key => $value) {
            # code...        
            $year = date('Y', strtotime($value->date_start));
            $ins_details[$year] = array(
                'id' => $value->id,
                'insurance_id' => $value->insurance_id,
                'policy_no' => $value->policy_no, 
                'self_rating' => $value->self_rating,
                'excess' => $value->excess,
                'remark' => $value->remark,
                'sum_insured' => $value->sum_insured,
                'gross_premium' => $value->gross_premium,
                'service_tax' => $value->service_tax,
                'stamp_duty' => $value->stamp_duty,
                'date_start' => $value->date_start,
                'date_end' => $value->date_end,                
            );
                
        } 
        
        //get risk
        
        // $risk = DB::table('risk')->where('ins_id', '=', $insurance->id)->get();
        // // $interest_insured = [];
        // $perils = [];
        // foreach ($risk as $key => $value) {            
        //     $risk_id = $value->id;
        //     $renewal_item_c = DB::table('renewal_item_controller')->where('ins_id', '=', $value->ins_id)->get();
        //     $perils_item = [];
        //     foreach($renewal_item_c as $items){
        //         $item_id = explode(",",$items->item_id);
        //         //form items array
        //         $perils_item[$items->year] = DB::table('additional_ins_item')->where('risk_id','=', $risk_id)->whereIn('id',$item_id)->whereNull('deleted_at')->get();
                
        //     }                       
        //     $perils[$risk_id][] = $perils_item;
        // }

        $risk = DB::table('renewal_item_controller')
                    ->join('risk','renewal_item_controller.risk_id','=','risk.id')
                    ->select('renewal_item_controller.year','renewal_item_controller.item_id','risk.*')
                    ->where('risk.ins_id','=',$insurance->id)->get();

        foreach($risk as $key => $value){
            $risk_id = $value->id;  
            $renewal_item_c = explode(',', $value->item_id);             
            $perils_item[$value->year] = DB::table('additional_ins_item')->where('risk_id','=', $risk_id)->whereIn('id',$renewal_item_c)->whereNull('deleted_at')->get();
              
            $perils[$risk_id][] = $perils_item;                        
        }
        
        $insurance['risk'] = $risk;
        // $insurance['interest_insured'] = $interest_insured;
        $insurance['perils'] = $perils;
        $insurance['ins_details'] = $ins_details;       
        
        return view('admin.insurances.show',compact('insurance'));
    }

    public function destroy(InsuranceDetails $insurance)
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
        // $perils = DB::table('perils')->where('id', $request->id)->first();
        $perils = DB::table('additional_ins_item')->where('id', $request->id)->first();
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
        $sum_insured = $param['sum_insured'];

        $affected = DB::table('additional_ins_item')->where('id', $id)->update([
                    'ref_no' => $code, 
                    'description' => $desc,
                    'rate' => $rate,
                    'sum_insured' => $sum_insured
                ]);    

        return response()->json(['url'=>url('/admin/insurances/'.$ins_id.'/edit')]);
    }
}
