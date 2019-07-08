<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTrack extends Model
{
    //
    protected $table   = 'wallets_track';
    protected $guarded = [];

    public $timestamps = false;

    public function wallet()
    {
        return $this->belongsTo('\App\Wallet');
    }
    
    public function getBalanceNowAttribute($value)
    {
        return rupiah($value);
    }
    
    public function getBalancePrevAttribute($value)
    {
        return rupiah($value);
    }
    
    public function getAmountAttribute($value)
    {
        return rupiah($value);
    }

    public function getRecordedAtAttribute($value)
    {
        return dateformat($value);
    }
}
