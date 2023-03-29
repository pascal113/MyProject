<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--for-valimate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Paths to exclude from the sitemap
     *
     * @var array
     */
    protected $except = [
        '\/cart',
        '\/cart\/.*',
        '\/checkout',
        '\/checkout\/.*',
        '\/my-account',
        '\/my-account\/.*',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = SitemapGenerator::create(config('app.url'));

        if (!$this->option('for-valimate')) {
            $saveTo = public_path('sitemap.xml');

            $sitemap->hasCrawled(function (Url $url) {
                foreach ($this->except as $exceptPath) {
                    if (preg_match('/'.$exceptPath.'/', $url->path())) {
                        dump('Skipping '.$url->path().'...');

                        return;
                    }
                }

                return $url;
            })
                ->writeToFile($saveTo);

            $this->info('✅ Done. '.public_path('sitemap.xml').' written.');
        } else {
            // Get an array of all URLs
            $urls = collect($sitemap->getSitemap()->getTags())->map(function ($tag) {
                return $tag->url;
            })->toArray();

            // Write to valimate.json for W3C validation
            $json = (object)[ 'urls' => $urls ];
            $fp = fopen('valimate.json', 'w');
            fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
            fclose($fp);
        }
    }
}
