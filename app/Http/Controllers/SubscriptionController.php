<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
        ]);
        try {
            Subscription::create($validated);

            return response()->json(['message' => 'Terima kasih telah mengikuti newsletter kami']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Gagal mengikuti newsletter kami'], 500);
        }
    }
}
