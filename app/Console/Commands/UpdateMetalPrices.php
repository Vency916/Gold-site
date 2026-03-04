<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MetalPriceService;

class UpdateMetalPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:update';

    protected $description = 'Fetch and store the latest gold and silver spot prices';

    public function handle(MetalPriceService $service)
    {
        $this->info('Updating metal prices...');
        $service->fetchLatestPrices();
        $this->info('Metal prices updated successfully.');
    }
}
