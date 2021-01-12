<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCompany extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'insurance_company';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ins_agent_code',
        'ins_agent_desc',
        'updated_at',
        'deleted_at',
    ];

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'insurance_comp_id', 'id');
    }

}
