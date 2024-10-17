<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'subject',
        'note',
        'attachments', // This will store file paths as JSON
        'task_id',
    ];

    protected $casts = [
        'attachments' => 'array', // Cast to array when retrieving
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
