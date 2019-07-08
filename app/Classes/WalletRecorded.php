<?php

namespace App\Classes;

use App\Wallet;

class WalletRecorded
{
    protected static $instance = null;
    private $_currentBalance = 0;
    private $_balance = 0;
    private $_amount = 0;
    private $_refId = '';
    private $_desc = '';
    private $_type = '';

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }

    public static function instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function setType($type = '')
    {
        $this->_type = $type;
        return $this;
    }

    public function setAmount($amount = '')
    {
        $this->_amount = $amount;
        return $this;
    }

    public function setBalance($balance = '')
    {
        $this->_balance = $balance;
        return $this;
    }

    public function setRefId($refid = '')
    {
        $this->_refId = $refid;
        return $this;
    }

    public function setDescription($desc = '')
    {
        $this->_desc = $desc;
        return $this;
    }

    public function saveBalance($key = array(), $operation='increase')
    {
        $db = new Wallet;
        if ($db->count() > 0) {
            $db = $db->where($key);
            // SAVE DATA WALLET
            $wallet = $db->first();
            if ($operation == 'increase') {
                $this->increaseBalance($wallet->balance);
            } else {
                $this->decreaseBalance();
            }
            $wallet->last_transaction = date('Y-m-d');
            $wallet->balance = $this->_currentBalance;
            $wallet->save();

            // SAVE DATA WALLET TRACK
            $track = $wallet->tracks()->create([
                'balance_now' => $this->_currentBalance,
                'balance_prev' => $this->_balance,
                'amount' => $this->_amount,
                'desc' => $this->_desc,
                'type' => $this->_type,
                'ref' => $this->_refId,
                'recorded_at' => date('Y-m-d'),
                'wallet_id' => $wallet->id
            ]);

            return true;
        }

        return false;
    }

    private function increaseBalance($balance = 0)
    {
        $this->_balance  = $balance;
        return $this->_currentBalance = $this->_balance + $this->_amount;
    }

    private function decreaseBalance()
    {
        return $this->_currentBalance = $this->_balance - $this->_amount;
    }

    public function getCurrentBalance($key = array())
    {
        return Wallet::where($key)->first();
    }
}
