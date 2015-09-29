<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
    }
    function index()
    {
	$this->cek_session->cek_login();
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/form-markdown/css/bootstrap-markdown.min.css"
        );
        $js=array(
            base_url()."resources/plugin/charts-flot/jquery.flot.min.js",
            base_url()."resources/plugin/charts-flot/jquery.flot.resize.min.js",
            base_url()."resources/plugin/charts-flot/jquery.flot.orderBars.min.js",
            base_url()."resources/plugin/easypiechart/jquery.easypiechart.min.js",
            base_url()."resources/plugin/charts-chartjs/Chart.min.js"
        );
        $this->template_admin->header_web(TRUE,"Welcome to ".$this->config->item('aplicationname'),$css,$js,TRUE,"");
        $this->template_admin->headerbar();
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");  
        $this->load->view("adminpage_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function login()
    {
        $this->cek_session->was_login();
        $css=array();
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
        );
        $this->template_admin->header_web(FALSE,"Login Form",$css,$js,TRUE," class='focusedform' ");
        $this->load->view("loginpage");
        $this->template_admin->footer();
    }
    function forgotpass()
    {
        $this->cek_session->was_login();
        $css=array();
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
        );
        $this->template_admin->header_web(FALSE,"Forgot Password",$css,$js,TRUE," class='focusedform' ");
        $this->load->view("forgotpass");
        $this->template_admin->footer();
    }
    function sendemailpass()
    {
        $this->cek_session->was_login();
        $datapesan=array();
        $datapesan['success']=FALSE;
        $datapesan['message']="Your email is not registered";
        $this->form_validation->set_rules('txtemail','Email','trim|required|valid_email|htmlspecialchars|xss_clean');
        if($this->form_validation->run()==FALSE)
        {
            $datapesan['message'] = validation_errors('<p>', '</p>');
        }
        else
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $name = $this->input->post('txtemail',TRUE);           
            $cek_autenticate = $this->mongo_db->findOne(array('email' => $name));
            if($cek_autenticate)
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
                $this->email->to($name);
                $subjek = "Request New Password";
                $newkeyactivation = $this->tambahan_fungsi->global_get_random(50);
                $datatinsert = array(
                    'activation_key' => $newkeyactivation,
                );
                $newlink = $this->template_admin->link("home/changepass/".$cek_autenticate['_id']."/".$newkeyactivation);
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($cek_autenticate['_id'])),array('$set'=>$datatinsert));
                $message = "";
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Settings");
                $temp_detail = $this->mongo_db->findOne(array('code' => "forpassemtemp"));
                if($temp_detail)
                {
                    $message = isset($temp_detail['value'])?$temp_detail['value']:'';
                    $message = str_replace("{greeter}", $this->tambahan_fungsi->ucapkan_salam(), $message);
                    $message = str_replace("{name}", $cek_autenticate['username'], $message);
                    $message = str_replace("{linkpassword}", $newlink, $message);
                    $message = str_replace("{newrequest}", $this->template_admin->link("home/forgotpass"), $message);
                    $message = str_replace("{emailadmin}", $this->config->item('adminemail'), $message);
                    $message = str_replace("{linksite}", base_url(), $message);
                }
                $this->email->subject($subjek);
                $this->email->message($message);			
                if(!$this->email->send())
                {
                    $error_message = $this->email->print_debugger();
                    $output['message'] =  $error_message;
                    $url = current_url();
                    $this->m_user->error_log(1,$error_message,"Fail to send Email for Request new Password",$url,$name);
                }
                else
                {
                    $datapesan['success'] = TRUE;
                    $datapesan['message'] = "We have send your new password by email, please check your email.";
                }
            }
            $url = current_url();
            $this->m_user->tulis_log("Try to request new password",$url,$name);
        }
        if(IS_AJAX)
        {            
            echo json_encode($datapesan);
        }
        else
        {
            redirect('home/forgotpass'); 
        }
    }
    function changepass($user_id="",$user_activation="")
    {
        $this->cek_session->was_login();
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $cek_autenticate = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($user_id), 'activation_key'=>$user_activation));
        $dataaddpage['user_id'] = "";
        $dataaddpage['user_activation'] = "";
        $dataaddpage['user_email'] = "";
        $dataaddpage['user_name'] = "";
        $dataaddpage['user_valid'] = FALSE;
        if($cek_autenticate)
        {
            $dataaddpage['user_id'] = $cek_autenticate['_id'];
            $dataaddpage['user_activation'] = $cek_autenticate['activation_key'];
            $dataaddpage['user_email'] = $cek_autenticate['email'];
            $dataaddpage['user_name'] = $cek_autenticate['username'];
            $dataaddpage['user_valid'] = TRUE;
        }
        $css=array();
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
        );
        $this->template_admin->header_web(TRUE,"Change Password",$css,$js,TRUE," class='focusedform' ");
        $this->load->view("changepass",$dataaddpage);
        $this->template_admin->footer();
    }
    function loginbychange()
    {
        $this->cek_session->was_login();
        $datapesan=array();
        $datapesan['login']=FALSE;
        $datapesan['message']="Fail to change password, Your activation key is wrong.";
        $this->form_validation->set_rules('txtemail','Email','trim|required|htmlspecialchars|valid_email|xss_clean');
        $this->form_validation->set_rules('txtuserid','User ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtactivationkey','Activation Key','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtpassword','Password','trim|min_length[6]|required|htmlspecialchars|xss_clean');
        if($this->form_validation->run()==FALSE)
        {
            $datapesan['message'] = validation_errors('<p>', '</p>');
        }
        else
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $email = $this->input->post('txtemail',TRUE);
            $userid = $this->input->post('txtuserid',TRUE);
            $activation = $this->input->post('txtactivationkey',TRUE);
            $newpass = $this->input->post('txtpassword',TRUE);   
            $url = current_url();
            $cek_autenticate = $this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid($userid),'email' => $email,'activation_key'=>$activation));
            if($cek_autenticate)
            {
                $datapesan['message']="Congratulation, your password has change.";   
                $newkeyactivation = $this->tambahan_fungsi->global_get_random(50);
                $datatinsert = array(
                    'activation_key' => $newkeyactivation,
                    'password' => md5($newpass),
                );
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($cek_autenticate['_id'])),array('$set'=>$datatinsert));
                $datapesan = $this->cek_session->generate_session($cek_autenticate);
                $datapesan['message'] = "Success login, redirect your page to home";
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
                $subjek = "Success change password";
                $message = "";
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Settings");
                $temp_detail = $this->mongo_db->findOne(array('code' => "changesucessemtemp"));
                if($temp_detail)
                {
                    $message = isset($temp_detail['value'])?$temp_detail['value']:'';
                    $message = str_replace("{greeter}", $this->tambahan_fungsi->ucapkan_salam(), $message);
                    $message = str_replace("{email}", $email, $message);
                    $message = str_replace("{password}", $newpass, $message);
                    $message = str_replace("{linksite}", base_url(), $message);
                }
                $this->email->subject($subjek);
                $this->email->message($message);			
                if(!$this->email->send())
                {
                    $error_message = $this->email->print_debugger();
                    $data['message'] =  $error_message;
                    $this->m_user->error_log(1,$error_message,"Fail send Email for change user password",$url,$email);
                }
            }            
            $this->m_user->tulis_log("Try to Change Password",$url,$email);
        }
        if(IS_AJAX)
        {            
            echo json_encode($datapesan);
        }
        else
        {
            if($datapesan['login'])
            {
                redirect('home'); 
            }
            else
            {
                redirect('home/login'); 
            }
        }
    }
    function cek_login()
    {
        $this->cek_session->was_login();
        $datapesan=array();
        $datapesan['login']=FALSE;
        $datapesan['message']="Fail to login, Your name's or password is wrong";
        $this->form_validation->set_rules('txtemail','Username or Email','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtpassword','Password','trim|required|htmlspecialchars|xss_clean');
        if($this->form_validation->run()==FALSE)
        {
            $datapesan['message'] = validation_errors('<p>', '</p>');
        }
        else
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $name = $this->input->post('txtemail',TRUE);
            $pass = $this->input->post('txtpassword',TRUE);            
            $cek_autenticate = $this->mongo_db->findOne(array('username' => $name,'password'=>md5($pass)));
            if($cek_autenticate)
            {
                $datapesan = $this->cek_session->generate_session($cek_autenticate);
                $datapesan['message'] = "Success login, redirect your page to home";
            }
            else
            {
                $cek_autenticate2 = $this->mongo_db->findOne(array('email' => $name,'password'=>md5($pass)));
                if($cek_autenticate2)
                {
                    $datapesan = $this->cek_session->generate_session($cek_autenticate2); 
                    $datapesan['message'] = "Success login, redirect your page to home";
                }
            }
            $url = current_url();
            $this->m_user->tulis_log("Try Login",$url,$name);
        }
        if(IS_AJAX)
        {            
            echo json_encode($datapesan);
        }
        else
        {
            if($datapesan['login'])
            {
                redirect('home'); 
            }
            else
            {
                redirect('home/login'); 
            }
        }
    }
    function logout()
    {
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Logout",$url,$user);
        $this->session->sess_destroy();
        redirect("home/login");
    }
}
