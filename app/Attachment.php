<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'attachments';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ins_id',
        'file_path',
        'updated_at',
        'deleted_at',
    ];


    public function insurance()
    {
        return $this->belongsTo(Insurance::class, 'ins_id');       
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'ins_agent');   
    }
}
