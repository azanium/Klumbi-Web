<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type='text/javascript' src='<?php echo base_url(); ?>resources/js/UnityObject.js'></script>
<div id="unityPlayer"><a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!"><img alt="Unity Web Player. Install now!" src="<?php echo base_url(); ?>resources/image/<?php echo $unity_error_image; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></a></div>
<script type="text/javascript">
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
if (typeof unityObject != "undefined") 
{
    var params = {
            disableContextMenu: true,
            backgroundcolor: "2c2c2c",
            bordercolor: "1F1F1F",
            textcolor: "000000",
            logoimage: "<?php echo base_url(); ?>resources/image/lilologo.small.png",
            progressbarimage: "<?php echo base_url(); ?>resources/image/progressbar.small.png",
            progressframeimage: "<?php echo base_url(); ?>resources/image/progressframe.small.png"
	};
   unityObject.embedUnity("unityPlayer", "<?php echo $url_unity; ?>", <?php echo $width; ?>, <?php echo $height; ?>, params);
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