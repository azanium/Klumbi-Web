<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 class Cek_Session
 {
     private $_ci;
     public function Cek_Session()
     {
         $this->_ci=&get_instance();
     } 
     function islogin()
     {
         return TRUE;
     }
     function get_default_datauser()
     {
         $this->_ci->load->model("m_userdata");
         $output = array();
         $temp_cek = $this->_ci->m_userdata->user_account_byid((string)$this->_ci->session->userdata('user_id'));
         if($temp_cek)
         {
            $output['email'] = $temp_cek['email'];
            $output['_id'] = $temp_cek['id'];
            $output['fb_id'] = $temp_cek['fb_id'];
            $output['twitter_id'] = $temp_cek['twitter_id'];
            $output['username'] = $temp_cek['username'];
            $output['join_date'] = $temp_cek['join_date'];
         }
         return $output;
     }
     function get_detail_datauser($userid="")
     {
         $this->_ci->load->model("m_userdata");
         $output = array();
         $temp_cek = $this->_ci->m_userdata->user_properties((string)$userid);
         if($temp_cek)
         {
            $output['avatarname'] = $temp_cek['avatarname'];
            $output['fullname'] = $temp_cek['fullname'];
            $output['sex'] = $temp_cek['sex'];
            $output['website'] = $temp_cek['website'];
            $output['link'] = $temp_cek['link'];
            $output['birthday'] = $temp_cek['birthday'];
            $output['birthday_dd'] = $temp_cek['birthday_dd'];
            $output['birthday_mm'] = $temp_cek['birthday_mm'];
            $output['birthday_yy'] = $temp_cek['birthday_yy'];
            $output['state_of_mind'] = $temp_cek['state_of_mind'];
            $output['about'] = $temp_cek['about'];
            $output['picture'] = $temp_cek['picture'];
            $output['location'] = $temp_cek['location'];
            $output['handphone'] = $temp_cek['handphone'];
            $output['status'] = (isset($temp_cek['status'])?$temp_cek['status']:"offline");   
            $output['bodytype'] = (isset($temp_cek['bodytype'])?$temp_cek['bodytype']:"medium");            
            $output['hide_email'] = (isset($temp_cek['hide_email'])?$temp_cek['hide_email']:FALSE);
            $output['hide_sex'] = (isset($temp_cek['hide_sex'])?$temp_cek['hide_sex']:FALSE);
            $output['hide_phone'] = (isset($temp_cek['hide_phone'])?$temp_cek['hide_phone']:FALSE);
            $output['show_birthday'] = (isset($temp_cek['show_birthday'])?$temp_cek['show_birthday']:"showall");
            $output['notification_event'] = (isset($temp_cek['notification_event'])?$temp_cek['notification_event']:FALSE);
            $output['notification_friend_request'] = (isset($temp_cek['notification_friend_request'])?$temp_cek['notification_friend_request']:FALSE);
            $output['notification_mention'] = (isset($temp_cek['notification_mention'])?$temp_cek['notification_mention']:FALSE);
            $output['notification_friend_post_data'] = (isset($temp_cek['notification_friend_post_data'])?$temp_cek['notification_friend_post_data']:FALSE);
            $output['notification_friend_love_data'] = (isset($temp_cek['notification_friend_love_data'])?$temp_cek['notification_friend_love_data']:FALSE);
            $output['notification_mix_collected'] = (isset($temp_cek['notification_mix_collected'])?$temp_cek['notification_mix_collected']:FALSE);
            $output['notification_friend_comment'] = (isset($temp_cek['notification_friend_comment'])?$temp_cek['notification_friend_comment']:FALSE);
            $output['notification_new_avataritem'] = (isset($temp_cek['notification_new_avataritem'])?$temp_cek['notification_new_avataritem']:FALSE);
            $output['notification_contest_result'] = (isset($temp_cek['notification_contest_result'])?$temp_cek['notification_contest_result']:FALSE);
            $output['friend_send_message'] = (isset($temp_cek['friend_send_message'])?$temp_cek['friend_send_message']:FALSE);
         }
         return $output;
     }
     function cek_login()
     {
         if(!$this->_ci->session->userdata('login'))
         {
            $datapesan=array();
            if(IS_AJAX)
            {
                $datapesan['login']=FALSE;
                $datapesan['message']="Sorry, You are not allowed to access this page";
                echo json_encode($datapesan);
            }
            else
            {
                redirect('home/login'); 
            }    
            exit;
         }
     }
     function was_login()
     {
         if($this->_ci->session->userdata('login'))
         {
            $datapesan=array();
            if(IS_AJAX)
            {
                $datapesan['login']=TRUE;
                $datapesan['message']="Warning, You are login";
                echo json_encode($datapesan);
            }
            else
            {
                redirect('home'); 
            }    
            exit;
         }
     }
     function generate_session($arraydata=array())
    {
        $returndata=array();
        $this->_ci->load->library('user_agent');
        $generatetime=date("Y-m-d H:i:s");
        $logsession=md5($generatetime);
        $browser="Unidentified User Agent";
        $platform="";
        if ($this->_ci->agent->is_browser())
        {
            $browser = $this->_ci->agent->browser().' '.$this->_ci->agent->version();
        }
        elseif ($this->_ci->agent->is_robot())
        {
            $browser = $this->_ci->agent->robot();
        }
        elseif ($this->_ci->agent->is_mobile())
        {
            $browser = $this->_ci->agent->mobile();
        }
        $platform=$this->_ci->agent->platform();
        $time_start=  strtotime($generatetime);
        $ip=$this->_ci->input->ip_address();
        $this->_ci->session->set_userdata('login',TRUE);
        $this->_ci->session->set_userdata('session_id',$logsession);
        $this->_ci->session->set_userdata('user_id',(string)$arraydata['_id']);
        $this->_ci->session->set_userdata('username',$arraydata['username']);
        $group="";
        if(isset($arraydata['access']))
        {
            $group=$arraydata['access'];
        }
        $this->_ci->session->set_userdata('brand',(isset($arraydata['brand_id'])?$arraydata['brand_id']:""));
        $this->_ci->session->set_userdata('group',$group);
        $this->_ci->session->set_userdata('email',$arraydata['email']);
        $this->_ci->mongo_db->select_db("Users");
        $this->_ci->mongo_db->select_collection("Session");
        $datatinsert=array(
            'session_id'  =>$logsession,
            'time_end'  =>$this->_ci->mongo_db->time($time_start),
            'time_start'  =>$this->_ci->mongo_db->time($time_start),
            'user_id'  =>$arraydata['_id'],
            'username'  =>$arraydata['username'],
            'counter'  =>1,
            'user_agent'=>$browser,
            'plat_form'=>$platform,
            'ip'=>$ip,                
        );
        $this->_ci->mongo_db->insert($datatinsert);
        $returndata['login']=TRUE;
        $returndata['message']="Success Registration, redirect your page to home";
        return $returndata;
    }
    function add_member($email="",$password="",$username="",$fullname="",$gender="",$tgl_lahir="2000-02-02",$fb_id="",$twitter_name="", $website="", $link="", $location="", $picture="",$twitter_id="")
    {
        $this->_ci->mongo_db->select_db("Users");
         $this->_ci->mongo_db->select_collection("Account");
         $key = $this->_ci->tambahan_fungsi->global_get_random(10);
         //$id=$this->_ci->mongo_db->mongoid($key.date('YmdHis'));
         $activkey=date('Y-m-d H:i:s');
         $datatinsert=array(
            'email'  =>$email,
             "valid" => FALSE,
             "artist" => FALSE,
             "point" => 10,
            'password'  =>md5($password),
            'username'=>$username,
            'join_date'=> $this->_ci->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'activation_key' =>md5($activkey), 
            'token_key' =>md5($key), 
            'fb_id'  =>$fb_id,
            "twitter_id" =>$twitter_id,
            'brand_id'=>'',
            'access'=>'',
        );
        $datatempaccountinsert = $this->_ci->mongo_db->insert($datatinsert);        
        $pecahtgllahir =  explode("-", $tgl_lahir);
        $pecahtgllahir2 =  explode("/", $tgl_lahir);
        $datatinsertprope=array(
            'lilo_id'=>(string)$datatempaccountinsert,
            'avatarname'  =>$fullname,
            'fullname' =>$fullname,
            'sex'=>$gender,                        
            'website'=>$website,
            'link'=>$link,
            'bodytype'=>"medium",
            'birthday'  =>$tgl_lahir,
            'birthday_dd'=>isset($pecahtgllahir[2])?$pecahtgllahir[2]:$pecahtgllahir2[0],
            'birthday_mm'=>isset($pecahtgllahir[1])?$pecahtgllahir[1]:$pecahtgllahir2[1],
            'birthday_yy' =>isset($pecahtgllahir2[2])?$pecahtgllahir2[2]:$pecahtgllahir[0], 
            'state_of_mind' => '',
            'about'  =>'',
            'picture'  =>$picture,
            'location'=>$location,                        
            'handphone'  =>''
        );
        $this->_ci->mongo_db->select_collection("Properties");
        $this->_ci->mongo_db->insert($datatinsertprope);
        return array(
            'email' => $email,
            'brand_id'=>'',
            'access'=>'',
            'username' =>$username,
            'activation_key' =>md5($activkey), 
            'token_key' =>md5($key), 
            '_id'=>(string)$datatempaccountinsert
        );
    }
 }