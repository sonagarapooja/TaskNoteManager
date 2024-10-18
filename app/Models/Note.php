<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'subject',
        'note',
        'attachment', // This will store file paths as JSON
        'task_id',
    ];

    protected $casts = [
        'attachment' => 'array', // Cast to array when retrieving
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
