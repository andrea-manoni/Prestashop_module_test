<?php

namespace testModule\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{
       public function testAction()
       {

              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig');
       }

}
?>