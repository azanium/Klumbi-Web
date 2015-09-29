<?php include (APPPATH.'libraries/facebook/facebook.php');
class Rapp_fb_rido extends Facebook
{
     private $_ci;
     public function Rapp_fb_rido()
     {
         $this->_ci=&get_instance();
         $config               = array(
             'appId'=>$this->_ci->config->item('appId'),
             'secret'=>$this->_ci->config->item('secret'),
             'fileUpload'=>TRUE,
             'cookie'=>TRUE,
         );
         parent::__construct($config);
     }
 }
 