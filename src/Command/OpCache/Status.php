<?php
namespace OpCache\Command\OpCache;

use OpCache\CurlService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 *
 * Class Status
 *
 * @package OpCache
 */
class Status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Opcache:opcache-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is to get the opcache status';

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
            $request = json_decode($request)->result;
            if ($request->opcache_enabled == false) {
                $this->error('Opcache setting is disabled');
            } else {
                $this->info('Memory Usage');
                $this->info('Used Memory : '.$this->convertMBOrKb($request->memory_usage->used_memory));
                $this->info('Free Memory : '.$this->convertMBOrKb($request->memory_usage->free_memory));
                $this->info('Wasted Memory : '.$this->convertMBOrKb($request->memory_usage->wasted_memory));
            }
        } else {
            $this->error('Opcache status cannot get, you need to check your configuration setting');
        }

        //send opcache status list to admin via email function will be implement later
    }

    /**
     * This function is to change bytes to MB and KB
     *
     * @param int $byte
     *
     * @return string
     */
    public function convertMBOrKb($bytes)
    {
        if ($bytes > 1048576) {
            return sprintf("%.2f MB", $bytes/1048576);
        } elseif ($bytes > 1024) {
            return sprintf("%.2f kB", $bytes/1024);
        }

        return sprintf("%d bytes", $bytes);
    }
}
