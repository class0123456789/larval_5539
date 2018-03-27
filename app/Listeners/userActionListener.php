<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use App\Events\userAction as userActionEvent;
class userActionListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(userActionEvent $event)
    {
        $str = '管理员:' . $event->adminName . '(id:' . $event->uid . ')' . $event->content;
        Log::info($str);
    }
}
