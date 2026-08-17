<?php

namespace App\Services;

use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\SettingRapor;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RaporTemplateService
{
    /**
     * Get SettingRapor for santri's Lembaga, or global fallback.
     */
    public function getSettingRaporForLembaga(?int $lembagaId): ?SettingRapor
    {
        if ($lembagaId) {
            $setting = SettingRapor::query()
                ->where('lembaga_id', $lembagaId)
                ->whereNull('mapel_id')
                ->whereNotNull('template_path')
                ->first();

            if ($setting) {
                return $setting;
            }
        }

        return SettingRapor::query()
            ->whereNull('lembaga_id')
            ->whereNull('mapel_id')
            ->whereNotNull('template_path')
            ->first();
    }

    /**
     * Check if a file is a valid Zip Archive (e.g. .docx).
     */
    protected function isZipArchive(string $filePath): bool
    {
        if (! file_exists($filePath)) {
            return false;
        }

        $zip = new \ZipArchive;
        $res = $zip->open($filePath);
        if ($res === true) {
            $zip->close();

            return true;
        }

        return false;
    }

    /**
     * Build report data payload for santri & semester.
     *
     * @return array<string, mixed>
     */
    public function buildRaporData(Santri $santri, ?Semester $semester): array
    {
        $santri->loadMissing(['kelas.lembaga', 'lembaga']);

        $nilais = Nilai::query()
            ->where('santri_id', $santri->id)
            ->when($semester, fn ($q) => $q->where('semester_id', $semester->id))
            ->with(['mapel'])
            ->get();

        $settingRaporMap = SettingRapor::query()
            ->whereIn('mapel_id', $nilais->pluck('mapel_id')->filter())
            ->get()
            ->keyBy('mapel_id');

        $setting = Setting::query()->first();

        $totalNilai = 0;
        $mapelRows = [];

        foreach ($nilais as $n) {
            $totalNilai += $n->nilai;
            $mapelId = $n->mapel_id;
            $mapelSetting = $settingRaporMap->get($mapelId);

            $predikatStr = $n->predikat?->value ?? 'C';
            $deskripsi = match ($predikatStr) {
                'A' => $mapelSetting?->deskripsi_a ?? 'Sangat baik dalam penguasaan materi.',
                'B' => $mapelSetting?->deskripsi_b ?? 'Baik dalam penguasaan materi.',
                'C' => $mapelSetting?->deskripsi_c ?? 'Cukup dalam penguasaan materi.',
                'D' => $mapelSetting?->deskripsi_d ?? 'Perlu bimbingan lebih lanjut.',
                default => 'Cukup.',
            };

            $mapelRows[] = [
                'nama' => $n->mapel?->nama ?? '-',
                'kitab' => $n->mapel?->kitab ?? '-',
                'nilai' => $n->nilai,
                'predikat' => $predikatStr,
                'deskripsi' => $deskripsi,
            ];
        }

        $rataRata = count($mapelRows) > 0 ? round($totalNilai / count($mapelRows), 1) : 0;

        $nisnVal = ! empty($santri->rfid_uid) ? $santri->rfid_uid : ($santri->noinduk ?? '-');
        $lembagaVal = $santri->lembaga?->nama ?? $santri->kelas?->lembaga?->nama ?? 'MDTA ARROQY';

        $semesterTipe = $semester?->tipe;
        $semesterVal = is_object($semesterTipe) && isset($semesterTipe->value)
            ? ucfirst($semesterTipe->value)
            : ucfirst((string) ($semesterTipe ?? 'Ganjil'));

        return [
            'nama' => $santri->nama_lengkap,
            'nisn' => $nisnVal,
            'noinduk' => $santri->noinduk ?? '-',
            'kelas' => $santri->kelas?->nama ?? '-',
            'lembaga' => $lembagaVal,
            'tahun_akademik' => $semester?->tahunAkademik?->nama ?? date('Y'),
            'semester' => $semesterVal,
            'nilai' => $rataRata,
            'deskripsi' => 'Rata-rata akumulasi nilai santri: '.$rataRata,
            'mapel_rows' => $mapelRows,
            'setting' => $setting,
        ];
    }

    /**
     * Render report template based on file format (.docx, .xml, .html) into PDF / printable output.
     *
     * @param  array<string, mixed>  $data
     */
    public function renderReport(SettingRapor $settingRapor, array $data): Response|BinaryFileResponse
    {
        $path = $settingRapor->template_path;
        $file = storage_path('app/public/'.$path);

        if (! file_exists($file)) {
            throw new \RuntimeException('File template tidak ditemukan: '.$path);
        }

        // If file is a zipped .docx archive
        if ($this->isZipArchive($file)) {
            return $this->renderDocxToPdf($settingRapor, $data);
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $content = file_get_contents($file) ?: '';

        // If file is Word Flat OPC XML
        if ($extension === 'xml' || str_contains($content, '<pkg:package') || str_contains($content, '<w:document')) {
            return $this->renderXmlTemplateToPdf($file, $data);
        }

        return $this->renderHtmlToPdf($file, $data);
    }

    /**
     * Fill values into Word (.docx) zip template and convert to PDF.
     *
     * @param  array<string, mixed>  $data
     */
    protected function renderDocxToPdf(SettingRapor $settingRapor, array $data): BinaryFileResponse|Response
    {
        $docxFile = $this->generateDocxReport($settingRapor, $data);
        $fileName = 'Rapor_'.Str::slug($data['nama']).'_'.date('Y').'.pdf';

        try {
            Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

            $phpWord = IOFactory::load($docxFile);
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');

            $pdfTempPath = storage_path('app/public/rapor_pdf_'.time().'_'.uniqid().'.pdf');
            $pdfWriter->save($pdfTempPath);

            @unlink($docxFile);

            return response()->download($pdfTempPath, $fileName, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$fileName.'"',
            ])->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            $docxFileName = 'Rapor_'.Str::slug($data['nama']).'_'.date('Y').'.docx';

            return response()->download($docxFile, $docxFileName)->deleteFileAfterSend(true);
        }
    }

    /**
     * Fill values into Word XML template (.xml) and convert to PDF.
     *
     * @param  array<string, mixed>  $data
     */
    protected function renderXmlTemplateToPdf(string $file, array $data): Response
    {
        $xmlContent = $this->generateXmlReport($file, $data);
        $fileName = 'Rapor_'.Str::slug($data['nama']).'_'.date('Y').'.pdf';

        // Extract paragraphs and tables from Word XML into styled HTML
        $html = "<html><head><meta charset='UTF-8'><style>
            body { font-family: 'Times New Roman', Times, serif; font-size: 13px; line-height: 1.5; color: #111; padding: 20px; }
            h1, h2, h3 { text-align: center; font-weight: bold; margin-bottom: 10px; }
            p { margin-bottom: 6px; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px; }
            th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
            th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        </style></head><body>";

        preg_match_all('/<w:p[^>]*>(.*?)<\/w:p>/s', $xmlContent, $paragraphs);

        if (! empty($paragraphs[1])) {
            foreach ($paragraphs[1] as $pXml) {
                $text = trim(strip_tags($pXml));
                if ($text !== '') {
                    $html .= '<p>'.htmlspecialchars($text, ENT_QUOTES, 'UTF-8').'</p>';
                }
            }
        } else {
            $html .= '<p>'.htmlspecialchars(strip_tags($xmlContent), ENT_QUOTES, 'UTF-8').'</p>';
        }

        $html .= '</body></html>';

        $dompdf = new Dompdf([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }

    /**
     * Fill values into HTML template and convert to PDF using Dompdf.
     *
     * @param  array<string, mixed>  $data
     */
    protected function renderHtmlToPdf(string $file, array $data): Response
    {
        $htmlContent = $this->generateHtmlReport($file, $data);
        $fileName = 'Rapor_'.Str::slug($data['nama']).'_'.date('Y').'.pdf';

        $dompdf = new Dompdf([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }

    /**
     * Generate a filled .docx file from the uploaded template using PhpWord.
     *
     * @param  array<string, mixed>  $data
     */
    public function generateDocxReport(SettingRapor $settingRapor, array $data): string
    {
        $templateFile = storage_path('app/public/'.$settingRapor->template_path);

        if (! file_exists($templateFile)) {
            throw new \RuntimeException('File template tidak ditemukan: '.$settingRapor->template_path);
        }

        if (! $this->isZipArchive($templateFile)) {
            throw new \RuntimeException('File template berformat .xml / non-zip. Gunakan pemrosesan XML.');
        }

        $templateProcessor = new TemplateProcessor($templateFile);

        // Sanitize broken Microsoft Word XML runs inside placeholders like {nis</w:t></w:r>...<w:t>n}
        try {
            $reflection = new \ReflectionClass($templateProcessor);
            if ($reflection->hasProperty('tempDocumentMainPart')) {
                $prop = $reflection->getProperty('tempDocumentMainPart');
                $prop->setAccessible(true);
                $xml = $prop->getValue($templateProcessor);
                if (is_string($xml)) {
                    $cleanXml = preg_replace_callback('/\{[^{}]*?<[^>]+>.*?\}/s', function ($m) {
                        return strip_tags($m[0]);
                    }, $xml);
                    $prop->setValue($templateProcessor, $cleanXml);
                }
            }
        } catch (\Throwable $e) {
            // Ignore reflection errors if any
        }

        $variables = [
            'nama' => (string) $data['nama'],
            'nisn' => (string) $data['nisn'],
            'noinduk' => (string) $data['noinduk'],
            'no_induk' => (string) $data['noinduk'],
            'kelas' => (string) $data['kelas'],
            'lembaga' => (string) $data['lembaga'],
            'unit_lembaga' => (string) $data['lembaga'],
            'tahun_akademik' => (string) $data['tahun_akademik'],
            'tahun' => (string) $data['tahun_akademik'],
            'semester' => (string) $data['semester'],
            'nilai' => (string) $data['nilai'],
            'deskripsi' => (string) $data['deskripsi'],
        ];

        foreach ($variables as $key => $val) {
            $variants = [
                $key,
                '{'.$key.'}',
                '${'.$key.'}',
                strtoupper($key),
                '{'.strtoupper($key).'}',
                '${'.strtoupper($key).'}',
                ucfirst($key),
                '{'.ucfirst($key).'}',
                '${'.ucfirst($key).'}',
            ];

            foreach ($variants as $variant) {
                $templateProcessor->setValue($variant, $val);
            }
        }

        if (! empty($data['mapel_rows']) && is_array($data['mapel_rows'])) {
            $rowKey = null;
            foreach (['mapel_nama', '${mapel_nama}', '{mapel_nama}', 'MAPEL_NAMA', '${MAPEL_NAMA}', '{MAPEL_NAMA}'] as $testKey) {
                try {
                    $templateProcessor->cloneRow($testKey, count($data['mapel_rows']));
                    $rowKey = $testKey;
                    break;
                } catch (\Throwable $e) {
                    continue;
                }
            }

            if ($rowKey) {
                foreach ($data['mapel_rows'] as $index => $row) {
                    $i = $index + 1;

                    $mapelVars = [
                        'mapel_nama' => (string) $row['nama'],
                        'mapel_kitab' => (string) $row['kitab'],
                        'mapel_nilai' => (string) $row['nilai'],
                        'mapel_predikat' => (string) $row['predikat'],
                        'mapel_deskripsi' => (string) $row['deskripsi'],
                    ];

                    foreach ($mapelVars as $mKey => $mVal) {
                        $templateProcessor->setValue($mKey.'#'.$i, $mVal);
                        $templateProcessor->setValue('{'.$mKey.'}#'.$i, $mVal);
                        $templateProcessor->setValue('${'.$mKey.'}#'.$i, $mVal);
                        $templateProcessor->setValue(strtoupper($mKey).'#'.$i, $mVal);
                        $templateProcessor->setValue('{'.strtoupper($mKey).'}#'.$i, $mVal);
                        $templateProcessor->setValue('${'.strtoupper($mKey).'}#'.$i, $mVal);
                    }
                }
            }
        }

        $tempPath = storage_path('app/public/temp_rapor_'.time().'_'.uniqid().'.docx');
        $templateProcessor->saveAs($tempPath);

        return $tempPath;
    }

    /**
     * Generate filled Word XML content string from .xml template file.
     *
     * @param  array<string, mixed>  $data
     */
    public function generateXmlReport(string $file, array $data): string
    {
        $xml = file_get_contents($file);

        if (! is_string($xml)) {
            return '';
        }

        // Sanitize broken Word XML tags split across run nodes like {nis</w:t>...<w:t>n}
        $xml = preg_replace_callback('/\{[^{}]*?<[^>]+>.*?\}/s', function ($m) {
            return strip_tags($m[0]);
        }, $xml);

        $variables = [
            'nama' => (string) $data['nama'],
            'nisn' => (string) $data['nisn'],
            'noinduk' => (string) $data['noinduk'],
            'no_induk' => (string) $data['noinduk'],
            'kelas' => (string) $data['kelas'],
            'lembaga' => (string) $data['lembaga'],
            'unit_lembaga' => (string) $data['lembaga'],
            'tahun_akademik' => (string) $data['tahun_akademik'],
            'tahun' => (string) $data['tahun_akademik'],
            'semester' => (string) $data['semester'],
            'nilai' => (string) $data['nilai'],
            'deskripsi' => (string) $data['deskripsi'],
        ];

        foreach ($variables as $key => $val) {
            $escapedVal = htmlspecialchars($val, ENT_XML1, 'UTF-8');
            $variants = [
                '{'.$key.'}',
                '${'.$key.'}',
                '{'.strtoupper($key).'}',
                '${'.strtoupper($key).'}',
                '{'.ucfirst($key).'}',
                '${'.ucfirst($key).'}',
            ];

            foreach ($variants as $variant) {
                $xml = str_replace($variant, $escapedVal, $xml);
            }
        }

        return $xml;
    }

    /**
     * Generate filled HTML string from .html / .htm / .blade.php template file.
     *
     * @param  array<string, mixed>  $data
     */
    public function generateHtmlReport(string $file, array $data): string
    {
        $html = file_get_contents($file);

        if (! is_string($html)) {
            return '';
        }

        $variables = [
            'nama' => (string) $data['nama'],
            'nisn' => (string) $data['nisn'],
            'noinduk' => (string) $data['noinduk'],
            'no_induk' => (string) $data['noinduk'],
            'kelas' => (string) $data['kelas'],
            'lembaga' => (string) $data['lembaga'],
            'unit_lembaga' => (string) $data['lembaga'],
            'tahun_akademik' => (string) $data['tahun_akademik'],
            'tahun' => (string) $data['tahun_akademik'],
            'semester' => (string) $data['semester'],
            'nilai' => (string) $data['nilai'],
            'deskripsi' => (string) $data['deskripsi'],
        ];

        foreach ($variables as $key => $val) {
            $escapedVal = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
            $variants = [
                '{'.$key.'}',
                '${'.$key.'}',
                '{'.strtoupper($key).'}',
                '${'.strtoupper($key).'}',
                '{'.ucfirst($key).'}',
                '${'.ucfirst($key).'}',
            ];

            foreach ($variants as $variant) {
                $html = str_replace($variant, $escapedVal, $html);
            }
        }

        if (! empty($data['mapel_rows']) && is_array($data['mapel_rows'])) {
            $rowsHtml = '';
            foreach ($data['mapel_rows'] as $index => $row) {
                $num = $index + 1;
                $rowsHtml .= "<tr>
                    <td style='padding:6px; border:1px solid #ccc; text-align:center;'>{$num}</td>
                    <td style='padding:6px; border:1px solid #ccc;'>".htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8')."</td>
                    <td style='padding:6px; border:1px solid #ccc;'>".htmlspecialchars($row['kitab'], ENT_QUOTES, 'UTF-8')."</td>
                    <td style='padding:6px; border:1px solid #ccc; text-align:center; font-weight:bold;'>".htmlspecialchars((string) $row['nilai'], ENT_QUOTES, 'UTF-8')."</td>
                    <td style='padding:6px; border:1px solid #ccc; text-align:center;'>".htmlspecialchars($row['predikat'], ENT_QUOTES, 'UTF-8')."</td>
                    <td style='padding:6px; border:1px solid #ccc;'>".htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8').'</td>
                </tr>';
            }

            $html = str_replace('{mapel_rows}', $rowsHtml, $html);
            $html = str_replace('${mapel_rows}', $rowsHtml, $html);
        }

        return $html;
    }
}
