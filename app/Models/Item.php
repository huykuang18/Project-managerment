<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [];

    public function issue()
    {
        return $this->hasMany(Issue::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
