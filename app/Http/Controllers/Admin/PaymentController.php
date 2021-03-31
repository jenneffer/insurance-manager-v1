<?php
namespace App\Http\Controllers\Admin;

use App\Payment;
use App\Company;
use App\User;
use App\Traits;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;
use Carbon\Carbon;


class PaymentController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment = Payment::all();        
        return view('admin.payments.index', compact('payment'));
    }

    public function create(){
        abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company = Company::all()->pluck('compDesc', 'id')->prepend(trans('All'), '');      

        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();
        
        $selected_company = "";

        return view('admin.payments.create',compact('company','start','end'));
    }
    // public function create(Request $request)
    // {
    //     $apiInsuranceDetailsController = new InsuranceDetailsController();
    //     $policy_no = $apiInsuranceDetailsController->getPolicyNumber($request->ins_details_id);
    //     $ins_details_id = $request->ins_details_id;        
    //     $insurance_id = $request->id;
    //     abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.payments.create',compact('policy_no','ins_details_id','insurance_id'));
    // }

    public function store(Request $request)
    {      
        $data = $request->all();
        $param = array();
        parse_str($data['data'], $param); //unserialize jquery string data  
 
        $paymentData = array(
            'company_id' => isset($param['company_id']) ? $param['company_id'] : "",
            'payment_to' => isset($param['payment_to']) ? $param['payment_to'] : "",
            'payment_mode' => isset($param['payment_mode']) ? $param['payment_mode'] : "",
            'paid_amount' => isset($param['paid_amount']) ? (double)$param['paid_amount'] : 0,
            'remark' => isset($param['remark']) ? $param['remark'] : "",
            'payment_date' => isset($param['payment_date']) ? $param['payment_date'] : "",            
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $payment = Payment::create($paymentData);

        //get last insert ID
        $paymentLastInsertID = $payment->id;
    
        if(!$payment->save()){
            App::abort(500, 'Error while saving');
        }else{
            //update the payment status di insurance_details table
            $ins_details_id = $param['insurance_details_id'];
            //convert insurance details id to array form
            $ins_detailsID = explode(",",$param['insurance_details_id']);
            DB::table('insurance_details')->whereIn('id', $ins_detailsID)->update([
                'payment_status' => 'paid',
                'payment_id' => $paymentLastInsertID
            ]);
                
            return response()->json(['url'=>url('/admin/payments')]);
        }        
    }

    public function edit(Payment $payment)
    {
        abort_if(Gate::denies('payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company->load('created_by');

        return view('admin.payments.edit', compact('company'));
    }

    // public function update(UpdateCompanyRequest $request, Company $company)
    // {
    //     $company->update($request->all());

    //     return redirect()->route('admin.companies.index');
    // }

    public function show(Payment $payment, Request $request)
    {                
        abort_if(Gate::denies('payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //get user session
        $sessionName = new UsersController();
        $user = $sessionName->getUsernameSession($request->user()->id);
      

        $paymentData = DB::table('insurance_details')
        ->select(['insurances.ins_class','insurance_details.policy_no', 'insurance_details.date_start', 'insurance_details.sum_insured','insurance_details.date_end', 'risk.risk_location', 'risk.risk_description', 'company.compDesc','policy_payment.payment_to','policy_payment.paid_amount', 'policy_payment.remark', 'policy_payment.payment_date', 'policy_payment.created_at', 'policy_payment.payment_mode'])
        ->join('insurances', 'insurances.id', '=', 'insurance_details.insurance_id')
        ->join('risk', 'risk.ins_id', '=', 'insurance_details.insurance_id')
        ->join('policy_payment', 'policy_payment.id', '=', 'insurance_details.payment_id')
        ->join('company', 'company.id', '=', 'policy_payment.company_id')        
        ->where('policy_payment.id', '=', $payment->id)
        ->get();
    
        //construct data
        $insData = [];
        foreach($paymentData as $data){
            $company = $data->compDesc;
            $pay_to = $data->payment_to;
            $created_date = $data->created_at;
            $payment_date = $data->payment_date;
            $paid_amount = $data->paid_amount;
            $payment_mode = $data->payment_mode;

            $insData[] = [
                'ins_class' => $data->ins_class,
                'policy_no' => $data->policy_no,
                'date_start' => $data->date_start,
                'date_end' => $data->date_end,
                'location' => $data->risk_location,
                'properties_insured' => $data->risk_description,
                'sum_insured' => $data->sum_insured,
            ];

        }
        $construtedData = [
            'company' => $company, 
            'pay_to' => $pay_to,
            'created_date' => $created_date,
            'payment_date' => $payment_date,
            'paid_amount' => $paid_amount,
            'payment_mode' => $payment_mode,
            'insurance_details' => $insData
        ];
        $moneyText = $this->numberTowords($paid_amount);
        return view('admin.payments.show', compact('construtedData','user','moneyText'));
    }

    public function destroy(Payment $payment)
    {
        abort_if(Gate::denies('payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->delete();

        return back();
    }

    public function massDestroy(MassDestroyPaymentRequest $request)
    {
        Company::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function InsuranceWithFilter ($select_company, $date_start, $date_end){
        $query = DB::table('insurances')
                            ->select(['insurance_details.id as ins_details_id', 'insurances.ins_class','insurance_details.policy_no', 'insurance_details.date_start', 'insurance_details.date_end','sum_insured','compCode','risk_description','risk_location'])
                            ->join('insurance_details', 'insurances.id','=','insurance_details.insurance_id')
                            ->join('company','company.id','=','insurances.ins_company')
                            ->join('risk','risk.ins_id','=','insurance_details.insurance_id')
                            ->where('payment_status','=','pending')
                            ->whereBetween('date_end', [$date_start, $date_end])
                            ->when($select_company, function ($query) use ($select_company){
                                return $query->where('ins_company', $select_company);                                
                            })
                            ->get();
        return $query;
        
    }

    public function filter(Request $request){
        $comp_id = $request->comp_id;
        $start = $request->date_start;
        $end = $request->date_end;

        $invList = $this->InsuranceWithFilter($comp_id, $start, $end);
        $arr_summary = [];
        foreach($invList as $row){
            $ins_details_id = $row->ins_details_id;
            $clasification = $row->ins_class;
            $policy_no = $row->policy_no;
            $date_start = $row->date_start;
            $date_end = $row->date_end;
            $sum_insured = $row->sum_insured;
            $compCode = $row->compCode;
            $risk_location = $row->risk_location;
            $risk_description = $row->risk_description;
           

            $data = array (
                "",   
                $ins_details_id,             
                $policy_no,
                $compCode,
                $clasification,
                $risk_location,
                $risk_description,
                number_format($sum_insured,2),
                $date_start,
                $date_end                         
            );

            $arr_summary[] = $data;
        }
        return response()->json($arr_summary);
    }

    public function create_payment(Request $request){
        $selected_ids = $request->selected_ids;
        $comp_id = $request->comp_id;

        //get company name
        $Company = new CompanyController();
        $comp = $Company->getCompanyName($comp_id);


        $list = DB::table('insurance_details')
        ->select(['insurance_details.id as ins_details_id','insurance_details.sum_insured','insurances.ins_class','insurance_details.policy_no', 'insurance_details.date_start', 'insurance_details.date_end', 'risk.risk_location', 'risk.risk_description', 'company.compDesc', 'insurance_company.ins_agent_desc'])
        ->join('insurances', 'insurances.id', '=', 'insurance_details.insurance_id')
        ->join('risk', 'risk.ins_id', '=', 'insurance_details.insurance_id')        
        ->join('company', 'company.id', '=', 'insurances.ins_company')
        ->join('insurance_company', 'insurance_company.id', '=', 'insurances.insurance_comp_id')
        ->whereIn('insurance_details.id', $selected_ids)
        ->get();

        $count = 0;
        $arr_summary = [];
        foreach($list as $row){
            $count++;
            $clasification = $row->ins_class;
            $policy_no = $row->policy_no;
            $date_start = $row->date_start;
            $date_end = $row->date_end;
            $risk_location = $row->risk_location;
            $risk_description = $row->risk_description;
            $sum_insured = $row->sum_insured;

            $data = array (
                "",
                $count,
                "<span>Classification : ".$clasification."</span><br><span>Policy No. : ".$policy_no."</span><br><span>Period of Insurance : ".$date_start." to ".$date_end."</span><br><span> Location : ".$risk_location."</span><br><span>Property Insured : ".$risk_description."</span>",
                number_format($sum_insured,2)            
            );

            $arr_summary[] = $data;
        }

        return response()->json(['insurance_data'=> $arr_summary,'company'=> $comp]);
    }
    public static function numberTowords(float $amount){
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $here_digits = array('', 'Hundred','Thousand','Million', 'Billion');
        while( $x < $count_length ) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
                '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
                '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
                }else $string[] = null;
            }
        $implode_to_rm = implode('', array_reverse($string));
        $get_cent = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
        " . $change_words[$amount_after_decimal % 10]) . ' Cents' : '';
        return ($implode_to_rm ? $implode_to_rm . 'Ringgit ' : '') . $get_cent;

    }
}
