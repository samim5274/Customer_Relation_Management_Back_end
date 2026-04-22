<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\Customer;
use App\Models\VisaCategory;
use App\Models\Country;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        try {
            $customers = Customer::with(['country','visaCategory'])->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Fetched all customers',
                'data' => $customers,
            ], 200);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching customers',
            ], 500);
        }
    }

    public function getVisaCategory(){
        try {
            $visaCategories = VisaCategory::all();

            return response()->json([
                'success' => true,
                'message' => 'Fetched all visa Categories',
                'data' => $visaCategories,
            ], 200);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching visa Categories',
            ], 500);
        }
    }

    public function getCountry()
    {
            try {
                $countries = Country::all();

                return response()->json([
                    'success' => true,
                    'message' => 'Fetched all countries',
                    'data' => $countries,
                ], 200);

            } catch (\Exception $e) {
                Log::error($e);
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while fetching countries',
                ], 500);
            }
        
    }
}
