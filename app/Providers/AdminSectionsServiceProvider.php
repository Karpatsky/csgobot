<?php

namespace App\Providers;

use App\Dealer;
use App\Item;
use App\Pattern;
use App\Site;
use App\Task;
use App\User;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        User::class => 'App\Http\Admin\Sections\Users',
        Dealer::class => 'App\Http\Admin\Sections\Dealers',
        Item::class => 'App\Http\Admin\Sections\Items',
        Site::class => 'App\Http\Admin\Sections\Sites',
        Task::class => 'App\Http\Admin\Sections\Tasks',
        Pattern::class => 'App\Http\Admin\Sections\Patterns',
    ];

    /**
     * Register sections.
     *
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
    	//

        parent::boot($admin);
    }
}
