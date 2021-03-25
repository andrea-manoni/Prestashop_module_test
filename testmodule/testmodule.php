<?php
/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */


if (!defined('_PS_VERSION_')) {
    exit;
}

use Language;

class testModule extends Module
{
    public function __construct()
    {
        $this->name = 'testModule';
        $this->tab = 'administration';
        $this->version = '1.1.0';
        $this->author = 'Andrea Manoni';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Test Module');
        $this->description = $this->l('Prestashop test module, fetching data from api and showing it in backoffice.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('TESTMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
		if (!Configuration::get('TESTMODULE_PASSWORD')) {
            $this->warning = $this->l('No passwords provided');
        }
		if(!Configuration::get('TESTMODULE_UPDATE_TIME')) {
			$this->warning = $this->l('Update time not set');
		}
	}

    public function setCRONJOB(){
        $t = time();
        Configuration::updateValue('TESTMODULE_UPDATED_CRON', $t);
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (
            !parent::install() ||
			!$this->registerHook('displayOrderConfirmation')||
            !$this->registerHook('actionFrontControllerSetMedia') ||
			!$this->registerHook('actionOrderStatusPostUpdate') &&
            !$this->registerHook('displayLogoAfter') &&
            $this->installTab()
        ) {
            return false;
        }
        Configuration::updateValue('TESTMODULE_NAME', 'Username');
        Configuration::updateValue('TESTMODULE_PASSWORD', 'password');
        Configuration::updateValue('TESTMODULE_UPDATE_TIME', 10);
        return true;
    }

    

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab()
        ;
    }


    public function disable($force_all = false)
    {
        return parent::disable($force_all)
            && $this->uninstallTab()
        ;
    }

    private function installTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminController');
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = 'AdminController';
        // Only since 1.7.7, you can define a route name
        $tab->route_name = 'demo_tab_route';
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Demo Tab', array(), 'Modules.testModule.Admin', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('DEFAULT');
        $tab->module = $this->name;

        return $tab->save();
    }

    private function uninstallTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminController');
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }



	


    public function uninstall()
    {
        if (
            !parent::uninstall() ||
            !Configuration::deleteByName('TESTMODULE_NAME') ||
			!Configuration::deleteByName('TESTMODULE_PASSWORD')||
			!Configuration::deleteByName('TESTMODULE_UPDATE_TIME') &&
            $this->uninstallTab()
        ) {
            return false;
        }

        return true;
    }


    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $testModuleName = strval(Tools::getValue('TESTMODULE_NAME'));
			$testModulePass = strval(Tools::getValue('TESTMODULE_PASSWORD'));
			$testModuleUpdateTime = (int)(Tools::getValue('TESTMODULE_UPDATE_TIME'));

            if (
                !$testModuleName ||
                empty($testModuleName) ||
                !Validate::isGenericName($testModuleName)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('TESTMODULE_NAME', $testModuleName);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
			
			 if (
                !$testModulePass ||
                empty($testModulePass) ||
                !Validate::isGenericName($testModulePass)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('TESTMODULE_PASSWORD', $testModulePass);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
			
			if (
			!$testModuleUpdateTime
		)
		{
			$output .= $this->displayError($this->l('Invalid Configuration value'));
		} else {
				Configuration::updateValue('TESTMODULE_UPDATE_TIME', $testModuleUpdateTime);
			}
			
		
        }
		
		
        return $output . $this->displayForm();
    }


    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Username'),
                    'name' => 'TESTMODULE_NAME',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'password',
                    'label' => $this->l('Password'),
                    'name' => 'TESTMODULE_PASSWORD',
                    'size' => 20,
                    'required' => true
                ],
				[
					'type' => 'html',
                    'label' => $this->l('Time to pass before new updates'),
                    'name' => 'TESTMODULE_UPDATE_TIME',
                    'size' => 20,
                    'required' => true,
					'html_content' => '<input type="number" name="TESTMODULE_UPDATE_TIME">'
				]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value['TESTMODULE_NAME'] = Tools::getValue('TESTMODULE_NAME', Configuration::get('TESTMODULE_NAME'));
		$helper->fields_value['TESTMODULE_PASSWORD'] = Tools::getValue('TESTMODULE_PASSWORD', Configuration::get('TESTMODULE_PASSWORD'));
		$helper->fields_value['TESTMODULE_UPDATE_TIME'] = (int)Tools::getValue('TESTMODULE_UPDATE_TIME', Configuration::get('TESTMODULE_UPDATE_TIME'));
        return $helper->generateForm($fieldsForm);
    }

    //HOOKS

    public function hookDisplayLogoAfter(){
        $this->context->smarty->assign([
			'my_module_name' => Configuration::get('TESTMODULE_NAME'),
            'my_module_password' => Configuration::get('TESTMODULE_PASSWORD'),
			'my_module_update_time' => Configuration::get('TESTMODULE_UPDATE_TIME'),
			'my_module_message' => $this->l('ORDER CONFIRMED!!!!!!!!!!!!!!!!!!!!!'),
			
        ]);
		
		return $this->display(__FILE__, 'testModule.tpl');
    }

    public function hookDisplayOrderConfirmation($params)
	{
		$this->context->smarty->assign([
			'my_module_name' => Configuration::get('TESTMODULE_NAME'),
            'my_module_password' => Configuration::get('TESTMODULE_PASSWORD'),
			'my_module_update_time' => Configuration::get('TESTMODULE_UPDATE_TIME'),
			'my_module_message' => $this->l('ORDER CONFIRMED!!!!!!!!!!!!!!!!!!!!!'),
			
        ]);
		
		return $this->display(__FILE__, 'testModule.tpl');
	}
		
	
	public function hookActionOrderStatusPostUpdate($params)
	{	
		Configuration::updateValue('TESTMODULE_ORDER', 'This is a new order1');	
	}

    public function hookActionFrontControllerSetMedia()
    {

        $this->context->controller->registerStylesheet(
            'TESTMODULE-style',
            $this->_path . 'views/css/testModule.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );

        $this->context->controller->registerJavascript(
            'TESTMODULE-javascript',
            $this->_path . 'views/js/testModule.js',
            [
                'position' => 'bottom',
                'priority' => 1000,
            ]
        );
    }

}
