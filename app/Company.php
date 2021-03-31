<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'company';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'compCode',
        'compDesc',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'ins_company', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'company_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
