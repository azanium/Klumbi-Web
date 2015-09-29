<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Welcome extends CI_Controller 
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
            base_url()."resources/plugin/bslide/styles.css",
        );
        $js=array(
            base_url()."resources/plugin/bslide/shBrushXml.js",
            base_url()."resources/plugin/bslide/jquery.bxslider2.min.js",
        );
        $this->template_admin->header_web(TRUE,"Image Slider ".$this->config->item('aplicationname'),$css,$js,TRUE,"class='horizontal-nav'");
        $this->template_admin->headerbar();        
        $this->template_admin->top_menu(FALSE);
        $this->template_admin->big_top_menu(FALSE,"Slider");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("welcomepage_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
}
