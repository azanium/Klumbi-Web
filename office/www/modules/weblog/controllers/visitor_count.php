<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Visitor_count extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Log Visits","module11",FALSE,TRUE,"home");
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Visits",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("visitor_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Visitor");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"desc";
        $iSortCol_0=(isset($_GET['iSortCol_0']))?(int)$_GET['iSortCol_0']:0;
        $jns_sorting=-1;
        if($sSortDir_0=="asc")
        {
            $jns_sorting=1;
        }
        $keysearchdt="datetime";
        if($iSortCol_0==1)
        {
            $keysearchdt="REMOTE_ADDR";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="HTTP_USER_AGENT";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="date";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="_SERVER";
        }
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter=$this->mongo_db->regex($regex);
            $pencarian=array(
                $keysearchdt=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array($keysearchdt=>$jns_sorting));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $tgl="";
            $time="";
            if(isset($dt['date']) && $dt['date']!="")
            {
                $tgl=$dt['date'];
            }
            if(isset($dt['time']) && $dt['time']!="")
            {
                $time=$dt['time'];
            }
            $delete="";            
            if($this->m_checking->actions("Log Visits","module11","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Error Log ".$dt['REMOTE_ADDR']."')","",'Delete',"delete.png","","linkdelete");
            }  
            $data_browser = (isset($dt['browser_string']))?$dt['browser_string']:"";
            $dataHTTP_USER_AGENT = (isset($dt['HTTP_USER_AGENT']))?$dt['HTTP_USER_AGENT']:"";
            $stringserver= $dt['_SERVER']["SCRIPT_NAME"];
            $output['aaData'][] = array(
                $i,
                $dt['REMOTE_ADDR'],
                $data_browser."<br />".$dataHTTP_USER_AGENT,
                $tgl." ".$time,
                '<pre>'.$stringserver.'</pre>',
                $delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('weblog/visitor_count/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Log Visits","module11","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Visitor");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $output = array(
            "message" =>"Data is deleted",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('weblog/visitor_count/index'); 
        }
    }
    function truncate()
    {
        $this->m_checking->actions("Log Visits","module11","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Visitor");
        $this->mongo_db->remove(array());
        $output = array(
            "message" =>"Data is deleted",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('weblog/visitor_count/index'); 
        }
    }
}


