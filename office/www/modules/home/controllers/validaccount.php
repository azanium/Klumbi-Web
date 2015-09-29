<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Validaccount extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }    
    function index($iduser="",$tokenkey="",$activationkey="")
    {
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/fullcalendar/fullcalendar.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/fullcalendar/fullcalendar.min.js",
        );
        $this->template_admin->header_web(FALSE,"Verify Account User",$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(FALSE);
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $datapage["status"] = FALSE;     
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $dataaccount = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($iduser),"token_key"=>$tokenkey,"activation_key"=>$activationkey));
        if($dataaccount)
        {
            $datapage["status"] = TRUE;
            $newupdate = array(
                "token_key"=>md5($this->tambahan_fungsi->global_get_random(25)),
                "activation_key"=>md5(date('Y-m-d H:i:s')),
                "valid" => TRUE
            );
            $this->mongo_db->update(array('_id' => $this->mongo_db->mongoid($iduser)),array('$set'=>$newupdate));
        }
        $this->load->view("validaccount",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
}
