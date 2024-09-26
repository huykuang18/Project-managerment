<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $fillable = [
        'project_id',
        'filename',
        'link',
        'file',
        'user_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
