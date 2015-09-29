<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Eaccount extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->load->model("m_userdata");
        $this->cek_session->cek_login();
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/form-stepy/jquery.stepy.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-toggle/toggle.min.js",
        );
        $this->template_admin->header_web(TRUE,"User Setting Acount",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("eaccount");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function openmyaccount()
    {
        if(IS_AJAX)
        {
            $this->load->view("editaccount");
        }
        else
        {
            redirect('home/eaccount/index'); 
        }        
    }
    function cek_login()
    {
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
                $datapesan['message'] = "Success login, redirect your page to home";
                $datapesan['login']=TRUE;
                $datapesan['htmlopen']= $this->__taghtmluseraccount((string)$cek_autenticate["_id"]);
            }
            else
            {
                $cek_autenticate2 = $this->mongo_db->findOne(array('email' => $name,'password'=>md5($pass)));
                if($cek_autenticate2)
                {
                    $datapesan['login']=TRUE;
                    $datapesan['message'] = "Success login, redirect your page to home";
                    $datapesan['htmlopen']= $this->__taghtmluseraccount((string)$cek_autenticate2["_id"]);
                }
            }
            $url = current_url();
            $this->m_user->tulis_log("Try Login to change User Account",$url,$name);
        }
        if(IS_AJAX)
        {            
            echo json_encode($datapesan);
        }
        else
        {
            redirect('home/eaccount/index'); 
        }
    }
    function __taghtmluseraccount($iduser="")
    {
        $tempdtuser = $this->m_userdata->user_properties($iduser);
        $tempdtaccountuser = $this->m_userdata->user_account_byid($iduser);
        $propertipage['id'] = $tempdtaccountuser["id"];
        $propertipage['email'] = $tempdtaccountuser["email"];
        $propertipage['username'] = $tempdtaccountuser["username"];
        $propertipage['twitter'] = $tempdtaccountuser["twitter_id"];
        $propertipage['facebook'] = $tempdtaccountuser["fb_id"];
        $datareturn = $this->load->view("editaccount_view",$propertipage,TRUE);
        return $datareturn;
    }
}