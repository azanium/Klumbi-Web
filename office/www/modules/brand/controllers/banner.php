<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banner extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Banner","module9",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Color");
        $isiform['colorls']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("AvatarBodyPart");
        $isiform['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));
        $this->mongo_db->select_collection("Brand");
        $isiform['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.css?v=2.1.5",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css", 
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
            base_url()."resources/plugin/form-multiselect/js/jquery.multi-select.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Home Banner",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listbanner_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Banner");
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
            $keysearchdt="ID";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="Descriptions";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="type";
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
        if($this->session->userdata('brand')!="")
        {
            $pencarian= array_merge($pencarian,array("brand_id"=>$this->session->userdata('brand')));
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
            $ID=(!isset($dt['ID'])?"":$dt['ID']);
            $name=(!isset($dt['name'])?"":$dt['name']);            
            $descriptions=(!isset($dt['Descriptions'])?"":$dt['Descriptions']);
            $type=(!isset($dt['type'])?"":$dt['type']);
            $picture="";
            $app_path=$this->config->item('path_asset_img')."preview_images/";
            if(isset($dt['picture']) && $dt['picture']!="")
            {
                $tempfile = explode("/", $dt['picture']);
                if(count($tempfile)>1)
                {
                    $linkimg = $this->config->item('path_asset_img').$dt['picture'];
                }
                else
                {
                    $linkimg = $app_path.$dt['picture'];
                }
                $picture = "<a class='fancybox' href='".$linkimg."'><img src='".$linkimg."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>";
            }
            $detail="";
            $delete=""; 
            $data_like="";
            $data_comments="";
            if($this->m_checking->actions("Banner","module9","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            if($this->m_checking->actions("Banner","module9","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete ".$name." ?')","",'Delete',"delete.png","","linkdelete");
            } 
            if($this->m_checking->actions("Banner","module9","View Likes",TRUE,FALSE,"home"))
            {
                $data_like=$this->template_icon->link_icon_notext("brand/bannersosial/datalike/".$dt['_id'],'Like ',"heart.png");
            }
            if($this->m_checking->actions("Banner","module9","View Comments",TRUE,FALSE,"home"))
            {
                $data_comments=$this->template_icon->link_icon_notext("brand/bannersosial/datacomments/".$dt['_id'],'Comment',"comments.png");
            }
            $output['aaData'][] = array(
                $i,
                $ID,
                $name,
                $descriptions,
                $type,
                $picture,                
                $data_comments.$data_like.$detail.$delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/banner/index'); 
        }
    }
    function generatecode()
    {
        $output = array(
            "message" =>"Data Code is Generated",
            "success" =>TRUE,
            "data" =>$this->tambahan_fungsi->global_get_random(8),
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/banner/index'); 
        }
    }
    function get_data($id="")
    {
        $this->m_checking->actions("Banner","module9","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Banner");
        $output['message'] = "Data not found";
        $output['success'] = FALSE;
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['_id'] = (!isset($tampung['_id'])?"":(string)$tampung['_id']);
            $output['ID'] = (!isset($tampung['ID'])?"":$tampung['ID']);
            $output['name'] = (!isset($tampung['name'])?"":$tampung['name']);
            $output['Descriptions'] = (!isset($tampung['Descriptions'])?"":$tampung['Descriptions']);
            $output['urlPicture'] = (!isset($tampung['picture'])?"":$tampung['picture']);
            $output['dataValue'] = (!isset($tampung['dataValue'])?"":$tampung['dataValue']);
            $tempfile = explode("/", $tampung['picture']);
            if(count($tempfile)>1)
            {
                $linkimg = $this->config->item('path_asset_img').$tampung['picture'];
            }
            else
            {
                $linkimg = $this->config->item('path_asset_img')."preview_images/".$tampung['picture'];
            }
            $output['pictureurl'] = $linkimg;
            $output['tag'] = (!isset($tampung['tags'])?"":$tampung['tags']);
            $output['textcolor'] = (!isset($tampung['textcolor'])?"":$tampung['textcolor']);
            $output['brand'] = (!isset($tampung['brand_id'])?"":$tampung['brand_id']);
            $output['type'] = (!isset($tampung['type'])?"":$tampung['type']);
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/banner/index'); 
        }
    }
    function cruid_banner()
    {        
        $this->form_validation->set_rules('txtvalue','Value','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttag','Tags','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdescriptions','Descriptions Banner','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','Name Banner','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('IDCode','Code Banner','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('type','Tipe Banner','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','File Image','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcolor','Text Color','trim|htmlspecialchars|xss_clean');
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
            $type = $this->input->post('type',TRUE);
            $IDCode = $this->input->post('IDCode',TRUE);
            $name = $this->input->post('name',TRUE);
            $txtdescriptions = $this->input->post('txtdescriptions',TRUE);
            $txtpictureurl = $this->input->post('txtfileimgname',TRUE);
            $txtvalue = $this->input->post('txtvalue',TRUE);  
            $txttag = $this->input->post('txttag',TRUE); 
            $textcolor = $this->input->post('txtcolor',TRUE); 
            $tanggalupdate=$this->mongo_db->time(strtotime(date("Y-m-d H:i:s")));
            if($this->session->userdata('brand')=="")
            {
                $dtbrand = $_POST['brand'];
            }
            else
            {
                $dtbrand = $this->session->userdata('brand');
            }
            $datatinsert=array(
                'ID'=>$IDCode,
                'name'=>$name,
                'Descriptions'=>$txtdescriptions,
                'type'=>$type,
                'picture'=>$txtpictureurl,
                'dataValue'=>$txtvalue,
                'date'=>$tanggalupdate,
                'brand_id'=>$dtbrand,
                'tags'=>$txttag,
                'textcolor'=>$textcolor,
            );
            if($id=='')
            {
                $this->m_checking->actions("Banner","module9","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Banner");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Banner",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {                
                $this->m_checking->actions("Banner","module9","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Banner");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Banner",$url,$user);
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
            redirect('brand/banner/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Banner","module9","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Banner");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            $tempfile = explode("/", $data['picture']);
            if(count($tempfile)>1)
            {
                $linkimg = $this->config->item('path_upload').$data['picture'];
            }
            else
            {
                $linkimg = $this->config->item('path_upload')."preview_images/".$data['picture'];
                @unlink($linkimg);
            }            
        }
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $output = array(
            "message" =>"Data is deleted",
            "success" =>TRUE,
        );
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Banner",$url,$user);
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/banner/index'); 
        }
    }
}


