<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    function uploader()
    {
        $output['message'] = "File is empty";
        $output['success'] = FALSE;
        $output['name'] = "";
        $fileimg ="";
        $uploaddir = $this->config->item('path_upload');
        if(isset($_FILES['fileimage']))
        {
            if($_FILES['fileimage']['name']!="")        
            {
                $config['upload_path'] = $uploaddir."preview_images/";
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '100000';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';
                $config['max_filename']  = 0;
                $config['overwrite']  = TRUE;
                $config['encrypt_name']  = FALSE;
                $config['remove_spaces']  = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('fileimage'))
                {
                    $output['message'] = $this->upload->display_errors("<p class='error'>","</p>");
                }
                else
                {
                    $output['message'] = "File success upload";
                    $hasil = $this->upload->data();
                    $output['name'] = $hasil['file_name'];
                    $output['success'] = TRUE;
                }
            }
        }
        echo json_encode( $output );
    }
    function generatecode($jml=10)
    {
        $output = array(
            "message" =>"Data Code is Generated",
            "success" =>TRUE,
            "data" =>$this->tambahan_fungsi->global_get_random((int)$jml),
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }
    function option_category($name="")
    {
        $output["message"] = "Data not found";
        $output["success"] = FALSE;
        $output["data"] = array();
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Category");
        $data=$this->mongo_db->find(array("tipe"=>$name),0,0,array("name"=>1));
        if($data)
        {
            $output["success"] = TRUE;
            $option = "<option value=''>&nbsp;</option>";
            foreach($data as $dt)
            {
               $option  .= "<option value='".$dt['name']."'>".$dt['name']."</option>";       
            }
            $output["data"] = $option;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }
    function uploaderwithpath($namafile="")
    {
        $output['message'] = "File is empty";
        $output['success'] = FALSE;
        $output['name'] = "";
        $fileimg ="";
        $folder = $this->input->post('folder',TRUE);
        $uploaddir = $this->config->item('path_upload');
        if(isset($_FILES[$namafile]))
        {
            if($_FILES[$namafile]['name']!="")        
            {
                $config['upload_path'] = $uploaddir.$folder;
                $config['allowed_types'] = '*';
                $config['max_size']	= '9000000';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';
                $config['max_filename']  = 0;
                $config['overwrite']  = TRUE;
                $config['encrypt_name']  = FALSE;
                $config['remove_spaces']  = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload($namafile))
                {
                    $output['message'] = $this->upload->display_errors("<p class='error'>","</p>");
                }
                else
                {
                    $output['message'] = "File success upload";
                    $hasil = $this->upload->data();
                    $output['name'] = $hasil['file_name'];
                    $output['success'] = TRUE;
                }
            }
        }
        echo json_encode( $output );
    }
    function get_list_avatar_bysearch()
    {
        $brand=$this->input->post('brand',TRUE);
        $tipe=$this->input->post('tipe',TRUE);
        $gender=$this->input->post('gender',TRUE);
        $datafilter=array(
            'payment'=>array(
                '$in'=>array('Unlock','Paid',)
                ),
        );
        if($brand!="")
        {
            $datafilter=  array_merge($datafilter,array(
                'brand_id'=>$brand
            ));
        }
        if($tipe!="")
        {
            $datafilter=  array_merge($datafilter,array(
                'tipe'=>$tipe
            ));
        }
        if($gender!="")
        {
            $datafilter=  array_merge($datafilter,array(
                'gender'=>$gender
            ));
        }
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $dataimg=$this->mongo_db->find($datafilter,0,0,array('name'=>1));
        $output['message'] = "<i class='error'>No Avatar found</i>";
        $output['success'] = FALSE;
        $output['data'] = array();
        if($dataimg)
        {
            foreach($dataimg as $result)
            {
                $output['data'][]=array(
                    '_id'=>(string)$result['_id'],
                    'name'=>$result['name'],
                    'preview_image'=>$result['preview_image'],
                );
            }
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }       
    function uploadercdn()
    {
        $urltarget=$this->config->item('server_upload_data');
        $imagedir = $this->input->post('imagedir',TRUE);
        $imagename = $this->input->post('imagename',TRUE);
        $copydir = $this->input->post('pathdir',TRUE);
        $dtpost=array(
            'extra_info' => '123456',
            'fileimage'=>'@'.realpath($imagedir),
            'alamatpath'=>$copydir,
            'email'=>'uda_rido@yahoo.com',
        );
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$urltarget);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dtpost);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));
	//,CURLOPT_SSL_VERIFYHOST => 1
	$result=curl_exec ($ch);
	curl_close ($ch);
	echo $result;
    }
}
