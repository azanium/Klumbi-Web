<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Content extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }    
    function index($alias="")
    {
        $alias = urldecode($alias);
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentPage");
        $temp = $this->mongo_db->findOne(array('alias' => $alias,'state_document'=>'publish'));
        $addpage['title']="No Title";
        $addpage['data']="<i class='error'>No data Found</i>";
        if($temp)
        {
            $addpage['title']=$temp['title'];
            $addpage['data']=$temp['text'];
        }
        else
        {
            $generatetime=date("Y-m-d H:i:s");
            $time_start=  strtotime($generatetime);
            $datainsertredim = array(
                'title'  =>$alias,
                'text'  =>'',
                'alias'  =>$alias,                
                'document_update'=>$this->mongo_db->time($time_start),
                'state_document'  =>'publish',
                'document_write'=>$this->mongo_db->time($time_start),
            );
            $this->mongo_db->update(array('alias'=> (string)$alias),array('$set'=>$datainsertredim),array('upsert' => TRUE));
        }
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array();
        $this->template_admin->header_web(TRUE,$addpage['title'],$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->top_menu(TRUE);
        $this->template_admin->big_top_menu(FALSE,"");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("content",$addpage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
}
