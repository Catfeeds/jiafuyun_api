<?php
/**
 * $APP_NAME 统一入口
 */

require_once dirname(__FILE__) . '/init.php';

 //DI()->filter = 'Common_SignFilter';


//装载你的接口
DI()->loader->addDirs(array('Malsapi','Library'));

/** ---------------- 响应接口请求 ---------------- **/
if(isset($_REQUEST['service']))$_REQUEST['service'] = $_REQUEST['service'].'.Go';
//$_REQUEST['regulator_id'] = 123;

$api = new PhalApi();
$rs = $api->response();
$rs->output();

