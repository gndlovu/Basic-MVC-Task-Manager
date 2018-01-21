<?php

/**
 * Description of modal - This class handles the modification of a task object
 *
 * @author gndlovu
 */
class TasksModel
{
    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;

    public function __construct($Id = null)
    {
        $this->TaskDataSource = file_get_contents(DB_PATH);
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource); // Should decode to an array of Task objects
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }

    protected function Create()
    {
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = 'New Task';
        $this->TaskDescription = 'New Description';
    }

    protected function getUniqueId()
    {
        //Use max task id plus 1
        $max_id = $this->GetMaxId();

        return ($max_id + 1);
    }

    protected function LoadFromId($Id = null)
    {
        if ($Id && $Id != '-1') {
            if (!empty($this->TaskDataSource)) {
                foreach ($this->TaskDataSource as $task) {
                    if ($task->TaskId == $Id) {
                        $this->TaskId = $task->TaskId;
                        $this->TaskName = $task->TaskName;
                        $this->TaskDescription = $task->TaskDescription;

                        return true;
                    }
                }
            }
        } else
            return null;
    }

    public function ListTasks()
    {
        return $this->TaskDataSource;
    }

    public function Save()
    {
        $max_id = $this->GetMaxId();
        if ($max_id < $this->TaskId) {
            //Add new task
            $index = !empty($this->TaskDataSource) ? count($this->TaskDataSource) : 0;

            $new_task = new stdClass();
            $new_task->TaskId = $this->TaskId;
            $new_task->TaskName = $this->TaskName;
            $new_task->TaskDescription = $this->TaskDescription;

            $this->TaskDataSource[$index] = $new_task;
        } else {
            //Update task
            foreach ($this->TaskDataSource as $index => $task) {
                if ($task->TaskId == $this->TaskId) {
                    $this->TaskDataSource[$index]->TaskName = $this->TaskName;
                    $this->TaskDataSource[$index]->TaskDescription = $this->TaskDescription;
                }
            }
        }

        $this->SaveTaskDataSource();
    }

    public function Delete()
    {
        foreach ($this->TaskDataSource as $index => $task) {
            if ($task->TaskId == $this->TaskId) {
                //Remove from array and resort
                array_splice($this->TaskDataSource, $index, 1);
            }
        }

        $this->SaveTaskDataSource();
    }

    private function GetMaxId()
    {
        $max_id = 0;

        if (!empty($this->TaskDataSource)) {
            foreach ($this->TaskDataSource as $task) {
                if ($task->TaskId > $max_id) {
                    $max_id = $task->TaskId;
                }
            }
        }

        return $max_id;
    }

    private function SaveTaskDataSource()
    {
        file_put_contents(DB_PATH, json_encode($this->TaskDataSource));
    }
}

?>
