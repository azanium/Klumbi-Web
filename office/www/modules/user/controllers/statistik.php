<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Statistik extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Player Room Statistics","module13",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Game");
        $dataaddd['listdt']=$this->mongo_db->command_values(array('distinct' => "PlayerStats", 'key' => "room"));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",            
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Statistik Rooms Player",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("user_statistik",$dataaddd);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("PlayerStats");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $pencarian=array();
        if(isset($_GET['tglshow']))
        {
            if($_GET['tglshow']!="")
            {
                $pencarian=array(
                    'date'=>$_GET['tglshow'],
                );
            }            
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('date'=>-1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        foreach($data as $dt)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $dataproperties=$this->mongo_db->findOne(array('lilo_id' => (string)$dt['userid']));
            $fullname="";
            $twitter="";
            $phone="";
            $sex="";
            if($dataproperties)
            {
                $fullname=isset($dataproperties['fullname'])?$dataproperties['fullname']:"";
                $twitter=isset($dataproperties['twitter'])?$dataproperties['twitter']:"";
                $phone=isset($dataproperties['handphone'])?$dataproperties['handphone']:"";
                $sex=isset($dataproperties['sex'])?$dataproperties['sex']:"";
            }
            $output['aaData'][] = array(
                $fullname,
                $sex,
                $twitter,
                $phone,
                $dt['room'],
                $dt['date'],
                $dt['visit'],
            );         
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/statistik/index'); 
        }
    }
    function chart($room="")
    {
        $this->m_checking->actions("Player Room Statistics","module13","Reporting Graph",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $datatemp=$this->mongo_db->command_values(array('distinct' => "PlayerStats", 'key' => "date"));
        $this->mongo_db->select_collection("PlayerStats");
        $dataadd['isidata']=array();
        if($datatemp)
        {
            $temp=array();
            foreach($datatemp['values'] as $dt)
            {
                $poll_jml = $this->mongo_db->count(array('date' => $dt,'room'=>$room));
                $datavisitalltemp=$this->mongo_db->find(array('date' => $dt,'room'=>$room),0,0,array());
                $pengunjungall=0;
                if($datavisitalltemp)
                {
                    foreach($datavisitalltemp as $result)
                    {
                        $pengunjungall +=$result['visit'];
                    }
                }
                $temp[]=array(
                    'tgl'=>$dt,
                    'jml'=>$poll_jml,
                    'totalpengunjung'=>$pengunjungall,
                );
            }
            $dataadd['isidata']=$temp;
            $this->load->view("chart_room",$dataadd);
        }
    }
}
