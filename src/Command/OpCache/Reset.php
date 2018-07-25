<?php
namespace OpCache\Command\OpCache;

use OpCache\CurlService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class Reset
 *
 * @package OpCache
 */
class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Opcache:opcache-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is to clear the opcache';

    /**
     * @var CurlService $curlService
     */
    public $curlService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CurlService $curlService)
    {
        parent::__construct();
        $this->curlService = $curlService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = $this->curlService->sendRequest($this);

        if ($request) {
            $this->info('Opcache is successfully clear');
        } else {
            $this->error('Opcache is not clear');
        }
    }
}
