<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Browse extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    function list_data_mix()
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
            $keysearchdt="bodytype";
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
            $title=(!isset($dt['name'])?"":$dt['name']);
            $gender=(!isset($dt['gender'])?"":$dt['gender']);
            $size=(!isset($dt['bodytype'])?"":$dt['bodytype']);
            $img="";
            if(!empty($dt['picture']))
            {
               $img ="<a class='fancybox' href='".$this->config->item('path_asset_img')."preview_images/".$dt['picture']."'><img src='".$this->config->item('path_asset_img')."preview_images/".$dt['picture']."' class='img-thumbnail' style='max-width:75px; max-height:75px;'/></a>";
            }
            $detail=$this->template_icon->detail_onclick("getdatamix('".(string)$dt['_id']."')","",'Set Value',"bullet_go.png","","linkdetail");
            $output['aaData'][] = array(
                $i,
                $title,
                $gender,
                $size,
                $img,
                $detail,
            );
            $i++;           
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
    function get_detail_mix($id="")
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("AvatarMix");
        $output['message'] = "Fail to load data mix";
        $output['success'] = FALSE;        
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['_id'] = (!isset($tampung['_id'])?"":(string)$tampung['_id']);  
            $output['name'] = (!isset($tampung['name'])?"":$tampung['name']);  
            $output['gender'] = (!isset($tampung['gender'])?"":$tampung['gender']);  
            $output['size'] = (!isset($tampung['bodytype'])?"":$tampung['bodytype']);  
            $output['description'] = (!isset($tampung['description'])?"":$tampung['description']); 
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
    function get_detail_avataritem($id="")
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $output['message'] = "Fail to load data avatar";
        $output['success'] = FALSE;        
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $output['_id'] = (!isset($tampung['_id'])?"":(string)$tampung['_id']);  
            $output['name'] = (!isset($tampung['name'])?"":$tampung['name']);  
            $output['gender'] = (!isset($tampung['gender'])?"":$tampung['gender']);   
            $output['tipe'] = (!isset($tampung['tipe'])?"":$tampung['tipe']);  
            $output['payment'] = (!isset($tampung['payment'])?"":$tampung['payment']); 
            $output['color'] = (!isset($tampung['color'])?"":$tampung['color']); 
            $output['code'] = (!isset($tampung['code'])?"":$tampung['code']); 
            $output['category'] = (!isset($tampung['category'])?"":$tampung['category']); 
            $output['brand_id'] = (!isset($tampung['brand_id'])?"":$tampung['brand_id']); 
            $this->mongo_db->select_collection("AvatarBodyPart");
            $temp_type = $this->mongo_db->findOne(array('name' => $output['tipe']));
            $nmtype = "";
            if($temp_type)
            {
                $nmtype = $temp_type['title'];
            }  
            $output['val_type'] = $nmtype;
            $output['element'] = str_replace(".unity3d", "",(!isset($tampung['element'])?"":$tampung['element']));
            $output['material'] = str_replace(".unity3d", "",(!isset($tampung['material'])?"":$tampung['material']));
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
}