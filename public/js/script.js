$(document).ready(function() {
	var projectId = $("#task_project").val();
	
    taskByProject(projectId);

	$('#task_project').change(function() {
		var projectId = $("#task_project").val();
		
		taskByProject(projectId);
	});

});

function taskByProject(projectId) {
	var url = '/dashboard/tasks/create/ajax/team/' + projectId;
	$('#task_save').attr('disabled',true);
	
	$.ajax({
		url: url,
		method: 'GET',
		success: function(data) {
			$('#task_team option:first-child').val(data.id).text(data.name);
			$('#task_save').attr('disabled',false);
		}
	});
}

$(document).on('click', '.task-edit', (e) => {
	let taskId = e.target.id;
	let url = '/dashboard/tasks/update/' + taskId;

	$.ajax({
		url: url,
		method: 'GET',
		success: function(data) {
			$('#taskUpdate').html(data);
			$('#taskUpdate').modal();
		}
	})
});