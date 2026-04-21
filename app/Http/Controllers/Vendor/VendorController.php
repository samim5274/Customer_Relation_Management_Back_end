<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function getVendor(){

        try{
            $vendor = Vendor::first();

            if(!$vendor){
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found for this user.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $vendor
            ], 200);
        }catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Vendor fetched failed. ' . $e->getMessage()
            ], 500);
        }
    }

    public function editVendor(Request $request, $id)
    {
        try {
            $user = $request->user();
            $vendor = Vendor::find($id);

            if (!$vendor || ($user->vendor_id != $id && $user->role !== 'admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized or Vendor not found.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'shop_name'        => 'required|string|max:255',
                'shop_slug'        => 'required|string|max:255|unique:vendors,shop_slug,' . $id,
                'shop_description' => 'nullable|string|max:1000',
                'email'            => 'nullable|email|max:255',
                'phone'            => 'nullable|string|max:20',
                'address'          => 'nullable|string|max:500',
                'city'             => 'nullable|string|max:100',
                'state'            => 'nullable|string|max:100',
                'country'          => 'nullable|string|max:100',
                'postal_code'      => 'nullable|string|max:20',
                'shop_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'shop_logo_2'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update basic info
            $vendor->update($request->only([
                'shop_name', 'shop_slug', 'shop_description', 'email',
                'phone', 'address', 'city', 'state', 'country', 'postal_code'
            ]));

            // Handle Primary Logo
            if ($request->hasFile('shop_logo')) {
                if ($vendor->shop_logo) \Storage::disk('public')->delete($vendor->shop_logo);
                $vendor->shop_logo = $request->file('shop_logo')->store("vendors/logos", 'public');
            }

            // Handle White Logo (shop_logo_2)
            if ($request->hasFile('shop_logo_2')) {
                if ($vendor->shop_logo_2) \Storage::disk('public')->delete($vendor->shop_logo_2);
                $vendor->shop_logo_2 = $request->file('shop_logo_2')->store("vendors/logos_white", 'public');
            }

            $vendor->save();

            return response()->json([
                'success' => true,
                'message' => 'Vendor settings updated successfully',
                'data'    => $vendor
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
