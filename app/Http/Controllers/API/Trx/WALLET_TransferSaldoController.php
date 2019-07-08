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

class WALLET_TransferSaldoController extends APIController
{
    /**
    * Custom Login Message Validation
    *
    * @return array
    */
    private function customMessage()
    {
        return [
            'username.required' => 'Penerima tidak boleh kosong',
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
                'username' => 'required',
                'amount' => 'required',
            ],
            $this->customMessage()
        );

        if ($validator->fails()) {
            return $this->errorResponse(['validation' => [$validator->errors()]], 400);
        }

        if (Auth::check()) {
            $existsReceive = User::where(['username' => trim($request->username)])->count();
            if($existsReceive < 1)
            {
                return $this->errorResponse("Pengguna dengan username ".$request->username." tidak dapat kami temukan.", 404);
            }
            
            if(!is_numeric($request->amount))
            {
                return $this->errorResponse("Masukan jumlah nominal dengan benar", 500);
            }

            if($request->amount <= 0)
            {
                return $this->errorResponse("Jumlah nominal harus lebih dari 0", 500);
            }
            
            $receive = getUser(['username' => $request->username]);
            $userid = Auth::user()->id;
            if($receive->id == $userid)
            {
                return $this->errorResponse("Maaf kamu tidak dapat dapat mentransfer saldo ke dompetmu sendiri", 500);
            }

            // SET INIT DATA
            $wallet = Wallet::where(['user_id' => $userid])->first();
            $balance = (int) $wallet->balance;
            $amount = (int) $request->amount;
            $userid = $userid;
            $username = $request->username;

            if ($balance <= 0) { //Check If Current Balance Lower Than Zero
                return $this->errorResponse('Ooops... Maaf, transaksi tidak bisa dilakukan, saldo kamu 0', 500);
            } elseif (($balance - $amount) < 0) { //Check If Current Balance Insufficient
                return $this->errorResponse('Ooops... Maaf, transaksi tidak bisa dilakukan, saldo kamu tidak mencukupi untuk transfer', 500);
            } else {
                try {
                    // RECORD NEW TRANSACTION
                    $transNumber = '#TRX-'.date('Ymd').'-'.time();
                    $trx = new Transaction;
                    $trx->transaction_number = $transNumber;
                    $trx->type = 'out';
                    $trx->amount = $amount;
                    $trx->status = 'completed';
                    $trx->description = 'Transfer Saldo Dari Dompet sebesar ' . rupiah($amount) . ' ke '.$receive->name;
                    $trx->user_id = $userid;
                    $trx->sender_id = $userid;
                    $trx->receive_id = $receive->id ?? '';
                    $trx->save();

                    // REDUCE & RECORD SENDER BALANCE
                    $reduce = WalletRecorded::instance();
                    $reduce->setAmount($amount)
                    ->setBalance($balance)
                    ->setRefId($transNumber)
                    ->setDescription('Transfer saldo sebesar '.$amount)
                    ->setType('out')
                    ->saveBalance(['user_id' => $userid], 'reduce');

                    // REDUCE & RECORD  BALANCE
                    $add = WalletRecorded::instance();
                    $add->setAmount($amount)
                    ->setBalance($balance)
                    ->setRefId($transNumber)
                    ->setDescription('Terima saldo sebesar '.$amount)
                    ->setType('in')
                    ->saveBalance(['user_id' => $receive->id], 'increase');
                } catch (\Exception $e) {
                    return $this->errorResponse($e->getMessage(), 200);
                }
            }

            $current_balance = WalletRecorded::instance()->getCurrentBalance(['user_id' => $userid]);
            $current_balance = !empty($current_balance->balance) ? $current_balance->balance : 0;

            return $this->baseResponse(['balance' => rupiah($current_balance)], 'Transfer Saldo Successfuly', 200);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
}
