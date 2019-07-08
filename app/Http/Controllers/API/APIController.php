<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class APIController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Define json format for output
     *
     * @param  $data  : main data
     * @param  $message  : message response
     * @param  $status_code  : response status code
     * @param  $other  : additional data
     * @return json
     */
    protected function baseResponse($data, $message = '', $status_code, $other = null)
    {
        // standar response json: basic structure
        $response = [
            'error'     => false,
            'message'   => $message,
            'data'      => $data,
            'other'     => $other
        ];

        if ($data) {
            // check if method toArray exists
            if (method_exists($data, 'toArray')) {
                // convert data object to Array
                $data = $data->toArray();
                
                // if current_page exists then reformatting json structure: pagination structure
                if (array_key_exists('current_page', $data)) {
                    $response = [
                        'error' => false,
                        'message' => $message,
                        'data' => $data['data'],
                        'other' => $other,
                        'paging' => [
                            'current_page' => $data['current_page'],
                            'first_page_url' => $data['first_page_url'],
                            'from' => $data['from'],
                            'last_page' => $data['last_page'],
                            'last_page_url' => $data['last_page_url'],
                            'next_page_url' => $data['next_page_url'],
                            'path' => $data['path'],
                            'limit' => $data['per_page'],
                            'prev_page_url' => $data['prev_page_url'],
                            'to' => $data['to'],
                            'total' => $data['total']
                        ]
                    ];
                }
            }
        }

        return response()->json($response, $status_code);
    }

    /**
     * Error response format
     *
     * @param  $message  : message response
     * @param  $status_code  : response status code
     * @return json
     */
    protected function errorResponse($message = '', $status_code)
    {
        $response = [
            'error' => true,
            'message' => $message,
            'data' => null
        ];

        return response()->json($response, $status_code);
    }

    /**
     * Error response format
     *
     * @return json
     */
    protected function noContent()
    {
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
