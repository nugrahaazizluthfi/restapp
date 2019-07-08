<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $table   = 'wallets';
    protected $guarded = [];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function tracks()
    {
        return $this->hasMany('\App\WalletTrack')->orderBy('id', 'desc');
    }
}
