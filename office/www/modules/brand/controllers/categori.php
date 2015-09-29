<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Categori extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Category","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("AvatarBodyPart");
        $isiform['type']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.css?v=2.1.5",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
        );
        $this->template_admin->header_web(TRUE,"Category",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listcategori_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Category");
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
            $keysearchdt="tipe";
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
        if(isset($_GET['cmbtipeview']))
        {
            if($_GET['cmbtipeview']!="")
            {
                $pencarian=  array_merge($pencarian,array('tipe'=>$_GET['cmbtipeview']));
            }            
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
            $picture = "";
            $filepicture = "";
            if(isset($dt['image']))
            {
                $picture = "<a class='fancybox' href='".$this->config->item('path_asset_img')."preview_images/".$dt['image']."'><img src='".$this->config->item('path_asset_img')."preview_images/".$dt['image']."' alt='".$dt['name']."' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>";
                $filepicture = $dt['image'];
            }
            if($this->m_checking->actions("Category","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Category ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("Category","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['tipe']."','".$dt['name']."','".$filepicture."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }            
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $dt['tipe'],
                $picture,
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
            redirect('brand/categori/index'); 
        }
    }
    function cruid_categori()
    {        
        $this->form_validation->set_rules('name','Category Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbtipe','Tipe','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','Image','trim|htmlspecialchars|xss_clean');
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
            $cmbtipe = $this->input->post('cmbtipe',TRUE);
            $fileimg = $this->input->post('txtfileimgname',TRUE);
            $datatinsert=array(
                'name'  =>$name,
                'tipe'  =>$cmbtipe,
            );
            if($fileimg!="")
            {
                $datatinsert=  array_merge($datatinsert,array('image'  =>$fileimg));
            }
            if($id=='')
            {
                $this->m_checking->actions("Category","module6","Add",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Category");
                $level_id=$this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Category",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Category","module6","Edit",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Category");
                $namalama=$this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
                if($namalama)
                {
                    $this->mongo_db->select_collection("Avatar");
                    $this->mongo_db->update(array('category'=> $namalama['name']),array('$set'=>array('category'=>$name)));
                }                
                $this->mongo_db->select_collection("Category");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Category",$url,$user);
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
            redirect('brand/categori/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Category","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Category");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            if(isset($data['image']))
            {
                @unlink($this->config->item('path_upload')."preview_images/".$data['image']);
            }            
        }
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Category",$url,$user);
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
            redirect('brand/categori/index'); 
        }
    }
}
