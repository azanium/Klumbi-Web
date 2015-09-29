<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Brand extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Brand","module2",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
        );
        $this->template_admin->header_web(TRUE,"Store",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listdata_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
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
            $keysearchdt="brand_id";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="name";
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
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('name'=>$jns_sorting));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $brand_id=(!isset($dt['brand_id'])?"":$dt['brand_id']);
            $contentpic=(!isset($dt['banner'])?"":$dt['banner']);
            $descriptions=(!isset($dt['description'])?"":$dt['description']);
            $detail="";
            $delete="";
            $data_like="";
            $data_comments="";
            $action_contract="";
            $poster="";
            $picture="";
            $filepicture="";
            $state="";
            $textstate = "New";
            if(isset($dt['picture']) && $dt['picture']!="")
            {
                $filepicture=$dt['picture'];
                $picture="<a class='fancybox' href='".$this->config->item('path_asset_img')."preview_images/".$dt['picture']."'><img src='".$this->config->item('path_asset_img')."preview_images/".$dt['picture']."' alt='".$dt['picture']."' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>";
            }
            if(isset($dt['banner']) && $dt['banner']!="")
            {
                $poster="<a class='fancybox' href='".$this->config->item('path_asset_img')."preview_images/".$dt['banner']."'><img src='".$this->config->item('path_asset_img')."preview_images/".$dt['banner']."' alt='".$dt['banner']."' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>";
            }
            if($this->m_checking->actions("Brand","module2","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."', '".$brand_id."', '".$dt['name']."', '".$filepicture."','".$contentpic."','".$descriptions."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Brand","module2","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Brand ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("Brand","module2","View Likes",TRUE,FALSE,"home"))
            {
                $data_like=$this->template_icon->link_icon_notext("brand/brandsosial/datalike/".$dt['_id'],'Like ',"heart.png");
            }
            if($this->m_checking->actions("Brand","module2","View Comments",TRUE,FALSE,"home"))
            {
                $data_comments=$this->template_icon->link_icon_notext("brand/brandsosial/datacomments/".$dt['_id'],'Comment',"comments.png");
            }
            if($this->m_checking->actions("Brand","module2","Manage Contract",TRUE,FALSE,"home"))
            {
                $action_contract=$this->template_icon->link_icon_notext("brand/contract/".$dt['_id'],'Manage Contract',"lock_go.png");
            }
            if($this->m_checking->actions("Brand","module2","State",TRUE,FALSE,"home"))
            {
                $replaceto = "Approved";
                if(isset($dt['state']))
                {
                    $textstate = $dt['state'];
                    if($dt['state']=="Approved")
                    {
                        $replaceto = "New";
                    }
                }
                $state=$this->template_icon->detail_onclick("changestate('".$dt['_id']."','".$replaceto."')","",'State',"rosette.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $brand_id,
                $dt['name'],
                $picture,
                $poster,
                $textstate,
                $state.$action_contract.$data_comments.$data_like.$detail.$delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/index'); 
        }
    }
    function contract($id="")
    {
        $this->m_checking->actions("Brand","module2","Manage Contract",FALSE,TRUE,"brand/index");
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
        );
        $this->template_admin->header_web(TRUE,"Managemen Contract Store",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $propertipage["id"] = $id;
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $data=$this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
        if($data)
        {
            
        }
        $this->load->view("listdata_managecontract",$propertipage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function changestate($id="",$state="New")
    {
        $this->m_checking->actions("Brand","module2","State",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $dataupdate=array(
            'state'  =>$state,
        );
        $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$dataupdate));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Update State Brand to ".$state,$url,$user);
        $output = array(
            "message" =>"Data state is update",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('brand/index'); 
        }
    }
    function cruid_brand()
    {        
        $this->form_validation->set_rules('brand_id','ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','Image','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgnamecontent','Image Content','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('descriptions','Descriptions','trim|htmlspecialchars|xss_clean');
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
            $brand_id = $this->input->post('brand_id',TRUE);
            $name = $this->input->post('name',TRUE);
            $picture = $this->input->post('txtfileimgname',TRUE);
            $picturebanner = $this->input->post('txtfileimgnamecontent',TRUE);
            $descriptions = $this->input->post('descriptions',TRUE);
            $tanggalupdate=$this->mongo_db->time(strtotime(date("Y-m-d H:i:s")));
            $datatinsert=array(
                'brand_id'  =>$brand_id,
                'name'  =>$name,
                'description'  =>$descriptions,
                'dateupdate'=>$tanggalupdate,
            );
            if($picture!="")
            {
                $datatinsert= array_merge($datatinsert,array('picture'  =>$picture));
            }
            if($picturebanner!="")
            {
                $datatinsert= array_merge($datatinsert,array('banner'  =>$picturebanner));
            }
            if($id=='')
            {
                $this->m_checking->actions("Brand","module2","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Brand");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Brand",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Brand","module2","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Brand");
                $namalama=$this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
                if($namalama)
                {
                    $this->mongo_db->select_collection("Avatar");
                    $this->mongo_db->update(array('brand_id'=> $namalama['brand_id']),array('$set'=>array('brand_id'=>$name)));
                }
                $this->mongo_db->select_collection("Brand");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Brand",$url,$user);
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
            redirect('brand/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Brand","module2","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            if(isset($data['picture']))
            {
                @unlink($this->config->item('path_upload')."preview_images/".$data['picture']);
            } 
            if(isset($data['banner']))
            {
                @unlink($this->config->item('path_upload')."preview_images/".$data['banner']);
            }
        }
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Brand",$url,$user);
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
            redirect('brand/index'); 
        }
    }
    function import($tofile="")
    {	
        $this->m_checking->actions("Brand","module2","Export",FALSE,TRUE,"brand/index");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $datapageadd['listdata'] = $this->mongo_db->find(array(),0,0,array());        
        $this->load->library(array('reporting','table'));
        $filename="brand";
        $temp['data_title'] = "Cetak Data Brand";
        if($tofile=="doc")
        {
            $this->reporting->header_rtf($filename);
            $this->load->view("report/report_brand",$datapageadd);
        }
        else if($tofile=="xls")
        {
            $this->reporting->header_xls($filename);
            $this->reporting->xls_bof();
            $this->reporting->xls_write_label(0,0,$temp['data_title'] );
            $this->reporting->xls_write_label(1,0,"No");
            $this->reporting->xls_write_label(1,1,"Brand");
            $this->reporting->xls_write_label(1,2,"Website");
            $index=4;
            foreach($datapageadd['listdata'] as $dt)
            {
                $this->reporting->xls_write_number($index,0,($index-3));
                $this->reporting->xls_write_label($index,1,(isset($dt['name'])?$dt['name']:""));
                $this->reporting->xls_write_label($index,2,(isset($dt['website'])?$dt['website']:""));
                $index++;
            }
            $this->reporting->xls_eof();
        }
        else
        {            
            $css=array();
            $js=array();
            $this->template_admin->header_web(FALSE,$temp['data_title'],$css,$js,FALSE,"");
            $this->load->view("report/report_brand",$datapageadd);
            $this->template_admin->footer();
        }
    }
}


