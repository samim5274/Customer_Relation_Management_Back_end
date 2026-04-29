<?php

namespace App\Http\Controllers\Followup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer;

class FollowupController extends Controller
{
    public function getCustomers(Request $request)
    {
        try {
            $search = $request->query('search');
            $customers = Customer::with(['country','visaCategory'])
                ->where('is_active', 1)
                ->when($search, function ($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%")
                        ->orWhere('present_address', 'like', "%{$search}%")
                        ->orWhere('permanent_address', 'like', "%{$search}%")
                        ->orWhere('passport_no', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('mother_name', 'like', "%{$search}%")
                        ->orWhere('occupation', 'like', "%{$search}%")
                        ->orWhere('religion', 'like', "%{$search}%")
                        ->orWhere('blood_group', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                    });
                })
                ->orderBy('id', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Fetched all customers',
                'data' => $customers,
            ], 200);

        } catch (\Exception $e) {
            \Log::error("Followup Error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}
