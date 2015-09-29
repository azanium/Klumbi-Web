<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-success">
                <div class='panel-heading'><h4><?php echo "Play ".$this->config->item('aplicationname'); ?></h4></div>
                <div class='panel-body'>
                    <div align="center" style="position: center;">
                        <?php
                        $property['unity_object']='_Game';
                        $property['unity_error_image']='getunity.wide.png';
                        $property['width']=800;;
                        $property['height']=400;        
                        $property['maps']='';
                        $property['url_unity']=$this->config->item('path_asset_img').'webplayer/Play_secure.unity3d?'.time(); 
                        $this->load->view("unity_object/loadunity",$property);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div align="center" style="position: center;">
                <img src="<?php echo base_url(); ?>resources/image/webcontrols.play.jpg" />
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function OnGameInit() 
{
    GetUnity().SendMessage("<?php echo $property['unity_object']; ?>", "StartGame", "<?php echo $property['maps']; ?>");
}
</script>