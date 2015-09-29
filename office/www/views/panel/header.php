<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="copyright" content="uda_rido@yahoo.com" />
<meta name="author" content="Rido Saputra" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="<?php echo $this->config->item('description'); ?>" />
<meta name="keywords" content="<?php echo $this->config->item('keyword'); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="ICON" href="<?php echo base_url()."resources/image/".$this->config->item('iconweb'); ?>" type="image/gif" />
<link rel="SHORTCUT ICON" href="<?php echo base_url()."resources/image/".$this->config->item('iconweb'); ?>" type="image/gif" />
<title><?php echo $title; ?></title>
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'  media='all' />
<?php
foreach($css as $data)
{
    echo "<link rel='stylesheet' type='text/css' media='all' href='".$data."' />";
}
?>
<!--[if lt IE 9]>
    <link rel='stylesheet' href='<?php echo base_url(); ?>resources/css/ridocss.css/ie8.css' type='text/css' media='all'>
<![endif]-->
<link href="<?php echo base_url(); ?>resources/css/ridocss.css" rel="stylesheet" type="text/css" media="all">
<link href="<?php echo base_url(); ?>resources/css/header-klumbi.css" rel="stylesheet" type="text/css" media="all" id="styleswitcher">
<link href="<?php echo base_url(); ?>resources/css/sidebar-klumbi.css" rel="stylesheet" type="text/css" media="all" id="headerswitcher"> 
<link rel='stylesheet' type='text/css' media='all' href='<?php echo base_url(); ?>resources/css/ridocss.css' />
<?php
foreach($js as $data)
{
        echo "<script type='text/javascript' src='".$data."'></script>";
}
?>
<!--[if lt IE 9]>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/charts-flot/excanvas.min.js"></script>
<![endif]-->
<script type='text/javascript' src='<?php echo base_url(); ?>resources/js/ridojs.js'></script>
</head> 
<body <?php echo $addbody; ?>>
<script language="javascript">
$(document).ready(function() { 
    //$("head link#headerswitcher").attr("href", '<?php echo base_url(); ?>resources/css/header-klumbi.css');
    //$("head link#styleswitcher").attr("href", '<?php echo base_url(); ?>resources/css/sidebar-klumbi.css');
});
var url_base="<?php echo $this->template_admin->link("home/index"); ?>";
function base_url()
{
    var urldata="<?php echo $this->template_admin->link("home/index"); ?>";
    return urldata;
}
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-29770706-1']);
_gaq.push(['_setDomainName', 'popbloop.com']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
    <div id="loadingprocess" style="display: none;z-index:99999; position: absolute;left: 50%;top: 30%;border: 1px solid #DDD;padding: 3px 0 3px 0;text-align: center;background-color: white;width: 350px;margin-left: -125px;margin-top: -15px;height: 60px;"><img src="<?php echo base_url(); ?>resources/image/loading_fb.gif" alt="loading" /><i class="process">Wait a minute, Your request being processed</i></div>