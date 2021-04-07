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
        'ins_correspond_address',
        'ins_issuing_branch',
        'ins_issuing_date',
        'ins_mortgagee',
        'insurance_comp_id',
        'created_by_id'
    ];

    public function insurance_details(){
        return $this->hasMany(InsuranceDetails::class,'insurance_id');
    }
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'id');   
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'ins_company');       
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'ins_agent');   
    }

    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_comp_id');   
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
