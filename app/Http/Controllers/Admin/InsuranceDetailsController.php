<?php

namespace App\Http\Controllers\Admin;

use App\InsuranceDetails;
use App\Risk;
use App\Perils;
use App\Http\Controllers\Controller;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InsuranceDetailsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('insurance_renew'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ins_id = $request->id;                   
        return view('admin.insurances.renew', compact('ins_id'));
    }

    public function renew_with_addition(Request $request){
        $ins_id = $request->id;
        $risk = DB::table('risk')->where('ins_id', '=', $request->id)->get();    
        $perils = [];
        $arr_risk_id = [];
        foreach ($risk as $key => $value) {
            $risk_id = $value->id;
            $arr_risk_id[] = $risk_id;
            // $interest_insured[$risk_id][] = DB::table('interest_insured')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
            $perils[$risk_id][] = DB::table('additional_ins_item')->where('risk_id','=', $risk_id)->whereNull('deleted_at')->get();
            
        }
        $risk_count = count($arr_risk_id);   
        //get the lowest and highest tab index  
        $lowest_index = min($arr_risk_id);
        $highest_index = max($arr_risk_id);

    	return view('admin.insurances.renew_with_addition',compact('risk','perils','risk_count','lowest_index','highest_index','ins_id'));
    }

    public function renew_without_addition(Request $request){
        $ins_id = $request->id;
    	return view('admin.insurances.renew_without_addition', compact('ins_id'));
    }

    public function update_renewal
    ( Request $request ){ //renewal without addition
        $ins_id = $request->ins_id;
        $data = $request->data;
        $param = array();
        parse_str($data, $param); //unserialize jquery string data  

        $insurance_renew = [
            'insurance_id' => $ins_id,
            'policy_no' => $param['ins_policy_no'],
            'sum_insured' => $param['ins_total_sum_insured'] == '' ? 0 : $param['ins_total_sum_insured'] , 
            'date_start' => $param['ins_date_start'], 
            'date_end' => $param['ins_date_end'], 
            'remark' => $param['ins_remark'],               
        ];
        //insert into insurance details table
        $insurance = InsuranceDetails::create($insurance_renew); //create model insurancerenew besok
        return response()->json(['url'=>url('/admin/insurances')]);  
    }

    public function update_renewal_add(Request $request){ //renewal with addition
        $ins_id = $request->ins_id;
        $data = $request->data;
        $param = array();
        parse_str($data, $param);

        $insurance_renew = [
            'insurance_id' => $ins_id,
            'policy_no' => $param['ins_policy_no'],
            'sum_insured' => $param['ins_total_sum_insured'] == '' ? 0 : $param['ins_total_sum_insured'] , 
            'date_start' => $param['ins_date_start'], 
            'date_end' => $param['ins_date_end'], 
            'remark' => $param['ins_remark'],               
        ];                
        //insert into insurance details table
        $insurance = InsuranceDetails::create($insurance_renew); 
        
        //additional risk
        $risk_location = $param['risk_location'];
        $risk_address = $param['risk_address'];
        $properties_insured = $param['properties_insured'];

        //additional item 
        $perils = json_decode($param['perils']);      
        $additional_items_id = [];
        $risk_id = "";  //will fix later if the tab involve is more than 1  
        foreach ($perils as $key => $value) {
            # code...            
            foreach ($value as $risk_tab_id => $risk_data_perils) {
                # code...
                $perilsData = array(
                        'risk_id' => $risk_tab_id,
                        'ref_no' => $risk_data_perils->ins_code,
                        'description' => $risk_data_perils->ins_desc_perils,
                        'rate' => $risk_data_perils->ins_rate,
                        'policy_no' =>  $param['ins_policy_no'],
                        'sum_insured' => $risk_data_perils->ins_sum_insured,
                    );

                $perils = Perils::create($perilsData);

                //kumpul id utk dimasukkan dalam table renewal_item_controller based on the current year
                //get the last insert id
                $lastInsertID = $perils->id;
                $risk_id = $risk_tab_id; 
                $additional_items_id[] = $lastInsertID;
                
            }
        }

        //insert into renewal_item_controller
        //implode additional_items_id & risk_tab_id
        $items_id = implode(",", $additional_items_id);
        //get year of date start
        $ds_year = date('Y', strtotime($param['ins_date_start']));
        
        DB::table('renewal_item_controller')->insert([
            'ins_id' => $ins_id,
            'year' =>$ds_year,
            'risk_id' => $risk_id,
            'item_id' => $items_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json(['url'=>url('/admin/insurances')]);   
    }
}