<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
    }    
    function index()
    {
        $this->cek_session->was_login();
        $css=array(
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
        );
        $this->template_admin->header_web(FALSE,"Register as new user",$css,$js,TRUE," class='focusedform' ");
        $this->load->view("register");
        $this->template_admin->footer();
    }
    function __checker_email($email="")
    {
         $this->mongo_db->select_db("Users");
         $this->mongo_db->select_collection("Account");
         $check_dt_user = $this->mongo_db->findOne(array("email"=>$email));
         if(!$check_dt_user)
         {
             return FALSE;
         }
         return TRUE;
    }
    function __checker_username($username="")
    {
         $this->mongo_db->select_db("Users");
         $this->mongo_db->select_collection("Account");
         $check_dt_user = $this->mongo_db->findOne(array("username"=>$username));
         if(!$check_dt_user)
         {
             return FALSE;
         }
         return TRUE;
    }
    function cekregis()
    {
        $this->form_validation->set_rules('txtemail','Email','trim|valid_email|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtusername','Username','trim|alpha|required|xss_clean');
        $this->form_validation->set_rules('txtname','Fullname','trim|required|xss_clean');
        $this->form_validation->set_rules('gender','Gender','trim|required|xss_clean');
        $this->form_validation->set_rules('txtbirthday','Birthday','trim|required|xss_clean');        
        $this->form_validation->set_rules('txtpassword','Password','trim|required|min_length[6]|xss_clean');
        $this->load->helper('recaptchalib');
        $url=current_url();
        $data['login']= FALSE;
        $data['message']= "";
        if($this->form_validation->run()==FALSE)
        {
            $temperror = validation_errors('<i class="error">','</i>');
            $data['message']= $temperror;
            $this->m_user->error_log("Login Error","Fail Registration data",$temperror,$url,"Register Check Data");
        }
        else
        {
            $emailregis = $this->input->post('txtemail',TRUE);
            $username = $this->input->post('txtusername',TRUE);
            $fullname = $this->input->post('txtname',TRUE);
            $birthday = $this->input->post('txtbirthday',TRUE);
            $gender = $this->input->post('gender',TRUE);
            $passwordregis = $this->input->post('txtpassword',TRUE);
            $cekemail = $this->__checker_email($emailregis);
            if(!$cekemail)
            {
                $resp = recaptcha_check_answer ($this->config->item('google_captcha_id_private'),$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
                if (!$resp->is_valid) 
                {
                    $this->m_user->error_log("The reCAPTCHA wasn't entered correctly. Go back and try it again.",$resp->error,$resp->error,$url,"Register Check Captcha");
                    $data['message']="<p class='error'>The reCAPTCHA wasn't entered correctly. Go back and try it again.</p>";
                } 
                else 
                {
                    $cekusername = $this->__checker_username($username);
                    if(!$cekusername)
                    {
                        $temp = $this->cek_session->add_member($emailregis,$passwordregis, strtolower($username),$fullname,$gender,$birthday);
                        $data = $this->cek_session->generate_session($temp);
                        $this->mongo_db->select_db("Social");
                        $this->mongo_db->select_collection("Social"); 
                        $filtering = array(
                            'type'=>'Register',
                            "user_id"=>$temp['_id'],
                            'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                        );
                        $this->mongo_db->insert($filtering);
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
                        $this->email->to($emailregis);
                        $subjek = "Register new user succsessfully";
                        $message = "";
                        $this->mongo_db->select_db("Game");
                        $this->mongo_db->select_collection("Settings");
                        $temp_detail = $this->mongo_db->findOne(array('code' => "regisemtemp"));
                        if($temp_detail)
                        {
                            $message = isset($temp_detail['value'])?$temp_detail['value']:'';
                            $message = str_replace("{greeter}", $this->tambahan_fungsi->ucapkan_salam(), $message);
                            $message = str_replace("{name}", $fullname, $message);
                            $message = str_replace("{linksite}", base_url(), $message);
                            $linkverify = base_url()."home/validaccount/index/".$temp['_id']."/".$temp['token_key']."/".$temp['activation_key'];
                            $message = str_replace("{verifyemail}", $linkverify, $message);
                        }
                        $this->email->subject($subjek);
                        $this->email->message($message);			
                        if(!$this->email->send())
                        {
                            $error_message = $this->email->print_debugger();
                            $data['message'] =  $error_message;
                            $this->m_user->error_log(1,$error_message,"Fail send Email for new user register by site",$url,$emailregis);
                        }
                        else
                        {
                            $data['message'] = "Thank you for registration.";
                        }
                        $url = current_url();
                        $this->m_user->tulis_log("New user Register from site",$url,$emailregis);
                    }
                    else
                    {
                        $data['message']= "<p class='error'>This username has been used by another user.</p>";
                    }
                }
            }
            else
            {
                $data['message']= "<p class='error'>This email has been used by another user.</p>";
            }
        }
        if(IS_AJAX)
        {
            echo json_encode($data);
        }
        else
        {
            if($data['login']==TRUE)
            {
                redirect('home/setting'); 
            }
            else
            {
                redirect('home/register'); 
            }
        }
    }
}
