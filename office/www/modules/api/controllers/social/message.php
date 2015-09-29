<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
    } 
    /*
     * Methode : GET
     * API broadcash message to user
     * Parameter :
     * 1. user_id
     * 2. subject
     * 3. message
     * Return JSON
     */
    function broadcase()
    {  
        $userid = isset($_GET['user_id'])?$_GET['user_id']:"";
        $subject = isset($_GET['subject'])?$_GET['subject']:""; 
        $message = isset($_GET['message'])?$_GET['message']:"";
        $output['success'] = FALSE;
        $output['message'] = "Fail to send Message";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $listdatauser = $this->mongo_db->find(array(),0,0,array('username'=>1)); 
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox"); 
            foreach($listdatauser as $dt)
            {
                $datatinsert = array( 
                    "friend_id" => (string)$dt["_id"], 
                    "user_id" => (string)$userid,
                    "sender" => (string)$userid,
                    'type'=>'bcmesage',
                    'read'=>FALSE,
                    'subject'=>$subject,
                    'message'=>$message,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                );
                $this->mongo_db->insert($datatinsert);
            }
            $output['success'] = TRUE;
            $output['message'] = "Message is send";
            $url = current_url();
            $this->m_user->tulis_log("Broadcast Message",$url,"API Unity");
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API broadcash message to user
     * Parameter :
     * 1. user_id
     * 2. friend_id
     * 3. subject
     * 4. message
     * Return JSON
     */
    function replay()
    {  
        $friend_id = isset($_GET['friend_id'])?$_GET['friend_id']:"";
        $userid = isset($_GET['user_id'])?$_GET['user_id']:"";
        $subject = isset($_GET['subject'])?$_GET['subject']:""; 
        $message = isset($_GET['message'])?$_GET['message']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox"); 
            $datatinsert = array( 
                "friend_id" => (string)$friend_id, 
                "user_id" => (string)$userid,
                "sender" => (string)$userid,
                'type'=>'inbox',
                'read'=>FALSE,
                'subject'=>$subject,
                'message'=>$message,
                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            );
            $this->mongo_db->insert($datatinsert);
            $datatinsert=array(
                "friend_id" => (string)$friend_id, 
                "sender" => (string)$userid,
                "user_id" => (string)$userid,
                'type'=>'send',
                'read'=>TRUE,
                'subject'=>$subject,
                'message'=>$message,
                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            );            
            $this->mongo_db->insert($datatinsert);
            $output['success'] = TRUE;
            $url = current_url();
            $this->m_user->tulis_log("Send & Replay Message",$url,"API Unity");
        }
        echo json_encode($output);
    }    
    /*
     * Methode : GET
     * API Delete User message
     * Parameter :
     * 1. _id
     * Return JSON
     */
    function delete($id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "fail to sdelete Message";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox"); 
            $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
            $url = current_url();
            $this->m_user->tulis_log("Delete Message User",$url,"API Unity");
            $output['success'] = TRUE;
            $output['message'] = "Message is deleted";
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Read User message
     * Parameter :
     * 1. id
     * Return JSON
     */
    function readdt($id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox"); 
            $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
            if($tampung)
            {
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>array('read'=>TRUE)));
                $namapengirim = "Admin ".$this->config->item('aplicationname');
                if($tampung["type"] === "inbox")
                {
                    $tempdtuser = $this->m_userdata->user_properties($tampung["user_id"]);
                    $namapengirim = $tempdtuser['fullname'];
                } 
                $output['data'] = array(
                    "user_id" => $tampung["user_id"],
                    "nama" => $namapengirim,
                    "subject" => $tampung['subject'],
                    "message" => $tampung['message'],
                    "datetime" => date('l, d F Y H:i:s', $tampung['datetime']->sec),
                    
                );
                $output['success'] = TRUE;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Set Status User message
     * Parameter :
     * 1. user_id
     * 2. status [read,unread]
     * Return JSON
     */
    function setstatus($user_id="",$status="read")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "fail to set status Message";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox"); 
            $this->mongo_db->update(array(
                'type'=>array('$in'=>array("inbox","bcmesage")),"friend_id" => $user_id
            ),array('$set'=>array('read'=>(($status==="read")?TRUE:FALSE))));
            $output['success'] = TRUE;
            $output['message'] = "Status Message is Set to ".$status;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Set Status User message
     * Parameter :
     * 1. user_id
     * 2. status [read,unread]
     * Return JSON
     */
    function list_message($user_id="",$start=0)
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "fail to load Message";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitmessage"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox");
            $output['count_unread'] = (int)$this->mongo_db->count2(array('type'=>array('$in'=>array("inbox","bcmesage")),'read'=>FALSE,"friend_id" => $user_id));
            $output['count_read'] = (int)$this->mongo_db->count2(array('type'=>array('$in'=>array("inbox","bcmesage")),'read'=>TRUE,"friend_id" => $user_id));
            $datapage = $this->mongo_db->find(array('type'=>array('$in'=>array("inbox","bcmesage")),"friend_id" => $user_id),(int)$start,(int)$limit,array('datetime'=>-1));           
            if($datapage)
            {
                foreach($datapage as $dt)
                {
//                    $statusmsg = (($dt["read"]==TRUE)?"":"active");
//                    $namapengirim = "Admin ".$this->config->item('aplicationname');
//                    $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
//                    $picture = base_url()."resources/image/index.jpg";
//                    if($tempdtuser['picture']=="")
//                    {
//                        if($tempdtuser['fb_id']!="")
//                        {
//                            $picture = "https://graph.facebook.com/".$tempdtuser['fb_id']."/picture?type=large";
//                        }
//                    }
//                    else
//                    {
//                        $picture = $tempdtuser['picture'];
//                    } 
//                    if($dt["type"] === "inbox")
//                    {
//                        $namapengirim = $tempdtuser['fullname'];
//                    }
//                    $output['contentpage'] .= "<li>";
//                    $output['contentpage'] .= "<a href='".$this->template_admin->link("inbox/message/index/".$dt["friend_id"])."' class='".$statusmsg."'>";
//                    $output['contentpage'] .= "<span class='time'>".date('h:i:s a,', $dt['datetime']->sec)."<br />".date('d F Y', $dt['datetime']->sec)."</span>";
//                    $output['contentpage'] .= "<img src='".$picture."' alt='avatar' />";
//                    $output['contentpage'] .= "<div>";
//                    $output['contentpage'] .= "<span class='name'>".$namapengirim."</span>";
//                    $output['contentpage'] .= "<span class='msg'>".word_limiter($dt['subject'],6)."</span>";
//                    $output['contentpage'] .= "</div>";
//                    $output['contentpage'] .= "</a>";
//                    $output['contentpage'] .= "</li>";
                    
                    
                    
//                    if($dt["sender"] === $idyou)
//                    {
//                        $tagdarisaya = "me";
//                    }   
//                    $tempdtuser = $this->m_userdata->user_properties($dt["sender"]);
//                    $picture = base_url()."resources/image/index.jpg";
//                    if($tempdtuser['picture']=="")
//                    {
//                        if($tempdtuser['fb_id']!="")
//                        {
//                            $picture = "https://graph.facebook.com/".$tempdtuser['fb_id']."/picture?type=large";
//                        }
//                    }
//                    else
//                    {
//                        $picture = $tempdtuser['picture'];
//                    }
//                    $output['message'] .= "<div class='chat-message ".$tagdarisaya."'>";
//                    $output['message'] .= "<div class='chat-contact'>";
//                    $output['message'] .= "<img src='".$picture."' alt='Avatar'>";
//                    $output['message'] .= "</div>";
//                    $output['message'] .= "<div class='chat-text'>";
//                    $output['message'] .= "<h5>".$dt["subject"]."</h5>";
//                    $output['message'] .= "<p>".$dt["message"]."</p>";
//                    $output['message'] .= "<p align='right'><small class='".(($tagdarisaya=="me")?"text-primary":"text-danger")."'>".date('h:i:s a, d F Y', $dt['datetime']->sec)."</small></p>";
//                    $output['message'] .= "</div>";
//                    $output['message'] .= "</div>";
                
                
                
                }
            }
            $output['success'] = TRUE;
            $output['message'] = "Message is Loaded";
        }
        echo json_encode($output);
    }
}