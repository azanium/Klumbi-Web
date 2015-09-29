<?php
define("PATH_ASSET_UPLOAD",$_SERVER['DOCUMENT_ROOT']."/bundles/");
define("URL_ASSET_IMAGE","http://".$_SERVER['HTTP_HOST']."/bundles/");
$base_url= "http://".$_SERVER['HTTP_HOST'];
$base_url .= preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/';
define("BASE_URL",$base_url);
?>
