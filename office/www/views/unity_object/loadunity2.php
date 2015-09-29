<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css" >
div.broken, div.missing {margin: auto; position: relative; top: 50%; width: 193px;}
div.broken a, div.missing a {height: 63px; position: relative; top: -31px;}
div.broken img, div.missing img {border-width: 0px;}
div.broken {display: none;}
div#unityPlayer { cursor: default; height: <?php echo $height; ?>px; width: <?php echo $width; ?>px;}
</style>
<div id="unityPlayer">
    <div class="missing"><a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!"><img alt="Unity Web Player. Install now!" src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63" /></a></div>
    <div class="broken"><a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now! Restart your browser after install."><img alt="Unity Web Player. Install now! Restart your browser after install." src="http://webplayer.unity3d.com/installation/getunityrestart.png" width="193" height="63" /></a></div>
</div>
<p>&laquo; created with <a href="http://unity3d.com/unity/" title="Go to unity3d.com">Unity</a> &raquo;</p>
<script type="text/javascript">
var unityObjectUrl = "http://webplayer.unity3d.com/download_webplayer-3.x/3.0/uo/UnityObject2.js";
if (document.location.protocol == 'https:')
{
    unityObjectUrl = unityObjectUrl.replace("http://", "https://ssl-");
}        
document.write('<script type="text\/javascript" src="' + unityObjectUrl + '"><\/script>');
var config = {
    width: <?php echo $width; ?>, 
    height: <?php echo $height; ?>,
    params: { enableDebugging:"0" }

};
var u = new UnityObject2(config);
$(document).ready(function() {
    var $missingScreen = $("#unityPlayer").find(".missing");
    var $brokenScreen = $("#unityPlayer").find(".broken");
    $missingScreen.hide();
    $brokenScreen.hide();
    u.observeProgress(function (progress){
        switch(progress.pluginStatus) 
        {
            case "broken":
                        $brokenScreen.find("a").click(function (e) {
                            e.stopPropagation();
                            e.preventDefault();
                            u.installPlugin();
                            return false;
                        });
                        $brokenScreen.show();
                        break;
            case "missing":
                        $missingScreen.find("a").click(function (e) {
                            e.stopPropagation();
                            e.preventDefault();
                            u.installPlugin();
                            return false;
                        });
                        $missingScreen.show();
                        break;
            case "installed":
                        $missingScreen.remove();
                        break;
            case "first":
                        break;
        }
    });
    u.initPlugin($("#unityPlayer")[0], "<?php echo $url_unity; ?>");
}); 
function set_material(material)
{
    return material + '.unity3d';
}
function get_session()
{
    return "<?php echo $this->session->userdata('user_id'); ?>";
}
function get_session_id()
{
    try
    {
        GetUnity().SendMessage("<?php echo $unity_object; ?>", "GetUserId", "<?php echo $this->session->userdata('user_id'); ?>");
    }
    catch(error){}
}
function GetUnity() 
{
    if (typeof unityObject != "undefined") 
    {
        return unityObject.getObjectById("unityPlayer");
    }
    return null;
}
function OnLiloLoaded() 
{
    try
    {
        GetUnity().SendMessage("<?php echo $unity_object; ?>", "ChangePlayerId", "<?php echo $this->session->userdata('user_id'); ?>");
    }
    catch(error){}
}
</script>