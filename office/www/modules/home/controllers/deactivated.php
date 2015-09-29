<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deactivated extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
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
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/form-stepy/jquery.stepy.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Deactivated Acount",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("deactivated");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function process()
    {        
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $account_detail = array();
        $account_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid((string)$this->session->userdata('user_id'))));
        if($account_detail)
        {
            $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid((string)$this->session->userdata('user_id'))));
            $this->mongo_db->select_collection("Properties");
            $properties_detail = array();
            $properties_detail = $this->mongo_db->findOne(array('lilo_id' => (string)$this->session->userdata('user_id')));
            $this->mongo_db->remove(array('lilo_id' => (string)$this->session->userdata('user_id')));
            $this->mongo_db->select_collection("DeletedUsers");
            $datatinsert=array(                
                'account'  =>$account_detail,
                'properties'  =>$properties_detail,
            );
            $this->mongo_db->insert($datatinsert);
            $url = current_url();
            $user = $this->session->userdata('username');
            $this->m_user->tulis_log("Deactivated User",$url,$user);
            $this->session->sess_destroy();
            redirect("home/welcome/index");
        }
        redirect("home/deactivated/index");
    }
}