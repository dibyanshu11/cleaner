<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected function getFilesAttribute($value){
        return json_decode($value);
    }


    public function sender()
    {
        return $this->belongsTo( User::class, 'sender_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo( User::class, 'rec_id', 'id');
    }
    
}
