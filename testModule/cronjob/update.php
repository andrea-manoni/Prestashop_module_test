<?php
require_once 'presta.koent.it/config/config.inc.php';
require_once 'presta.koent.it/classes/db/Db.php';



$sqlSearch = 'SELECT * FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_AUTO\'';
if ($resultAutoUpdate = Db::getInstance()->getRow($sqlSearch)) {
	if($resultAutoUpdate['data_auto']){
		mb_language('uni');
		mb_internal_encoding('UTF-8');
		$ch = curl_init('https://jsonplaceholder.typicode.com/posts');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data);
		$data = json_encode($data);
		$sqlSearch = 'SELECT * FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
if ($resultCronJob = Db::getInstance()->getRow($sqlSearch)) {
	$sqlSearch = 'SELECT data_value FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_UPDATE_TIME\'';
	if($resultUpdateTime = Db::getInstance()->getRow($sqlSearch)) {
		$now =  date_format( new DateTime() , 'Y-m-d H:i:s');
		$date_diff = date_diff(date_create($now),date_create($resultCronJob["date_upd"]));
		$mins = $date_diff->i;
		$date_diff->h > 0 ? $mins = ($date_diff->h * 60) + $mins : $mins = $mins;
		if($mins >= $resultUpdateTime["data_value"]) {
            $sql = 'UPDATE `prstshp_testmodule` SET `data_json`=\''.($data).'\', `date_upd` = CURRENT_TIMESTAMP WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
            Db::getInstance()->execute($sql);
                echo "UPDATE RECORD";
        }
	}
} else {
	$sql = 'INSERT INTO `prstshp_testmodule`(`data_name`, `data_value`,`data_json` ,`date_add`, `date_upd`) VALUES (\'TESTMODULE_CRONJOB\',NULL, \''.$data.'\', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
	Db::getInstance()->execute($sql);
		echo "NEW RECORD";
}
	
	}
}
?>