<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dialog extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('reporting','form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Dialogs","module5",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quest");
        $dataadd['listquest']=$this->mongo_db->find(array(),0,0,array('Description'=>1));
        $this->mongo_db->select_collection("Quiz");
        $dataadd['listquiz']=$this->mongo_db->find(array(),0,0,array('Description'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Dialog Story",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("dialog_view",$dataadd);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("DialogStory");
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
            $import="";
            $copy="";
            $detail="";
            $delete="";
            if($this->m_checking->actions("Dialogs","module5","Export",TRUE,FALSE,"home"))
            {
                $import=$this->template_icon->link_icon3("quest/dialog/export/".$dt['_id'],"Import","database_go.png","target=\"_blank\" class=\"linkdetail\" ");
            }
            if($this->m_checking->actions("Dialogs","module5","Duplicate",TRUE,FALSE,"home"))
            {
                $copy=$this->template_icon->detail_onclick("duplikat('".$dt['_id']."')","",'Create Duplicat',"application_side_contract.png","","linkdetail");
            }
            if($this->m_checking->actions("Dialogs","module5","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            }
            if($this->m_checking->actions("Dialogs","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Dialog with name ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                "<label><input type='checkbox' name='id_export[]' value='".(string)$dt['_id']."'/></label>",
                $dt['name'],
                $dt['description'],
                $import.$copy.$detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/dialog/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Dialogs","module5","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("DialogStory");
        $output['message'] = "Data not found";
        $output['success'] = FALSE;
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['success'] = TRUE;
            $output['_id'] = (string)$tampung['_id'];
            $output['name'] = $tampung['name'];
            $output['description'] = isset($tampung['description'])?$tampung['description']:"";
            $output['typedialog'] = isset($tampung['typedialog'])?$tampung['typedialog']:"";
            $output['dialogs'] = isset($tampung['dialogs'])?$tampung['dialogs']:array();
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/dialog/index'); 
        }
    }
    function duplicate()
    {
        $this->m_checking->actions("Dialogs","module5","Duplicate",FALSE,FALSE,"home");
        $this->form_validation->set_rules('txtidduplikat','ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('countduplikat','Count','trim|numeric|required|htmlspecialchars|xss_clean');
        $url = current_url();
        $user = $this->session->userdata('username');
        $output['message'] = "Fail duplicate data";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $kodeid = $this->input->post('txtidduplikat',TRUE);
            $jmlcount = $this->input->post('countduplikat',TRUE);
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("DialogStory");
            $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($kodeid)));
            if($tampung)
            {
                $temp = $tampung['name'];
                $return['description'] = isset($tampung['description'])?$tampung['description']:"";
                $return['typedialog'] = isset($tampung['typedialog'])?$tampung['typedialog']:"";
                $return['dialogs'] = isset($tampung['dialogs'])?$tampung['dialogs']:array();
                for($i=0;$i<(int)$jmlcount;$i++)
                {
                    $return['name'] = "Copy ".$temp."-".$i;
                    $this->mongo_db->update(array('_id'=>$this->mongo_db->mongoid()),array('$set'=>$return),array('upsert' => True));
                }
                $output['message'] = "<i class='success'>Success Duplicat data</i>"; 
                $output['success'] = TRUE;
            }
            $url = current_url();
            $user = $this->session->userdata('username');
            $this->m_user->tulis_log("Duplicate Dialog",$url,$user);
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/dialog/index'); 
        }
    }
    function cruid_dialog()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('description','Description','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('type','Type','trim|htmlspecialchars|xss_clean');  
        $url = current_url();
        $user = $this->session->userdata('username');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $name = $this->input->post('name',TRUE);
            $description = $this->input->post('description',TRUE);
            $type = $this->input->post('type',TRUE);
            $allvariable=array();
            if(isset($_POST['chiledid']))
            {
                for($i=0;$i<count($_POST['chiledid']);$i++)
                {
                    $optionchild=array();
                    $variablename=$_POST['chiledcount'][$i];
                    if(isset($_POST['optionschile'.$variablename]))
                    {
                        for($j=0;$j<count($_POST['optionschile'.$variablename]);$j++)
                        {
                            $pilihanoption=isset($_POST['optionschile'.$variablename][$j])?$_POST['optionschile'.$variablename][$j]:"";
                            $keteranganoption=isset($_POST['descriptionchile'.$variablename][$j])?$_POST['descriptionchile'.$variablename][$j]:"";
                            $nextidoption=isset($_POST['descriptionchilenextid'.$variablename][$j])?$_POST['descriptionchilenextid'.$variablename][$j]:"";
                            if($pilihanoption!="")
                            {
                                $optionchild[]=array(
                                    'option_type'=>$pilihanoption,
                                    'description'=>$keteranganoption,
                                    'next_id'=>$nextidoption,
                                );
                            }
                        }
                    }
                    $allvariable[]=array(
                            'id'=>$_POST['chiledid'][$i],
                            'description'=>$_POST['descriptionchild'][$i],
                            'options'=>$optionchild,
                    );    
                }
            }
            $datatinsert=array(
                'name'  =>$name,
                'description'  =>$description,
                'typedialog'  =>$type,
                'dialogs'=>$allvariable,
            );
            if($id=='')
            {
                $this->m_checking->actions("Dialogs","module5","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("DialogStory");
                $level_id=$this->mongo_db->insert($datatinsert);
                $output['message'] = "<i class='success'>New Data is added</i>";
                $output['success'] = TRUE;
                $this->m_user->tulis_log("Add Dialog",$url,$user);
            }
            else
            {
                $this->m_checking->actions("Dialogs","module5","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("DialogStory");
                $this->mongo_db->update(array('_id' => $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Dialog",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
                $output['success'] = TRUE;
            }           
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quest/dialog/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Dialogs","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("DialogStory");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Dialog",$url,$user);
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
            redirect('quest/dialog/index'); 
        }
    }
    function import()
    {
        $this->m_checking->actions("Dialogs","module5","Import Txt",FALSE,TRUE,"home");
        $this->load->helper('file');
        if($_FILES['txtfileimport']['name']!="")        
        {
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
            if (!$this->upload->do_upload('txtfileimport'))
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
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("DialogStory");
                foreach($dataprepare as $dt)
                {
                    $this->mongo_db->update(array('name'=>$dt->name),array('$set'=>$dt),array('upsert' => TRUE)); 
                }                
            }
        }
        redirect("quest/dialog/index");
    }
    function export($id="")
    {
        $this->m_checking->actions("Dialogs","module5","Export",FALSE,FALSE,"home");
        $this->load->helper('file');
        $this->load->helper('download');
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("DialogStory");
        $isifile="";
        $tempfile=array();
        $cari=array();
        if(isset($_GET['id_export']))
        {            
            for($i=0;$i<count($_GET['id_export']);$i++)
            {
                $cari[]=$this->mongo_db->mongoid($_GET['id_export'][$i]);
            }       
        }
        else 
        {
            $cari[]=$this->mongo_db->mongoid($id);
        }
        $data=$this->mongo_db->find(array('_id'=>array('$in'=>$cari)),0,0,array());            
        foreach($data as $dt)
        {
            unset($optiondialog);
            $optiondialog=array();
            if($dt['dialogs'])
            {
                foreach($dt['dialogs'] as $dt2)
                {
                    unset($optionchilddialog);
                    $optionchilddialog=array();
                    foreach($dt2['options'] as $dt3)
                    {
                        $optionchilddialog[]=array(
                            'option_type'=>isset($dt3['option_type'])?$dt3['option_type']:"",
                            'description'=>isset($dt3['description'])?$dt3['description']:"",
                            'next_id'=>isset($dt3['next_id'])?$dt3['next_id']:"",
                        );
                    }
                    $optiondialog[]=array(
                        'id'=>isset($dt2['id'])?$dt2['id']:"",
                        'description'=>isset($dt2['description'])?$dt2['description']:"",
                        'options'=>$optionchilddialog,
                    );
                }
            }
            $tempfile[]=array(
                'name'=>isset($dt['name'])?$dt['name']:"",
                'typedialog'=>isset($dt['typedialog'])?$dt['typedialog']:"",
                'description'=>isset($dt['description'])?$dt['description']:"",
                'dialogs'=>$optiondialog,
            );       
        }
        $uploaddir = $this->config->item('path_upload');
        $downloaddir = $this->config->item('path_asset_img');
        $isifile=  json_encode($tempfile);
        $namafile='dialog-export-'.date('Y-m-d');
        $this->reporting->header_txt($namafile);
        echo $isifile;
    }
    function excell()
    {	
        $this->m_checking->actions("Dialogs","module5","Import Exl",FALSE,TRUE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("DialogStory");
        $data=$this->mongo_db->find(array(),0,0,array());
        $namafile='dialog-'.date('Y-m-d');
        $this->reporting->header_xls($namafile);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>NO</td>";
        echo "<td>Name</td>";
        echo "<td>Type</td>";
        echo "<td>Description</td>";
        echo "<td>Dialogs</td>";
        echo "</tr>";
        $i=1;
        foreach($data as $dt)
        {
            echo "<tr>";
            echo "<td>".$i."</td>";
            echo "<td>".(isset($dt['name'])?$dt['name']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['typedialog'])?$dt['typedialog']:"&nbsp;")."</td>";
            echo "<td>".(isset($dt['description'])?$dt['description']:"&nbsp;")."</td>";
            echo "<td>";
            if($dt['dialogs'])
            {
                foreach($dt['dialogs'] as $dt2)
                {
                    echo "ID:".(isset($dt2['id'])?$dt2['id']:"&nbsp;")."<br />";
                    echo "Description:".(isset($dt2['description'])?$dt2['description']:"&nbsp;")."<br />";
                    echo "Options :<br />";
                    echo "<ol>";
                    foreach($dt2['options'] as $dt3)
                    {
                        echo "<li>";
                        echo "Type : ".(isset($dt3['option_type'])?$dt3['option_type']:"&nbsp;")."<br />";
                        echo "Description : ".(isset($dt3['description'])?$dt3['description']:"&nbsp;")."<br />";
                        echo "Next ID : ".(isset($dt3['next_id'])?$dt3['next_id']:"&nbsp;")."<br />";
                        echo "</li>";
                    }
                    echo "</ol>";
                    echo "<hr />";
                    echo "<br />";
                    echo "<br />";
                }
            }
            echo "</td>";
            echo "</tr>";
            $i++;           
        }  
        echo "</table>";
    }
}

