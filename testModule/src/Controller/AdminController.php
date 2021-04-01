<?php

namespace testModule\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{

       private function getData()
       {
              $sqlSearch = 'SELECT `data_json` FROM `prstshp_testmodule` WHERE `data_name`=\'TESTMODULE_CRONJOB\'';
              $resultCronJob = \Db::getInstance()->getRow($sqlSearch);
		   	  $values = array();
		   		$len = 0;
		   		$i = 0;
              if ($resultCronJob) {
				$res = ($resultCronJob["data_json"]);
				$res = preg_replace("!\r?\n!", "", $res);
				$res = json_decode($res);
				foreach($res as $post){
				if(!array_key_exists($post->userId, $values)){
					$values[$post->userId] = array();
					$values[$post->userId]["len"] = 0;
					$values[$post->userId]["num"] = 0;
				}
					$values[$post->userId]["len"] += strlen($post->body);
					$values[$post->userId]["num"] += 1;
				}
			  }
              return $values;
       }

       public function testAction()
       {      
		   	  
              $values = $this->getData();
              $yourService = $this->get('koent.test_module.custom_service');
              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig', [
                     'values' => $values,
              ]);
       }
}
