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
	
	$.ajax({
		url: url,
		method: 'GET',
		success: function(data) {
			$('#task_team option:first-child').val(data.id).text(data.name);
		}
	});
}
