<?php

namespace App\Http\Controllers\API\Wallet;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\Wallet;
use App\WalletTrack;
use Validator;
use Auth;

class CheckBalanceController extends APIController
{
    private $_limit = 10;
    /**
     * Get get balance
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function getSaldo(Request $request)
    {
        // AUTH CHECK
        if (Auth::check()) {
            $userid = $request->user_id;

            try {
                $wallet = new Wallet;
                $wallet = $wallet->select(['balance','last_transaction'])->where(['user_id' => $userid])->first();
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            return $this->baseResponse($wallet, 'Get Data Saldo Successfuly', 200);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    /**
     * Get check history balance
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function getSaldoData(Request $request)
    {
        $last_balance_id = !empty($request->get('last_balance_id')) ? $request->get('last_balance_id') : '';
        $filterType = !empty($request->get('filterType')) ? $request->get('filterType') : '';

        // AUTH CHECK
        if (Auth::check()) {
            $walletid = Auth::user()->wallet->id;

            try {
                $balance = new WalletTrack;
                $balance = $balance->where(['wallet_id' => $walletid]);

                if (!empty($last_balance_id)) {
                    $balance = $balance->where('id', '<', $last_balance_id);
                }

                if (!empty($filterType)) {
                    $balance = $balance->where('type', '=', $filterType);
                }

                $balance = $balance->limit($this->_limit)->orderBy('id', 'desc')->get();

                $temp = [];
                $last_balance_id = '';
                foreach ($balance as $key => $value) {
                    $temp[$key] = $value;
                    $last_balance_id = $value->id;
                }

                $other['last_balance_id'] = $last_balance_id;
                $other['next_data'] = count($temp);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            return $this->baseResponse($temp, 'Get Data Balance Successfuly', 200, $other);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    /**
    * Get total transaction
    *
    * @param  \Illuminate\Http\Request  $request
    * @return json
    */
    public function getTotal(Request $request)
    {
        $filterType = !empty($request->get('filterType')) ? $request->get('filterType') : '';

        // AUTH CHECK
        if (Auth::check()) {
            $walletid = Auth::user()->wallet->id;

            try {
                $balance = new WalletTrack;
                $balance = $balance->where(['wallet_id' => $walletid]);

                if (!empty($filterType)) {
                    $balance = $balance->where('type', '=', $filterType);
                }

                $balance = $balance->sum('amount');

                $data['total'] = rupiah($balance);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            return $this->baseResponse($data, 'Get Total Balance Successfuly', 200);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    
    /**
    * Get total transaction
    *
    * @param  \Illuminate\Http\Request  $request
    * @return json
    */
    public function getDetail($id)
    {
        // AUTH CHECK
        if (Auth::check()) {
            try {
                $detail = new WalletTrack;
                $detail = $detail->where(['id' => $id]);
                $detail = $detail->first();
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            return $this->baseResponse($detail, 'Get Transaction Successfuly', 200);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
}
