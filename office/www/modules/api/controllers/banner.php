<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banner extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }    
    /*
     * Methode : GET
     * API Get Count Banner
     * Parameter :
     * Return JSON
     */
    function count()
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Banner");        
            $tempdata = $this->mongo_db->count2(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get One Detail Banner
     * Parameter :
     * 1. ID
     * Return JSON
     */
    function get_one($ID="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Banner");        
            $tempdata = $this->mongo_db->findOne(array("ID"=>$ID));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "_id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "ID" => $tempdata['ID'],
                    "tags" => $tempdata['tags'],
                    "Descriptions" => $tempdata['Descriptions'],
                    "textcolor" => $tempdata['textcolor'],
                    "type" => $tempdata['type'],
                    "date" => date('Y-m-d H:i:s',$tempdata['date']->sec),
                    "dataValue" => $tempdata['dataValue'],
                    "url_picture" => "string(url)",
                    "picture" => "string",
                    "brand_id" =>  "string",
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Banner
     * Parameter :
     * 1. page
     * 2. name (optional if data want to return by filtering by name)
     * 3. brand (optional if data want to return by filtering by brand)
     * 4. tag (optional if data want to return by filtering by tag)
     * 5. type (optional if data want to return by filtering by type)
     * 6. category (optional if data want to return by filtering by category)
     * 7. keyordering[name,date] (optional defaul data is name)
     * 8. ordering [asc, desc]
     * Return JSON
     */
    function list_data($start=0)
    {  
        $name = isset($_GET['name'])?$_GET['name']:""; 
        $brand = isset($_GET['brand'])?$_GET['brand']:""; 
        $tag = isset($_GET['tag'])?$_GET['tag']:""; 
        $type = isset($_GET['type'])?$_GET['type']:""; 
        $category = isset($_GET['category'])?$_GET['category']:""; 
        $keyordering = isset($_GET['keyordering'])?$_GET['keyordering']:""; 
        $ordering = isset($_GET['ordering'])?$_GET['ordering']:"asc";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitbanner"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $filtering = array();
            $order = -1;
            $dtorder="date";
            if($ordering==="" || $ordering==="asc")
            {
                $order = 1;
            }
            if($keyordering==="name")
            {
                $dtorder = "name";
            }
            if($name!="")
            {
                $sSearch=(string) trim($name);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
                $filtering = array(
                    "name" => $filter,
                );
            }
            if($brand!="")
            {
                $filtering = array_merge($filtering, array("brand_id"=>$brand));
            }
            if($tag!="")
            {
                $sSearch=(string) trim($tag);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
                $filtering = array_merge($filtering, array("tags" => $filter));
            }
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Banner");
            $tempdata = $this->mongo_db->find($filtering,(int)$start,(int)$limit,array($dtorder=>$order));
            $output['count'] = (int)$this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "_id" => (string)$dt['_id'],
                        "name" => $dt['name'],
                        "ID" => $dt['ID'],
                        "tags" => $dt['tags'],
                        "Descriptions" => $dt['Descriptions'],
                        "textcolor" => $dt['textcolor'],
                        "type" => $dt['type'],
                        "date" => date('Y-m-d H:i:s',$dt['date']->sec),
                        "dataValue" => $dt['dataValue'],
                        "picture" => $dt['picture'],
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $dt['picture'],
                        "brand_id" =>  $dt['brand_id']
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}