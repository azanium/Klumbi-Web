<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->library('email');
    }
    /*
     * Methode : POST
     * API Send Email
     * Parameter :
     * 1. subject (subject of message)
     * 2. message (message of text will be send)
     * 3. email (array user email)
     * Return JSON
     */
    function index()
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $url = current_url();
            $subject = isset($_POST['subject'])?$_POST['subject']:"";
            $listemail = isset($_POST['email'])?$_POST['email']:"";
            $temp_message = isset($_POST['message'])?$_POST['message']:"";
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
            $this->email->to($listemail,$this->config->item('adminemail'));
            $this->email->subject($subject);
            $this->email->message($temp_message);
            if(!$this->email->send())
            {
                $error_message = $this->email->print_debugger();
                $output['message'] =  $error_message;
                $this->m_user->error_log(1,$error_message,"Fail send Email",$url,"API Unity");
            }
            else
            {
                $output['message'] = "Email is send.";
                $output['success'] = TRUE;
            }
            $this->m_user->tulis_log("Send Email",$url,"API Unity");
        }
        echo json_encode($output);
    }
    /*
     * Methode : POST
     * API Send Email
     * Parameter :
     * 1. email (array user email)
     * 2. user_id (id user who send email)
     * Return JSON
     */
    function invite_user()
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $url = current_url();
            $listemail = $_POST['email'];
            $userid = $_POST['user_id'];             
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
            $cekalamatemail = explode(";", $listemail);
            if(count($cekalamatemail)>1)
            {
                $this->email->to($cekalamatemail[0]);
                $emailcc = array();
                for($i=1;$i<count($cekalamatemail);$i++)
                {
                    $emailcc[] = $cekalamatemail[$i];
                }
                $this->email->cc($emailcc); 
            }
            else
            {
                $this->email->to($listemail,$this->config->item('adminemail'));
            }            
            $this->email->subject("You are Invited to join ".$this->config->item('aplicationname'));
            $temp_message = "";
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Settings");
            $temp_detail = $this->mongo_db->findOne(array('code' => "inviteemailuser"));
            $datauser = $this->m_userdata->user_properties($userid);
            if($temp_detail)
            {
                $temp_message = isset($temp_detail['value'])?$temp_detail['value']:'';
                $temp_message = str_replace("{greeter}", $this->tambahan_fungsi->ucapkan_salam(), $temp_message);
                $temp_message = str_replace("{name}", $datauser['fullname'], $temp_message);
                $temp_message = str_replace("{linksite}", base_url(), $temp_message);
            }
            $this->email->message($temp_message);
            if(!$this->email->send())
            {
                $error_message = $this->email->print_debugger();
                $output['message'] =  $error_message;
                $this->m_user->error_log(1,$error_message,"Fail send Email for contact by site",$url,"API Unity");
            }
            else
            {
                $output['message'] = "Email is send.";
                $output['success'] = TRUE;
            }
            $this->m_user->tulis_log("Send Invite Friend",$url,"API Unity");
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Email Verification
     * Parameter :
     * 1. user_id (id user who want to send email verification)
     * Return JSON
     */
    function verification($userid="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $url = current_url();
            $datauser = $this->m_userdata->user_properties($userid);
            $accountuser = $this->m_userdata->user_account_byid($userid);
            if($accountuser["email"]!="")
            {           
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
                $this->email->to($accountuser["email"],$datauser["fullname"]);
                $this->email->subject("Email Verification ".$this->config->item('aplicationname'));
                $temp_message = "";
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Settings");
                $temp_detail = $this->mongo_db->findOne(array('code' => "verificationemailuser"));            
                if($temp_detail)
                {
                    $temp_message = isset($temp_detail['value'])?$temp_detail['value']:'';
                    $temp_message = str_replace("{greeter}", $this->tambahan_fungsi->ucapkan_salam(), $temp_message);
                    $temp_message = str_replace("{name}", $datauser['fullname'], $temp_message);
                    $temp_message = str_replace("{linksite}", base_url(), $temp_message);
                    $key=md5($this->tambahan_fungsi->global_get_random(25));
                    $activkey=md5(date('Y-m-d H:i:s'));
                    $datatinsert = array(
                        'activation_key' =>$activkey, 
                        'token_key' =>$key, 
                    );
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("Account");
                    $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($userid)),array('$set'=>$datatinsert));
                    $linkverify = base_url()."home/validaccount/index/".$accountuser['id']."/".$key."/".$activkey;
                    $temp_message = str_replace("{verifyemail}", $linkverify, $temp_message);
                }
                $this->email->message($temp_message);
                if(!$this->email->send())
                {
                    $error_message = $this->email->print_debugger();
                    $output['message'] =  $error_message;
                    $this->m_user->error_log(1,$error_message,"Fail send Email email verification",$url,"API Unity");
                }
                else
                {
                    $output['message'] = "Email is send.";
                    $output['success'] = TRUE;
                }      
            }
            else
            {
                $output['message'] = "Email is not found.";
            }
            $this->m_user->tulis_log("Send Email Verification",$url,"API Unity");
        }
        echo json_encode($output);
    }
}
