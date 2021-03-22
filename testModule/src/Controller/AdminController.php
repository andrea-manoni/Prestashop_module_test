<?php

namespace testModule\Controller;

use Doctrine\Common\Cache\CacheProvider;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{
       private $cache;

       public function testAction() {
              $cache = $this->container->get('doctrine.cache');

              return $this->render('@Modules/testModule/views/templates/admin/test.html.twig');
       }

}
?>