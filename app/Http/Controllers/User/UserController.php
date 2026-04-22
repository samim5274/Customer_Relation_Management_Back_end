<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class UserController extends Controller
{
    public function getUsers()
    {
        try {
            $users = User::where('role', 'customer')->get();

            return response()->json([
                'success' => true,
                'message' => 'Fetched all users',
                'data' => $users,
            ], 200);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching users',
            ], 500);
        }
    }
}
