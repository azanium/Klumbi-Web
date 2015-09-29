<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
    }    
    function index()
    {
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Contact us",$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(FALSE);
        $this->template_admin->big_top_menu(FALSE,"Contact");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("contact");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function send()
    {
        $this->form_validation->set_rules('txtemail','Email','trim|valid_email|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtname','Username','trim|required|xss_clean');
        $this->form_validation->set_rules('txtsubject','Subject','trim|required|xss_clean');
        $this->form_validation->set_rules('txtmessage','Message','trim|required|xss_clean');
        $this->load->helper('recaptchalib');
        $url=current_url();
        $data['success']= FALSE;
        $data['message']= "";
        if($this->form_validation->run()==FALSE)
        {
            $temperror = validation_errors('<i class="error">','</i>');
            $data['message']= $temperror;
            $this->m_user->error_log("Send Contact Error","Fail Send contact",$temperror,$url,"Send Contact Check Data");
        }
        else
        {
            $email = $this->input->post('txtemail',TRUE);
            $username = $this->input->post('txtname',TRUE);
            $subject = $this->input->post('txtsubject',TRUE);
            $message = $this->input->post('txtmessage',TRUE);
            $resp = recaptcha_check_answer ($this->config->item('google_captcha_id_private'),$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
            if (!$resp->is_valid) 
            {
                $this->m_user->error_log("The reCAPTCHA wasn't entered correctly. Try it again.",$resp->error,$resp->error,$url,"Send contact Check Captcha");
                $data['message']="<p class='error'>The reCAPTCHA wasn't entered correctly. Go back and try it again.</p>";
            } 
            else 
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
                $this->email->to($email,$this->config->item('adminemail'));
                $this->email->subject($subject);
                $temp_message = "";
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Settings");
                $temp_detail = $this->mongo_db->findOne(array('code' => "kontactemtemp"));
                if($temp_detail)
                {
                    $temp_message = isset($temp_detail['value'])?$temp_detail['value']:'';
                    $temp_message = str_replace("{contentdata}", $message, $temp_message);
                }
                $this->email->message($temp_message);
                if(!$this->email->send())
                {
                    $error_message = $this->email->print_debugger();
                    $data['message'] =  $error_message;
                    $this->m_user->error_log(1,$error_message,"Fail send Email for contact by site",$url,$email);
                }
                else
                {
                    $data['message'] = "Thank you :D.";
                    $data['success']= TRUE;
                }
                $this->m_user->tulis_log("Send contact to admin email",$url,$email);
            }
        }
        if(IS_AJAX)
        {
            echo json_encode($data);
        }
        else
        {
            redirect('home/contact'); 
        }
    }
}
