<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the public contact form.
     */
    public function create(): View
    {
        return view('contact');
    }

    /**
     * Handle a public contact form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Contact::query()->create($data);

        return back()->with('status', __('Pesan terkirim, kami akan segera menghubungi Anda.'));
    }
}
