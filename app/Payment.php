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
        'company_id',
        'payment_to',       
        'paid_amount',
        'remark',
        'payment_date',
        'payment_mode',
        'created_by_id'
    ];

    public function insurancesDetails()
    {
        return $this->hasMany(InsuranceDetails::class, 'payment_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');       
    }
    

}
