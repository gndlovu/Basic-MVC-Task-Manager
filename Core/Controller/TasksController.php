<?php

/**
 * TasksController - This class handles task actions
 *
 * @author gndlovu
 */
class TasksController
{
    function __construct($task_id)
    {
        /* Get the view */
        include "Core/View/TasksView.php";
        $this->View = new TasksView($task_id);
    }

    function flow($params = array())
    {
        isset($params["function"]) == 1 ? "" : $params["function"] = "";
        isset($params["action"]) == 1 ? "" : $params["action"] = "";

        $function = clean_param($params["function"]);
        $action = clean_param($params["action"]);

        switch ($function) {
            case "":
            case "tasks": {
                switch ($action) {
                    case "":
                    case "dashboard": {
                        echo $this->View->dashboard();
                    };break;
                }
            };
            break;
            case "ajax": {
                switch ($action) {
                    case "add": {
                        echo json_encode($this->View->add_task($params['task_name'], $params['task_description']));
                    };break;
                    case "update": {
                        echo json_encode($this->View->update_task($params['task_name'], $params['task_description']));
                    };break;
                    case "delete": {
                        echo json_encode($this->View->delete_task());
                    };break;
                    case "reload_tasks": {
                        echo json_encode($this->View->reload_tasks());
                    };break;
                }
            };break;
        }
    }
}