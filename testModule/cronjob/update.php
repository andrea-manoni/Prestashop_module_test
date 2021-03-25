<?php
$servername = "localhost";
$username = "prestashop_1";
$password = "Ioov67^0";
$dbname = "prestashop_a";
$t = strval(time());
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$ch = curl_init('https://jsonplaceholder.typicode.com/posts/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch);
curl_close($ch);
echo $data;

//CHECK ON name=TESTMODULE_CRONJOB
//IF EXISTS UPDATE
//ELSE INSERT

$sqlSearch = 'SELECT * FROM `prstshp_configuration` WHERE `name`=\'TESTMODULE_CRONJOB\'';
$result = mysqli_query($conn, $sqlSearch);

if (mysqli_num_rows($result) > 0) {
	$sql = 'UPDATE `prstshp_configuration` SET `value`=\''.$t.'\', `date_upd` = CURRENT_TIMESTAMP WHERE `name`=\'TESTMODULE_CRONJOB\'';
	$message = 'Record updated';
} else {
	$sql = 'INSERT INTO `prstshp_configuration`(`name`, `value`, `date_add`, `date_upd`) VALUES (\'TESTMODULE_CRONJOB\', \''.$t.'\', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
	$message = 'New record created';
}

if ($conn->query($sql) === TRUE) {
  echo $message;
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>