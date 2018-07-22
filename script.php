<?php

$mysqli = new mysqli('localhost', 'root', '', 'test');
	
if (isset($_GET['search']) && strlen($_GET['search']) > 0){
	$term = $_GET['search'];
	if (preg_match("/(.)*(town)/",$_GET['select'])){
		$query = "
			SELECT id, name AS 'text',region_id 
			FROM city 
			WHERE name LIKE '%".$term."%' 
			LIMIT 10";
		$response = $mysqli->query($query);
		while($row = $response->fetch_array(MYSQLI_ASSOC)){
			if(isset($parent)){
				unset($parent);
			}
			$response_parent = $mysqli->query("
				SELECT * 
				FROM region 
				WHERE id =".$row['region_id']
			);
			$parent[] = $response_parent->fetch_array(MYSQLI_ASSOC);
			$response_parent = $mysqli->query("
				SELECT * 
				FROM country 
				WHERE id =".$parent[0]['country_id']
			);
			$parent[] = $response_parent->fetch_array(MYSQLI_ASSOC);
			$row['parents'] = $parent;
			if(count($row)>0){
				$result[] = $row;
			}
		}
	}
	elseif(preg_match("/(.)*(heading)/",$_GET['select'])){
		$query = "
			SELECT id,name AS 'text',id_parent 
			FROM headings 
			WHERE name LIKE '%".$term."%' 
			LIMIT 10";
		$response = $mysqli->query($query);
		
		while($row = $response->fetch_array(MYSQLI_ASSOC)){
			if(isset($parent)){
				unset($parent);
			}
			if($row['id_parent'] != 0 ){
				$response_parent = $mysqli->query("
					SELECT * 
					FROM headings 
					WHERE id =".$row['id_parent']
				);
				$parent[] = $response_parent->fetch_array(MYSQLI_ASSOC);
				if($parent[0]['id_parent'] != 0 ){
					$response_parent = $mysqli->query("
						SELECT * 
						FROM headings 
						WHERE id =".$parent[0]['id_parent']
					);
					$parent[] = $response_parent->fetch_array(MYSQLI_ASSOC);
				}
				$row['parents'] = $parent;
			}
			if(count($row)>0){
				$result[] = $row;
			}
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