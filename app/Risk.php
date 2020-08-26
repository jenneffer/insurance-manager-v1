<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risk extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'risk';
    // protected $dates = [
    //     'entry_date',
    //     'created_at',
    //     'updated_at',
    //     'deleted_at',
    // ];
    protected $fillable = [
        'ins_id',
        'risk_riskno',
        'risk_location',
        'risk_address',
        'risk_description',
        'risk_construction_code',       
    ];

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'id', 'ins_id');
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
