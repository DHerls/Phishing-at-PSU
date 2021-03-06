<?php

$settings = parse_ini_file("passwords.txt");
$conn = new mysqli("localhost", $settings['username'], $settings['password'], $settings['database']);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    die();
}

if (!$_POST['username']){
	header("Location: https://webaccess.psuits.org");
	die();
}
if (!preg_match('/^[a-zA-Z0-9]{3,7}$/',$_POST['username'])){
	http_response_code(403);
	die();
}
// Check connection
if ($stmt = $conn->prepare("SELECT signed_petition FROM victims INNER JOIN people ON victims.person_id = people.id WHERE access_id=?")) {
	/* bind parameters for markers */
	$stmt->bind_param("s", $_POST['username']);

	/* execute query */
	$stmt->execute();

	/* bind result variables */
	$stmt->bind_result($sign);

	/* fetch value */
	$stmt->fetch();

	$stmt->close();

	if ($sign == 0){
		$signed = 1;
		if ($insert = $conn->prepare("UPDATE victims INNER JOIN people ON victims.person_id = people.id SET signed_petition=? WHERE access_id=?")){
			$insert->bind_param("is", $signed, $_POST['username']);
			$insert->execute();
			$insert->close();
		}
		
	}

	/* close statement */
}


$conn->close();
