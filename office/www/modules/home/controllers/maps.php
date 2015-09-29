<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Maps extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }    
    function index()
    {
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array(
            "http://maps.google.com/maps/api/js?sensor=true",    
            base_url()."resources/plugin/gmaps/gmaps.js",
        );
        $this->template_admin->header_web(TRUE,"Google Maps Location",$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(FALSE);
        $this->template_admin->big_top_menu(FALSE,"Maps");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("maps");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
}
