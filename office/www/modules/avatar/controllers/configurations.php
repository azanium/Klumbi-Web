<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Configurations extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model(array("m_avatar","m_userdata"));
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Avatar Configurations","module7",FALSE,TRUE,"home");
    }
    function index()
    {        
        $defaulkelamin = "male";
        $isiform['genderdefault'] = $defaulkelamin;
        $isiform['sizedefault'] = "medium";
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");    
        $isiform['anigenderdefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"gender"));
        $isiform['facedefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"face"));
        $isiform['handdefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"hand"));
        $isiform['legdefault']=$this->m_avatar->default_item(array("gender" => $defaulkelamin,'tipe' =>"leg"));
        $isiform['bodydefault']=$this->m_avatar->default_item(array("gender"=>$defaulkelamin,'tipe' =>"body"));
        $isiform['pantsdefault']=$this->m_avatar->default_item(array("gender"=>$defaulkelamin,'tipe' =>"pants"));
        $isiform['eyesdefault']=$this->m_avatar->default_item(array('tipe'=>"face_part_eyes"));
        $isiform['eyebrowsdefault']=$this->m_avatar->default_item(array('tipe' =>"face_part_eye_brows"));
        $isiform['lipdefault']=$this->m_avatar->default_item(array('tipe' =>"face_part_lip"));
        $isiform['hairdefault']=$this->m_avatar->default_item(array('tipe' =>"hair"));        
        $isiform['hatdefault']=$this->m_avatar->default_item(array('tipe' =>"hat",'payment' =>"Default"));
        $isiform['shoesdefault']=$this->m_avatar->default_item(array('tipe' =>"shoes"));        
        $this->mongo_db->select_collection("Brand");
        $isiform['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("SkinColor");
        $isiform['skindefault']=$this->mongo_db->findOne(array());
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
            base_url()."resources/css/avatareditor.css",
            base_url()."resources/plugin/ThumbnailGridExpandingPreview/css/component.css",
            base_url()."resources/plugin/ThumbnailGridExpandingPreview/css/default.css",
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
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
            base_url()."resources/plugin/ThumbnailGridExpandingPreview/js/modernizr.custom.js",
            base_url()."resources/plugin/ThumbnailGridExpandingPreview/js/grid.js",
        );
        $this->template_admin->header_web(TRUE,"User Mix",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listconfigurations_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
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
            $keysearchdt="gender";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="description";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="brand_id";
        }
        else if($iSortCol_0==5)
        {
            $keysearchdt="date";
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
            $title=(!isset($dt['name'])?"":$dt['name']);
            $gender=(!isset($dt['gender'])?"":$dt['gender']);
            $description=(!isset($dt['description'])?"":$dt['description']);
            $tglexpire="";
            if($dt['date']!="")
            {
                $tglexpire=date('Y-m-d H:i:s', $dt['date']->sec);
            }
            $img="";
            if(!empty($dt['picture']))
            {
               $img ="<a class='fancybox' href='".$this->config->item('path_asset_img')."preview_images/".$dt['picture']."'><img src='".$this->config->item('path_asset_img')."preview_images/".$dt['picture']."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>&nbsp;&nbsp;".$dt['picture'];
            }
            $detail="";
            $delete="";   
            $overwrite="";
            $data_like="";
            $data_comments="";
            if($this->m_checking->actions("Avatar Configurations","module7","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","",'Edit',"pencil.png","","linkdetail");
            } 
            if($this->m_checking->actions("Avatar Configurations","module7","Overwrite to Default Avatar",TRUE,FALSE,"home"))
            {
                $overwrite =$this->template_icon->detail_onclick("overwritedefault('".$dt['_id']."','Are you sure want to Overwrite this avatar mix to default avatar ?')","",'Overwrite to Default Avatar',"tag_blue_edit.png","","linkdelete");
            }
            if($this->m_checking->actions("Avatar Configurations","module7","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Avatar Mix ".$title."')","",'Delete',"delete.png","","linkdelete");
            }              
            if($this->m_checking->actions("Avatar Configurations","module7","View Likes",TRUE,FALSE,"home"))
            {
                $data_like=$this->template_icon->link_icon_notext("avatar/avatarconfigurations/datalike/".$dt['_id'],'Like ',"heart.png");
            }
            if($this->m_checking->actions("Avatar Configurations","module7","View Comments",TRUE,FALSE,"home"))
            {
                $data_comments=$this->template_icon->link_icon_notext("avatar/avatarconfigurations/datacomments/".$dt['_id'],'Comment',"comments.png");
            }
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");
            $brandid = (!isset($dt['brand_id'])?"":$dt['brand_id']);
            $tempnamebrand = $this->mongo_db->findOne(array("brand_id"=>$brandid));
            $brandname="";
            if($tempnamebrand)
            {
                $brandname = $tempnamebrand['name'];
            }
            $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
            $output['aaData'][] = array(
                $i,
                $title,
                $gender,
                $description,
                $brandname,
                $tglexpire,
                $tempdtuser['fullname'],
                $img,
                $data_comments.$data_like.$overwrite.$detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('avatar/configurations/index'); 
        }
    }
    function detail_data($id="")
    {
        $this->m_checking->actions("Avatar Configurations","module7","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
        $output['message'] = "Data tidak ditemukan";
        $output['success'] = FALSE;
        $tampung = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['message'] = "<i class='success'>Data ditemukan</i>";
            $output['success'] = TRUE;
            $output['data']['_id'] = (string)$tampung['_id'];
            $output['data']['name'] = $tampung['name'];
            $output['data']['gender'] = $tampung['gender'];
            $output['data']['size'] = $tampung['bodytype'];
            $output['data']['description'] = $tampung['description'];
            $output['data']['brand_id'] = $tampung['brand_id'];
            $tempdtconfigall = $this->m_avatar->avatarconfig($tampung['configuration'],$tampung["bodytype"],"web");
            $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
            if($tempconfigstring == "[[]]")
            {
                $tempconfigstring = "";
            }
            $output['data']['configurations'] = $tempconfigstring;
            $output['data']['dataconf'] = $tempdtconfigall['dataconf'];
        }
        else
        {
            $output['message'] = "<i class='error'>Data Avatar User Mix Tidak ditemukan</i>";
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('avatar/configurations/index'); 
        }
    }
    function overwrite($id="")
    {
        $this->m_checking->actions("Avatar Configurations","module7","Overwrite to Default Avatar",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
        $output['message'] = "Fail to overwrite";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['message'] = "<i class='success'>Data is success to be overwrite</i>";
            $gender = (!isset($tampung['gender'])?"":$tampung['gender']);
            $datatinsert=array(               
                'configuration'=>(!isset($tampung['configuration'])?"":$tampung['configuration']),
                'name'  =>(!isset($tampung['name'])?"":$tampung['name']),
                "description" => (!isset($tampung['description'])?"":$tampung['description']),
                'gender' => $gender,
            );
            $datafilter=array(
                'gender'=> $gender,
            );
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("DefaultAvatar");
            $this->mongo_db->update($datafilter,array('$set'=>$datatinsert),array('upsert' => TRUE));
            $output['success'] = TRUE;
        }
        else
        {
            $output['message'] = "<i class='error'>Data is fail to be overwrite</i>";
        }
        $this->m_user->tulis_log("Ovewrite to Default Avatar",$url,$user);
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('avatar/configurations/index'); 
        }
    }
    function cruid_avatar()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('title','Name Avatar Mix','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtgender','Gender','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtskin','Skin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtsize','Body Size','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('eyes','Eyes','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('eyeBrows','Eyebrow','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('lip','Lip','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hair','Hair','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('body','Body','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hat','Hat','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('shoes','Shoes','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('pants','Pants','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('hand','Hand','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('leg','Leg','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','File Image','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('description','Description','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('face','Face','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('gender','Gender','trim|htmlspecialchars|xss_clean'); 
        $output['message'] = "";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        $generatetime=strtotime(date("Y-m-d H:i:s"));
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $title = $this->input->post('title',TRUE);
            $gender = $this->input->post('txtgender',TRUE);
            $skin = $this->input->post('txtskin',TRUE);
            $size = $this->input->post('txtsize',TRUE);            
            $eyes = $this->input->post('eyes',TRUE);
            $eyebrows = $this->input->post('eyeBrows',TRUE);
            $lip = $this->input->post('lip',TRUE);
            $hair = $this->input->post('hair',TRUE);
            $body = $this->input->post('body',TRUE);
            $hat = $this->input->post('hat',TRUE);
            $shoes = $this->input->post('shoes',TRUE);
            $pants = $this->input->post('pants',TRUE);
            $hands = $this->input->post('hand',TRUE);
            $legs = $this->input->post('leg',TRUE);
            $face = $this->input->post('face',TRUE);
            $idgender = $this->input->post('gender',TRUE);
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'hand','gender'=>$gender));
            if($cekdatatemp)
            {
                $hands = (string)$cekdatatemp["_id"];
            }
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'face','gender'=>$gender));
            if($cekdatatemp)
            {
                $face = (string)$cekdatatemp["_id"];
            }
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'gender','gender'=>$gender));
            if($cekdatatemp)
            {
                $idgender = (string)$cekdatatemp["_id"];
            }
            $cekdatatemp = $this->mongo_db->findOne(array('tipe'=>'leg','gender'=>$gender));
            if($cekdatatemp)
            {
                $legs = (string)$cekdatatemp["_id"];
            }
            $description = $this->input->post('description',TRUE);
            $fileimg = $this->input->post('txtfileimgname',TRUE);
            if($this->session->userdata('brand')=="")
            {
                $dtbrand = $_POST['brand'];
            }
            else
            {
                $dtbrand = $this->session->userdata('brand');
            }
            $datatinsert=array(
                'name'  =>$title,
                'gender'  =>$gender,
                'user_id' => $this->session->userdata('user_id'),
                'bodytype'=>$size,
                "author" => $this->session->userdata('username'),
                "description" => $description,
                "brand_id" => $dtbrand,
                'configuration'=>array(
                    array('tipe'=>"Skin","color"=>$skin),
                    array('tipe'=>"Leg","id"=>$legs),
                    array('tipe'=>"Hand","id"=>$hands),
                    array('tipe'=>"Face","id"=>$face),
                    array('tipe'=>"Gender","id"=>$idgender),                    
                    array('tipe'=>"EyeBrows","id"=>(string)$eyebrows),
                    array('tipe'=>"Eyes","id"=>(string)$eyes),
                    array('tipe'=>"Lip","id"=>(string)$lip),
                    array('tipe'=>"Hair","id"=>(string)$hair),
                    array('tipe'=>"Body","id"=>(string)$body),
                    array('tipe'=>"Pants","id"=>(string)$pants),
                    array('tipe'=>"Shoes","id"=>(string)$shoes),
                    array('tipe'=>"Hat","id"=>(string)$hat),
                ),
                'date'=>$this->mongo_db->time($generatetime),
            );
            if($fileimg!="")
            {
                $datatinsert=  array_merge( $datatinsert, array('picture'  =>$fileimg));
            }
            if($id=='')
            {
                $this->m_checking->actions("Avatar Configurations","module7","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("AvatarMix");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Avatar Mix",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {                
                $this->m_checking->actions("Avatar Configurations","module7","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("AvatarMix");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Avatar Mix",$url,$user);
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
            redirect('avatar/configurations/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Avatar Configurations","module7","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            @unlink($this->config->item('path_upload')."preview_images/".$data['picture']);
        }
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Avatar Mix",$url,$user);
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
            redirect('avatar/configurations/index'); 
        }
    }
}


