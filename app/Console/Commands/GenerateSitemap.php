<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Sitemap::create('https://www.mqalamin.sch.id')
            ->add(Url::create('/')->setPriority(1)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->add(Url::create('/daftar')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->add(Url::create('/about')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create('/privacy')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create('/terms')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create('/cookies')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->add(Url::create('/kontak')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully');
    }
}
