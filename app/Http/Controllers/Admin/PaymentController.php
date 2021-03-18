<?php
namespace App\Http\Controllers\Admin;

use App\Payment;
use App\Traits;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;


class PaymentController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment = Payment::all();

        return view('admin.payments.index', compact('payment'));
    }

    public function create(Request $request)
    {
        $apiInsuranceDetailsController = new InsuranceDetailsController();
        $policy_no = $apiInsuranceDetailsController->getPolicyNumber($request->ins_details_id);
        $ins_details_id = $request->ins_details_id;        
        $insurance_id = $request->id;
        abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.payments.create',compact('policy_no','ins_details_id','insurance_id'));
    }

    public function store(StorePaymentRequest $request)
    {      
        $payment = Payment::create($request->all());        
        if(!$payment->save()){
            App::abort(500, 'Error while saving');
        }else{
            //update the payment status di insurance_details table
            $ins_details_id = $request->insurance_details_id;
            if (DB::table('insurance_details')->where('id', $ins_details_id)->exists()) {
                DB::table('insurance_details')->where('id', $ins_details_id)->update([
                    'payment_status' => 'paid'
                ]);
            }
                
            return redirect()->route('admin.payments.index');
        }        
    }

    public function edit(Payment $payment)
    {
        abort_if(Gate::denies('payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company->load('created_by');

        return view('admin.payments.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->all());

        return redirect()->route('admin.companies.index');
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $insurance_id = $request->id;
        $ins_details_id = $request->ins_details_id;

        //get user session
        $sessionName = new UsersController();
        $user = $sessionName->getUsernameSession($request->user()->id);
        // var_dump($user->name);        

        $payment = DB::table('insurance_details')
        ->select(['insurance_details.id as ins_details_id', 'insurances.ins_class','insurance_details.policy_no', 'insurance_details.date_start', 'insurance_details.date_end', 'risk.risk_location', 'risk.risk_description', 'company.compDesc', 'insurance_company.ins_agent_desc','policy_payment.paid_amount', 'policy_payment.remark', 'policy_payment.payment_date', 'policy_payment.created_at', 'policy_payment.payment_mode'])
        ->join('insurances', 'insurances.id', '=', 'insurance_details.insurance_id')
        ->join('risk', 'risk.ins_id', '=', 'insurance_details.insurance_id')
        ->join('policy_payment', 'policy_payment.insurance_details_id', '=', 'insurance_details.id')
        ->join('company', 'company.id', '=', 'insurances.ins_company')
        ->join('insurance_company', 'insurance_company.id', '=', 'insurances.insurance_comp_id')
        ->where('risk.ins_id', '=', $insurance_id)
        ->where('insurance_details.id', '=', $ins_details_id)
        ->first();
        //get money value in text
        $moneyText = $this->numberTowords($payment->paid_amount);
        return view('admin.payments.show', compact('payment','moneyText','user'));
    }

    public function destroy(Payment $payment)
    {
        abort_if(Gate::denies('payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $company->delete();

        return back();
    }

    public function massDestroy(MassDestroyCompanyRequest $request)
    {
        Company::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
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
