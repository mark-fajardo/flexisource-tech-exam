<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ImportCustomerDataService;
use Illuminate\Console\Command;

/**
 * Class ImportCustomerDataCommand
 * @package App\Console\Commands
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.25
 */
class ImportCustomerDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:import {--length=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from the randomuser.me API';

    /**
     * @var ImportCustomerDataService
     */
    private ImportCustomerDataService $importCustomerDataService;

    /**
     * ImportCustomerDataCommand constructor
     */
    public function __construct(ImportCustomerDataService $importCustomerDataService)
    {
        parent::__construct();
        $this->importCustomerDataService = $importCustomerDataService;   
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $results = (int) $this->option('length');

        $this->info('Fetching all customers\' data');
        $customers = $this->importCustomerDataService->fetchCustomers($results);

        $this->info('Importing all customers\' data');
        $this->importCustomerDataService->importCustomers($customers);
        
        $this->info('Done importing all customers\'s data');
    }
}
