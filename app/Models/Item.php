<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'name',
        'project_id',
        'parent_id',
        'order'
    ];

    public function issue()
    {
        return $this->hasMany(Issue::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
