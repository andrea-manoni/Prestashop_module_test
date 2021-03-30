<?php

namespace testModule\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{
       private function getData()
       {
              $sqlSearch = 'SELECT `data_json` FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
              $resultCronJob = \Db::getInstance()->getRow($sqlSearch);
              if ($resultCronJob) {
                     $json = json_decode($resultCronJob["data_json"]);
                     
              }
              return $json[0]->userId;
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
