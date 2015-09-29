<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event extends CI_Controller 
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
            base_url()."resources/plugin/fullcalendar/fullcalendar.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/fullcalendar/fullcalendar.min.js",
        );
        $this->template_admin->header_web(TRUE,"Our Events",$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(FALSE);
        $this->template_admin->big_top_menu(FALSE,"Event");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("event");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
    function list_data()
    {
        $start = (double)$_GET['start'];
        $end = (double)$_GET['end'];
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Event");
        $output =  array();
        $filtering = array("start"=>array('$gte'=> $this->mongo_db->time($start), '$lt'=>  $this->mongo_db->time($end)) );
        $temp = $this->mongo_db->find($filtering);
        if($temp)
        {
            $i=1;
            foreach($temp as $dt_temp)
            {
                $output[] = array(
                    "allDay" => ((bool)$dt_temp['allDay'])?TRUE:FALSE,
                    "title" => $dt_temp['title'],
                    "description" => $dt_temp['description'],
                    "id" => $i, 
                    "code" => (string)$dt_temp['_id'],      
                    "start" => date('Y-m-d H:i:s', $dt_temp['start']->sec),
                    "start_date" => date('Y-m-d', $dt_temp['start']->sec),
                    "start_time" => date('H:i:s', $dt_temp['start']->sec),
                    "end" => date('Y-m-d H:i:s', $dt_temp['end']->sec),
                    "end_date" => date('Y-m-d', $dt_temp['end']->sec),
                    "end_time" => date('H:i:s', $dt_temp['end']->sec),
                    "color" => $dt_temp['color'],
                    "url" => $dt_temp['url'],
                    "picture" => $dt_temp['picture'],
                );
                $i++;
            }
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect("home/event");
        }
    }
}
