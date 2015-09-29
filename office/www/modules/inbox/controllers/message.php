<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->helper('text');
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function index($keyuserid="")
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
//        $tempusermsg = $this->mongo_db->findOne(array('type'=>array('$in'=>array("inbox","bcmesage")),"friend_id" => (string)$this->session->userdata('user_id')));
//        if($keyuserid==="" && isset($tempusermsg))
//        {
//            $keyuserid = (string)$tempusermsg["friend_id"];
//        }        
        $datapage["iduser"] = $keyuserid;
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $filtering = array(
            'type'=>'Follower',
            "friend_id"=>(string)$this->session->userdata('user_id'),
        );
        $datapage["listfollower"] = $this->mongo_db->find($filtering,0,0,array("datetime"=>-1));
        $datapage["countfollower"] = $this->mongo_db->count2($filtering); 
        $filtering = array(
            'type'=>'Follower',
            "user_id"=>(string)$this->session->userdata('user_id'),
        );
        $datapage["listfollowing"] = $this->mongo_db->find($filtering,0,0,array("datetime"=>-1));
        $datapage["countfollowing"] = $this->mongo_db->count2($filtering); 
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css", 
            base_url()."resources/css/chatroom.css", 
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Message Replay",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("message_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function send()
    {        
        $this->form_validation->set_rules('txtidfrien','ID Friend','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtmessage','Message','trim|required|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = TRUE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $idfrien = $this->input->post('txtidfrien',TRUE);
            $subject = $this->input->post('txtsubject',TRUE);
            $message = $this->input->post('txtmessage',TRUE);
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox");
            $datatinsert=array(
                "friend_id" => $idfrien, 
                "sender" => (string)$this->session->userdata('user_id'),
                "user_id" => (string)$this->session->userdata('user_id'),
                'type'=>'inbox',
                'read'=>FALSE,
                'subject'=>$subject,
                'message'=>$message,
                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            );            
            $this->mongo_db->insert($datatinsert);
            $datatinsert=array(
                "friend_id" => $idfrien, 
                "sender" => (string)$this->session->userdata('user_id'),
                "user_id" => (string)$this->session->userdata('user_id'),
                'type'=>'send',
                'read'=>TRUE,
                'subject'=>$subject,
                'message'=>$message,
                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            );            
            $this->mongo_db->insert($datatinsert);
            $this->m_user->tulis_log("Replay Message",$url,$user);
            $output['message'] = "<i class='success'>Message was Send</i>";
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('inbox/message/index'); 
        }
    }
    function listmesage()
    {
        $output['message'] = "";
        $output['success'] = FALSE;
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
        $idfriend = (string)$this->input->post('idfriend',TRUE);
        $idyou = (string)$this->session->userdata('user_id');
        $queryfilter = array(
            'type'=>array('$in'=>array("inbox","bcmesage")),
             '$or' => array(
                array("friend_id" => $idfriend, "user_id" => $idyou),
                array("user_id" => $idfriend, "friend_id" => $idyou),
            )
        );
        $datapage = $this->mongo_db->find($queryfilter,0,0,array('datetime'=>1));          
        if($datapage)
        {
            $output['success'] = TRUE;
            foreach($datapage as $dt)
            {
                $tagdarisaya = "chat-primary";
                if($dt["sender"] === $idyou)
                {
                    $tagdarisaya = "me";
                }   
                $tempdtuser = $this->m_userdata->user_properties($dt["sender"]);
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
                $output['message'] .= "<div class='chat-message ".$tagdarisaya."'>";
                $output['message'] .= "<div class='chat-contact'>";
                $output['message'] .= "<img src='".$picture."' alt='Avatar'>";
                $output['message'] .= "</div>";
                $output['message'] .= "<div class='chat-text'>";
                $output['message'] .= "<h5>".$dt["subject"]."</h5>";
                $output['message'] .= "<p>".$dt["message"]."</p>";
                $output['message'] .= "<p align='right'><small class='".(($tagdarisaya=="me")?"text-primary":"text-danger")."'>".date('h:i:s a, d F Y', $dt['datetime']->sec)."</small></p>";
                $output['message'] .= "</div>";
                $output['message'] .= "</div>";
            }
        }
        else
        {
            $output['message'] = "<i class='error'>No Conversation</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('inbox/message/index'); 
        }
    }
}

