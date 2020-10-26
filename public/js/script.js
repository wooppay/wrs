$(document).ready(function() {
	var projectId = $("#task_project").val();
	
    taskByProject(projectId);

	$('#task_project').change(function() {
		var projectId = $("#task_project").val();
		
		taskByProject(projectId);
	});

});

function taskByProject(projectId) {
	$('#task_save').attr('disabled',true);
	
	$.ajax({
		url: routeTeamByProject,
		method: 'POST',
		data: {
			project_id: projectId
		},
		success: function(data) {
			$('#task_team option:first-child').val(data.id).text(data.name);
			$('#task_save').attr('disabled',false);
		}
	});
}

$(document).on('click', '.task-edit', (e) => {
	let taskId = e.target.id;

	$.ajax({
		url: routeTaskUpdate,
		method: 'POST',
		data: {
			task_id: taskId
		},
		success: function(data) {
			$('#taskUpdate').html(data);
			$('#taskUpdate').modal();
		}
	})
});

$(document).on('click', '.task-archive', (e) => {
	var taskId = e.target.id;

	$('#task-archive-confirm').attr('data', taskId);
	$('#taskArchive').modal('show');
});

$(document).on('click', '#task-archive-confirm', (e) => {
	let taskId = e.target.getAttribute('data');

	$.ajax({
		url: routeTaskArchive,
		method: 'POST',
		data: {
			task_id: taskId
		},
		success: function (data) {
			location.href = dashboardUrl;
		},
		error: function (data) {
			location.href = dashboardUrl;
		}
	})
});