<?php
	include_once('includes/db.inc');
	
	$project_id = pg_escape_string($_POST['projectId']);
	$query = "SELECT group_project_id,project_name FROM project_group_list WHERE group_id = $project_id";
	
	$results = $db->get_results($query,ARRAY_A);
	
	echo "<table border='1'><tr><th>Subproject ID</th><th>Subproject Name</th></tr>";
	
	foreach($results as $result) {
		echo "<tr><td>$result[group_project_id]</td><td>$result[project_name]</td>";
	}
	echo "</table>";

?>