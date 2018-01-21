jQuery.extend({
    doAJAX: function (url, data, type, callback) {
        type = (!type) ? "GET" : type;

        return $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                swal("Oops!", "Error Code: " + XMLHttpRequest.status + " : " + XMLHttpRequest.statusText, "error");
            },
            success: function (data) {
                callback(data);
            }
        });
    }
});

$(document).ready(function () {
    $('#myModal').on('show.bs.modal', function (event) {

        var triggerElement = $(event.relatedTarget); // Element that triggered the modal
        var modal = $(this);
        var TaskId;
        var TaskName;
        var TaskDescription;

        if (triggerElement.attr("id") == 'newTask') {
            modal.find('.modal-title').text('New Task');
            $('#deleteTask').hide();
            TaskId = '-1';
            TaskName = '';
            TaskDescription = '';
        } else {
            modal.find('.modal-title').text('Task details');
            $('#deleteTask').show();

            TaskId = triggerElement.attr("id");
            TaskName = triggerElement.find('h4').text();
            TaskDescription = triggerElement.find('p').text();
        }

        $('input#InputTaskId').val(TaskId);
        $('input#InputTaskName').val(TaskName);
        $('textarea#InputTaskDescription').val(TaskDescription);
    });

    $('#myModal').on('hide.bs.modal', function (event) {
        $('input#InputTaskId').val('');
        $('input#InputTaskName').val('');
        $('textarea#InputTaskDescription').val('');
    });

    $('#saveTask').click(function () {
        var TaskId = $('input#InputTaskId').val();
        var TaskName = $('input#InputTaskName').val();
        var TaskDescription = $('textarea#InputTaskDescription').val();

        doTaskAction((TaskId == '-1') ? 'add' : 'update', TaskId, TaskName, TaskDescription);
    });

    $('#deleteTask').click(function () {
        var TaskId = $('input#InputTaskId').val();
        var TaskName = $('input#InputTaskName').val();

        swal({
                title: "Are you sure?",
                text: "Task \"" + TaskName + "\" will be deleted and this cannot be undone!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Delete!",
                closeOnConfirm: true
            },
            function () {
                doTaskAction('delete', TaskId);
            });
    });
});

function doTaskAction(action, TaskId, TaskName, TaskDescription) {
    $.doAJAX(base_url + '?function=ajax&action=' + action, {'task_id': TaskId, 'task_name': TaskName, 'task_description': TaskDescription}, 'POST', function (response) {
        if (response.status == true) {
            swal("Good job!", response.message, "success");
            updateTaskList();
            $('#myModal').modal('hide');
        }
        else {
            swal("Oops!", response.error_description, "error");
        }
    });
}

function updateTaskList() {
    $.doAJAX(base_url + '?function=ajax&action=reload_tasks', {}, 'GET', function (response) {
        $("#TaskList").html(response);
    });
}