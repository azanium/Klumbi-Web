<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dataimage extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->load->helper(array('directory','path','file'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Filemanager 1","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/elfinder/css/theme.css",
            base_url()."resources/plugin/elfinder/css/elfinder.min.css",
        );
        $js=array(
            base_url()."resources/plugin/elfinder/js/elfinder.min.js",
        );
        $this->template_admin->header_web(TRUE,"File Manager",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("dataimage_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $config = array(
            'debug' => FALSE,
            'locale' => '',
            'roots' => array(
              array(
                'driver'        => 'LocalFileSystem',
                'path'          => $this->config->item("path_upload"),
                'URL'           => $this->config->item("path_asset_img"),
                'accessControl' => 'access'
              )
            )
        );
        $this->load->library('rd_elfinder',$config);
    } 
}

