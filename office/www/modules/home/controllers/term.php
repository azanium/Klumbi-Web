<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Term extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }    
    function index($alias="faq")
    {
        $alias = urldecode($alias);
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Article");
        $temp = $this->mongo_db->findOne(array('alias' => $alias,'state_document'=>'publish'));
        $addpage['title']="No Title";
        $addpage['data']="<i class='error'>No data Found</i>";
        $addpage['update']="";
        if($temp)
        {
            $addpage['title']=$temp['title'];
            $addpage['data']=$temp['text'];
            if($temp['document_update']!="")
            {
                $addpage['update']=date('Y-m-d H:i:s', $temp['document_update']->sec);
            }
        }
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array();
        $this->template_admin->header_web(TRUE,$addpage['title'],$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->template_admin->big_top_menu(FALSE,"Term");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/menu_term");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("term",$addpage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
}
