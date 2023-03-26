<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\QuotationController;
use Exception;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('home');
    }

    public function handleData(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "http://127.0.0.1:8000/api/quotation",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($request->toArray()),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "authorization: " . env('JWT_SECRET')
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
        {
            echo "cURL Error #:" . $err;
        }
        else
        {
            $data = json_decode($response);
        }

        $data = json_decode($response);
        return view('home', ['data' => $data]);
    }
}
