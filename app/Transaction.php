<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table   = 'transactions';
    protected $guarded = [];

    public function getAmountAttribute($value)
    {
        return rupiah($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return dateformat($value);
    }

    public function getStatusAttribute($value)
    {
        return ucwords($value);
    }
}
