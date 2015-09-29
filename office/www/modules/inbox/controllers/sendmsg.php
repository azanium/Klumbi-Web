<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sendmsg extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Broadcash Message","module2",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $pencarian=array();
        $datapage['datalist'] = $this->mongo_db->find($pencarian,0,0,array('username'=>1)); 
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css", 
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-multiselect/js/jquery.multi-select.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Broadcast Message",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("sendmsg_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function send()
    {
        $this->form_validation->set_rules('txtsubject','Subject','trim|required|xss_clean');
        $this->form_validation->set_rules('txtmessage','Message','trim|required|xss_clean');
        $url=current_url();
        $user = $this->session->userdata('username');
        $data['success']= FALSE;
        $data['message']= "";
        if($this->form_validation->run()==FALSE)
        {
            $temperror = validation_errors('<i class="error">','</i>');
            $data['message']= $temperror;
            $this->m_user->error_log("Send Broadcash Message","Fail Send Message",$temperror,$url,"Send Broadcash Message");
        }
        else
        {
            $listdatauser = $this->input->post('txtsendto',TRUE);
            $subject = $this->input->post('txtsubject',TRUE);
            $message = $this->input->post('txtmessage',TRUE);
            if(isset($_POST["chkagrement"]))
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
                        "user_id" => (string)$this->session->userdata('user_id'),
                        "sender" => (string)$this->session->userdata('user_id'),
                        'type'=>'bcmesage',
                        'read'=>FALSE,
                        'subject'=>$subject,
                        'message'=>$message,
                        'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                    );
                    $this->mongo_db->insert($datatinsert);
                }
            }
            else
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Inbox");            
                if(isset($_POST["txtsendto"]))
                {
                    for($i=0;$i<count($_POST["txtsendto"]);$i++)
                    {
                        if($_POST["txtsendto"][$i]!="")
                        {
                            $datatinsert = array(                
                                "friend_id" => $_POST["txtsendto"][$i], 
                                "user_id" => (string)$this->session->userdata('user_id'),
                                "sender" => (string)$this->session->userdata('user_id'),
                                'type'=>'bcmesage',
                                'read'=>FALSE,
                                'subject'=>$subject,
                                'message'=>$message,
                                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                            );
                            $this->mongo_db->insert($datatinsert);
                        }                        
                    }
                }
            }
            $data['message'] = "Message was send";
            $data['success']= TRUE;
            $this->m_user->tulis_log("Send broadcash message",$url,$user);
        }
        if(IS_AJAX)
        {
            echo json_encode($data);
        }
        else
        {
            redirect('inbox/sendmsg/index'); 
        }
    }
}




