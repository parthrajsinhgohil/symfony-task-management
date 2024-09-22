
$(document).ready(function() {
    $('.status-select').change(function() {
        const taskId = $(this).data('task-id');
        const newStatus = $(this).val();
    
        $.ajax({
            url: 'http://127.0.0.1:8000/update-task-status',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id: taskId, status: newStatus }),
            success: function(response) {
                // console.log(response)
                alert('Task status updated');
            },
            error: function(xhr) {
                // console.log(taskId, newStatus);
                alert('Error updating task status: ');
            }
        });
    });
});