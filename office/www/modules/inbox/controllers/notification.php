<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Notification extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->helper('text');
        $this->load->library(array('session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function index()
    {
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social"); 
        $output['success'] = TRUE;
        $output['contentpage'] = "";
        $idusersession = (string)$this->session->userdata('user_id');
        $listtypenotif = array("UserLoveMixNotification","UserCommentMixNotification","UserCollectNotification");
        $filterunsee = array(
            'type'=>array('$in'=> $listtypenotif),
            'read'=>FALSE,
            "user_id" => $idusersession,
            "friend_id" => array('$ne'=>$idusersession)
        );
        $filterallnotif = array(
            'type'=>array('$in'=> $listtypenotif),
            "user_id" => (string)$this->session->userdata('user_id'),
            "friend_id" => array('$ne'=>$idusersession)
        );
        $output['count'] = (int)$this->mongo_db->count2($filterunsee);
        $datapage = $this->mongo_db->find($filterallnotif,0,20,array('datetime'=>-1));           
        if($datapage)
        {
            $notificationtemplate = array("notification-warning","notification-order","notification-user","notification-failure","notification-danger","notification-success");
            $indextemplate = 0;
            foreach($datapage as $dt)
            {
                $statusmsg = (($dt["read"]==TRUE)?"":"active");
                $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                $namapengirim = "No Name";
                if($tempdtuser)
                {
                    $namapengirim = $tempdtuser['fullname'];
                }
                $styletip = "icon-heart";
                $action = "";
                if($dt["type"] === "UserLoveMixNotification")
                {
                    $styletip = "icon-heart";
                    $action = "Love Mix";
                }
                else if($dt["type"] === "UserCommentMixNotification")
                {
                    $styletip = "icon-edit-sign";
                    $action = "Comment Mix";
                }
                else if($dt["type"] === "UserCollectNotification")
                {
                    $styletip = "icon-beaker";
                    $action = "Collect your Mix";
                }
                if($indextemplate>5)
                {
                    $indextemplate = 0;
                }
                $output['contentpage'] .= "<li>";
                $output['contentpage'] .= "<a href='".$this->template_admin->link("member/timeline/index")."' class='".$notificationtemplate[$indextemplate]." ".$statusmsg."'>";
                $output['contentpage'] .= "<span class='time'>".date('h:i:s a,', $dt['datetime']->sec)."<br />".date('d F Y', $dt['datetime']->sec)."</span>";
                $output['contentpage'] .= "<i class='".$styletip."'></i><span class='msg'>".  word_limiter($namapengirim,2)." ".$action."</span>";
                $output['contentpage'] .= "</a>";
                $output['contentpage'] .= "</li>";
                $indextemplate++;
            }
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
    }
    function setsee()
    {
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $output['success'] = TRUE;
        $output['message'] = "Fail set message to be read.";
        $this->mongo_db->update(array(
            'type'=>array('$in'=>array("UserLoveMixNotification","UserCommentMixNotification","UserCollectNotification")),
            "user_id" => (string)$this->session->userdata('user_id'),
        ),array('$set'=>array('read'=>TRUE)));
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
    }
    function message()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
        $output['success'] = TRUE;
        $output['contentpage'] = "";
        $output['count'] = (int)$this->mongo_db->count2(array('type'=>array('$in'=>array("inbox","bcmesage")),'read'=>FALSE,"friend_id" => (string)$this->session->userdata('user_id')));
        $datapage = $this->mongo_db->find(array('type'=>array('$in'=>array("inbox","bcmesage")),"friend_id" => (string)$this->session->userdata('user_id')),0,10,array('datetime'=>-1));           
        if($datapage)
        {
            foreach($datapage as $dt)
            {
                $statusmsg = (($dt["read"]==TRUE)?"":"active");
                $namapengirim = "Admin ".$this->config->item('aplicationname');
                $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                $picture = base_url()."resources/image/index.jpg";
                if($tempdtuser['picture']=="")
                {
                    if($tempdtuser['fb_id']!="")
                    {
                        $picture = "https://graph.facebook.com/".$tempdtuser['fb_id']."/picture?type=large";
                    }
                }
                else
                {
                    $picture = $tempdtuser['picture'];
                } 
                if($dt["type"] === "inbox")
                {
                    $namapengirim = $tempdtuser['fullname'];
                }
                $output['contentpage'] .= "<li>";
                $output['contentpage'] .= "<a href='".$this->template_admin->link("inbox/message/index/".$dt["friend_id"])."' class='".$statusmsg."'>";
                $output['contentpage'] .= "<span class='time'>".date('h:i:s a,', $dt['datetime']->sec)."<br />".date('d F Y', $dt['datetime']->sec)."</span>";
                $output['contentpage'] .= "<img src='".$picture."' alt='avatar' />";
                $output['contentpage'] .= "<div>";
                $output['contentpage'] .= "<span class='name'>".$namapengirim."</span>";
                $output['contentpage'] .= "<span class='msg'>".word_limiter($dt['subject'],6)."</span>";
                $output['contentpage'] .= "</div>";
                $output['contentpage'] .= "</a>";
                $output['contentpage'] .= "</li>";
            }
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
    }
    function setread()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
        $output['success'] = TRUE;
        $output['message'] = "Fail set message to be read.";
        $this->mongo_db->update(array(
            'type'=>array('$in'=>array("inbox","bcmesage")),"friend_id" => (string)$this->session->userdata('user_id')
        ),array('$set'=>array('read'=>TRUE)));
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
    }
}
