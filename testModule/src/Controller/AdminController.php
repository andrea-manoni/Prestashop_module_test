<?php

namespace testModule\Controller;

use mysqli;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{

       private function getData()
       {
              $servername = "localhost";
        	$username = "prestashop_1";
        	$password = "Ioov67^0";
        		$dbname = "prestashop_9";
              // Create connection
              $conn = new mysqli($servername, $username, $password, $dbname);
              if ($conn->connect_error) {
                     die("Connection failed: " . $conn->connect_error);
              }
              $sqlSearch = 'SELECT `data_json` FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
              $resultCronJob = mysqli_query($conn, $sqlSearch);
              if (mysqli_num_rows($resultCronJob) > 0) {
                     $valueToPrint = mysqli_fetch_assoc($resultCronJob);
                     return $valueToPrint["data_json"];
              }
              return false;
       }

       public function testAction()
       {      
              $value = $this->getData() !== false ? $this->getData() : "No data found in database";
              $yourService = $this->get('koent.test_module.custom_service');
              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig', [
                     'customMessage' => $value,
              ]);
       }
}
