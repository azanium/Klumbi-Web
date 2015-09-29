<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sendemail extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Broadcash Email","module2",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $sSearch=(string) trim("@");
        $sSearch = quotemeta($sSearch);
        $regex = "/$sSearch/i";
        $filter=$this->mongo_db->regex($regex);
        $pencarian=array(
            'email'=>$filter,
        );
        $datapage['datalist'] = $this->mongo_db->find($pencarian,0,0,array('email'=>1)); 
        $datapage['tagcontent'] = $this->tambahan_fungsi->global_get_random(15);
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
        $this->template_admin->header_web(TRUE,"Broadcast Email",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("sendemail_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function send()
    {
        $this->form_validation->set_rules('txtsubject','Subject','trim|xss_clean');
        $this->form_validation->set_rules('txtmessage','Message','trim|xss_clean');
        $url=current_url();
        $user = $this->session->userdata('username');
        $data['success']= FALSE;
        $data['message']= "";
        if($this->form_validation->run()==FALSE)
        {
            $temperror = validation_errors('<i class="error">','</i>');
            $data['message']= $temperror;
            $this->m_user->error_log("Send Broadcash Email","Fail Send Broadcash Email",$temperror,$url,"Send Broadcash Email");
        }
        else
        {
            $email = $this->input->post('txtsendto',TRUE);
            $bccto = $this->input->post('bccto',TRUE);
            $subject = $this->input->post('txtsubject',TRUE);
            $message = $this->input->post('txtmessage',TRUE);
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
            $this->email->to($email);
            $this->email->cc($bccto); 
            $this->email->subject($subject);
            $this->email->message($message);
            if(!$this->email->send())
            {
                $error_message = $this->email->print_debugger();
                $data['message'] =  $error_message;
                $this->m_user->error_log(1,$error_message,"Fail to send Broadcash Email",$url,$email);
            }
            else
            {
                $data['message'] = "Thank you :D.";
                $data['success']= TRUE;
            }
            $this->m_user->tulis_log("Send broadcash Email",$url,$user);
        }
        if(IS_AJAX)
        {
            echo json_encode($data);
        }
        else
        {
            redirect('inbox/sendemail/index'); 
        }
    }
}




