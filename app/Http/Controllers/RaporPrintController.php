<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Semester;
use App\Services\RaporTemplateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RaporPrintController extends Controller
{
    public function __construct(
        protected RaporTemplateService $raporTemplateService
    ) {}

    public function print(Request $request, int $santriId): Response|BinaryFileResponse
    {
        $santri = Santri::query()->with(['kelas.lembaga', 'lembaga'])->findOrFail($santriId);
        $semester = Semester::query()->active()->with('tahunAkademik')->first();

        $data = $this->raporTemplateService->buildRaporData($santri, $semester);
        $settingRapor = $this->raporTemplateService->getSettingRaporForLembaga($santri->lembaga_id ?? $santri->kelas?->lembaga_id);

        if ($settingRapor && $settingRapor->template_path) {
            return $this->raporTemplateService->renderReport($settingRapor, $data);
        }

        return response()->view('rapor-print', $data);
    }
}
