<?php

namespace App\Console\Commands;

use App\Models\ProductVariant;
use App\Services\GatewayService;
use Illuminate\Console\Command;

class SyncWashClubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:wash-clubs {--convert-ids-to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync local product_variants with WashConnect wash clubs data.';

    private const DEV_TO_PROD_ID_CONVERSION_MAP = [
        "3f1350c2-8063-44bc-9fdf-191076296e1a" => "602e2d6d-fdef-4993-88f3-43d78bf67a34",
        "b2c3f885-4f76-4025-8150-c2efe2af6238" => "e3319f8e-5e5e-45a0-babb-237a641747c8",
        "674ed3c4-49ec-46f8-89a6-522a16fb6f7d" => "0d7e9cb7-f51f-41da-a266-b70a8f117fd1",
    ];

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
        if ($this->option('convert-ids-to')) {
            $this->convertIdsTo($this->option('convert-ids-to'));
        }

        $this->info('⏳ Retrieving current prices from WashConnect...');

        $washConnectResponse = GatewayService::get('v2/wash-connect/sales-items', [ 'useAppToken' => true ]);

        if (!$washConnectResponse || !is_array($washConnectResponse->data)) {
            $this->error('Invalid response received from Gateway. Skipping.');

            return;
        }

        $salesItems = collect($washConnectResponse->data);

        $this->info('👀 '.$salesItems->count().' Sales Item'.($salesItems->count() === 1 ? '' : 's').' received from Gateway.');

        $numUpdated = ProductVariant::all()->reduce(function ($acc, $variant) use ($salesItems) {
            if ($salesItem = $salesItems->firstWhere('inventoryItemID', $variant->wash_connect_id)) {
                if ($salesItem->fullPrice && $salesItem->fullPrice !== $variant->price) {
                    $variant->price = $salesItem->fullPrice;
                    $variant->save();

                    $acc++;
                }
            }

            return $acc;
        }, 0);

        $this->info('✅ '.$numUpdated.' product variant price'.($numUpdated === 1 ? '' : 's').' updated.');
        $this->info('');
        $this->info('Done syncing wash clubs.');
    }

    /**
     * Convert ProductVariant IDs from/to dev/prod, to match what is stored in WashConnect Sales Items
     */
    private function convertIdsTo(string $to = 'production'): void
    {
        $to = (($to === 'production' || $to === 'prod') ? 'production' : null) ??
            (($to === 'development' || $to === 'dev') ? 'development' : null) ??
            null;

        if (!$to) {
            $this->error('Invalid convert-ids-to value. Must be "production"/"prod" or "development"/"dev".');
            die();
        }

        $this->info('⏳ Converting to '.$to.' WashConnect IDs...');

        $map = $to === 'production' ? static::DEV_TO_PROD_ID_CONVERSION_MAP : array_flip(static::DEV_TO_PROD_ID_CONVERSION_MAP);

        $numUpdated = ProductVariant::all()->reduce(function ($acc, $variant) use ($map) {
            if (array_key_exists($variant->wash_connect_id, $map)) {
                $variant->wash_connect_id = $map[$variant->wash_connect_id];
                $variant->save();

                $acc++;
            }

            return $acc;
        }, 0);

        $this->info('✅ '.$numUpdated.' product variant'.($numUpdated === 1 ? '' : 's').' converted to '.$to.' IDs.');
        $this->info('');
    }
}
