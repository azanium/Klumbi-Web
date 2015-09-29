<?php include (APPPATH.'libraries/twitter/twitteroauth.php');
class Rapp_tw_rido extends TwitterOAuth
{
     private $_ci;
     public function Rapp_tw_rido()
     {
         $this->_ci=&get_instance();
         $appkey = $this->_ci->config->item('twitter_key');
         $appsecret = $this->_ci->config->item('twitter_secret');
         parent::__construct($appkey,$appsecret);
     }
     public function rdconnection($o_token="",$o_token_secret="")
     {
         $appkey = $this->_ci->config->item('twitter_key');
         $appsecret = $this->_ci->config->item('twitter_secret');         
         return parent::__construct($appkey, $appsecret, $o_token, $o_token_secret);
     }
 }
 