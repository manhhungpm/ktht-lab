<?php

namespace App\Console\Commands;


use App\Models\Statistic\MsisdnSummaryType;

use Illuminate\Console\Command;

class FakeMsisdnSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fake Partner Account Month 3';

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
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $time_start = microtime(true);

        for($i = 0; $i< 1000000; $i++){
            $record = [
                'msisdn' => mt_rand(90000000000,99999999999),
                'duration_type_id' => mt_rand(1,3),
                'carrier' => array_rand(['viettel','vinaphone','mobifone','gmobile','other','vietnamobile']),
                'num_call_out' => mt_rand(30,500),
                'sum_duration_call_out' => mt_rand(600,7500),
                'num_call_label_spam' => mt_rand(10,200),
                'num_call_label_not_spam' => mt_rand(10,200),
                'num_call_not_label' => mt_rand(10,200),
            ];
            $new = new MsisdnSummaryType();
            $new->fill($record);
            $new->save();
        }


        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        $this->info(
            'Done in ' . $execution_time . ' secs'
        );
    }



}
