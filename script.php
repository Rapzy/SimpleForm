<?php

$mysqli = new mysqli('localhost', 'root', '', 'test');

if (isset($_GET['search']) && strlen($_GET['search']) > 0){
	$term = $_GET['search'];
	
	$query = "SELECT id,name AS 'text',id_parent FROM headings WHERE name LIKE '%".$term."%' LIMIT 10";
	$response = $mysqli->query($query);
	
	while($row = $response->fetch_array(MYSQLI_ASSOC)){
		if($row['id_parent'] != 0 ){
			$response_parent = $mysqli->query("SELECT name AS 'text_parent' FROM headings WHERE id_parent =".$row['id']);
			$parent = $response_parent->fetch_array(MYSQLI_ASSOC);
			$row['text_parent'] = $parent['text_parent'];
		}
		if(count($row)>0){
			$result[] = $row;
		}
	}
	if(isset($result)){
		MakeResponse($result);
	}
}

function MakeResponse($data){
	echo json_encode($data);
}

$mysqli->close();

?>