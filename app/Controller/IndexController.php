<?php

namespace Controller;

use Model\Task;

Class IndexController extends BaseController
{
    /***
     * action task list
     */
    public function index()
    {
        $tasks = Task::all();

        return $this->render('task-list',  [
            'tasks' => $tasks
        ]);
    }
}
