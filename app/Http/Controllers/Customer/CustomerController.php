<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreCustomerRequest;
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

    public function createCustomer(StoreCustomerRequest $request)
    {
        DB::beginTransaction();

        try{
            $data = $request->validated();

            // safe upload
            $upload = function ($field, $folder) use ($request) {
                if ($request->hasFile($field)) {
                    return $request->file($field)->store($folder, 'public');
                }
                return null;
            };

            // files
            $data['photo'] = $upload('user_photo', 'customers/photo');
            $data['passport_photo'] = $upload('passport_photo', 'customers/passport');
            $data['national_id_photo'] = $upload('nid_photo', 'customers/nid');
            $data['spouse_photo'] = $upload('spouse_photo', 'customers/spouse');
            $data['spouse_nid_photo'] = $upload('spouse_nid_photo', 'customers/spouse_nid');

            // mapping (safe)
            $data['country_id'] = $data['country'] ?? null;
            $data['visa_category_id'] = $data['visaCategory'] ?? null;

            unset($data['country'], $data['visaCategory']);

            $customer = Customer::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully',
                'data' => $customer
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error("Customer Create Error", [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(), // show real error
            ], 500);
        }
    }
}
