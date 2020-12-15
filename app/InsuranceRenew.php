<?php

namespace App;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceRenew extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'insurance_details';

    protected $dates = [       
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'insurance_id',
        'policy_no',                
        'date_start',
        'date_end',
        'sum_insured',        
        'remark',        
    ];   

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
