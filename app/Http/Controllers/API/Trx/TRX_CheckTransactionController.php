<?php

namespace App\Http\Controllers\API\Trx;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\Transaction;
use Validator;
use Auth;
use App\WalletTrack;

class TRX_CheckTransactionController extends APIController
{
    private $_limit = 10;
    /**
     * Get transaction list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function getTransactionData(Request $request)
    {
        $last_transaction_id = !empty($request->get('last_transaction_id')) ? $request->get('last_transaction_id') : '';
        $filterType = !empty($request->get('filterType')) ? $request->get('filterType') : '';

        // AUTH CHECK
        if (Auth::check()) {
            $userid = Auth::user()->id;

            try {
                $transaction = new Transaction;
                $transaction = $transaction->where(['user_id' => $userid]);

                if (!empty($last_transaction_id)) {
                    $transaction = $transaction->where('id', '<', $last_transaction_id);
                }

                if (!empty($filterType)) {
                    $transaction = $transaction->where('type', '=', $filterType);
                }

                $transaction = $transaction->limit($this->_limit)->orderBy('id', 'desc')->get();

                $temp = [];
                $last_transaction_id = '';
                foreach ($transaction as $key => $value) {
                    $temp[$key] = $value;
                    $last_transaction_id = $value->id;
                }

                $other['last_transaction_id'] = $last_transaction_id;
                $other['next_data'] = count($temp);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            return $this->baseResponse($temp, 'Get Transaction Successfuly', 200, $other);
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
            $userid = Auth::user()->id;

            try {
                $transaction = new Transaction;
                $transaction = $transaction->where(['user_id' => $userid]);

                if (!empty($filterType)) {
                    $transaction = $transaction->where('type', '=', $filterType);
                }

                $transaction = $transaction->sum('amount');

                $data['total'] = rupiah($transaction);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 200);
            }

            return $this->baseResponse($data, 'Get Transaction Successfuly', 200);
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
                $detail = new Transaction;
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
