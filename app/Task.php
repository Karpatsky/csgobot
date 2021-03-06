<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['item_id', 'site_id', 'float', 'id', 'chat_id', 'pattern'];

    public function item(){
        return $this->belongsTo(Item::class);
    }
}
