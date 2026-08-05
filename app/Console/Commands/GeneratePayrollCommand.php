<?php

namespace App\Console\Commands;

use App\Actions\GeneratePayrollAction;
use App\Enums\GuruStatus;
use App\Exceptions\PayrollCutoffNotReachedException;
use App\Models\Guru;
use App\Services\SemesterService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('payroll:generate {bulan : Month number (1-12)} {tahun : Four-digit year} {--bisyaroh= : Base bisyaroh amount applied to every active guru}')]
#[Description('Generate payroll for every active guru for the given month/year')]
class GeneratePayrollCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(GeneratePayrollAction $action, SemesterService $semesterService): int
    {
        $semester = $semesterService->current();

        if ($semester === null) {
            $this->error('No active semester found.');

            return self::FAILURE;
        }

        $bisyaroh = $this->option('bisyaroh');

        if ($bisyaroh === null) {
            $this->error('The --bisyaroh option is required.');

            return self::FAILURE;
        }

        $bulan = (int) $this->argument('bulan');
        $tahun = (int) $this->argument('tahun');

        $gurus = Guru::query()->where('status', GuruStatus::Aktif)->get();

        foreach ($gurus as $guru) {
            try {
                $action->handle($guru, $semester, $bulan, $tahun, (int) $bisyaroh);
                $this->info("Payroll generated for guru #{$guru->id}.");
            } catch (PayrollCutoffNotReachedException $exception) {
                $this->error($exception->getMessage());

                return self::FAILURE;
            }
        }

        $this->info("Payroll generation complete for {$gurus->count()} guru(s).");

        return self::SUCCESS;
    }
}
