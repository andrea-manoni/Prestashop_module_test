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
    }

    public function checkTable($name)
    {
        $servername = "localhost";
        $username = "prestashop_1";
        $password = "Ioov67^0";
        $dbname = "prestashop_9";
        $conn = new mysqli($servername,  $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sqlSearch = 'SELECT * FROM `prstshp_testmodule` WHERE `name`=\'' . $name . '\'';
        if ($conn->query($sqlSearch) === TRUE) {
            echo "Table prstshp_testmodule created successfully";
            $result = mysqli_query($conn, $sqlSearch);
            if (mysqli_num_rows($result) > 0) {
                $conn->close();
                return true;
            } else {
                $conn->close();
                return false;
            }
        } else {
            $conn->close();
            return false;
        }
    }

    public function getTableValue($name)
    {
        $servername = "localhost";
        $username = "prestashop_1";
        $password = "Ioov67^0";
        $dbname = "prestashop_9";
        $conn = new mysqli($servername,  $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sqlSearch = 'SELECT * FROM `prstshp_testmodule` WHERE `name`=\'' . $name . '\'';
        if ($conn->query($sqlSearch) === TRUE) {
            echo "Table prstshp_testmodule created successfully";
            $result = mysqli_query($conn, $sqlSearch);
            if (mysqli_num_rows($result) > 0) {
                $value = mysqli_fetch_assoc($result);
                $conn->close();
                return $value["value"];
            } else {
                $conn->close();
                return false;
            }
        } else {
            $conn->close();
            return false;
        }
    }

    public function updateTableValue($name, $data)
    {
        $servername = "localhost";
        $username = "prestashop_1";
        $password = "Ioov67^0";
        $dbname = "prestashop_9";
        $conn = new mysqli($servername,  $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = 'UPDATE `prstshp_testmodule` SET `value`=\'' . $data . '\', `date_upd` = CURRENT_TIMESTAMP WHERE `name`=\'' . $name . '\'';
        if ($conn->query($sql) === TRUE) {
            echo "Table prstshp_testmodule created successfully";
            return true;
        } else {
            $conn->close();
            return false;
        }
    }

    public function createTable()
    {
        $servername = "localhost";
        $username = "prestashop_1";
        $password = "Ioov67^0";
        $dbname = "prestashop_9";
        // Create connection
        $conn = new mysqli($servername,  $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "CREATE TABLE prstshp_testmodule (
            id_testmodule INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            data_name VARCHAR(50) NOT NULL,
            data_value VARCHAR(50) DEFAULT NULL,
            data_json JSON,
            date_add DATETIME DEFAULT NULL,
            date_upd DATETIME DEFAULT NULL
            )";
        if ($conn->query($sql) === TRUE) {
            $conn->close();
            echo "Table prstshp_testmodule created successfully";
            return true;
        } else {
            $conn->close();
            echo "Error creating table: " . $conn->error;
            return false;
        }
    }

    public function insertFirstData()
    {
        $sqlName = 'INSERT INTO `prstshp_testmodule`(`data_name`, `data_value`,`data_json` ,`date_add`, `date_upd`) VALUES (\'TESTMODULE_NAME\', \'Username\',NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
        
        $sqlPass = 'INSERT INTO `prstshp_testmodule`(`data_name`, `data_value`,`data_json` ,`date_add`, `date_upd`) VALUES (\'TESTMODULE_PASSWORD\', \'pass\',NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';
        
        $sqlUpd = 'INSERT INTO `prstshp_testmodule`(`data_name`, `data_value`,`data_json` ,`date_add`, `date_upd`) VALUES (\'TESTMODULE_UPDATE_TIME\', "10" ,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)';

        return Db::getInstance()->execute($sqlName) && Db::getInstance()->execute($sqlPass) && Db::getInstance()->execute($sqlUpd);
    }

    public function deleteTable()
    {
        $servername = "localhost";
        $username = "prestashop_1";
        $password = "Ioov67^0";
        $dbname = "prestashop_9";
        // Create connection
        $conn = new mysqli($servername,  $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "DROP TABLE prstshp_testmodule";
        $message = "NOPE";
        if ($conn->query($sql) === TRUE) {
            echo "Table prstshp_testmodule created successfully";
            $conn->close();
            return true;
        } else {
            echo "Error creating table: " . $conn->error;
            $conn->close();
            return false;
        }
    }


    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (
            !parent::install() ||
            !$this->registerHook('displayOrderConfirmation') ||
            !$this->registerHook('actionFrontControllerSetMedia') ||
            !$this->registerHook('actionOrderStatusPostUpdate') &&
            $this->installTab()

        ) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS prstshp_testmodule (
            `id_testmodule` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `data_name` VARCHAR(50) NOT NULL,
            `data_value` VARCHAR(50) DEFAULT NULL,
            `data_json` JSON,
            `date_add` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `date_upd` DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;'";


        return Db::getInstance()->execute($sql) && $this->insertFirstData();
    }





    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab();
    }


    public function disable($force_all = false)
    {
        return parent::disable($force_all)
            && $this->uninstallTab();
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
            $this->uninstallTab() &&
            $this->deleteTable()

        ) {
            return false;
        }

        return true;
    }


    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $testModuleName = strval($this->getTableValue("TESTMODULE_NAME"));
            $testModulePass = strval($this->getTableValue("TESTMODULE_PASSWORD"));
            $testModuleUpdateTime = intval($this->getTableValue("TESTMODULE_UPDATE_TIME"));

            if (
                !$testModuleName ||
                empty($testModuleName) ||
                !Validate::isGenericName($testModuleName)
            ) {
                $output .= $this->displayError($this->l('Invalid Username value'));
            } else {
                $this->updateTableValue('TESTMODULE_NAME', $testModuleName);
                $output .= $this->displayConfirmation($this->l('Username updated'));
            }

            if (
                !$testModulePass ||
                empty($testModulePass) ||
                !Validate::isGenericName($testModulePass)
            ) {
                $output .= $this->displayError($this->l('Invalid password value'));
            } else {
                $this->updateTableValue('TESTMODULE_PASSWORD', $testModulePass);
                $output .= $this->displayConfirmation($this->l('Password updated'));
            }

            if (
                !$testModuleUpdateTime ||
                empty($testModuleUpdateTime) ||
                !is_numeric($testModuleUpdateTime)
            ) {
                $output .= $this->displayError($this->l('Invalid Update time value'));
            } else {
                $this->updateTableValue('TESTMODULE_UPDATE_TIME', $testModuleUpdateTime);
                $output .= $this->displayConfirmation($this->l('Update time updated'));
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
                    'type' => 'text',
                    'label' => $this->l('Time to pass before new updates in minutes'),
                    'name' => 'TESTMODULE_UPDATE_TIME',
                    'size' => 20,
                    'required' => true,
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
        $helper->fields_value['TESTMODULE_NAME'] = $this->getTableValue("TESTMODULE_NAME");
        $helper->fields_value['TESTMODULE_PASSWORD'] = $this->getTableValue("TESTMODULE_PASSWORD");
        $helper->fields_value['TESTMODULE_UPDATE_TIME'] = $this->getTableValue("TESTMODULE_UPDATE_TIME");
        return $helper->generateForm($fieldsForm);
    }

    //HOOKS

    public function hookDisplayLogoAfter()
    {
        $this->context->smarty->assign([
            'my_module_name' => $this->getTableValue("TESTMODULE_NAME"),
            'my_module_password' => $this->getTableValue("TESTMODULE_PASSWORD"),
            'my_module_update_time' => $this->getTableValue("TESTMODULE_UPDATE_TIME"),
            'my_module_message' => $this->l('ORDER CONFIRMED!!!!!!!!!!!!!!!!!!!!!'),

        ]);

        return $this->display(__FILE__, 'testModule.tpl');
    }

    public function hookDisplayOrderConfirmation($params)
    {
        $this->context->smarty->assign([
            'my_module_name' => $this->getTableValue("TESTMODULE_NAME"),
            'my_module_password' => $this->getTableValue("TESTMODULE_PASSWORD"),
            'my_module_update_time' => $this->getTableValue("TESTMODULE_UPDATE_TIME"),
            'my_module_message' => $this->l('ORDER CONFIRMED!!!!!!!!!!!!!!!!!!!!!'),

        ]);

        return $this->display(__FILE__, 'testModule.tpl');
    }


    public function hookActionOrderStatusPostUpdate($params)
    {
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
