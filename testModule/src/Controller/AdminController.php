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


       public function testAction()
       {
              $value = $this->getData() != false ? $this->getData() : "No data found in database";
              $yourService = $this->get('koent.test_module.custom_service');
              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig', [
                     'customMessage' => $value,
              ]);
       }
}
