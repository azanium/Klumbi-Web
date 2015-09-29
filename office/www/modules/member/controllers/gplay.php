<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gplay extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function index()
    {
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array(
            base_url()."resources/plugin/charts-chartjs/Chart.min.js"
        );
        $this->template_admin->header_web(TRUE,"Play ".$this->config->item('aplicationname'),$css,$js,TRUE,"");
        $this->template_admin->headerbar();
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("gplay_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
}
