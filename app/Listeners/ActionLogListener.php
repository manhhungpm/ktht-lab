<?php

namespace App\Listeners;

use App\Events\ActionLog;
use App\Repositories\Admin\ActionsLogRepository;

class ActionLogListener
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
     * @param ActionLog $event
     */
    public function handle(ActionLog $event)
    {
        $this->actionsLogRepository->store($event->data);
    }
}
