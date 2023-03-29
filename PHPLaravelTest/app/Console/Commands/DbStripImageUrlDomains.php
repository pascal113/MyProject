<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbStripImageUrlDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:strip-image-url-domains {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove references to a specific domain stored in the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $fromDomain = $this->argument('domain');

        $pageSubColsToSearch = [ 'image', 'logo', 'icon', 'photo' ];
        $pages = Page::where(DB::raw('TRUE'));
        foreach ($pageSubColsToSearch as $col) {
            $pages = $pages->orWhere('content', 'LIKE', DB::raw('"%\"'.$col.'\": \"https://'.$fromDomain.'%"'));
        }
        $pages = $pages->get();
        foreach ($pages as $page) {
            $strContent = json_encode($page->content);
            $strContent = preg_replace('/("('.implode('|', $pageSubColsToSearch).')":[ ]?")https:\\\\\/\\\\\/'.$fromDomain.'/', '$1', $strContent);

            $page->content = json_decode($strContent);

            $page->save();
        }

        $this->info('✅ '.$pages->count().' page'.($pages->count() === 1 ? '' : 's').' updated.');

        $products = Product::where('image', 'LIKE', DB::raw('"https://'.$fromDomain.'%"'))->get();
        foreach ($products as $product) {
            $product->image = preg_replace('/^(https:\/\/)'.$fromDomain.'/', '', $product->image);

            $product->save();
        }

        $this->info('✅ '.$products->count().' product'.($products->count() === 1 ? '' : 's').' updated.');
    }
}
