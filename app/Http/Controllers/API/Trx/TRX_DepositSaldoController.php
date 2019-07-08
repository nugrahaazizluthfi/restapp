<?php

namespace App\Http\Controllers\API\Trx;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\Transaction;
use App\Wallet;
use App\Classes\WalletRecorded;
use Validator;
use Auth;
use App\User;

class TRX_DepositSaldoController extends APIController
{
    /**
    * Custom Login Message Validation
    *
    * @return array
    */
    private function customMessage()
    {
        return [
            'amount.required' => 'Jumlah uang tidak boleh atau kurang dari 0',
        ];
    }

    /**
     * TRANSFER Action
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function action(Request $request)
    {
        // Validation request
        $validator = Validator::make(
            $request->all(),
            [
                'amount' => 'required',
            ],
            $this->customMessage()
        );

        if ($validator->fails()) {
            return $this->errorResponse(['validation' => [$validator->errors()]], 400);
        }

        $amount = $request->amount;

        if (Auth::check()) {
            if (!is_numeric($request->amount)) {
                return $this->errorResponse("Masukan jumlah nominal dengan benar", 500);
            }

            if ($request->amount <= 0) {
                return $this->errorResponse("Jumlah nominal harus lebih dari 0", 500);
            }

            $userid = Auth::user()->id;
            $wallet = Wallet::where(['user_id' => $userid])->first();
            $balance = (int) $wallet->balance;
            $amount = (int) $request->amount;

            // SET INIT DATA
            try {
                // RECORD NEW TRANSACTION
                $transNumber = '#DEPO-'.date('Ymd').'-'.time();
                $trx = new Transaction;
                $trx->transaction_number = $transNumber;
                $trx->type = 'in';
                $trx->amount = $amount;
                $trx->status = 'completed';
                $trx->description = 'Deposit Saldo sebesar ' . rupiah($amount);
                $trx->user_id = $userid;
                $trx->sender_id = $userid;
                $trx->receive_id = $userid ?? '';
                $trx->save();

                // REDUCE & RECORD  BALANCE
                $add = WalletRecorded::instance();
                $add->setAmount($amount)
                    ->setBalance($balance)
                    ->setRefId($transNumber)
                    ->setDescription('Terima saldo sebesar '.$amount)
                    ->setType('in')
                    ->saveBalance(['user_id' => $userid], 'increase');
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            $current_balance = WalletRecorded::instance()->getCurrentBalance(['user_id' => $userid]);
            $current_balance = !empty($current_balance->balance) ? $current_balance->balance : 0;

            return $this->baseResponse(['balance' => rupiah($current_balance)], 'Deposit saldo berhasil', 200);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
}
