<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Slideshow extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Slideshow","module6",FALSE,TRUE,"home");
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
        );
        $this->template_admin->header_web(TRUE,"Slide Show",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("slideshow_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Slideshow");
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
        $keysearchdt="no";
        if($iSortCol_0==2)
        {
            $keysearchdt="title";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="image";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="link";
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
            if($this->m_checking->actions("Slideshow","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Slideshow ".$dt['title']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("Slideshow","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            $img ="<a class='fancybox' href='".$dt['image']."'><img src='".$dt['image']."' class='img-thumbnail' style='max-width:75px; max-height:75px;'/></a>";
            $output['aaData'][] = array(
                $i,
                $dt['no'],
                $dt['title'],
                $img,
                $dt['link'],
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
            redirect('setting/slideshow/index'); 
        }
    }
    function cruid_slideshow()
    {        
        $this->form_validation->set_rules('title','Title','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('ordered','Order Number','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('imageurl','Url Image','trim|required|prep_url|xss_clean');
        $this->form_validation->set_rules('linkdata','Link Image','trim|prep_url|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $data['success']=FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $data['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $ordered = (int)$this->input->post('ordered',TRUE);
            $title = $this->input->post('title',TRUE);
            $imageurl = $this->input->post('imageurl',TRUE);
            $linkdata = $this->input->post('linkdata',TRUE);
            $description = $_POST['description'];
            $url = current_url();
            $user = $this->session->userdata('username');
            $datatinsert=array(
                'no'  =>$ordered,
                'title'  =>$title,
                'image'  =>$imageurl,
                'link'  =>$linkdata,
                'description'  =>$description,
            );
            if($id=='')
            {
                $this->m_checking->actions("Slideshow","module6","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("Slideshow");
                $level_id=$this->mongo_db->insert($datatinsert);                
                $this->m_user->tulis_log("Add New Slideshow",$url,$user);
                $data['message'] = "New data is added";
            }
            else
            {
                $this->m_checking->actions("Slideshow","module6","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("Slideshow");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Data Slideshow",$url,$user);
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
            redirect('setting/slideshow/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Slideshow","module6","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Slideshow");
        $return['success']=FALSE;
        $return['message'] = "Your data is not found";
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $return['success']=TRUE;
            $return['_id'] = $id;
            $return['no'] = $tampung['no'];
            $return['title'] = $tampung['title'];
            $return['image'] = $tampung['image'];
            $return['link'] = $tampung['link'];
            $return['description'] = $tampung['description'];            
        }
        if(IS_AJAX)
        {
            echo json_encode( $return );
        }
        else
        {
            redirect('setting/slideshow/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Slideshow","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Slideshow");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Data Slideshow",$url,$user);
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
            redirect('setting/slideshow/index'); 
        }
    }
}
