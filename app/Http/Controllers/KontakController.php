<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function index()
    {
        return view('kontak');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        try {
            Contact::create($validated);
            return response()->json(['message' => 'Pesan berhasil dikirim']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Pesan gagal dikirim'], 500);
        }
    }
}
