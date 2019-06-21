<?php

define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('CMD_PATH',$itemPath.'/command/refreshDir.php');
define('APP_PATH',$itemPath.'/app/index.php');
define('RESOURCE_CENTER',dirname($itemPath).'/resource');
define('LOG_PATH',$itemPath.'/storage/');
define('VIEW_PATH',$itemPath.'/view/');
