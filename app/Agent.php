<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'agents';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'agentCode',
        'agentDesc',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'ins_agent', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
