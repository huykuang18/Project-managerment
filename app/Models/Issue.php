<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;
    protected $table = 'issues';
    protected $fillable = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function creater()
    {
        return $this->belongsTo(User::class);
    }
}
