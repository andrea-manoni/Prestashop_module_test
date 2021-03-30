<?php
$servername = "localhost";
        $username = "prestashop_1";
        $password = "Ioov67^0";
        $dbname = "prestashop_9";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	echo "ERROR";
  die("Connection failed: " . $conn->connect_error);
}

$ch = curl_init('https://jsonplaceholder.typicode.com/posts');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch);
curl_close($ch);
json_encode($data);


//CHECK ON name=TESTMODULE_CRONJOB AND name="TESTMODULE_UPDATE_TIME"
//IF CRONJOB !EXISTS THEN INSERT
//ELSE IF CURRENT_TIMESTAMP - LAST CRONJOB_UPDATE_TIME > TESTMODULE_UPDATE_TIME
//UPDATE


$sqlSearch = 'SELECT * FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
$resultCronJob = mysqli_query($conn, $sqlSearch);
if (mysqli_num_rows($resultCronJob) > 0) {
	$sqlSearch = 'SELECT data_value FROM `prstshp_testmodule` WHERE`data_name`=\'TESTMODULE_UPDATE_TIME\'';
	$resultUpdateTime = mysqli_query($conn, $sqlSearch);
	if(mysqli_num_rows($resultUpdateTime) > 0) {
		$valueUpdate = mysqli_fetch_assoc($resultUpdateTime);
		$updateCronJob = mysqli_fetch_assoc($resultCronJob);
		$now =  date_format( new DateTime() , 'Y-m-d H:i:s');
		$date_diff = date_diff(date_create($now),date_create($updateCronJob["date_upd"]));
		$mins = $date_diff->i;
		$date_diff->h > 0 ? $mins = ($date_diff->h * 60) + $mins : $mins = $mins;
		if($mins >= $valueUpdate["data_value"]){
		$sql = 'UPDATE `prstshp_testmodule` SET `data_json`=\''.$data.'\', `date_upd` = CURRENT_TIMESTAMP WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
		$message = 'Record updated';
			if ($conn->query($sql) === TRUE) {
  				echo $message;
			} else {
 				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		} else {
			echo "Too early for an update";
		}
	}
} else {
	$sql = 'INSERT INTO `prstshp_testmodule`(`data_name`, `data_value`,`data_json` ,`date_add`, `date_upd`) VALUES (\'TESTMODULE_CRONJOB\',NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
	$message = 'New record created';
	if ($conn->query($sql) === TRUE) {
  echo $message;
} else {
 echo "Error: " . $sql . "<br>" . $conn->error;
}
}



$conn->close();
?>