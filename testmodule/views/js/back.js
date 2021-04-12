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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

var isFirstLoad = true;
document.addEventListener('DOMContentLoaded', () => {
	if (isFirstLoad) {
		isFirstLoad = false;
		enableAutoUpdate();
	}
});

function enableAutoUpdate(){
let autoupdate = $('input[name="TESTMODULE_AUTO"]');
	for (input of autoupdate) {
		input.disabled = false;
	}
	checkAutoUpdate();
	setOnClick();
	
}

function checkAutoUpdate(){
	let autoupdate = $('input[name="TESTMODULE_AUTO"]');
	for (input of autoupdate) {
		if((input.checked) && (input.value == '1')){
			document.getElementById("TESTMODULE_UPDATE_TIME").disabled = false;
		} else if((input.checked) && (input.value == '0')) {
			document.getElementById("TESTMODULE_UPDATE_TIME").disabled = true;
		}
	}
}

function setOnClick(){
	let autoupdate = $('input[name="TESTMODULE_AUTO"]');
	for (input of autoupdate) {
		console.log("CIAO");
		input.onclick = function() {checkAutoUpdate()};
	}
}

