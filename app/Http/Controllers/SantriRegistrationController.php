<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Santri;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SantriRegistrationController extends Controller
{
    /**
     * Show the public santri registration form.
     */
    public function create(): View
    {
        return view('santri-registration', [
            'lembagas' => Lembaga::query()->active()->ordered()->with('kelas')->get(),
            'kelasList' => Kelas::query()->with('lembaga')->orderBy('nama')->get(),
            'genders' => Gender::cases(),
        ]);
    }

    /**
     * Handle a public santri registration submission. The record is created
     * with PendingApproval status; an admin must approve it via
     * ApproveSantriRegistrationAction before it counts toward kelas capacity.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = collect(Santri::validationRules())->except('status')->all();

        $data = $request->validate($rules);
        $data['status'] = SantriStatus::PendingApproval;

        if (empty($data['lembaga_id']) && ! empty($data['kelas_id'])) {
            $data['lembaga_id'] = Kelas::query()->find($data['kelas_id'])?->lembaga_id;
        }

        Santri::query()->create($data);

        return back()->with('status', 'Pendaftaran berhasil dikirim, menunggu konfirmasi admin.');
    }
}
