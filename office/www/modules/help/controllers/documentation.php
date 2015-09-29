<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Documentation extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Documentation","module16",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"API Doc",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("documentation_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Documentation");
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
        $keysearchdt="name";
        if($iSortCol_0==1)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="descriptions";
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
        $pencarian = array_merge($pencarian,array('type'  =>"detail"));
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
            $detail="";
            if($this->m_checking->actions("Documentation","module16","Preview",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("detaildata('".$dt['_id']."')","#editdata",'Preview',"zoom_in.png","","","data-toggle='modal'");
            }
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $dt['descriptions'],
                $detail,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('help/documentation/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Documentation","module16","Preview",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Documentation");
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        $output['message'] = "Data not found";
        $output['success'] = FALSE;
        if($tampung)
        {
            $uriadd = "?";
            $textcontent = "<h4 class='text-success'>".$tampung["name"]."</h4>";
            $textcontent .= "<div class='well'>".$tampung["descriptions"]."</div>";
            if(isset($tampung["uri"]))
            {
                $textcontent .= "<h5>Parameter Data URI</h5>";
                $textcontent .= "<dl class='dl-horizontal'>";
                foreach($tampung["uri"] as $dttemp=>$wrtemp)
                {
                    $textcontent .= "<dt><code>".$wrtemp['dturi']."</code></dt><dd>".$wrtemp['descuri']."</dd>";
                }
                $textcontent .= "</dl>";
            }
            if(isset($tampung["param"]))
            {
                $textcontent .= "<hr /><h5>Data Param Methode ".  strtoupper($tampung["methode"]) ."</h5>";
                $textcontent .= "<dl class='dl-horizontal'>";                
                foreach($tampung["param"] as $dttemp=>$wrtemp)
                {
                    $textcontent .= "<dt><code>".$wrtemp['dtparam']."</code></dt><dd>".$wrtemp['descparam']."</dd>";
                    $uriadd .= $wrtemp['dtparam']."=&";
                }
                $textcontent .= "</dl>";
            }
            $textcontent .= "<div class='panel'><div class='list-group'>";//".$tampung["url"].$uriadd."
            $textcontent .= "<div class='list-group-item'><i class='icon-link'></i> ".$tampung["url"]."</div>";
            $textcontent .= "<a href='#' class='list-group-item'><i class='icon-save'></i> Methode : ".$tampung["methode"]."</a>";
            $textcontent .= "<a href='#' class='list-group-item'><i class='icon-resize-small'></i> Return : ".$tampung["return"]."</a>";
            $textcontent .= "<a href='#' class='list-group-item'><i class='icon-time'></i> Last update : ".date('Y-m-d H:i:s', $tampung["lastupdate"]->sec)."</a>";
            $textcontent .= "</div></div>";
            if(isset($tampung["response"]))
            {
                $textcontent .= "<div class='row'>";
                $textcontent .= "<div class='col-md-12'>";
                $textcontent .= "<h4>Ext. Response</h4>";
                $textcontent .= "<pre class='prettyprint linenums'>";
                $stringtext = json_encode($tampung["response"]);
                $textcontent .= $this->tambahan_fungsi->replace_response( $stringtext );
                $textcontent .= "</pre>";
                $textcontent .= "</div>";
                $textcontent .= "</div>";
            }
            $output['message'] = "Data is loaded";
            $output['success'] = TRUE;
            $output['content'] = $textcontent;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('help/documentation/index'); 
        }
    }
}




