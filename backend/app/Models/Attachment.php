<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'original_filename'];

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }
}
