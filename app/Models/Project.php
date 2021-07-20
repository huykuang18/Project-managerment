<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'project_name',
        'manager_id',
        'customer',
        'description',
        'date_start',
        'date_end',
        'intend_time',
        'dev_quantity',
        'test_quantity'
    ];

    public function manager()
    {
        return $this->hasOne(User::class);
    }

    public function items()
    {
        return $this->hasMany(Module::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
