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

elseif(isset($_GET['form'])){
	foreach ($_GET as $key => $value) {
		$user[$key] = htmlentities(trim($value));
	}
	if($user['form'] == 'form-doer'){
		$prefix = 'doer';
	}
	elseif($user['form'] == 'form-customer'){
		$prefix = 'customer';
	}

	$query = "
		SELECT `country`.`id` AS 'country_id'
		FROM `city` 
		JOIN `region` ON `city`.`region_id` = `region`.`id`
		JOIN `country` ON `region`.`country_id` = `country`.`id` 
		WHERE `city`.`id` = ".$user[$prefix.'-town'];
	$response = $mysqli->query($query);
	$row = $response->fetch_array(MYSQLI_ASSOC);
	
	$query = "
			INSERT INTO users (user, email, phone, city_id, heading_id, country_id)
			VALUES ('".$prefix."','".$user[$prefix.'-email']."','".$user[$prefix.'-tel']."',".$user[$prefix.'-town'].",".$user[$prefix.'-heading'].",".$row['country_id'].")";
	$mysqli->query($query);
}
elseif (isset($_GET['type']) && $_GET['type'] == 'count'){
	$country = [21,0,1,81];
	$result['doer'] = CountUsers('doer',$country);
	$result['customer'] = CountUsers('customer',$country);
	MakeResponse($result);
} 

function CountUsers($type, $country){
	$mysqli = new mysqli('localhost', 'root', '', 'test');
	for ($i=0; $i < count($country); $i++) { 
		$query = "
			SELECT COUNT(*) AS 'count'
			FROM  users
			WHERE country_id = ".$country[$i]." AND user='".$type."'";
		$response = $mysqli->query($query);
		$row = $response->fetch_array(MYSQLI_ASSOC);
		$result[] = $row;
	}
	$mysqli->close();
	return $result;
}

function MakeResponse($data){
	echo json_encode($data);
}

$mysqli->close();

?>