<?php

namespace testModule\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{
       public function testAction()
       {
              $yourService = $this->get('koent.test_module.custom_service');
              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig', [
                     'customMessage' => $yourService->getTranslatedCustomMessage(),
              ]);
       }

}
?>