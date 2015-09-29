<?php
session_cache_limiter('private');
session_cache_expire(2880);
session_save_path('session');
error_reporting(0);
ob_start();
session_start();
include_once('config/modules.php');
include_once('libraries/baselibs.php');
$module = strtolower(trim(arg(0)));
$role = strtolower(trim(arg(1)));
$function = strtolower(trim(arg(2)));
if (strlen($function) == 0) 
{
	$function = 'default';
}
$module_alias = unserialize(LILO_MODULE_ALIAS);
if (!is_dir(LILO_MODULE_DIR . '/' . $module_alias[$module]) || !isset($module_alias[$module])) 
{
	die("HTTP/1.0 404 Not Found");
	exit;
}
$module_file = LILO_MODULE_DIR . '/' . $module_alias[$module] . '/' . $role . '.php';
if (is_file($module_file)) 
{
	include_once($module_file);
} 
else 
{
	die("HTTP/1.0 404 Not Found");
	exit;
}
$func_to_exec = $module . "_" . $role . "_" . $function;
if (!function_exists($func_to_exec)) 
{
	die("HTTP/1.0 404 Not Found");
	exit;
}
print($func_to_exec());
?>