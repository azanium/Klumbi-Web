<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Error_log extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Error Visits","module11",FALSE,TRUE,"home");
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
        $this->template_admin->header_web(TRUE,"Error Actions",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("error_log_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Error");
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
            $keysearchdt="error_number";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="url";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="datetime";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="ip_address";
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
            if(isset($dt['datetime']) && $dt['datetime']!="")
            {
                $tgl=date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $delete="";            
            if($this->m_checking->actions("Error Visits","module11","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Error Log ".$dt['ip_address']."')","",'Delete',"delete.png","","linkdelete");
            }  
            $error_number=(isset($dt['error_number']))?$dt['error_number']:"";
            $error_message=(isset($dt['error_message']))?$dt['error_message']:"";
            $error_desc=(isset($dt['error_desc']))?$dt['error_desc']:"";
            $error_user=(isset($dt['user']))?$dt['user']:"";
            $error_URL=(isset($dt['url']))?$dt['url']:"";
            $output['aaData'][] = array(
                $i,
                $error_number."<br />".$error_message."<br />".$error_desc."<br />".$error_user,
                $error_URL,
                $tgl,
                $dt['ip_address'],
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
            redirect('weblog/error_log/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Error Visits","module11","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Error");
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
            redirect('weblog/error_log/index'); 
        }
    }
    function truncate()
    {
        $this->m_checking->actions("Error Visits","module11","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Error");
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
            redirect('weblog/error_log/index'); 
        }
    }
}


