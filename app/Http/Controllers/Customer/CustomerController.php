<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
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

    public function getCustomerById($id){
        try {
            $customer = Customer::with(['country', 'visaCategory'])->find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer details fetched successfully',
                'data' => $customer,
            ], 200);

        } catch (\Exception $e) {
            \Log::error("Error fetching customer ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal server error occurred',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function updateCustomer(UpdateCustomerRequest $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $data = $request->validated();

            // relation id fix
            $data['country_id'] = $request->country;
            $data['visa_category_id'] = $request->visaCategory;

            $imageMap = [
                'user_photo'        => 'photo',
                'passport_photo'    => 'passport_photo',
                'nid_photo'         => 'national_id_photo',
                'spouse_photo'      => 'spouse_photo',
                'spouse_nid_photo'  => 'spouse_nid_photo',
            ];

            foreach ($imageMap as $inputName => $dbColumn) {
                if ($request->hasFile($inputName)) {
                    // delete old image
                    if ($customer->$dbColumn) {
                        Storage::disk('public')->delete($customer->$dbColumn);
                    }
                    //save new image
                    $data[$dbColumn] = $request->file($inputName)->store('customers', 'public');
                }
            }

            // ৪. data save for update
            $customer->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'data' => $customer
            ], 200);

        } catch (\Exception $e) {
            Log::error("Update Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer. ' . $e->getMessage(),
            ], 500);
        }
    }
}
