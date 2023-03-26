<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use DateTime;
use Session;

class QuotationController extends Controller
{
    public function calculate(Request $request)
    {
        $token = $request['token'];

        if (! ($token == env('JWT_SECRET')))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        foreach(['age', 'currency_id', 'start_date', 'end_date'] as $parameter)
        {
            if (! ($request[$parameter]))
            {
                return response()->json(['error' => 'Missing required parameter: ' . $parameter], 422);
            }
        }

        $fixedRate = 3;
        $ageList = explode(',', $request['age']);

        if(! in_array($request['currency_id'], ['EUR', 'GBP', 'USD'])) return response()->json(['error' => 'Invalid parameter currency_id. It has to be either EUR, GBP or USD'], 422);

        try
        {
            $tripStartDate = Carbon::parse($request['start_date']);
        }
        catch (InvalidFormatException $e)
        {
            return response()->json(['error' => 'Parameter start_date is not valid date.'], 422);
        }

        try
        {
            $tripEndDate = Carbon::parse($request['end_date']);
        }
        catch (InvalidFormatException $e)
        {
            return response()->json(['error' => 'Parameter end_date is not valid date.'], 422);
        }

        if($tripStartDate > $tripEndDate) return response()->json(['error' => 'parameter end_date is greater than parameter start_date. Probably you want to swap those two values'], 422);

        $tripLength = $tripStartDate->diffInDays($tripEndDate) + 1;
        $ageTotalLoad = 0;

        foreach ($ageList as $age)
        {
            if($age < 18 || $age > 70) return response()->json(['error' => 'Age ' . $age . ' is out of range 18-70 years'], 422);
            if(strval($age) !== strval(intval($age))) return response()->json(['error' => 'Age ' . $age . ' is not an integer'], 422);
            $ageTotalLoad += $this->getAgeLoad($age);
        }

        $total = $fixedRate * $ageTotalLoad * $tripLength;

        $quotation_id = $_SESSION['quotation_id'] ?? 1;
        $_SESSION['quotation_id'] = $quotation_id + 1;

        return response()->json([
            'total' => round($total, 2),
            'currency_id' => $request['currency_id'],
            'quotation_id' => $quotation_id,
        ]);
    }

    private function getAgeLoad($age)
    {
        if ($age >= 18 && $age <= 30)
        {
            return 0.6;
        }
        elseif ($age >= 31 && $age <= 40)
        {
            return 0.7;
        }
        elseif ($age >= 41 && $age <= 50)
        {
            return 0.8;
        }
        elseif ($age >= 51 && $age <= 60)
        {
            return 0.9;
        }
        elseif ($age >= 61 && $age <= 70)
        {
            return 1;
        }
    }
}
