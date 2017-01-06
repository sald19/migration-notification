<?php

namespace App\Console\Commands;

use App\Notifications\SiteMigrationHasChanged;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class VerifySiteMigrationHasChanged extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify-site-migration-has-changed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verify if migration site Panama has changed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $urlBanner = '/images/PAGINA%20WEB%20ANGEL%202016/JUNIO/artes%20para%20web%20-%20cirsol%2028-6-16.jpg';
        $user = new User();
        libxml_use_internal_errors(true);
        $url = 'http://www.migracion.gob.pa/';
        $html = file_get_contents($url);

        $crawler = new Crawler($html);
        $data = $crawler->filter('#top-a p')
            ->first()
            ->filter('a')
            ->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });

        if(! in_array($urlBanner, $data)) {
            $user->notify(new SiteMigrationHasChanged());
        }
    }
}
