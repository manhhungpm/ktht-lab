<?php

namespace App\Listeners;

use App\Events\LoggedIn;
use App\Repositories\Admin\ActionsLogRepository;
use App\Models\ActionsLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
{
    protected $actionsLogRepository;

    /**
     * LogSuccessfulLogin constructor.
     * @param ActionsLogRepository $actionsLogRepository
     */
    public function __construct(ActionsLogRepository $actionsLogRepository)
    {
        //
        $this->actionsLogRepository = $actionsLogRepository;
    }

    /**
     * Handle the event.
     *
     * @param  LoggedIn $event
     * @return void
     */
    public function handle(LoggedIn $event)
    {
        $arr = [
            'user_id' => $event->user->id,
            'username' => $event->user->name,
            'action_name' => LOGIN,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'class_name' => (new User())->getTable(),
            'object_id' => $event->user->id,
            'object_name' => $event->user->name,
            'new_value' => json_encode($event->user)
        ];
        $this->actionsLogRepository->store($arr);
    }
}
