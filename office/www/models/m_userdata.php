<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_userdata extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();     
    }
    function user_account_byid($_iduser)
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($_iduser)));
        $output['success'] = FALSE;
        if($tamp)
        {
            $output['success'] = TRUE;
            $output['id'] = (string)$tamp['_id'];
            $output['email'] = $tamp['email'];
            $output['activation_key'] = $tamp['activation_key'];
            $output['token_key'] = $tamp['token_key'];
            $output['fb_id'] = $tamp['fb_id'];
            $output['twitter_id'] = $tamp['twitter_id'];
            $output['username'] = $tamp['username'];
            $output['join_date'] = date('Y-m-d H:i:s', $tamp['join_date']->sec);
        }
        return $output;
    }
    function user_account($email)
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $tamp = $this->mongo_db->findOne(array('email' => $email));
        $output['success'] = FALSE;
        if($tamp)
        {
            $output['success'] = TRUE;
            $output['id'] = (string)$tamp['_id'];
            $output['email'] = $tamp['email'];
            $output['fb_id'] = $tamp['fb_id'];
            $output['twitter_id'] = $tamp['twitter_id'];
            $output['username'] = $tamp['username'];
        }
        return $output;
    }
    function user_properties($lilo_id="")
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Properties");
        $tamp = $this->mongo_db->findOne(array('lilo_id' => (string)$lilo_id));
        $output['success'] = FALSE;
        if($tamp)
        {
            $output['success'] = TRUE;
            $output['lilo_id'] = $tamp['lilo_id'];
            $output['about'] = $tamp['about'];
            $output['avatarname'] = $tamp['avatarname'];
            $output['birthday'] = $tamp['birthday'];
            $output['birthday_dd'] = $tamp['birthday_dd'];
            $output['birthday_mm'] = $tamp['birthday_mm'];
            $output['birthday_yy'] = $tamp['birthday_yy'];
            $output['fullname'] = $tamp['fullname'];
            $output['handphone'] = $tamp['handphone'];
            $output['link'] = $tamp['link'];
            $output['bodytype'] = (isset($tamp['bodytype'])?$tamp['bodytype']:"medium");
            $output['location'] = $tamp['location'];
            $output['sex'] = $tamp['sex'];
            $output['state_of_mind'] = $tamp['state_of_mind'];
            $output['website'] = $tamp['website'];
            $output['picture'] = $tamp['picture'];            
            $output['hide_email'] = (isset($tamp['hide_email'])?$tamp['hide_email']:FALSE);
            $output['status'] = (isset($tamp['status'])?$tamp['status']:"offline"); 
            $output['hide_sex'] = (isset($tamp['hide_sex'])?$tamp['hide_sex']:FALSE);
            $output['hide_phone'] = (isset($tamp['hide_phone'])?$tamp['hide_phone']:FALSE);
            $output['show_birthday'] = (isset($tamp['show_birthday'])?$tamp['show_birthday']:"showall");
            $output['notification_event'] = (isset($tamp['notification_event'])?$tamp['notification_event']:FALSE);
            $output['notification_friend_request'] = (isset($tamp['notification_friend_request'])?$tamp['notification_friend_request']:FALSE);
            $output['notification_mention'] = (isset($tamp['notification_mention'])?$tamp['notification_mention']:FALSE);
            $output['notification_friend_post_data'] = (isset($tamp['notification_friend_post_data'])?$tamp['notification_friend_post_data']:FALSE);
            $output['notification_friend_love_data'] = (isset($tamp['notification_friend_love_data'])?$tamp['notification_friend_love_data']:FALSE);
            $output['notification_mix_collected'] = (isset($tamp['notification_mix_collected'])?$tamp['notification_mix_collected']:FALSE);
            $output['notification_friend_comment'] = (isset($tamp['notification_friend_comment'])?$tamp['notification_friend_comment']:FALSE);
            $output['notification_new_avataritem'] = (isset($tamp['notification_new_avataritem'])?$tamp['notification_new_avataritem']:FALSE);
            $output['notification_contest_result'] = (isset($tamp['notification_contest_result'])?$tamp['notification_contest_result']:FALSE);
            $output['friend_send_message'] = (isset($tamp['friend_send_message'])?$tamp['friend_send_message']:FALSE);
        }
        else
        {
            $tgl_lahir = date("Y-m-d");
            $pecahtgllahir =  explode("-", $tgl_lahir);
            $output = array( 
                'lilo_id' => (string)$lilo_id,
                'avatarname' => "No Name",
                'fullname' => "No Name",
                'sex' => "male",       
                'bodytype'=>"medium",
                'website' => '',
                'link' => '',
                'birthday' =>'',
                'birthday_dd' => $pecahtgllahir[2],
                'birthday_mm' => $pecahtgllahir[1],
                'birthday_yy' => $pecahtgllahir[0],    
                'state_of_mind' => '',
                'about' => '',
                'picture' => '',
                'location'=> '',                        
                'handphone' => '',
            );
            $this->mongo_db->insert($output);
        }
        return $output;
    }
    function settingvalue($name)
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Settings");
        $tamp = $this->mongo_db->findOne(array('code'  =>$name));
        $output = array();
        if($tamp)
        {
            $output = array(
                'type'=>'number',
                'value'=>'5',
            );
        }
        return $output;
    }
}