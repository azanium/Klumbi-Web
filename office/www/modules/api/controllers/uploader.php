<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Uploader extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : POST
     * API upload data
     * Parameter :
     * 1. nameuploader
     * 2. datafile(array)
     * Return JSON
     */
    function index($namafile="")
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
                $config['allowed_types'] = 'jpg|jpeg|gif|png|unity3d|zip';
                $config['max_size']	= '9000000';
                $config['max_width']  = '2048';
                $config['max_height']  = '1600';
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
                    $output['message'] = "File uploaded successfully";
                    $hasil = $this->upload->data();
                    $output['name'] = $hasil['file_name'];
                    $output['url'] = $this->config->item('path_asset_img').$folder.$hasil['file_name'];
                    $output['success'] = TRUE;
                }
            }
        }
        echo json_encode( $output );
    }
}
