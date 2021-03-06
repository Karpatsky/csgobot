<?php

namespace App\Console\Commands;

use App\Site;
use App\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class Lootfarm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lootfarm:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Log::info('lootfarm check');
        $site = Site::find(10);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $site->get_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $items = json_decode(curl_exec($curl));
        $items = collect($items);
        Log::info(count($items));

        $tasks = Task::with('item')->where('site_id', '=', 10)->get();
        foreach ($tasks as $task){
            $item = $items->where('name', '=', $task->item->full_name)->first();
            if ($item){
                Telegram::sendMessage([
                    'chat_id' => $task->chat_id,
                    'text' => "{$task->item->name}\r\n{$site->url}\r\n{$task->item->phase}"
                ]);
                $task->delete();
            }
        }

        Log::info('end check lootfarm');
    }
}
