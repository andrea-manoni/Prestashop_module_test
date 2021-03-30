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
              $password = "Y7dzb4^4";
              $dbname = "prestashop_a";
              // Create connection
              $conn = new mysqli($servername, $username, $password, $dbname);
              if ($conn->connect_error) {
                     die("Connection failed: " . $conn->connect_error);
              }
              $sqlSearch = 'SELECT `value` FROM `prstshp_configuration` WHERE `name`=\'TESTMODULE_CRONJOB\'';
              $resultCronJob = mysqli_query($conn, $sqlSearch);
              if (mysqli_num_rows($resultCronJob) > 0) {
                     $valueToPrint = mysqli_fetch_assoc($resultCronJob);
                     return $valueToPrint["value"];
              }
              return false;
       }


       public function createTable(){
              $servername = "localhost";
              $username = "prestashop_1";
              $password = "Ioov67^0";
              $dbname = "prestashop_9";
              // Create connection
              $conn = new mysqli($servername, $username, $password, $dbname);
              if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
              }
              $sql = "CREATE TABLE prstshp_testmodule (
                  id_testmodule INT NOT NULL UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  data_name VARCHAR NOT NULL,
                  data_value text DEFAULT NULL,
                  data_json JSON DEFAULT NULL,
                  date_add DATETIME(),
                  date_upd DATETIME(),
                  PRIMARY KEY(id_testmodule)
                  )";
                  $message = "NOPE";
                  if ($conn->query($sql) === TRUE) {
                    echo "Table prstshp_configuration created successfully";
                    $message = "CIAO";
                  } else {
                    echo "Error creating table: " . $conn->error;
                  }
                  
                  $conn->close();
                  return $message;
          }

       public function testAction()
       {      
              $message = $this->createTable();
              $value = $this->getData() != false ? $this->getData() : "No data found in database";
              $yourService = $this->get('koent.test_module.custom_service');
              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig', [
                     'customMessage' => $message,
              ]);
       }
}
