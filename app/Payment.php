<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'policy_payment';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'insurance_details_id',
        'insurance_id',
        'policy_no',        
        'paid_amount',
        'remark',
        'payment_date',
        'payment_mode'
    ];

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'id', 'ins_id');
    }
    public function insurancesDetails()
    {
        return $this->belongsTo(InsuranceDetails::class, 'insurance_details_id','id');
    }

}
