<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Insurance extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'insurances';

    protected $dates = [       
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ins_agent',
        'ins_company',
        'ins_class',
        'ins_policy_no',
        'ins_correspond_address',
        'ins_date_start',
        'ins_date_end',
        'ins_issuing_branch',
        'ins_issuing_date',
        'ins_gross_premium',
        'ins_service_tax',
        'ins_stamp_duty',
        'ins_total_sum_insured',
        'ins_self_rating',
        'ins_remark'
    ];

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'ins_id');   
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'ins_company');       
    }

    public function getEntryDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEntryDateAttribute($value)
    {
        $this->attributes['entry_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
