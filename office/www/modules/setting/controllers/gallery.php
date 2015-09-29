<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gallery extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Gallery","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentCategory");
        $isiform['searchcategory']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.css?v=2.1.5",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Gallery",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("gallery_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Gallery");
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
        $keysearchdt="title";
        if($iSortCol_0==2)
        {
            $keysearchdt="title";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="keysearch";
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
            $delete="";
            if($this->m_checking->actions("Gallery","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Gallery ".$dt['title']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("Gallery","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['title']."','".$dt['keysearch']."','".$dt['image']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            $img ="<a class='fancybox' href='".$dt['image']."'><img src='".$dt['image']."' class='img-thumbnail' style='max-width:120px; max-height:120px;'/></a>";
            $output['aaData'][] = array(
                $i,
                $dt['title'],
                $img,
                $dt['keysearch'],
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
            redirect('setting/gallery/index'); 
        }
    }
    function cruid_gallery()
    {        
        $this->form_validation->set_rules('title','Title','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('imageurl','Url Image','trim|required|prep_url|xss_clean');
        $this->form_validation->set_rules('searchcategory','Key Image','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $data['success']=FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $data['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $title = $this->input->post('title',TRUE);
            $imageurl = $this->input->post('imageurl',TRUE);
            $keysearch = $this->input->post('searchcategory',TRUE);
            $url = current_url();
            $user = $this->session->userdata('username');
            $datatinsert=array(
                'title'  =>$title,
                'image'  =>$imageurl,
                'keysearch'  =>$keysearch,
            );
            if($id=='')
            {
                $this->m_checking->actions("Gallery","module6","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Website");
                $this->mongo_db->select_collection("Gallery");
                $level_id=$this->mongo_db->insert($datatinsert);                
                $this->m_user->tulis_log("Add New Gallery",$url,$user);
                $data['message'] = "New data is added";
            }
            else
            {
                $this->m_checking->actions("Gallery","module6","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Website");
                $this->mongo_db->select_collection("Gallery");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Data Gallery",$url,$user);
                $data['message'] = "Data is Updated";
            }  
            $data['success']=TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $data );
        }
        else
        {
            redirect('setting/gallery/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Gallery","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Gallery");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Data Gallery",$url,$user);
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
            redirect('setting/gallery/index'); 
        }
    }
}
