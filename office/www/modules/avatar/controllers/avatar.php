<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avatar extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Avatar","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("AvatarBodyPart");
        $isiform['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));
        $temptipe=$this->mongo_db->findOne();        
        $this->mongo_db->select_collection("Category");
        $isiform['category']=$this->mongo_db->find(array('tipe'=>$temptipe['name']),0,0,array('name'=>1));
        $this->mongo_db->select_collection("Brand");
        $isiform['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("Payment");
        $isiform['payment']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentType");
        $isiform['searchtype']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("ContentCategory");
        $isiform['searchcategory']=$this->mongo_db->find(array(),0,0,array('name'=>1));
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
            base_url()."resources/plugin/form-colorpicker/js/bootstrap-colorpicker.min.js",
        );
        $this->template_admin->header_web(TRUE,"Avatar Item",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("avatar_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
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
        if($iSortCol_0==2)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="tipe";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="gender";
        }
        else if($iSortCol_0==5)
        {
            $keysearchdt="category";
        }
        else if($iSortCol_0==6)
        {
            $keysearchdt="payment";
        }
        else if($iSortCol_0==7)
        {
            $keysearchdt="brand_id";
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
        if(isset($_GET['tipeshow']))
        {
            if($_GET['tipeshow']!="")
            {
                $pencarian=  array_merge($pencarian,array('tipe'=>$_GET['tipeshow']));
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
            $childcode=(!isset($dt['code'])?"":$dt['code']);
            $childname=(!isset($dt['name'])?"":$dt['name']);
            $childtipe=(!isset($dt['tipe'])?"":$dt['tipe']);
            $childgender=(!isset($dt['gender'])?"":$dt['gender']);
            $childcategory=(!isset($dt['category'])?"":$dt['category']);
            $childpayment=(!isset($dt['payment'])?"":$dt['payment']); 
            $brandname="";
            if(isset($dt['brand_id']))
            {
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Brand");
                $checkdata=$this->mongo_db->findOne(array('brand_id'=>$dt['brand_id']));
                if($checkdata)
                {
                    $brandname = $checkdata['name'];
                } 
                else
                {
                    $brandname = $dt['brand_id'];
                }
            } 
            $childcolor=(!isset($dt['color'])?"":$dt['color']);            
            $warna= "<span style='width: 15px;height: 15px;display:block;margin:20px 3px 3px 3px;background-color: ".$childcolor.";'></span>";
            $uploaddir = $this->config->item('path_upload');
            $downloaddir = $this->config->item('path_asset_img');
            $picture = "";
            if(isset($dt['preview_image']) && $dt['preview_image']!="")
            {
                $tempfile = explode("/", $dt['preview_image']);
                if(count($tempfile)>1)
                {
                    $linkimg = $downloaddir.$dt['preview_image'];
                    $directoryimage = $uploaddir.$dt['preview_image'];
                }
                else
                {
                    $linkimg = $downloaddir."preview_images/".$dt['preview_image'];
                    $directoryimage = $uploaddir."preview_images/".$dt['preview_image'];
                }
                if(!file_exists($directoryimage))
                {
                    $linkimg = base_url()."resources/image/index.jpg";
                }
                $picture = "<a class='fancybox' href='".$linkimg."'><img src='".$linkimg."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>";
            }
            $download ="<div class='btn-group'>";
            if(!empty($dt['element_web_thin']) && $dt['element_web_thin']!="")
            {
                $download .=  "<a class='btn btn-sm btn-success' style='text-decoration:none;' href='".$downloaddir."characters/".$dt['element_web_thin']."' title='Download Elemen Web Thin' target='_blank'><i class='icon-globe'></i> Element Thin <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_web_thin']) && $dt['material_web_thin']!="")
            {
                $download .=  "<a class='btn btn-sm btn-success' style='text-decoration:none;' href='".$downloaddir."materials/".$dt['material_web_thin']."' title='Download Materian Web Thin' target='_blank'><i class='icon-globe'></i> Material Thin <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_web_medium']) && $dt['element_web_medium']!="")
            {
                $download .=  "<a class='btn btn-sm btn-success' style='text-decoration:none;' href='".$downloaddir."characters/".$dt['element_web_medium']."' title='Download Elemen Web Medium' target='_blank'><i class='icon-globe'></i> Element Medium <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_web_medium']) && $dt['material_web_medium']!="")
            {
                $download .=  "<a class='btn btn-sm btn-success' style='text-decoration:none;' href='".$downloaddir."materials/".$dt['material_web_medium']."' title='Download Materian Web Medium' target='_blank'><i class='icon-globe'></i> Material Medium <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_web_fat']) && $dt['element_web_fat']!="")
            {
                $download .=  "<a class='btn btn-sm btn-success' style='text-decoration:none;' href='".$downloaddir."characters/".$dt['element_web_fat']."' title='Download Elemen Web Fat' target='_blank'><i class='icon-globe'></i> Element Fat <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_web_fat']) && $dt['material_web_fat']!="")
            {
                $download .=  "<a class='btn btn-sm btn-success' style='text-decoration:none;' href='".$downloaddir."materials/".$dt['material_web_fat']."' title='Download Materian Web Fat' target='_blank'><i class='icon-globe'></i> Material Fat <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_ios_thin']) && $dt['element_ios_thin']!="")
            {
                $download .=  "<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$downloaddir."characters/iOS/".$dt['element_ios_thin']."' title='Download Elemen iOS Thin' target='_blank'><i class='icon-apple'></i> Element Thin <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_ios_thin']) && $dt['material_ios_thin']!="")
            {
                $download .=  "<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$downloaddir."materials/iOS/".$dt['material_ios_thin']."' title='Download Materian iOS Thin' target='_blank'><i class='icon-apple'></i> Material Thin <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_ios_medium']) && $dt['element_ios_medium']!="")
            {
                $download .=  "<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$downloaddir."characters/iOS/".$dt['element_ios_medium']."' title='Download Elemen iOS Medium' target='_blank'><i class='icon-apple'></i> Element Medium <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_ios_medium']) && $dt['material_ios_medium']!="")
            {
                $download .=  "<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$downloaddir."materials/iOS/".$dt['material_ios_medium']."' title='Download Materian iOS Medium' target='_blank'><i class='icon-apple'></i> Material Medium <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_ios_fat']) && $dt['element_ios_fat']!="")
            {
                $download .=  "<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$downloaddir."characters/iOS/".$dt['element_ios_fat']."' title='Download Elemen iOS Fat' target='_blank'><i class='icon-apple'></i> Element Fat <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_ios_fat']) && $dt['material_ios_fat']!="")
            {
                $download .=  "<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$downloaddir."materials/iOS/".$dt['material_ios_fat']."' title='Download Materian iOS Fat' target='_blank'><i class='icon-apple'></i> Material Fat <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_android_thin']) && $dt['element_android_thin']!="")
            {
                $download .=  "<a class='btn btn-sm btn-midnightblue' style='text-decoration:none;' href='".$downloaddir."characters/Android/".$dt['element_android_thin']."' title='Download Elemen Android Thin' target='_blank'><i class='icon-android'></i> Element Thin <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_android_thin']) && $dt['material_android_thin']!="")
            {
                $download .=  "<a class='btn btn-sm btn-midnightblue' style='text-decoration:none;' href='".$downloaddir."materials/Android/".$dt['material_android_thin']."' title='Download Materian Android Thin' target='_blank'><i class='icon-android'></i> Material Thin <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_android_medium']) && $dt['element_android_medium']!="")
            {
                $download .=  "<a class='btn btn-sm btn-midnightblue' style='text-decoration:none;' href='".$downloaddir."characters/Android/".$dt['element_android_medium']."' title='Download Elemen Android Medium' target='_blank'><i class='icon-android'></i> Element Medium <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_android_medium']) && $dt['material_android_medium']!="")
            {
                $download .=  "<a class='btn btn-sm btn-midnightblue' style='text-decoration:none;' href='".$downloaddir."materials/Android/".$dt['material_android_medium']."' title='Download Materian Android Medium' target='_blank'><i class='icon-android'></i> Material Medium <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            if(!empty($dt['element_android_fat']) && $dt['element_android_fat']!="")
            {
                $download .=  "<a class='btn btn-sm btn-midnightblue' style='text-decoration:none;' href='".$downloaddir."characters/Android/".$dt['element_android_fat']."' title='Download Elemen Android Fat' target='_blank'><i class='icon-android'></i> Element Fat <i class='glyphicon glyphicon-asterisk'></i></a>";
            }
            if(!empty($dt['material_android_fat']) && $dt['material_android_fat']!="")
            {
                $download .=  "<a class='btn btn-sm btn-midnightblue' style='text-decoration:none;' href='".$downloaddir."materials/Android/".$dt['material_android_fat']."' title='Download Materian Android Fat' target='_blank'><i class='icon-android'></i> Material Fat <i class='glyphicon glyphicon-cutlery'></i></a>";
            }
            $download .=  "</div>";       
            $detail="";
            $delete=""; 
            $data_like="";
            $data_comments="";
            if($this->m_checking->actions("Avatar","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            if($this->m_checking->actions("Avatar","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Avatar with name ".$dt['name']." ?')","",'Delete',"delete.png","","linkdelete");
            } 
            if($this->m_checking->actions("Avatar","module6","View Likes",TRUE,FALSE,"home"))
            {
                $data_like=$this->template_icon->link_icon_notext("avatar/avataritems/datalike/".$dt['_id'],'Like ',"heart.png");
            }
            if($this->m_checking->actions("Avatar","module6","View Comments",TRUE,FALSE,"home"))
            {
                $data_comments=$this->template_icon->link_icon_notext("avatar/avataritems/datacomments/".$dt['_id'],'Comment',"comments.png");
            }
            $output['aaData'][] = array(
                $i,
                $childcode,
                $childname,
                $childtipe,
                $childgender,
                $childcategory,
                $childpayment,
                $brandname,
                $warna,
                $picture,
                $download,
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
            redirect('avatar/avatar/index'); 
        }
    }
    function cruid_avatar()
    {        
        $this->form_validation->set_rules('txtid','Key','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','File Image','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileelemenwebthin','Elemen Web Thin','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfilematerialwebthin','Material Web Thin','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileelemenwebmedium','Elemen Web Medium','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfilematerialwebmedium','Material Web Medium','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileelemenwebfat','Elemen Web Fat','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfilematerialwebfat','Material Web Fat','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfileelemeniosthin','Elemet iOS Thin','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfilematerialiosthin','Material iOS Thin','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfileelemeniosmedium','Elemet iOS Medium','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfilematerialiosmedium','Material iOS Medium','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfileelemeniosfat','Elemet iOS Fat','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfilematerialiosfat','Material iOS Fat','trim|htmlspecialchars|xss_clean');        
	$this->form_validation->set_rules('txtfileelemenandroidthin','Elemen Android Thin','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfilematerialandroidthin','Material Android Thin','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfileelemenandroidmedium','Elemen Android Medium','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfilematerialandroidmedium','Material Android Medium','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfileelemenandroidfat','Elemen Android Fat','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtfilematerialandroidfat','Material Android Fat','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('IDCode','Code','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','Avatar Item Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbgender','Gender','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbtipe','Avatar Body Part Type','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbcategory','Category','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbpayment','Payment','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcolor','Color','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttag','Tag','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('searctype','Search Type','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('searchcategory','Search Category','trim|htmlspecialchars|xss_clean');  
        $this->form_validation->set_rules('txtdesc','Description','trim|htmlspecialchars|xss_clean');  
        $this->form_validation->set_rules('txtharga','Price','trim|numeric|required|htmlspecialchars|xss_clean');
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
            $code = $this->input->post('IDCode',TRUE);
            $name = $this->input->post('name',TRUE);
            $gender = $this->input->post('cmbgender',TRUE);
            $tipe = $this->input->post('cmbtipe',TRUE);
            $category = $this->input->post('cmbcategory',TRUE);        
            $payment = $this->input->post('cmbpayment',TRUE);
            $color = $this->input->post('txtcolor',TRUE);
            $txtharga = $this->input->post('txtharga',TRUE);
            $txttag = $this->input->post('txttag',TRUE);
            $searctype = $this->input->post('searctype',TRUE);
            $searchcategory = $this->input->post('searchcategory',TRUE);            
            if($this->session->userdata('brand')=="")
            {
                $dtbrand = $_POST['brand'];
            }
            else
            {
                $dtbrand = $this->session->userdata('brand');
            }
            $txtfileimgname = $this->input->post('txtfileimgname',TRUE);
            $fileelemenwebthin = $this->input->post('txtfileelemenwebthin',TRUE);
            $filematerialwebthin = $this->input->post('txtfilematerialwebthin',TRUE);            
            $fileelemenwebmedium = $this->input->post('txtfileelemenwebmedium',TRUE);
            $filematerialwebmedium = $this->input->post('txtfilematerialwebmedium',TRUE);            
            $fileelemenwebfat = $this->input->post('txtfileelemenwebfat',TRUE);
            $filematerialwebfat = $this->input->post('txtfilematerialwebfat',TRUE);            
            $fileelemeniosthin = $this->input->post('txtfileelemeniosthin',TRUE);
            $filematerialiosthin = $this->input->post('txtfilematerialiosthin',TRUE);    
            $fileelemeniosmedium = $this->input->post('txtfileelemeniosmedium',TRUE);
            $filematerialiosmedium = $this->input->post('txtfilematerialiosmedium',TRUE);            
            $fileelemeniosfat = $this->input->post('txtfileelemeniosfat',TRUE);
            $filematerialiosfat = $this->input->post('txtfilematerialiosfat',TRUE);            
            $fileelemenandroidthin = $this->input->post('txtfileelemenandroidthin',TRUE);
            $filematerialandroidthin = $this->input->post('txtfilematerialandroidthin',TRUE);            
            $fileelemenandroidmedium = $this->input->post('txtfileelemenandroidmedium',TRUE);
            $filematerialandroidmedium = $this->input->post('txtfilematerialandroidmedium',TRUE);            
            $fileelemenandroidfat = $this->input->post('txtfileelemenandroidfat',TRUE);
            $filematerialandroidfat = $this->input->post('txtfilematerialandroidfat',TRUE);
            $txtdesc = $this->input->post('txtdesc',TRUE);
            $tanggalupdate=$this->mongo_db->time(strtotime(date("Y-m-d H:i:s")));
            if($code==="")
            {
                $code = "C".$this->tambahan_fungsi->global_get_random(5).$this->tambahan_fungsi->global_get_random(4);
            }
            $datatinsert=array(
                'code'=>$code,
                'name'  =>$name,
                'descriptions'  =>$txtdesc,
                'gender'  =>$gender,
                'category'  =>$category,
                'tipe'  =>$tipe,
                'payment'  =>$payment,
                'brand_id'  =>$dtbrand,
                'color'  =>$color,
                'tags'  =>$txttag,
                'price'  =>$txtharga,
                'search_type'  =>$searctype,
                'search_category'  =>$searchcategory,
                'element_web_thin'  =>$fileelemenwebthin,
                'material_web_thin'  =>$filematerialwebthin,    
                'element_web_medium'  =>$fileelemenwebmedium,
                'material_web_medium'  =>$filematerialwebmedium,
                'element_web_fat'  =>$fileelemenwebfat,
                'material_web_fat'  =>$filematerialwebfat,                
                'element_ios_thin'  =>$fileelemeniosthin,
                'material_ios_thin'  =>$filematerialiosthin,
                'element_ios_medium'  =>$fileelemeniosmedium,
                'material_ios_medium'  =>$filematerialiosmedium,
                'element_ios_fat'  =>$fileelemeniosfat,
                'material_ios_fat'  =>$filematerialiosfat,                
                'element_android_thin'  =>$fileelemenandroidthin,
                'material_android_thin'  =>$filematerialandroidthin,
                'element_android_medium'  =>$fileelemenandroidmedium,
                'material_android_medium'  =>$filematerialandroidmedium,
                'element_android_fat'  =>$fileelemenandroidfat,
                'material_android_fat'  =>$filematerialandroidfat,                
                'preview_image'  =>$txtfileimgname,
                'last_update'  =>$tanggalupdate,
            );
            if($id=='')
            {
                $this->m_checking->actions("Avatar","module6","Add",FALSE,TRUE,"home");                  
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Avatar");
                $idinsert=$this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Avatar Item",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Avatar","module6","Edit",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Avatar");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Avatar Item",$url,$user);
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
            redirect('avatar/avatar/index'); 
        }
    }
    function get_detail($id="")
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $output['message'] = "Fail to load data Avatar Item";
        $output['success'] = FALSE;        
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['_id'] = (!isset($tampung['_id'])?"":(string)$tampung['_id']);
            $output['code'] = (!isset($tampung['code'])?"":$tampung['code']); 
            $output['name'] = (!isset($tampung['name'])?"":$tampung['name']); 
            $output['descriptions'] = (!isset($tampung['descriptions'])?"":$tampung['descriptions']);
            $output['image'] = (!isset($tampung['preview_image'])?"":$tampung['preview_image']); 
            $output['gender'] = (!isset($tampung['gender'])?"":$tampung['gender']);  
            $output['size'] = (!isset($tampung['size'])?"":$tampung['size']);  
            $output['tipe'] = (!isset($tampung['tipe'])?"":$tampung['tipe']);  
            $output['payment'] = (!isset($tampung['payment'])?"":$tampung['payment']); 
            $output['color'] = (!isset($tampung['color'])?"":$tampung['color']); 
            $output['category'] = (!isset($tampung['category'])?"":$tampung['category']); 
            $output['brand_id'] = (!isset($tampung['brand_id'])?"":$tampung['brand_id']); 
            $output['tags'] = (!isset($tampung['tags'])?"":$tampung['tags']); 
            $output['price'] = (!isset($tampung['price'])?"":$tampung['price']); 
            $output['search_type'] = (!isset($tampung['search_type'])?"":$tampung['search_type']); 
            $output['search_category'] = (!isset($tampung['search_category'])?"":$tampung['search_category']);            
            $output['element_web_thin'] = (!isset($tampung['element_web_thin'])?"":$tampung['element_web_thin']);
            $output['material_web_thin'] = (!isset($tampung['material_web_thin'])?"":$tampung['material_web_thin']);
            $output['element_web_medium'] = (!isset($tampung['element_web_medium'])?"":$tampung['element_web_medium']);
            $output['material_web_medium'] = (!isset($tampung['material_web_medium'])?"":$tampung['material_web_medium']);
            $output['element_web_fat'] = (!isset($tampung['element_web_fat'])?"":$tampung['element_web_fat']);
            $output['material_web_fat'] = (!isset($tampung['material_web_fat'])?"":$tampung['material_web_fat']);            
            $output['element_ios_thin'] = (!isset($tampung['element_ios_thin'])?"":$tampung['element_ios_thin']);
            $output['material_ios_thin'] = (!isset($tampung['material_ios_thin'])?"":$tampung['material_ios_thin']);
            $output['element_ios_medium'] = (!isset($tampung['element_ios_medium'])?"":$tampung['element_ios_medium']);
            $output['material_ios_medium'] = (!isset($tampung['material_ios_medium'])?"":$tampung['material_ios_medium']);
            $output['element_ios_fat'] = (!isset($tampung['element_ios_fat'])?"":$tampung['element_ios_fat']);
            $output['material_ios_fat'] = (!isset($tampung['material_ios_fat'])?"":$tampung['material_ios_fat']);            
            $output['element_android_thin'] = (!isset($tampung['element_android_thin'])?"":$tampung['element_android_thin']);
            $output['material_android_thin'] = (!isset($tampung['material_android_thin'])?"":$tampung['material_android_thin']);            
            $output['element_android_medium'] = (!isset($tampung['element_android_medium'])?"":$tampung['element_android_medium']);
            $output['material_android_medium'] = (!isset($tampung['material_android_medium'])?"":$tampung['material_android_medium']);            
            $output['element_android_fat'] = (!isset($tampung['element_android_fat'])?"":$tampung['element_android_fat']);
            $output['material_android_fat'] = (!isset($tampung['material_android_fat'])?"":$tampung['material_android_fat']);
            $output['success'] = TRUE; 
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect("avatar/avatar/index"); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Avatar","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");        
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            @unlink($this->config->item('path_upload')."preview_images/".$data['preview_image']);
            @unlink($this->config->item('path_upload')."characters/".$data['element_web_thin']);
            @unlink($this->config->item('path_upload')."materials/".$data['material_web_thin']);
            @unlink($this->config->item('path_upload')."characters/".$data['element_web_medium']);
            @unlink($this->config->item('path_upload')."materials/".$data['material_web_medium']);
            @unlink($this->config->item('path_upload')."characters/".$data['element_web_fat']);
            @unlink($this->config->item('path_upload')."materials/".$data['material_web_fat']);
            @unlink($this->config->item('path_upload')."characters/iOS/".$data['element_ios_thin']);
            @unlink($this->config->item('path_upload')."materials/iOS/".$data['material_ios_thin']);
            @unlink($this->config->item('path_upload')."characters/iOS/".$data['element_ios_medium']);
            @unlink($this->config->item('path_upload')."materials/iOS/".$data['material_ios_medium']);
            @unlink($this->config->item('path_upload')."characters/iOS/".$data['element_ios_fat']);
            @unlink($this->config->item('path_upload')."materials/iOS/".$data['material_ios_fat']);
            @unlink($this->config->item('path_upload')."characters/Android/".$data['element_android_thin']);
            @unlink($this->config->item('path_upload')."materials/Android/".$data['material_android_thin']);
            @unlink($this->config->item('path_upload')."characters/Android/".$data['element_android_medium']);
            @unlink($this->config->item('path_upload')."materials/Android/".$data['material_android_medium']);
            @unlink($this->config->item('path_upload')."characters/Android/".$data['element_android_fat']);
            @unlink($this->config->item('path_upload')."materials/Android/".$data['material_android_fat']);
        }
        $cek=$this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Avatar Item",$url,$user);
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
            redirect('avatar/avatar/index'); 
        }
    }
    function prosesimport()
    {
        $this->m_checking->actions("Avatar","module6","Import",FALSE,TRUE,"home");
        $this->load->helper('file');
        $uploaddir = $this->config->item('path_upload');
        $config['upload_path'] = $uploaddir;
        $config['allowed_types'] = 'txt';
        $config['max_size']	= '10000';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';
        $config['max_filename']  = 0;
        $config['overwrite']  = FALSE;
        $config['encrypt_name']  = TRUE;
        $config['remove_spaces']  = TRUE;
        $this->load->library('upload', $config);
        if($_FILES['fileimportbrand']['name']!="")        
        {
            if (!$this->upload->do_upload('fileimportbrand'))
            {
                $data['warning'] = array('error' => $this->upload->display_errors());
            }
            else
            {
                $data['warning'] = array('upload_data' => $this->upload->data());
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare=  json_decode($string);
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Brand");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        if($_FILES['fileimportpayment']['name']!="")        
        {
            if (!$this->upload->do_upload('fileimportpayment'))
            {
                $data['warning'] = array('error' => $this->upload->display_errors());
            }
            else
            {
                $data['warning'] = array('upload_data' => $this->upload->data());
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare=  json_decode($string);
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Payment");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        if($_FILES['fileimportcategory']['name']!="")        
        {
            if (!$this->upload->do_upload('fileimportcategory'))
            {
                $data['warning'] = array('error' => $this->upload->display_errors());
            }
            else
            {
                $data['warning'] = array('upload_data' => $this->upload->data());
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare=  json_decode($string);
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Category");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        if($_FILES['fileimporttype']['name']!="")        
        {
            if (!$this->upload->do_upload('fileimporttype'))
            {
                $data['warning'] = array('error' => $this->upload->display_errors());
            }
            else
            {
                $data['warning'] = array('upload_data' => $this->upload->data());
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare=  json_decode($string);
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("AvatarBodyPart");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        if($_FILES['fileimportavatar']['name']!="")        
        {
            if (!$this->upload->do_upload('fileimportavatar'))
            {
                $data['warning'] = array('error' => $this->upload->display_errors());
            }
            else
            {
                $data['warning'] = array('upload_data' => $this->upload->data());
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare=  json_decode($string);
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Avatar");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        redirect("avatar/avatar/index");
    }
    function processexport()
    {
        $this->m_checking->actions("Avatar","module6","Export",FALSE,TRUE,"home");
        $this->load->library('zip');
        $uploaddir = $this->config->item('path_upload');
        $namafilecategory='avatar-category-'.date('Y-m-d').'.zip';
        //image Category
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Category");
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            if(isset($dt['image']))
            {
                $this->zip->read_file($uploaddir."preview_images/".$dt['image'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafilecategory);
        $this->zip->clear_data();
        //Avatar
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $data=$this->mongo_db->find(array(),0,0,array());
        $namafile_elemet_web='avatar-element-web'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['element']))
            {
                $this->zip->read_file($uploaddir."characters/".$dt['element'], TRUE);
            }  
        }
        $this->zip->archive($uploaddir."download/".$namafile_elemet_web);
        $this->zip->clear_data();
        $namafile_elemet_ios='avatar-element-ios'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {             
            if(isset($dt['element_ios']))
            {
                $this->zip->read_file($uploaddir."characters/iOS/".$dt['element_ios'], TRUE);
            } 
        }
        $this->zip->archive($uploaddir."download/".$namafile_elemet_ios);
        $this->zip->clear_data();
        $namafile_elemet_android='avatar-element-android'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {            
            if(isset($dt['element_android']))
            {
                $this->zip->read_file($uploaddir."characters/Android/".$dt['element_android'], TRUE);
            } 
        }
        $this->zip->archive($uploaddir."download/".$namafile_elemet_android);
        $this->zip->clear_data();
        $namafile_material_web='avatar-material-web'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['material']))
            {
                $this->zip->read_file($uploaddir."materials/".$dt['material'], TRUE);
            }   
        }
        $this->zip->archive($uploaddir."download/".$namafile_material_web);
        $this->zip->clear_data();
        $namafile_material_ios='avatar-material-ios'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['material_ios']))
            {
                $this->zip->read_file($uploaddir."materials/iOS/".$dt['material_ios'], TRUE);
            }    
        }
        $this->zip->archive($uploaddir."download/".$namafile_material_ios);
        $this->zip->clear_data();
        $namafile_material_android='avatar-material-android'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        { 
            if(isset($dt['material_android']))
            {
                $this->zip->read_file($uploaddir."materials/Android/".$dt['material_android'], TRUE);
            }    
        }
        $this->zip->archive($uploaddir."download/".$namafile_material_android);
        $this->zip->clear_data();
        $namafile_image='avatar-image'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        { 
            if(isset($dt['preview_image']))
            {
                $this->zip->read_file($uploaddir."preview_images/".$dt['preview_image'], TRUE);
            }    
        }
        $this->zip->archive($uploaddir."download/".$namafile_image);
        $this->zip->clear_data();
        //avatar body part type
        $namafile='avatar-'.date('Y-m-d').'.zip';
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("AvatarBodyPart");
        $isifile="";
        $isifilesqllite="create table if not exists AvatarBodyPart (id varchar(100) not null primary key, title varchar(100), name varchar(100), parent varchar(100));\r\n";
        $tempfile=array();
        $name="AvatarBodyPartType.txt";
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'title'=>isset($dt['title'])?$dt['title']:"",
                'name'=>isset($dt['name'])?$dt['name']:"",
                'parent'=>isset($dt['parent'])?$dt['parent']:"",
            ); 
            $isifilesqllite .="insert into AvatarBodyPart values('".$dt['_id']."','".(isset($dt['title'])?$dt['title']:"")."','".(isset($dt['name'])?$dt['name']:"")."','".(isset($dt['parent'])?$dt['parent']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data($name, $isifile);
        //Payment
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Payment");
        $isifile="";
        $tempfile=array();
        $name="payment.txt";
        $isifilesqllite .="create table if not exists payment (id varchar(100) not null primary key, name varchar(100));\r\n";
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'name'=>isset($dt['name'])?$dt['name']:"",
            );  
            $isifilesqllite .="insert into payment values('".$dt['_id']."','".(isset($dt['name'])?$dt['name']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data($name, $isifile);
        //Brand
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $isifile="";
        $tempfile=array();
        $name="brand.txt";
        $isifilesqllite .="create table if not exists brand (id varchar(100) not null primary key, name varchar(100),brand_id varchar(100));\r\n";
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'name'=>isset($dt['name'])?$dt['name']:"",
                'brand_id'=>isset($dt['brand_id'])?$dt['brand_id']:"",
            );       
            $isifilesqllite .="insert into brand values('".$dt['_id']."','".(isset($dt['name'])?$dt['name']:"")."','".(isset($dt['brand_id'])?$dt['brand_id']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data($name, $isifile);
        //Category
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Category");
        $isifile="";
        $tempfile=array();
        $name="category.txt";
        $isifilesqllite .="create table if not exists category (id varchar(100) not null primary key, name varchar(100),tipe varchar(100),image varchar(200));\r\n";
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'name'=>isset($dt['name'])?$dt['name']:"",
                'tipe'=>isset($dt['tipe'])?$dt['tipe']:"",
                'image'=>isset($dt['image'])?$dt['image']:"",
            );    
            $isifilesqllite .="insert into category values('".$dt['_id']."','".(isset($dt['name'])?$dt['name']:"")."','".(isset($dt['tipe'])?$dt['tipe']:"")."','".(isset($dt['image'])?$dt['image']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data($name, $isifile);
        //Avatar
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $isifile="";
        $tempfile=array();
        $isifilesqllite .="create table if not exists avatar (id varchar(100) not null primary key,code varchar(100),ame varchar(100),gender varchar(100),category varchar(100),tipe varchar(100),payment varchar(100),brand_id varchar(100),size varchar(100),color varchar(100),element varchar(100),material varchar(100),preview_image varchar(100));\r\n";
        $name="avatar.txt";
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'code'=>isset($dt['code'])?$dt['code']:"",
                'name'=>isset($dt['name'])?$dt['name']:"",
                'descriptions'=>isset($dt['descriptions'])?$dt['descriptions']:"",
                'gender'=>isset($dt['gender'])?$dt['gender']:"",
                'category'=>isset($dt['category'])?$dt['category']:"",
                'tipe'=>isset($dt['tipe'])?$dt['tipe']:"",
                'payment'=>isset($dt['payment'])?$dt['payment']:"",
                'brand_id'=>isset($dt['brand_id'])?$dt['brand_id']:"",
                'color'=>isset($dt['color'])?$dt['color']:"",
                'element_web_thin'=>isset($dt['element_web_thin'])?$dt['element_web_thin']:"",
                'material_web_thin'=>isset($dt['material_web_thin'])?$dt['material_web_thin']:"",
                'element_web_medium'=>isset($dt['element_web_medium'])?$dt['element_web_medium']:"",
                'material_web_medium'=>isset($dt['material_web_medium'])?$dt['material_web_medium']:"",
                'element_web_fat'=>isset($dt['element_web_fat'])?$dt['element_web_fat']:"",
                'material_web_fat'=>isset($dt['material_web_fat'])?$dt['material_web_fat']:"",
                'element_ios_thin'=>isset($dt['element_ios_thin'])?$dt['element_ios_thin']:"",
                'material_ios_thin'=>isset($dt['material_ios_thin'])?$dt['material_ios_thin']:"",
                'element_ios_medium'=>isset($dt['element_ios_medium'])?$dt['element_ios_medium']:"",
                'material_ios_medium'=>isset($dt['material_ios_medium'])?$dt['material_ios_medium']:"",
                'element_ios_fat'=>isset($dt['element_ios_fat'])?$dt['element_ios_fat']:"",
                'material_ios_fat'=>isset($dt['material_ios_fat'])?$dt['material_ios_fat']:"",
                'element_android_thin'=>isset($dt['element_android_thin'])?$dt['element_android_thin']:"",
                'material_android_thin'=>isset($dt['material_android_thin'])?$dt['material_android_thin']:"",
                'element_android_medium'=>isset($dt['element_android_medium'])?$dt['element_android_medium']:"",
                'material_android_medium'=>isset($dt['material_android_medium'])?$dt['material_android_medium']:"",
                'element_android_fat'=>isset($dt['element_android_fat'])?$dt['element_android_fat']:"",
                'material_android_fat'=>isset($dt['material_android_fat'])?$dt['material_android_fat']:"",
                'preview_image'=>isset($dt['preview_image'])?$dt['preview_image']:"",
                'tags'=>isset($dt['tags'])?$dt['tags']:"",
                'price'=>isset($dt['price'])?$dt['price']:"",
                'search_type'=>isset($dt['search_type'])?$dt['search_type']:"",
                'search_category'=>isset($dt['search_category'])?$dt['search_category']:"",
                'last_update'  =>isset($dt['last_update'])?$dt['last_update']:"",
            ); 
            $isifilesqllite .="insert into avatar values('".$dt['_id']."','".(isset($dt['code'])?$dt['code']:"")."','".(isset($dt['name'])?$dt['name']:"")."','".(isset($dt['gender'])?$dt['gender']:"")."','".(isset($dt['category'])?$dt['category']:"")."','".(isset($dt['tipe'])?$dt['tipe']:"")."','".(isset($dt['payment'])?$dt['payment']:"")."','".(isset($dt['brand_id'])?$dt['brand_id']:"")."','".(isset($dt['color'])?$dt['color']:"")."','".(isset($dt['element'])?$dt['element']:"")."','".(isset($dt['material'])?$dt['material']:"")."','".(isset($dt['preview_image'])?$dt['preview_image']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data($name, $isifile);
        $this->zip->add_data("sqllite.sql", $isifilesqllite);
        $this->zip->add_data("pathdownload-category.txt", $this->config->item('path_asset_img')."download/".$namafilecategory);
        $this->zip->add_data("pathdownload-element-web.txt", $this->config->item('path_asset_img')."download/".$namafile_elemet_web);
        $this->zip->add_data("pathdownload-element-ios.txt", $this->config->item('path_asset_img')."download/".$namafile_elemet_ios);
        $this->zip->add_data("pathdownload-element-android.txt", $this->config->item('path_asset_img')."download/".$namafile_elemet_android);
        $this->zip->add_data("pathdownload-material-web.txt", $this->config->item('path_asset_img')."download/".$namafile_material_web);
        $this->zip->add_data("pathdownload-material-ios.txt", $this->config->item('path_asset_img')."download/".$namafile_material_ios);
        $this->zip->add_data("pathdownload-material-android.txt", $this->config->item('path_asset_img')."download/".$namafile_material_android);
        $this->zip->add_data("pathdownload-image.txt", $this->config->item('path_asset_img')."download/".$namafile_image);
        $this->zip->download($namafile); 
    }
}
