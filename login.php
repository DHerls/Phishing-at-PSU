<?php

$settings = parse_ini_file("passwords.txt");
$conn = new mysqli("localhost", $settings['username'], $settings['password']);

if (!$_POST['username']){
	header("Location: https://webaccess.psuits.org");
	die();
}

if (!preg_match('/[a-zA-Z0-9]{3,7}',$_POST['username']){
	http_response_code(403);
	die();
}

// Check connection
if ($conn) {
    if ($stmt = $mysqli->prepare("SELECT * FROM victims WHERE access_id=?")) {

		/* bind parameters for markers */
		$stmt->bind_param("s", $_POST['username']);

		/* execute query */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($victim);

		/* fetch value */
		$stmt->fetch();

		print_r($victim);

		/* close statement */
    $stmt->close();
	}
}

$conn->close();
