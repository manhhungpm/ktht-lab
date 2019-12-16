<?php

namespace App\Listeners;

use App\User;
use App\Events\LoggedOut;
use App\Repositories\Admin\ActionsLogRepository;
use Carbon\Carbon;

class LogSuccessfulLogout
{
    protected $actionsLogRepository;

    /**
     * LogSuccessfulLogout constructor.
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
     * @param  LoggedOut $event
     * @return void
     */
    public function handle(LoggedOut $event)
    {
        $arr = [
            'user_id' => $event->user->id,
            'username' => $event->user->name,
            'action_name' => LOGOUT,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'class_name' => (new User())->getTable(),
            'object_id' => $event->user->id,
            'object_name' => $event->user->name,
            'old_value' => json_encode($event->user)
        ];
        $this->actionsLogRepository->store($arr);
    }
}
