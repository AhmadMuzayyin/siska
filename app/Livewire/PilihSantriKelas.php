<?php

namespace App\Livewire;

use App\Models\Kelas;
use App\Models\Santri;
use Livewire\Component;

class PilihSantriKelas extends Component
{
    public $kelas_id = '';

    public $santri_id = '';

    public function updatedKelasId($value)
    {
        $this->santri_id = '';
    }

    public function getSantrisProperty()
    {
        if ($this->kelas_id) {
            return Santri::where('kelas_id', $this->kelas_id)->get();
        }

        return collect();
    }

    public function render()
    {
        $kelasList = Kelas::all();

        return view('livewire.pilih-santri-kelas', [
            'kelasList' => $kelasList,
        ]);
    }
}
