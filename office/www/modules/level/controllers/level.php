<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Level extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Level","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $isibrand = $this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentCategory");
        $isicategory = $this->mongo_db->find(array(),0,0,array('name'=>1));
        $isiform['listbrand']=$isibrand;
        $isiform['listcategory']=$isicategory;
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css",            
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
            base_url()."resources/plugin/form-multiselect/js/jquery.multi-select.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
            base_url()."resources/plugin/fancyBox-master/source/jquery.fancybox.pack.js?v=2.1.5",
        );
        $this->template_admin->header_web(TRUE,"Level",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("listdata_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Level");
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
        else
        {
            if(isset($_GET['brandviews']))
            {
                if($_GET['brandviews']!="")
                {
                    $pencarian=  array_merge($pencarian,array('brand_id'=>$_GET['brandviews']));
                }            
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
            $img = "&nbsp;";
            if(!empty($dt['preview_file']))
            {
               $img = "<a class='fancybox' href='".$this->config->item('path_asset_img')."level/web/".$dt['directory']."/".$dt['preview_file']."'><img src='".$this->config->item('path_asset_img')."level/web/".$dt['directory']."/".$dt['preview_file']."' style='max-width:75px; max-height:75px;' class='img-thumbnail'/></a>";
            }
            $download = "<p><a class='btn btn-sm btn-sky' style='text-decoration:none;' href='".$this->config->item('path_asset_img')."level/web/".$dt['directory']."/".$dt['source_file']."' target='_blank'><i class='icon-download-alt'></i> Level Package</a></p>";
            if(!empty($dt['skybox_file']))
            {
                $download .=  "<p><a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$this->config->item('path_asset_img')."level/web/".$dt['directory']."/".$dt['skybox_file']."' target='_blank'><i class='icon-download-alt'></i> Skybox</a></p>";
            }
            if(!empty($dt['audio_file']))
            {
                $download .= "<p><a class='btn btn-sm btn-magenta' style='text-decoration:none;' href='".$this->config->item('path_asset_img')."level/web/".$dt['directory']."/".$dt['audio_file']."' target='_blank'><i class='icon-download-alt'></i> Audio</a></p>";
            }
            $detail="";
            $delete="";
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
            if($this->m_checking->actions("Level","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Level ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            } 
            if($this->m_checking->actions("Level","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("lihatdetail('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $brandname,
                isset($dt['category'])?$dt['category']:"",
                $img,
                $download,
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
            redirect('level/index'); 
        }
    }
    function add_new()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');  
        $this->form_validation->set_rules('lvlname','Level Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcategory','Category','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttag','Tags','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtserver','Server Address','trim|htmlspecialchars|valid_ip');
        $this->form_validation->set_rules('txtport','Server PORT','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtchanel','Number of Channels','trim|numeric|htmlspecialchars|xss_clean');
	$this->form_validation->set_rules('txtccu','Max CCU per Channel','trim|numeric|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtwordsizex','World Size X','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtwordsizey','World Size Y','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtintareax','Interest Area X','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtintareay','Interest Area Y','trim|numeric|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Level","module6","Add",FALSE,TRUE,"home");
            $uploaddir = $this->config->item('path_upload');
            $extractfile = $this->tambahan_fungsi->global_get_random(5).substr(md5(date('y-m-d H:i:s').time()), 0, 10);
            if(!is_dir($uploaddir."level/".$extractfile))
            {
                mkdir($uploaddir."level/web/".$extractfile);
                mkdir($uploaddir."level/Android/".$extractfile);
                mkdir($uploaddir."level/iOS/".$extractfile);
            }
            $lvlname = $this->input->post('lvlname',TRUE);
            if($this->session->userdata('brand')=="")
            {
                $brand = $_POST['brand'];
            }
            else
            {
                $brand = $this->session->userdata('brand');
            }
            $category = $this->input->post('txtcategory',TRUE);
            $txttag = $this->input->post('txttag',TRUE);
            $txtserver = $this->input->post('txtserver',TRUE);
            $txtport = $this->input->post('txtport',TRUE);        
            $txtchanel = $this->input->post('txtchanel',TRUE);
            $txtccu = $this->input->post('txtccu',TRUE);
            $txtwordsizex = $this->input->post('txtwordsizex',TRUE);
            $txtwordsizey = $this->input->post('txtwordsizey',TRUE);
            $txtintareax = $this->input->post('txtintareax',TRUE);
            $txtintareay = $this->input->post('txtintareay',TRUE);
            $filelvl=$_FILES['filelvl']['name'];
            $filelvl_ios=$_FILES['filelvl_ios']['name'];
            $filelvl_android=$_FILES['filelvl_android']['name'];
            $fileskybox=$_FILES['fileskybox']['name'];
            $fileskybox_ios=$_FILES['fileskybox_ios']['name'];
            $fileskybox_android=$_FILES['fileskybox_android']['name'];
            $fileaudio =$_FILES['fileaudio']['name'];
            $fileaudio_ios =$_FILES['fileaudio_ios']['name'];
            $fileaudio_android =$_FILES['fileaudio_android']['name'];
            $fileimg ="";  
            $pathtemp="";
            if($_FILES['filelvl']['name']!="")        
            {
                if (@move_uploaded_file($_FILES['filelvl']['tmp_name'], $uploaddir."level/web/".$_FILES['filelvl']['name']))
                {
                    $zip = new ZipArchive;
                    if ($zip->open($uploaddir."level/web/".$_FILES['filelvl']['name']) === TRUE) 
                    {
                        $zip->extractTo($uploaddir."level/web/".$extractfile);
                        $zip->close();
                        rename($uploaddir."level/web/".$_FILES['filelvl']['name'], $uploaddir."level/web/".$extractfile."/"."unzip_".$_FILES['filelvl']['name']);
                        $pathtemp=$uploaddir."level/web/".$extractfile;
                    }                    
                }
            }
            if($_FILES['filelvl_ios']['name']!="")        
            {
                if (@move_uploaded_file($_FILES['filelvl_ios']['tmp_name'], $uploaddir."level/iOS/".$_FILES['filelvl_ios']['name']))
                {
                    $zip = new ZipArchive;
                    if ($zip->open($uploaddir."level/iOS/".$_FILES['filelvl_ios']['name']) === TRUE) 
                    {
                        $zip->extractTo($uploaddir."level/iOS/".$extractfile);
                        $zip->close();
                        rename($uploaddir."level/iOS/".$_FILES['filelvl_ios']['name'], $uploaddir."level/iOS/".$extractfile."/"."unzip_".$_FILES['filelvl_ios']['name']);
                    }                    
                }
            }
            if($_FILES['filelvl_android']['name']!="")        
            {
                if (@move_uploaded_file($_FILES['filelvl_android']['tmp_name'], $uploaddir."level/Android/".$_FILES['filelvl_android']['name']))
                {
                    $zip = new ZipArchive;
                    if ($zip->open($uploaddir."level/Android/".$_FILES['filelvl_android']['name']) === TRUE) 
                    {
                        $zip->extractTo($uploaddir."level/Android/".$extractfile);
                        $zip->close();
                        rename($uploaddir."level/Android/".$_FILES['filelvl_android']['name'], $uploaddir."level/Android/".$extractfile."/"."unzip_".$_FILES['filelvl_android']['name']);
                    }                    
                }
            }
            if($_FILES['fileskybox']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileskybox']['tmp_name'], $uploaddir."level/web/".$extractfile."/".$_FILES['fileskybox']['name']);
            }
            if($_FILES['fileskybox_ios']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileskybox_ios']['tmp_name'], $uploaddir."level/iOS/".$extractfile."/".$_FILES['fileskybox_ios']['name']);
            }
            if($_FILES['fileskybox_android']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileskybox_android']['tmp_name'], $uploaddir."level/Android/".$extractfile."/".$_FILES['fileskybox_android']['name']);
            }
            if($_FILES['fileaudio']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileaudio']['tmp_name'], $uploaddir."level/web/".$extractfile."/".$_FILES['fileaudio']['name']);
            }
            if($_FILES['fileaudio_ios']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileaudio_ios']['tmp_name'], $uploaddir."level/iOS/".$extractfile."/".$_FILES['fileaudio_ios']['name']);
            }
            if($_FILES['fileaudio_android']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileaudio_android']['tmp_name'], $uploaddir."level/Android/".$extractfile."/".$_FILES['fileaudio_android']['name']);
            }
            if($_FILES['fileimg']['name']!="")        
            {
                $config['upload_path'] = $uploaddir."level/web/".$extractfile."/";
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '10000';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';
                $config['max_filename']  = 0;
                $config['overwrite']  = FALSE;
                $config['encrypt_name']  = TRUE;
                $config['remove_spaces']  = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('fileimg'))
                {
                    $output['message'] = array('error' => $this->upload->display_errors());
                }
                else
                {
                    $output['message'] = array('upload_data' => $this->upload->data());
                    $hasil = $this->upload->data();
                    $fileimg = $hasil['file_name'];
                }
            }
            if(file_exists($pathtemp . '/level.ini'))
            {
                $ini_array = parse_ini_file($pathtemp . '/level.ini', true);
                $lightmaps = NULL;
                if(is_array($ini_array) && count($ini_array))
                {
                    foreach($ini_array as $key => $val)
                    {
                        if($key == 'rendersettings')
                        {
                            $fogActive = $ini_array[$key]['fogActive'];
                            $fogColor = $ini_array[$key]['fogColor'];
                            $fogColor_expl = explode(',', $fogColor);
                            $fogColor_r = $fogColor_expl[0];
                            $fogColor_g = $fogColor_expl[1];
                            $fogColor_b = $fogColor_expl[2];
                            $fogColor_a = $fogColor_expl[3];
                            $fogDensity = $ini_array[$key]['fogDensity'];
                            $fogStartDistance = $ini_array[$key]['fogStartDistance'];
                            $fogEndDistance = $ini_array[$key]['fogEndDistance'];
                            $fogMode = $ini_array[$key]['fogMode'];
                            unset($ini_array[$key]);
                        } 
                        else if($key == 'lightmaps')
                        {
                            foreach($ini_array[$key] as $key_ => $val_)
                            {
                                if(substr($key_, 0, 5) == 'near_' || substr($key_, 0, 4) == 'far_')
                                {
                                    if(trim($val_) != '')
                                    {
                                        $ini_array[$key][$key_] = $val_;
                                    }
                                }
                            }
                            $lightmaps = $ini_array[$key];
                            unset($ini_array[$key]);
                        } 
                        else 
                        {
                            $asset_file = $ini_array[$key]['objectName'] . '.unity3d';
                            $asset_file = str_replace('//', '/', $asset_file);
                            $ini_array[$key]['asset_file'] = $asset_file;
                            $position_xyz = $ini_array[$key]['position'];
                            $rotation_xyz = $ini_array[$key]['rotation'];
                            $position_xyz_expl = explode(',', $position_xyz);
                            $position_x = $position_xyz_expl[0];
                            $position_y = $position_xyz_expl[1];
                            $position_z = $position_xyz_expl[2];
                            $ini_array[$key]['position_x'] = $position_x;
                            $ini_array[$key]['position_y'] = $position_y;
                            $ini_array[$key]['position_z'] = $position_z;
                            $rotation_xyz_expl = explode(',', $rotation_xyz);
                            $rotation_x = $rotation_xyz_expl[0];
                            $rotation_y = $rotation_xyz_expl[1];
                            $rotation_z = $rotation_xyz_expl[2];
                            $ini_array[$key]['rotation_x'] = $rotation_x;
                            $ini_array[$key]['rotation_y'] = $rotation_y;
                            $ini_array[$key]['rotation_z'] = $rotation_z;
                            $lightmapTilingOffset_xyzw = $ini_array[$key]['lightmapTilingOffset'];
                            $lightmapTilingOffset_xyzw_expl = explode(',', $lightmapTilingOffset_xyzw);
                            $lightmapTilingOffset_x = $lightmapTilingOffset_xyzw_expl[0];
                            $lightmapTilingOffset_y = $lightmapTilingOffset_xyzw_expl[1];
                            $lightmapTilingOffset_z = $lightmapTilingOffset_xyzw_expl[2];
                            $lightmapTilingOffset_w = $lightmapTilingOffset_xyzw_expl[3];
                            $ini_array[$key]['lightmapTilingOffset_x'] = $lightmapTilingOffset_x;
                            $ini_array[$key]['lightmapTilingOffset_y'] = $lightmapTilingOffset_y;
                            $ini_array[$key]['lightmapTilingOffset_z'] = $lightmapTilingOffset_z;
                            $ini_array[$key]['lightmapTilingOffset_w'] = $lightmapTilingOffset_w;
                        }
                     }
                 }
            }
            $datatinsert=array(
                'name'          =>$lvlname,
                'assets'        => $ini_array,
                'lightmaps'	=> $lightmaps,
                'tags'          =>$txttag,
                'category'          =>$category,
                'server_ip'     =>$txtserver,
                'brand_id'      =>$brand,
                'server_port'   =>$txtport,
                'channel_number'=>$txtchanel,
                'max_ccu_per_channel'=>$txtccu,
                'world_size_x'  =>$txtwordsizex,
                'world_size_y'  =>$txtwordsizey,
                'interest_area_x'=>$txtintareax,
                'interest_area_y'=>$txtintareay,
                'preview_file'   => $fileimg,
                'skybox_file'    => $fileskybox,
                'skybox_file_ios'    => $fileskybox_ios,
                'skybox_file_android'    => $fileskybox_android,
                'audio_file'     => $fileaudio,
                'audio_file_ios'     => $fileaudio_ios,
                'audio_file_android'     => $fileaudio_android,
                'source_file'	 => "unzip_".$filelvl,
                'source_file_ios'	 => "unzip_".$filelvl_ios,
                'source_file_android'	 => "unzip_".$filelvl_android,
                'directory'	 => $extractfile,
                'fogActive'	 => (bool)$fogActive,
                'fogColor'	 => (string)$fogColor,
                'fogColor_r'	 => (float)$fogColor_r,
                'fogColor_g'	 => (float)$fogColor_g,
                'fogColor_b'	 => (float)$fogColor_b,
                'fogColor_a'	 => (float)$fogColor_a,
                'fogDensity'	 => (float)$fogDensity,
                'fogStartDistance'=> (float)$fogStartDistance,
                'fogEndDistance' => (float)$fogEndDistance,
                'fogMode'	 => (string)$fogMode
            );
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Level");
            $this->mongo_db->remove(array('name' => $lvlname));
            $level_id=$this->mongo_db->insert($datatinsert);
            $this->mongo_db->update($datatinsert, array_merge($datatinsert, array('lilo_id' => (string)$level_id)), array("multiple" => false));
            $url = current_url();
            $user = $this->session->userdata('username');
            $this->m_user->tulis_log("Add Level",$url,$user);
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('level/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Level","module6","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentCategory");
        $isicategory = $this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");
        $isibrand = $this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("Level");
        $level_detail = $this->mongo_db->findOne(array('lilo_id' => $id));
        $isiform['isiform']=array(
            'txtid'=>'',
            'lvlname'=>'',
            'filelvl'=>'',
            'filelvl_ios'=>'',
            'filelvl_android'=>'',
            'fileskybox'=>'',
            'fileskybox_ios'=>'',
            'fileskybox_android'=>'',
            'fileaudio'=>'',
            'fileaudio_ios'=>'',
            'fileaudio_android'=>'',
            'fileimg'=>'',
            'brand_id'=>$isibrand,
            'selected_brand'=>'',
            'category_id'=>$isicategory,
            'selected_category'=>'',
            'txttag'=>'',
            'txtserver'=>'',
            'txtport'=>'',
            'txtchanel'=>'',
            'txtccu'=>'',
            'txtwordsizex'=>'1000',
            'txtwordsizey'=>'1000',
            'txtintareax'=>'20',
            'txtintareay'=>'20',
        );
        $isiform['skybox_file']='';
        $isiform['audio_file']='';
        $isiform['assets']='';
        $isiform['directory']='';
        $isiform['lightmaps']=array();        
        $isiform['fogActive']='';
        $isiform['fogColor']='';
        $isiform['fogDensity']='';
        $isiform['fogStartDistance']='';
        $isiform['fogEndDistance']='';
        $isiform['fogMode']='';
        if($level_detail)
        {
           $tempfiledt=(isset($level_detail['source_file'])?$level_detail['source_file']:"");
           $tempfileskyp=(isset($level_detail['skybox_file'])?$level_detail['skybox_file']:"");
           $tempfileskyp_ios=(isset($level_detail['skybox_file_ios'])?$level_detail['skybox_file_ios']:"");
           $tempfileskyp_android=(isset($level_detail['skybox_file_android'])?$level_detail['skybox_file_android']:"");
           $tempaudiofile=(isset($level_detail['audio_file'])?$level_detail['audio_file']:"");
           $tempaudiofile_ios=(isset($level_detail['audio_file_ios'])?$level_detail['audio_file_ios']:"");
           $tempaudiofile_android=(isset($level_detail['audio_file_android'])?$level_detail['audio_file_android']:"");
           $tempimgfile=(isset($level_detail['preview_file'])?$level_detail['preview_file']:"");
           $directoryname=isset($level_detail['directory'])?$level_detail['directory']:"";
           $filedt=($tempfiledt)?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/web/".$directoryname."/".$tempfiledt,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempfiledt:"";
           $fileskyp=($tempfileskyp!="")?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/web/".$directoryname."/".$tempfileskyp,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempfileskyp:"";
           $fileskyp_ios=($tempfileskyp_ios!="")?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/iOS/".$directoryname."/".$tempfileskyp_ios,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempfileskyp_ios:"";
           $fileskyp_android=($tempfileskyp_android!="")?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/Android/".$directoryname."/".$tempfileskyp_android,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempfileskyp_android:"";
           $audiofile=($tempaudiofile!="")?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/web/".$directoryname."/".$tempaudiofile,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempaudiofile:"";
           $audiofile_ios=($tempaudiofile_ios!="")?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/iOS/".$directoryname."/".$tempaudiofile_ios,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempaudiofile_ios:"";
           $audiofile_android=($tempaudiofile_android!="")?$this->template_icon->detail(FALSE,$this->config->item('path_asset_img')."level/Android/".$directoryname."/".$tempaudiofile_android,'Download File',"application_put.png","","linkdelete"," target='_blank'")."&nbsp;&nbsp;".$tempaudiofile_android:"";
           $imgfile=($tempimgfile!="")?"<a class='fancybox' href='".$this->config->item('path_asset_img')."level/web/".$directoryname."/".$tempimgfile."'><img src='".base_url()."resources/image/icon/camera_link.png' alt='lihat' /></a>&nbsp;&nbsp;".$tempimgfile:"";
           $brand_id=  isset($level_detail['brand_id'])?$level_detail['brand_id']:"";
           $category_id=  isset($level_detail['category'])?$level_detail['category']:"";
           $isiform['isiform']=array(
                'txtid'=>$level_detail['_id'],
                'lvlname'=>$level_detail['name'],
                'filelvl'=>$filedt,
                'filelvl_ios'=>'',
                'filelvl_android'=>'',
                'fileskybox'=>$fileskyp,
                'fileskybox_ios'=>$fileskyp_ios,
                'fileskybox_android'=>$fileskyp_android,
                'fileaudio'=>$audiofile,
                'fileaudio_ios'=>$audiofile_ios,
                'fileaudio_android'=>$audiofile_android,
                'fileimg'=>$imgfile,
                'brand_id'=>$isibrand,
                'selected_brand'=>$brand_id,
                'category_id'=>$isicategory,
                'selected_category'=>$category_id,
                'txttag'=>$level_detail['tags'],
                'txtserver'=>$level_detail['server_ip'],
                'txtport'=>$level_detail['server_port'],
                'txtchanel'=>$level_detail['channel_number'],
                'txtccu'=>$level_detail['max_ccu_per_channel'],
                'txtwordsizex'=>$level_detail['world_size_x'],
                'txtwordsizey'=>$level_detail['world_size_y'],
                'txtintareax'=>$level_detail['interest_area_x'],
                'txtintareay'=>$level_detail['interest_area_y'],
            );
           $isiform['_id']=$level_detail['_id'];
           $isiform['assets']=$level_detail['assets'];
           $isiform['directory']=$level_detail['directory'];
           $isiform['lightmaps']=$level_detail['lightmaps'];
           $isiform['fogActive']=$level_detail['fogActive'];
           $isiform['fogColor']=$level_detail['fogColor'];
           $isiform['fogDensity']=$level_detail['fogDensity'];
           $isiform['fogStartDistance']=$level_detail['fogStartDistance'];
           $isiform['fogEndDistance']=$level_detail['fogEndDistance'];
           $isiform['fogMode']=$level_detail['fogMode'];
           if(isset($level_detail['skybox_file']))
           {
		$file_name_expl = explode('/', $level_detail['skybox_file']);
		$c = count($file_name_expl) - 1;
		$level_detail['skybox_file_originalname'] = $file_name_expl[$c];
                $isiform['skybox_file']=$level_detail['skybox_file_originalname'];
           }
           if(isset($level_detail['audio_file']))
           {
		$file_name_expl = explode('/', $level_detail['audio_file']);
		$c = count($file_name_expl) - 1;
		$level_detail['audio_file_originalname'] = $file_name_expl[$c];
                $isiform['audio_file']=$level_detail['audio_file_originalname'];
           }
        }
        $this->load->view("listdata_detail_view",$isiform);
    }
    function update()
    {
        $this->form_validation->set_rules('lvlname','Level Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttag','Tags','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcategory','Category','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtserver','Server Address','trim|htmlspecialchars|valid_ip');
        $this->form_validation->set_rules('txtport','Server PORT','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtchanel','Number of Channels','trim|numeric|htmlspecialchars|xss_clean');
	$this->form_validation->set_rules('txtccu','Max CCU per Channel','trim|numeric|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txtwordsizex','World Size X','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtwordsizey','World Size Y','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtintareax','Interest Area X','trim|numeric|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtintareay','Interest Area Y','trim|numeric|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Level","module6","Edit",FALSE,TRUE,"home");
            $txtid = $this->input->post('txtid',TRUE);
            if($this->session->userdata('brand')=="")
            {
                $brand = $_POST['brand'];
            }
            else
            {
                $brand = $this->session->userdata('brand');
            }
            $category = $this->input->post('txtcategory',TRUE);
            $lvlname = $this->input->post('lvlname',TRUE);
            $txttag = $this->input->post('txttag',TRUE);
            $txtserver = $this->input->post('txtserver',TRUE);
            $txtport = $this->input->post('txtport',TRUE);        
            $txtchanel = $this->input->post('txtchanel',TRUE);
            $txtccu = $this->input->post('txtccu',TRUE);
            $txtwordsizex = $this->input->post('txtwordsizex',TRUE);
            $txtwordsizey = $this->input->post('txtwordsizey',TRUE);
            $txtintareax = $this->input->post('txtintareax',TRUE);
            $txtintareay = $this->input->post('txtintareay',TRUE);
            $fileskybox="";
            $fileskybox_ios="";
            $fileskybox_android="";            
            $fileaudio ="";
            $fileaudio_ios ="";
            $fileaudio_android ="";
            $fileimg =""; 
            $uploaddir = $this->config->item('path_upload');
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Level");
            $level_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($txtid)));
            if($_FILES['fileskybox']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileskybox']['tmp_name'], $uploaddir."level/web/".$level_detail['directory']."/".$_FILES['fileskybox']['name']);
                $fileskybox=$_FILES['fileskybox']['name'];
            }
            if($_FILES['fileskybox_ios']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileskybox_ios']['tmp_name'], $uploaddir."level/iOS/".$level_detail['directory']."/".$_FILES['fileskybox_ios']['name']);
                $fileskybox_ios=$_FILES['fileskybox_ios']['name'];
            }
            if($_FILES['fileskybox_android']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileskybox_android']['tmp_name'], $uploaddir."level/Android/".$level_detail['directory']."/".$_FILES['fileskybox_android']['name']);
                $fileskybox_android=$_FILES['fileskybox_android']['name'];
            }
            if($_FILES['fileaudio']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileaudio']['tmp_name'], $uploaddir."level/web/".$level_detail['directory']."/".$_FILES['fileaudio']['name']);
                $fileaudio =$_FILES['fileaudio']['name'];
            }
            if($_FILES['fileaudio_ios']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileaudio_ios']['tmp_name'], $uploaddir."level/iOS/".$level_detail['directory']."/".$_FILES['fileaudio_ios']['name']);
                $fileaudio_ios =$_FILES['fileaudio_ios']['name'];
            }
            if($_FILES['fileaudio_android']['name']!="")        
            {
                @move_uploaded_file($_FILES['fileaudio_android']['tmp_name'], $uploaddir."level/Android/".$level_detail['directory']."/".$_FILES['fileaudio_android']['name']);
                $fileaudio_android =$_FILES['fileaudio_android']['name'];
            }
            if($_FILES['fileimg']['name']!="")        
            {
                $config['upload_path'] = $uploaddir."level/web/".$level_detail['directory']."/";
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '10000';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';
                $config['max_filename']  = 0;
                $config['overwrite']  = FALSE;
                $config['encrypt_name']  = TRUE;
                $config['remove_spaces']  = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('fileimg'))
                {
                    $output['message'] = array('error' => $this->upload->display_errors());
                }
                else
                {
                    $output['message'] = array('upload_data' => $this->upload->data());
                    $hasil = $this->upload->data();
                    $fileimg = $hasil['file_name'];
                }
            }
            $datatinsert=array(
                'name'          =>$lvlname,
                'brand_id'     =>$brand,
                'category'     =>$category,
                'tags'          =>$txttag,
                'server_ip'     =>$txtserver,
                'server_port'   =>$txtport,
                'channel_number'=>$txtchanel,
                'max_ccu_per_channel'=>$txtccu,
                'world_size_x'  =>$txtwordsizex,
                'world_size_y'  =>$txtwordsizey,
                'interest_area_x'=>$txtintareax,
                'interest_area_y'=>$txtintareay,
            );
            if($fileskybox !="")
            {
                $tambahanskype['skybox_file']=$fileskybox;
                $datatinsert=array_merge($datatinsert, $tambahanskype);
            }
            if($fileskybox_ios !="")
            {
                $tambahanskype['skybox_file_ios']=$fileskybox_ios;
                $datatinsert=array_merge($datatinsert, $tambahanskype);
            }
            if($fileskybox_android !="")
            {
                $tambahanskype['skybox_file_android']=$fileskybox_android;
                $datatinsert=array_merge($datatinsert, $tambahanskype);
            }
            if($fileaudio !="")
            {
                $tambahanaudio['audio_file']=$fileaudio;
                $datatinsert=array_merge($datatinsert, $tambahanaudio);
            }
            if($fileaudio_ios !="")
            {
                $tambahanaudio['audio_file_ios']=$fileaudio_ios;
                $datatinsert=array_merge($datatinsert, $tambahanaudio);
            }
            if($fileaudio_android !="")
            {
                $tambahanaudio['audio_file_android']=$fileaudio_android;
                $datatinsert=array_merge($datatinsert, $tambahanaudio);
            }
            if($fileimg !="")
            {
                $tambahanimg=array('preview_file'=>$fileimg);
                $datatinsert=array_merge($datatinsert, $tambahanimg);
            }
            $this->mongo_db->update_set(array('_id' => $this->mongo_db->mongoid($txtid)),$datatinsert);        
            $url = current_url();
            $user = $this->session->userdata('username');
            $this->m_user->tulis_log("Update Level",$url,$user);
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('level/index'); 
        }
    }
    function removelist($id="",$objectid="")
    {
        $this->m_checking->actions("Level","module6","Delete",FALSE,TRUE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Level");
        $deletewhere=array(
            'assets.'.$objectid=>1,
        );
        $this->mongo_db->update_unset(array('_id' => $this->mongo_db->mongoid($id)),$deletewhere);
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Level Detail",$url,$user);
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
            redirect('level/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Level","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Level");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Level",$url,$user);
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
            redirect('level/index'); 
        }
    }
    function import()
    {
        $this->m_checking->actions("Level","module6","Import",FALSE,TRUE,"home");
        $this->load->helper('file');
        $output['message'] = "File is empty";
        $output['success'] = FALSE;
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
        if($_FILES['txtfileimport']['name']!="")        
        {
            if (!$this->upload->do_upload('txtfileimport'))
            {
                $output['message'] = $this->upload->display_errors("<p class='error'>","</p>");
            }
            else
            {
                $output['message'] = "File success upload";
                $output['success'] = TRUE;
                $hasil = $this->upload->data();
                $fileimg = $hasil['file_name'];
                $string = read_file($uploaddir.$fileimg);
                @unlink($uploaddir.$fileimg);
                $dataprepare=array();
                $dataprepare =  json_decode($string);
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Level");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        echo json_encode( $output );
    }
    function export()
    {
        $this->m_checking->actions("Level","module6","Export",FALSE,TRUE,"level/index");
        $this->load->library('zip');
        $uploaddir = $this->config->item('path_upload');        
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Level");
        $data=$this->mongo_db->find(array(),0,0,array());
        $namafileimage='level-image-'.date('Y-m-d').'.zip';
        $directoryname="";
        foreach($data as $dt)
        {
            if(isset($dt['preview_file']))
            {
                $this->zip->read_file($uploaddir."level/web/".$dt['directory']."/".$dt['preview_file'], TRUE);
            }
            $directoryname=$dt['directory'];
        }
        $this->zip->archive($uploaddir."download/".$namafileimage);
        $this->zip->clear_data();
        $namafile_audio_web='level-audio-web'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['audio_file']))
            {
                $this->zip->read_file($uploaddir."level/web/".$dt['directory']."/".$dt['audio_file'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafile_audio_web);
        $this->zip->clear_data();
        $namafile_audio_ios='level-audio-ios'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['audio_file_ios']))
            {
                $this->zip->read_file($uploaddir."level/iOS/".$dt['directory']."/".$dt['audio_file_ios'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafile_audio_ios);
        $this->zip->clear_data();
        $namafile_audio_android='level-audio-android'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['audio_file_android']))
            {
                $this->zip->read_file($uploaddir."level/Android/".$dt['directory']."/".$dt['audio_file_android'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafile_audio_android);
        $this->zip->clear_data();
        $namafile_skybox_web='level-skybox-web'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['skybox_file']))
            {
                $this->zip->read_file($uploaddir."level/web/".$dt['directory']."/".$dt['skybox_file'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafile_skybox_web);
        $this->zip->clear_data();
        $namafile_skybox_ios='level-skybox-ios'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['skybox_file_ios']))
            {
                $this->zip->read_file($uploaddir."level/iOS/".$dt['directory']."/".$dt['skybox_file_ios'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafile_skybox_ios);
        $this->zip->clear_data();
        $namafile_skybox_android='level-skybox-android'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['skybox_file_android']))
            {
                $this->zip->read_file($uploaddir."level/Android/".$dt['directory']."/".$dt['skybox_file_android'], TRUE);
            }                   
        }
        $this->zip->archive($uploaddir."download/".$namafile_skybox_android);
        $this->zip->clear_data();
        $namafile_download='level-'.date('Y-m-d H:i:s').'.zip';
        $isifile="";
        $tempfile=array();
        $isifilesqllite ="";
        $fileweb="";
        $fileios="";
        $fileandroid="";        
        foreach($data as $dt)
        {
            if(isset($dt['source_file']))
            {
                $fileweb .=$this->config->item('path_asset_img')."level/web/".$dt['source_file']."\r\n";
            } 
            if(isset($dt['source_file_ios']))
            {
                $fileios .=$this->config->item('path_asset_img')."level/iOS/".$dt['source_file_ios']."\r\n";
            }
            if(isset($dt['source_file_android']))
            {
                $fileandroid .=$this->config->item('path_asset_img')."level/Android/".$dt['source_file_android']."\r\n";
            }
            $tempfile[]=array(
                'name'=>isset($dt['name'])?$dt['name']:"",
                'assets'=>isset($dt['assets'])?$dt['assets']:"",
                'lightmaps'=>isset($dt['lightmaps'])?$dt['lightmaps']:"",
                'tags'=>isset($dt['tags'])?$dt['tags']:"",
                'server_ip'=>isset($dt['server_ip'])?$dt['server_ip']:"",
                'brand_id'=>isset($dt['brand_id'])?$dt['brand_id']:"",
                'category'=>isset($dt['category'])?$dt['category']:"",
                'server_port'=>isset($dt['server_port'])?$dt['server_port']:"",
                'channel_number'=>isset($dt['channel_number'])?$dt['channel_number']:"",
                'max_ccu_per_channel'=>isset($dt['max_ccu_per_channel'])?$dt['max_ccu_per_channel']:"",
                'world_size_x'=>isset($dt['world_size_x'])?$dt['world_size_x']:"",
                'world_size_y'=>isset($dt['world_size_y'])?$dt['world_size_y']:"",
                'interest_area_x'=>isset($dt['interest_area_x'])?$dt['interest_area_x']:"",
                'interest_area_y'=>isset($dt['interest_area_y'])?$dt['interest_area_y']:"",
                'preview_file'=>isset($dt['preview_file'])?$dt['preview_file']:"",
                'skybox_file'=>isset($dt['skybox_file'])?$dt['skybox_file']:"",
                'skybox_file_ios'=>isset($dt['skybox_file_ios'])?$dt['skybox_file_ios']:"",
                'skybox_file_android'=>isset($dt['skybox_file_android'])?$dt['skybox_file_android']:"",
                'audio_file'=>isset($dt['audio_file'])?$dt['audio_file']:"",
                'audio_file_ios'=>isset($dt['audio_file_ios'])?$dt['audio_file_ios']:"",
                'audio_file_android'=>isset($dt['audio_file_android'])?$dt['audio_file_android']:"",
                'source_file'=>isset($dt['source_file'])?$dt['source_file']:"",
                'source_file_ios'=>isset($dt['source_file_ios'])?$dt['source_file_ios']:"",
                'source_file_android'=>isset($dt['source_file_android'])?$dt['source_file_android']:"",
                'directory'=>isset($dt['directory'])?$dt['directory']:"",
                'fogActive'=>isset($dt['fogActive'])?$dt['fogActive']:"",
                'fogColor'=>isset($dt['fogColor'])?$dt['fogColor']:"",
                'fogColor_r'=>isset($dt['fogColor_r'])?$dt['fogColor_r']:"",
                'fogColor_g'=>isset($dt['fogColor_g'])?$dt['fogColor_g']:"",
                'fogColor_b'=>isset($dt['fogColor_b'])?$dt['fogColor_b']:"",
                'fogColor_a'=>isset($dt['fogColor_a'])?$dt['fogColor_a']:"",
                'fogDensity'=>isset($dt['fogDensity'])?$dt['fogDensity']:"",
                'fogStartDistance'=>isset($dt['fogStartDistance'])?$dt['fogStartDistance']:"",
                'fogEndDistance'=>isset($dt['fogEndDistance'])?$dt['fogEndDistance']:"",
                'fogMode'=>isset($dt['fogMode'])?$dt['fogMode']:"",                
            );    
//            $isifilesqllite .="insert into avatar values('".$dt['_id']."','".(isset($dt['code'])?$dt['code']:"")."','".(isset($dt['name'])?$dt['name']:"")."','".(isset($dt['gender'])?$dt['gender']:"")."','".(isset($dt['category'])?$dt['category']:"")."','".(isset($dt['tipe'])?$dt['tipe']:"")."','".(isset($dt['payment'])?$dt['payment']:"")."','".(isset($dt['brand_id'])?$dt['brand_id']:"")."','".(isset($dt['size'])?$dt['size']:"")."','".(isset($dt['color'])?$dt['color']:"")."','".(isset($dt['element'])?$dt['element']:"")."','".(isset($dt['material'])?$dt['material']:"")."','".(isset($dt['preview_image'])?$dt['preview_image']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data("level.txt", $isifile);
        $this->zip->add_data("sqllite.sql", $isifilesqllite);
        $this->zip->add_data("pathdownload-image.txt", $this->config->item('path_asset_img')."download/".$namafileimage);
        $this->zip->add_data("pathdownload-audio-web.txt", $this->config->item('path_asset_img')."download/".$namafile_audio_web);
        $this->zip->add_data("pathdownload-audio-ios.txt", $this->config->item('path_asset_img')."download/".$namafile_audio_ios);
        $this->zip->add_data("pathdownload-audio-android.txt", $this->config->item('path_asset_img')."download/".$namafile_audio_android);
        $this->zip->add_data("pathdownload-skybox-web.txt", $this->config->item('path_asset_img')."download/".$namafile_skybox_web);
        $this->zip->add_data("pathdownload-skybox-ios.txt", $this->config->item('path_asset_img')."download/".$namafile_skybox_ios);
        $this->zip->add_data("pathdownload-skybox-android.txt", $this->config->item('path_asset_img')."download/".$namafile_skybox_android);
        $this->zip->add_data("pathdownload-file-web.txt", $fileweb);
        $this->zip->add_data("pathdownload-file-ios.txt", $fileios);
        $this->zip->add_data("pathdownload-file-android.txt", $fileandroid);
        $this->zip->add_dir($directoryname);
        $this->zip->download($namafile_download); 
    }
}
