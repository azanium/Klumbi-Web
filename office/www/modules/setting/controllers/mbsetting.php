<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mbsetting extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Setting","module2",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/form-colorpicker/js/bootstrap-colorpicker.min.js",
        );
        $this->template_admin->header_web(TRUE,"Templates",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("mbsetting_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Settings");
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
        else if($iSortCol_0==3)
        {
            $keysearchdt="code";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="value";
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
            $detail="";
            if($this->m_checking->actions("Setting","module2","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#",'Edit',"pencil.png","","","");
            }
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $dt['code'],
                $dt['value'],
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
            redirect('setting/mbsetting/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Setting","module2","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Settings");
        $temp_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        $data['txtid']='';
        $data['code']='';
        $data['type']='';
        $data['value']='';
        $data['name']='';
        $data['default']=''; 
        $data['format']='';
        $data['options']='';
        $data['descriptions']='';
        $data['classword']='contentdt'.$this->tambahan_fungsi->global_get_random(10);
        if($temp_detail)
        {
            $data['txtid']=$temp_detail['_id'];
            $data['code']=isset($temp_detail['code'])?$temp_detail['code']:'';
            $data['type']=isset($temp_detail['type'])?$temp_detail['type']:'';
            $data['value']=isset($temp_detail['value'])?$temp_detail['value']:'';
            $data['name']=isset($temp_detail['name'])?$temp_detail['name']:'';
            $data['default']=isset($temp_detail['self_value'])?$temp_detail['self_value']:''; 
            $data['format']=isset($temp_detail['format'])?$temp_detail['format']:'';
            $data['options']=isset($temp_detail['options'])?$temp_detail['options']:'';
            $data['descriptions']=isset($temp_detail['descriptions'])?$temp_detail['descriptions']:'';
            $this->load->view("mbsetting_iframe",$data);
        }
        else
        {
            echo "<i class='error'>Setting not found.</i>";
        }
    }
    function cruid_setting()
    {        
        $this->form_validation->set_rules('name','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttype','Type','trim|required|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $name = $this->input->post('name',TRUE);
            $dttype = $this->input->post('txttype',TRUE);
            $dtvalue = $_POST['txtvalue'];
            if($dttype==="number")
            {
                $dtvalue=(int)$dtvalue;
            }
            $datatinsert=array(
                'name'  =>$name,
                'value'  =>$dtvalue,
            );
            $this->m_checking->actions("Setting","module2","Edit",FALSE,FALSE,"home");
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Settings");
            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
            $this->m_user->tulis_log("Update Setting For Mobile",$url,$user);
            $output['message'] = "<i class='success'>Data is updated</i>"; 
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('setting/mbsetting/index'); 
        }
    }
}




