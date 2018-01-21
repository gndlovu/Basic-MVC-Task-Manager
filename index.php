<?php
include "Includes/config.php";
include "Includes/library.php";
include "Core/Controller/TasksController.php";

$function = isset($_REQUEST["function"]) ? clean_param($_REQUEST["function"]) : '';
$active_task_id = isset($_REQUEST['task_id']) ? $_REQUEST['task_id'] : '-1';

$controller = new TasksController($active_task_id);

if ($function === "ajax") {
    $controller->flow($_REQUEST);
    return false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Basic MVC Task Manager</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css">

    <script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-blockui.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="assets/js/core.js"></script>

    <script type="text/javascript">
        var base_url = 'index.php';
        window.onbeforeunload = function (e) {
            $.blockUI({message: "<img src='assets/images/loader.gif' />", css: {backgroundColor: 'transparent', border: 'none'}});
        };

        $(document).ajaxStart(function () {
            $.blockUI({message: "<img src='assets/images/loader.gif' />", css: {backgroundColor: 'transparent', border: 'none'}});
        }).ajaxStop(function () {
            $.unblockUI();
        });
    </script>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" id="InputTaskId">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 5px;;">
                            <input id="InputTaskName" type="text" placeholder="Task Name" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <textarea id="InputTaskDescription" placeholder="Description" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="deleteTask" type="button" class="btn btn-danger">Delete Task</button>
                <button id="saveTask" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2 class="page-header">Task List</h2>
            <!-- Button trigger modal -->
            <button id="newTask" type="button" class="btn btn-primary btn-lg" style="width:100%;margin-bottom: 5px;" data-toggle="modal" data-target="#myModal">
                Add Task
            </button>
            <div id="TaskList" class="list-group">
                <?php $controller->flow($_REQUEST); ?>
            </div>
        </div>
    </div>

</div>
</body>
</html>