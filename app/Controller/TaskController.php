<?php

namespace Controller;

use Intervention\Image\ImageManagerStatic as Image;
use Model\Task;

Class TaskController extends BaseController
{

    public function create()
    {

        $task = new Task();
        $errors = [];
        $message = '';
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $task->fill(\App::$request['request']);

            if ($errors = \Validator::validate(\App::$request['request'], ['name' => 'required', 'email' => ['email', 'required']])) {
                $message = 'errors';
            }
            else {
                $task->save();
                $imagePath = $task->id . "-image.jpg";
                if (isset(\App::$request['files']['picture']) && \App::$request['files']['picture']['tmp_name'] ) {
                    $img = Image::make(\App::$request['files']['picture']['tmp_name'])->resize(320, 240)->save(\App::$PUBLIC_DIR . "/media/$imagePath");
                    $task->update(['picture' => $imagePath]);
                }
                $message = 'ok';
            }
        }
        return $this->render('create', compact('task', 'errors', 'message'));
    }

    public function edit($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return [
                'body' => '404 Task is not found',
                'code' => 404
            ];
        }

        $errors = [];
        $message = '';
        if(\App::$request['server']['REQUEST_METHOD'] == "POST") {
            $task->fill(\App::$request['request']);

            if ($errors = \Validator::validate(\App::$request['request'], ['name' => 'required', 'email' => ['email', 'required']])) {
                $message = 'errors';
            }
            else {
                $task->save();
                $imagePath = $task->id . "-image.jpg";
                if (isset(\App::$request['files']['picture']) && \App::$request['files']['picture']['tmp_name']) {
                    $img = Image::make(\App::$request['files']['picture']['tmp_name'])->resize(320, 240)->save(\App::$PUBLIC_DIR . "/media/$imagePath");
                    $task->update(['picture' => $imagePath]);
                }
                $message = 'ok';
            }
        }
        $mode = 'edit';
        return $this->render('create', compact('task', 'errors', 'message', 'mode'));
    }
}