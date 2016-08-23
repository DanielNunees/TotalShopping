<html><head></head><body>
<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
* PrestaShop Webservice Library
* @package PrestaShopWebservice
*/

// Here we define constants /!\ You need to replace this parameters
define('DEBUG', false);											// Debug mode
define('PS_SHOP_PATH', 'http://127.0.1.1/prestashop');		// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'DPPRE8CVJ4D4GAXIDVCBQ3LBI5CZT9GL');	// Auth key (Get it in your Back Office)
require_once('./PSWebServiceLibrary.php');

// Here we make the WebService Call
try
{
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  
  // Here we set the option array for the Webservice : we want customers resources
  //	$assoc = xmlDoc.getElementsByTagName("quantity");
  $opt = array(
    'resource' => 'products',
    'display'  => '[price,name,id]'

  );
  $xml = $webService->get($opt);
  $resources = $xml->children()->children();
}
catch (PrestaShopWebserviceException $e)
{
  // Here we are dealing with errors
  $trace = $e->getTrace();
  if ($trace[0]['args'][0] == 404) echo 'Bad ID';
  else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
  else echo 'Other error: ' , $e->getMessage(),"\n";
}
if (isset($resources))
{ 


    $resources= json_encode($resources);
  	print_r($resources);

}
?>
</body></html>
