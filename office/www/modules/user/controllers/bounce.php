<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bounce extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Length of Stay","module13",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("PlayerVisit");
        $dataaddd['listdt']=$this->mongo_db->command_values(array('distinct' => "PlayerVisit", 'key' => "date"));
        $dataaddd['listroom']=$this->mongo_db->command_values(array('distinct' => "PlayerVisit", 'key' => "room"));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
        );
        $js=array();
        $this->template_admin->header_web(TRUE,"Statistik Rooms Visits",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("bounce_view",$dataaddd);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
}