<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sociallog extends CI_Controller 
{
    private $page=null;
    private $scop=null;
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('form_validation','rapp_fb_rido','rapp_tw_rido'));        
    }
    function index()
    {
        $this->cek_session->was_login();
        $this->__checker_fb_connect();        
    }
    function __checker_fb_connect()
    {
        $user = $this->rapp_fb_rido->getUser();
        $url=current_url();
        if($user)
        {
            try 
            {
                 $isnewmember = FALSE;
                 $user_profile = $this->rapp_fb_rido->api('/me');
                 $this->m_user->tulis_log("Run Login by Facebook",$url,$user_profile['name']);
                 $this->mongo_db->select_db("Users");
                 $this->mongo_db->select_collection("Account");
                 $temp_id = $user_profile['email'];
                 $search_temp = array("email"=>$user_profile['email']);
                 if($temp_id==null)
                 {
                     $search_temp = array("fb_id"=>$user_profile['id']);
                 }
                 $check_dt_user=$this->mongo_db->findOne($search_temp);
                 if(!$check_dt_user)
                 {
                     $isnewmember = TRUE;
                     $link = isset($user_profile['link'])?$user_profile['link']:"";
                     $kota = $user_profile['location'];
                     $location = isset($kota['name'])?$kota['name']:"no location";
                     $picture = 'https://graph.facebook.com/'.$user_profile['id'].'/picture';
                     $temp = $this->cek_session->add_member($user_profile['email'], md5("facebook".date('Y-m-d H:i:s')), str_replace("@", "", $user_profile['email']), $user_profile['name'], $user_profile['gender'], $user_profile['birthday'], $user_profile['id'], $user_profile['website'], $link, $location, $picture);
                     if($user_profile['email']!=null)
                     {
                        $this->load->library('email');                
                        $this->config->load("email_config");                
                        $config['protocol'] = $this->config->item('protocol');
                        $config['mailpath'] = $this->config->item('mailpath');
                        $config['mailtype'] = $this->config->item('mailtype');
                        $config['charset'] = $this->config->item('charset');
                        $config['wordwrap'] = $this->config->item('wordwrap');
                        $config['smtp_port'] = $this->config->item('smtp_port');
                        $config['smtp_pass'] = $this->config->item('smtp_pass');
                        $config['smtp_user'] = $this->config->item('smtp_user');
                        $config['smtp_host'] = $this->config->item('smtp_host');
                        $this->email->initialize($config);
                        $this->email->from($this->config->item('adminemail'), $this->config->item('adminname'));
                        $this->email->to($user_profile['email']);
                        $subjek = "Register new user succsessfully";
                        $message = "";                        
                        $this->mongo_db->select_db("Game");
                        $this->mongo_db->select_collection("Settings");
                        $temp_detail = $this->mongo_db->findOne(array('code' => "regisemtemp"));
                        if($temp_detail)
                        {
                            $message = isset($temp_detail['value'])?$temp_detail['value']:'';
                            $message = str_replace("{greeter}", $this->tambahan_fungsi->ucapkan_salam(), $message);
                            $message = str_replace("{name}", $user_profile['name'], $message);
                            $message = str_replace("{linksite}", base_url(), $message);
                            $linkverify = base_url()."home/validaccount/index/".$temp['_id']."/".$temp['token_key']."/".$temp['activation_key'];
                            $message = str_replace("{verifyemail}", $linkverify, $message);
                            
                        }
                        $this->email->subject($subjek);
                        $this->email->message($message);			
                        if(!$this->email->send())
                        {
                           $error_message = $this->email->print_debugger();
                           $this->m_user->error_log(1,$error_message,"Fail send Email for new user register by Facebook",$url,$user_profile['id']);
                        }
                     }
                 }
                 $this->mongo_db->select_db("Users");
                 $this->mongo_db->select_collection("Account");
                 $data_user = $this->mongo_db->findOne(array("email"=>$user_profile['email']));
                 $this->cek_session->generate_session($data_user);
                 if($isnewmember)
                 {
                    redirect("home/setting");
                 }
                 else
                 {
                    redirect($this->session->userdata('urlsebelumnya'));
                 }
            }
            catch(FacebookApiException $e) 
            {
                error_log($e);
                $user = null; 
                $this->m_user->error_log($e->getType(),$e->getMessage(),json_encode($e->getResult()),$url,"Loggin check from FB apps");
                redirect("home/sociallog/loginbyfbsec/yes");
            }
        }
        else 
        {
            redirect("home/sociallog/loginbyfbsec/yes");
        }
    }
    function loginbyfbsec($inlinesite="yes")
    {
        if($inlinesite==="yes")
        {
            $this->page = "http://www.popbloop.com/home/sociallog/index";
        }
        else
        {
            $this->page = 'http://www.facebook.com/pages/-/'.$this->config->item('appId').'?sk=app_'.$this->config->item('appId');
        }
        redirect($this->rapp_fb_rido->getLoginUrl(
                array(
                    'canvas'    => 1,
                    'fbconnect' => 0,
                    'scope' => 'email, publish_stream, user_hometown, user_location, user_photos, user_website, user_birthday',
                    'redirect_uri' => $this->page,
                    )
                ));
    }
    function loginbytwitter()
    {
        $this->cek_session->was_login();
        $callback = $this->template_admin->link("home/sociallog/confirmdttwitter");
        $oauthRequest = $this->rapp_tw_rido->getRequestToken($callback);
        $this->session->set_userdata("o_tok",$oauthRequest['oauth_token']);
        $this->session->set_userdata("o_tok_secret",$oauthRequest['oauth_token_secret']);
        $registerUrl = $this->rapp_tw_rido->getAuthorizeURL($oauthRequest);
        redirect($registerUrl);
    }
    function confirmdttwitter()
    {
        $this->cek_session->was_login();
        $url=current_url();
        $o_token = $this->session->userdata('o_tok');
        $o_token_secret = $this->session->userdata('o_tok_secret');
        $cek_state = $this->rapp_tw_rido->rdconnection($o_token,$o_token_secret);
        $access_token = $this->rapp_tw_rido->getAccessToken($_REQUEST['oauth_verifier']);
        $data = $this->rapp_tw_rido->get('account/verify_credentials', array());
        $id_twitter = $access_token['user_id'];
        $username = $access_token['screen_name'];
        $this->m_user->tulis_log("Run Login by Twitter",$url,$data->name);
        $this->mongo_db->select_db("Users");
        $isnewmember = FALSE;
        $this->mongo_db->select_collection("Account");
        $search_temp = array("twitter_id"=>$id_twitter);
        $check_dt_user = $this->mongo_db->findOne($search_temp);
        if(!$check_dt_user)
        {
            $this->cek_session->add_member("Twitter not allow give email", md5("twitter".date('Y-m-d H:i:s')), $username, $data->name, "male", date('Y-m-d'), "", $data->url, "https://twitter.com/account/redirect_by_id/".$id_twitter, $data->location, $data->profile_image_url, $id_twitter);
            $isnewmember = TRUE;
        }
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $data_user = $this->mongo_db->findOne(array("twitter_id"=>$id_twitter));
        $this->cek_session->generate_session($data_user);
        if($isnewmember)
        {
            redirect("home/setting");
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya'));
        }
    }
    function linkto_facebook()
    {
        $this->cek_session->cek_login();
        $user = $this->rapp_fb_rido->getUser();
        $url=current_url();
        if($user)
        {
            try 
            {
                 $user_profile = $this->rapp_fb_rido->api('/me');
                 $this->m_user->tulis_log("Run Connect by Facebook",$url,$user_profile['name']);
                 $this->mongo_db->select_db("Users");
                 $this->mongo_db->select_collection("Account");
                 $temp_id = $user_profile['email'];
                 $search_temp = array("fb_id"=>$user_profile['id']);
                 $check_dt_user=$this->mongo_db->findOne($search_temp);
                 if(!$check_dt_user)
                 {
                    $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($this->session->userdata('user_id'))),array('$set'=>array('fb_id'  =>$user_profile['id'])));
                 }
                 else
                 {
                    if($this->session->userdata('user_id')!=(string)$check_dt_user["_id"])
                    {
                        $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($this->session->userdata('user_id'))),array('$set'=>array('fb_id'  =>$user_profile['id'])));
//                        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid((string)$check_dt_user["_id"])));
//                        $this->mongo_db->select_collection("Account");
//                        $this->mongo_db->remove(array('lilo_id' => (string)$check_dt_user["_id"]));

                    }
                 }
                 redirect("home/account");
            }
            catch(FacebookApiException $e) 
            {
                error_log($e);
                $user = null; 
                $this->m_user->error_log($e->getType(),$e->getMessage(),json_encode($e->getResult()),$url,"Connect to FB apps");
                redirect("home/sociallog/linkto_fbsec/yes");
            }
        }
        else 
        {
            redirect("home/sociallog/linkto_fbsec/yes");
        }
    }
    function linkto_fbsec($inlinesite="yes")
    {
        $this->cek_session->cek_login();
        if($inlinesite==="yes")
        {
            $this->page = "http://www.popbloop.com/home/sociallog/linkto_facebook";
        }
        else
        {
            $this->page = 'http://www.facebook.com/pages/-/'.$this->config->item('appId').'?sk=app_'.$this->config->item('appId');
        }
        redirect($this->rapp_fb_rido->getLoginUrl(
                array(
                    'canvas'    => 1,
                    'fbconnect' => 0,
                    'scope' => 'email, publish_stream, user_hometown, user_location, user_photos, user_website, user_birthday',
                    'redirect_uri' => $this->page,
                    )
                ));
    }
    function linkto_twitter()
    {
        $this->cek_session->cek_login();
        $callback = $this->template_admin->link("home/sociallog/confirm_connect_twitter");
        $oauthRequest = $this->rapp_tw_rido->getRequestToken($callback);
        $this->session->set_userdata("o_tok",$oauthRequest['oauth_token']);
        $this->session->set_userdata("o_tok_secret",$oauthRequest['oauth_token_secret']);
        $registerUrl = $this->rapp_tw_rido->getAuthorizeURL($oauthRequest);
        redirect($registerUrl);
    }
    function confirm_connect_twitter()
    {
        $this->cek_session->cek_login();
        $url=current_url();
        $o_token = $this->session->userdata('o_tok');
        $o_token_secret = $this->session->userdata('o_tok_secret');
        $cek_state = $this->rapp_tw_rido->rdconnection($o_token,$o_token_secret);
        $access_token = $this->rapp_tw_rido->getAccessToken($_REQUEST['oauth_verifier']);
        $data = $this->rapp_tw_rido->get('account/verify_credentials', array());
        $id_twitter = $access_token['user_id'];
        $username = $access_token['screen_name'];
        $this->m_user->tulis_log("Connect Account with Twitter",$url,$data->name);
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $search_temp = array("twitter_id"=>$id_twitter);
        $check_dt_user = $this->mongo_db->findOne($search_temp);
        if(!$check_dt_user)
        {
            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($this->session->userdata('user_id'))),array('$set'=>array('twitter_id'  =>$id_twitter)));
            $this->mongo_db->select_collection("Properties");
            $data_user = $this->mongo_db->findOne(array("lilo_id"=>$this->session->userdata('user_id')));
            if($data_user)
            {
                if(isset($data_user['twitter']))
                {
                    if($data_user['twitter']=="")
                    {
                        $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid((string)$data_user['_id'])),array('$set'=>array('twitter'  =>"@".$username)));
                    }
                }
                else
                {
                    $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid((string)$data_user['_id'])),array('$set'=>array('twitter'  =>"@".$username)));
                }
            }
        }
        else
        {
            if($this->session->userdata('user_id')!=(string)$check_dt_user["_id"])
            {
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($this->session->userdata('user_id'))),array('$set'=>array('twitter_id'  =>$id_twitter)));
                $this->mongo_db->select_collection("Properties");
                $data_user = $this->mongo_db->findOne(array("lilo_id"=>$this->session->userdata('user_id')));
                if($data_user)
                {
                    $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid((string)$data_user['_id'])),array('$set'=>array('twitter'  =>"@".$username)));
                }
//                $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid((string)$check_dt_user["_id"])));
//                $this->mongo_db->select_collection("Account");
//                $this->mongo_db->remove(array('lilo_id' => (string)$check_dt_user["_id"]));                
            }
        }
        redirect("home/account");
    }
    function unlinksocial($linkname="")
    {
        $this->cek_session->cek_login();
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account"); 
        $arrayset = array();
        if($linkname==="facebook")
        {
            $arrayset = array('fb_id'  =>"");
        }
        else if($linkname==="twitter")
        {
            $arrayset = array('twitter_id'  =>"");
        }
        $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($this->session->userdata('user_id'))),array('$set'=>$arrayset));
        redirect("home/account");
    }
}
