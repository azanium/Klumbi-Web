<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipe extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Avatar Body Part Type","module6",FALSE,TRUE,"home");
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
        $this->template_admin->header_web(TRUE,"Avatar Body Part",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listtipe_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("AvatarBodyPart");
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
            $keysearchdt="title";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="parent";
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
            $parent=(!isset($dt['parent'])?"":$dt['parent']);
            $title=(!isset($dt['title'])?"":$dt['title']);
            $detail="";
            $delete="";            
            if($this->m_checking->actions("Avatar Body Part Type","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['name']."','".$title."','".$parent."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            if($this->m_checking->actions("Avatar Body Part Type","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Tipe ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            } 
            $output['aaData'][] = array(
                $i,
                $title,
                $dt['name'],
                $parent,
                $detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/tipe/index'); 
        }
    }
    function list_data_load()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("AvatarBodyPart");
        $data=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));
        $output['message'] = "Fail Load Data";
        $output['success'] = FALSE;
        if($data)
        {
            $output['success'] = TRUE;
            foreach($data as $dt)
            {
                $output['data'][] = $dt['name'];
            }
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/tipe/index'); 
        }
    }
    function cruid_tipe()
    {        
        $this->form_validation->set_rules('name','Tipe Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('title','Tipe Title','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
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
            $title = $this->input->post('title',TRUE);
            $parent='';
            if(isset($_POST['cmbtipe']) && isset($_POST['rdhaveparen']))
            {
                $parent=$_POST['cmbtipe'];
            }
            $datatinsert=array(
                'title'  =>$title,
                'name'  =>$name,
                'parent'=>$parent,
            );
            if($id=='')
            {
                $this->m_checking->actions("Avatar Body Part Type","module6","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("AvatarBodyPart");
                $level_id=$this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Body Type",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {                
                $this->m_checking->actions("Avatar Body Part Type","module6","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("AvatarBodyPart");
                $namalama=$this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
                $this->mongo_db->select_collection("Category");
                $this->mongo_db->update(array('tipe'=> $namalama['name']),array('$set'=>array('tipe'=>$name)));
                $this->mongo_db->select_collection("Avatar");
                $this->mongo_db->update(array('tipe'=> $namalama['name']),array('$set'=>array('tipe'=>$name)));
                $this->mongo_db->select_collection("AvatarBodyPart");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Body Type",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
            }  
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/tipe/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Avatar Body Part Type","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("AvatarBodyPart");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Body Type",$url,$user);
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
            redirect('brand/tipe/index'); 
        }
    }
}


