<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Animation extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Animation","module6",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Payment");
        $isiform['payment']=$this->mongo_db->find(array(),0,0,array('name'=>1));
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
        );
        $this->template_admin->header_web(TRUE,"Animations",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("animation_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Animation");
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
            $keysearchdt="permission";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="gender";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="description";
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
        if(isset($_GET['cmbgenderview']))
        {
            if($_GET['cmbgenderview']!="")
            {
                $pencarian=  array_merge($pencarian,array('gender'=>$_GET['cmbgenderview']));
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
            $img="";
            $lokasifile="";
            $animation_file="<div class='btn-group'>";
            $uploaddir = $this->config->item('path_upload');
            $uploaddata = $this->config->item('path_asset_img');
            if(!empty($dt['preview_file']))
            {
               $img ="<a class='fancybox' href='".$uploaddata."preview_images/".$dt['preview_file']."'><img src='".$uploaddata."preview_images/".$dt['preview_file']."' class='img-thumbnail' style='max-width:75px; max-height:75px;'/></a>&nbsp;&nbsp;";
            }
            if(isset($dt['animation_file'])&& $dt['animation_file']!="")
            {
                $animation_file ="<a class='btn btn-sm btn-orange' style='text-decoration:none;' href='".$uploaddata."animations/web/".$dt['animation_file']."' title='Download Animation for Web' target='_blank'><i class='icon-globe'></i> ".$dt['animation_file']." <i class='icon-cloud-download'></i></a>";
            }
            if(isset($dt['animation_file_ios'])&& $dt['animation_file_ios']!="")
            {
                $animation_file .="<a class='btn btn-sm btn-green' style='text-decoration:none;' href='".$uploaddata."animations/iOS/".$dt['animation_file_ios']."' title='Download Animation for iOS' target='_blank'><i class='icon-apple'></i> ".$dt['animation_file_ios']." <i class='icon-cloud-download'></i></a>";
            }
            if(isset($dt['animation_file_android']) && $dt['animation_file_android']!="")
            {
                $animation_file .="<a class='btn btn-sm btn-magenta' style='text-decoration:none;' href='".$uploaddata."animations/Android/".$dt['animation_file_android']."' title='Download Animation Android' target='_blank'><i class='icon-android'></i> ".$dt['animation_file_android']." <i class='icon-cloud-download'></i></a>";
            }
            $permition=isset($dt['permission'])?$dt['permission']:"";            
            $fileimage=isset($dt['preview_file'])?$dt['preview_file']:"";
            $fileweb=isset($dt['animation_file'])?$dt['animation_file']:"";
            $fileios=isset($dt['animation_file_ios'])?$dt['animation_file_ios']:"";
            $fileandroid=isset($dt['animation_file_android'])?$dt['animation_file_android']:"";
            $detail="";
            $delete="";
            $animation_file .=  "</div>";
            if($this->m_checking->actions("Animation","module6","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['name']."','".$dt['description']."','".$dt['gender']."','".$permition."','".$fileimage."','".$fileweb."','".$fileios."','".$fileandroid."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Animation","module6","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Animation ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $permition,
                $dt['gender'],
                $dt['description'],
                $img.$animation_file,
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
            redirect('inventory/animation/index'); 
        }
    }
    function cruid_animation()
    {        
        $this->form_validation->set_rules('name','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('description','Description','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbgender','Gender','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbpayment','Payment','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileweb','Web','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileios','iOS','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtfileandroid','Android','trim|htmlspecialchars|xss_clean');
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
            $description = $this->input->post('description',TRUE);
            $kelamin = $this->input->post('cmbgender',TRUE);
            $payment = $this->input->post('cmbpayment',TRUE);            
            $fileimage = $this->input->post('txtfileimgname',TRUE);
            $fileweb = $this->input->post('txtfileweb',TRUE);
            $ios = $this->input->post('txtfileios',TRUE);
            $android = $this->input->post('txtfileandroid',TRUE);
            $datatinsert=array(
                'name'  =>$name,   
                'permission'  =>$payment, 
                'gender'  =>$kelamin, 
                "description"=>$description,
                'preview_file'  =>$fileimage,
                'animation_file'  =>$fileweb,
                'animation_file_ios'  =>$ios,
                'animation_file_android'  =>$android,
            );
            if($id=='')
            {
                $this->m_checking->actions("Animation","module6","Add",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Animation");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Animation",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Animation","module6","Edit",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Animation");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Animation",$url,$user);
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
            redirect('inventory/animation/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Animation","module6","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Animation");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            $uploaddir = $this->config->item('path_upload');
            @unlink($uploaddir."preview_images/".$data['preview_file']);
            @unlink($uploaddir."animations/web/".$data['animation_file']);
            @unlink($uploaddir."animations/iOS/".$data['animation_file_ios']);
            @unlink($uploaddir."animations/Android/".$data['animation_file_android']);
        }
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Animation",$url,$user);
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
            redirect('inventory/animation/index'); 
        }
    }
    function processimport()
    {
        $this->m_checking->actions("Animation","module6","Import",FALSE,TRUE,"home");
        $output['message'] = "";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
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
        if($_FILES['fileimport']['name']!="")        
        {
            if (!$this->upload->do_upload('fileimport'))
            {
                $output['message'] = $this->upload->display_errors();
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
                $this->mongo_db->select_collection("Animation");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        redirect("inventory/animation/index");
    }
    function processexport()
    {
        $this->m_checking->actions("Animation","module6","Export",FALSE,TRUE,"home");
        $this->load->library('zip');
        $uploaddir = $this->config->item('path_upload');
        $namafileweb='animation-web'.date('Y-m-d').'.zip';
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Animation");
        $data=$this->mongo_db->find(array(),0,0,array());
        foreach($data as $dt)
        {
            if(isset($dt['animation_file']))
            {
                $this->zip->read_file($uploaddir."animations/web/".$dt['animation_file'], TRUE);
            }                   
        }       
        $this->zip->archive($uploaddir."download/".$namafileweb);
        $this->zip->clear_data();
        $namafileios='animation-ios'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['animation_file_ios']))
            {
                $this->zip->read_file($uploaddir."animations/iOS/".$dt['animation_file_ios'], TRUE);
            }                   
        }       
        $this->zip->archive($uploaddir."download/".$namafileios);
        $this->zip->clear_data();
        $namafileandroid='animation-android'.date('Y-m-d').'.zip';
        foreach($data as $dt)
        {
            if(isset($dt['animation_file_android']))
            {
                $this->zip->read_file($uploaddir."animations/Android/".$dt['animation_file_android'], TRUE);
            }                   
        }       
        $this->zip->archive($uploaddir."download/".$namafileandroid);
        $this->zip->clear_data();
        $namafiledownload='animation-'.date('Y-m-d').'.zip';
        $isifile="";
        $tempfile=array();
        $isifilesqllite ="create table if not exists Animation (id varchar(100) not null primary key, name varchar(100), permission varchar(100), preview_file varchar(100), gender varchar(100), description varchar(100), animation_file_web varchar(100), animation_file_ios varchar(100), animation_file_android varchar(100));\r\n";
        foreach($data as $dt)
        {
            $tempfile[]=array(
                'name'=>isset($dt['name'])?$dt['name']:"",
                'permission'=>isset($dt['permission'])?$dt['permission']:"",
                'preview_file'=>isset($dt['preview_file'])?$dt['preview_file']:"",
                'gender'=>isset($dt['gender'])?$dt['gender']:"",
                'description'=>isset($dt['description'])?$dt['description']:"",
                'animation_file'=>isset($dt['animation_file'])?$dt['animation_file']:"",
                'animation_file_ios'=>isset($dt['animation_file_ios'])?$dt['animation_file_ios']:"",
                'animation_file_android'=>isset($dt['animation_file_android'])?$dt['animation_file_android']:"",
            );  
            $isifilesqllite .="insert into Animation values('".$dt['_id']."','".(isset($dt['name'])?$dt['name']:"")."','".(isset($dt['permission'])?$dt['permission']:"")."','".(isset($dt['preview_file'])?$dt['preview_file']:"")."','".(isset($dt['gender'])?$dt['gender']:"")."','".(isset($dt['description'])?$dt['description']:"")."','".(isset($dt['animation_file'])?$dt['animation_file']:"")."','".(isset($dt['animation_file_ios'])?$dt['animation_file_ios']:"")."','".(isset($dt['animation_file_ios'])?$dt['animation_file_ios']:"")."','".(isset($dt['animation_file_android'])?$dt['animation_file_android']:"")."');\r\n";
        }
        $isifile=  json_encode($tempfile);
        $this->zip->add_data("mongodb.txt", $isifile);        
        $this->zip->add_data("sqllite.sql", $isifilesqllite);
        $this->zip->add_data("pathdownload-web.txt", $this->config->item('path_asset_img')."download/".$namafileweb);
        $this->zip->add_data("pathdownload-ios.txt", $this->config->item('path_asset_img')."download/".$namafileios);
        $this->zip->add_data("pathdownload-android.txt", $this->config->item('path_asset_img')."download/".$namafileandroid);
        $this->zip->download($namafiledownload); 
    }
}