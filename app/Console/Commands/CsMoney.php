<?php

namespace App\Console\Commands;

use App\Item;
use App\Site;
use App\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class CsMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csmoney:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking csmoney items';

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
        Log::info('csmoney check');
        $csmoney = Site::find(7);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $csmoney->get_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = json_decode(curl_exec($curl));

        $statuses = ['FN' => '(Factory New)', 'MW' => '(Minimal Wear)', 'FT' => '(Field-Tested)',
            'BS' => '(Battle-Scarred)', 'WW' => '(Well-Worn)'];

        $db_items = Item::all();

        foreach ($curl_response as $item) {
            $item_name = '';
            try {
                $item_name = $item->m . " {$statuses[$item->e]}";
            } catch (\Exception $exception) {
                $item_name = $item->m;
            }

            $db_item = $db_items->where('full_name', '=', $item_name)->first();
            if ($db_item) {
                $tasks = Task::where('item_id', '=', $db_item->id)->where('site_id', '=', 7)->get();
                foreach ($tasks as $task) {
                    if ($item->f[0] <= $task->float || !$task->float) {
                        Telegram::sendMessage([
                            'chat_id' => $task->chat_id,
                            'text' => "{$db_item->name}\r\n{$csmoney->url}\r\n{$db_item->phase}\r\n{$item->f[0]}"
                        ]);
                        $task->delete();
                    }
                }
            }
        }

        Log::info('end check csmoney');

    }
}
