<?php
/**
 * Description of view - This class handles the view of the task object
 *
 * @author gndlovu
 */
class TasksView
{
    protected $mdl;

    public function __construct($task_id)
    {
        include_once("Core/Modal/TasksModel.php");
        $this->mdl = new TasksModel($task_id);
    }

    public function dashboard()
    {
        $taskArray = $this->mdl->ListTasks();

        $html = '';
        if (empty($taskArray)) {
            $html .= '<a id="newTask" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">No Tasks Available</h4>
                    <p class="list-group-item-text">Click here to create one</p>
                </a>';
        } else {
            foreach ($taskArray as $task) {
                $html .= '<a id="' . $task->TaskId . '" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">' . $task->TaskName . '</h4>
                    <p class="list-group-item-text">' . $task->TaskDescription . '</p>
                </a>';
            }
        }

        return $html;
    }

    public function add_task($task_name, $task_desc)
    {
        if (!$task_name) {
            return ['status' => false, 'error_description' => 'You need to provide a task name!'];
        } else {
            //Check if already exists!
            $taskArray = $this->mdl->ListTasks();
            if (!empty($taskArray)) {
                foreach ($taskArray as $task) {
                    if ($task->TaskName === $task_name) {
                        return ['status' => false, 'error_description' => 'Task name already exists. Provide a new name!'];
                    }
                }
            }

            $this->mdl->TaskName = $task_name;
            $this->mdl->TaskDescription = $task_desc;
            $this->mdl->Save();
        }

        return ['status' => true, 'message' => "Task \"$task_name\" successfully added!"];
    }

    public function update_task($task_name, $task_desc)
    {
        if (!$task_name) {
            return ['status' => false, 'error_description' => 'You need to provide a task name!'];
        } else {
            //Check if already exists!
            $taskArray = $this->mdl->ListTasks();
            foreach ($taskArray as $task) {
                if ($task->TaskName === $task_name && $task->TaskId != $this->mdl->TaskId) {
                    return ['status' => false, 'error_description' => 'Task name already exists. Provide a new name!'];
                }
            }

            $this->mdl->TaskName = $task_name;
            $this->mdl->TaskDescription = $task_desc;
            $this->mdl->Save();
        }

        return ['status' => true, 'message' => "Task \"$task_name\" successfully updated!"];
    }

    public function delete_task()
    {
        $this->mdl->Delete();
        return ['status' => true, 'message' => "Task \"" . $this->mdl->TaskName . "\" successfully deleted!"];
    }

    public function reload_tasks()
    {
        return $this->dashboard();
    }
}