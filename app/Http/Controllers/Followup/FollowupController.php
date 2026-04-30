<?php

namespace App\Http\Controllers\Followup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Customer;
use App\Models\FollowUp;

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

    public function getFollowupHistory($id)
    {
        try {
            $history = FollowUp::with('user') 
                ->where('customer_id', $id)
                ->orderBy('id', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $history
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'title'             => 'required|string|max:255',
            'note'              => 'nullable|string',
            'status'            => 'required|in:pending,contacted,interested,not_interested,closed',
            'priority'          => 'required|in:low,medium,high',
            'follow_up_date'    => 'nullable|date',
            'contact_type'      => 'nullable|in:call,whatsapp,email,meeting,other',
            'outcome'           => 'nullable|string',
            'deal_amount'       => 'nullable|numeric|min:0',
            'is_converted'      => 'boolean',
            'meta'              => 'nullable|array',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                
                // ১. ফলো-আপ ডাটা তৈরি
                $followup = FollowUp::create([
                    'customer_id'       => $validated['customer_id'],
                    'user_id'           => auth()->id(), // যে লগইন করে আছে তার ID
                    'title'             => $validated['title'],
                    'note'              => $validated['note'],
                    'status'            => $validated['status'],
                    'priority'          => $validated['priority'],
                    'follow_up_date'    => $validated['follow_up_date'],
                    'last_contacted_at' => now(), // বর্তমানে কন্টাক্ট করা হয়েছে
                    'contact_type'      => $validated['contact_type'],
                    'outcome'           => $validated['outcome'],
                    'deal_amount'       => $validated['deal_amount'],
                    'is_converted'      => $validated['is_converted'] ?? false,
                    'meta'              => $request->meta,
                ]);

                // ২. কাস্টমার টেবিলের স্ট্যাটাস আপডেট করা (ঐচ্ছিক কিন্তু প্রফেশনাল)
                $customer = Customer::find($validated['customer_id']);
                if ($customer) {
                    $customer->update([
                        'lead_status' => $validated['status'],
                        'last_followup_date' => now()
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Follow-up recorded successfully',
                    'data' => $followup->load('user') // রিলেশনসহ ডাটা রিটার্ন
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save follow-up: ' . $e->getMessage()
            ], 500);
        }
    }
}
