<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contest extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }    
    /*
     * Methode : GET
     * API Get Count Contest
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
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Contest");        
            $tempdata = $this->mongo_db->count2(array("valid"=>TRUE));
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
     * API Get One Detail Contest
     * Parameter :
     * 1. id
     * Return JSON
     */
    function get_one($id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Contest");        
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($id)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "_id" => (string)$tempdata['_id'],
                    "votestate" => $tempdata['votestate'],
                    "valid" => $tempdata['valid'],
                    "update" => date('Y-m-d H:i:s',$tempdata['update']->sec),
                    "end" => date('Y-m-d H:i:s',$tempdata['end']->sec),
                    "begin" => date('Y-m-d H:i:s',$tempdata['begin']->sec),
                    "tag" => $tempdata['tag'],
                    "state" => $tempdata['state'],
                    "rewards" => $tempdata['rewards'],
                    "name" => $tempdata['name'],
                    "order" => $tempdata['order'],
                    "info" => $tempdata['info'],
                    "gender" => $tempdata['gender'],
                    "description" => $tempdata['description'],
                    "brand_id" => $tempdata['brand_id'],
                    "code" => $tempdata['code'],
                    "count" => (int)$tempdata['count'],
                    "requireds" => $tempdata['requireds'],                    
                    "url_imageicon" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['imageicon'],
                    "imageicon" => $tempdata['imageicon'],
                    "url_imagebanner" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['imagebanner'],
                    "imagebanner" => $tempdata['imagebanner'],
                    "url_imagecontent" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['imagecontent'],
                    "imagecontent" => $tempdata['imagecontent'],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Contest
     * Parameter :
     * 1. page
     * 2. name (optional if data want to return by filtering by name)
     * 3. gender (optional if data want to return by filtering by gender)
     * 4. date (optional if data want to return by filtering by date format yyyy-mm-dd)
     * 5. brand (optional if data want to return by filtering by brand)
     * 6. tag (optional if data want to return by filtering by tag)
     * 7. type (optional if data want to return by filtering by type)
     * 8. category (optional if data want to return by filtering by category)
     * 9. keyordering[name,date] (optional defaul data is name)
     * 10. ordering [asc, desc]
     * Return JSON
     */
    function list_data($start=0)
    {  
        $name = isset($_GET['name'])?$_GET['name']:""; 
        $gender = isset($_GET['gender'])?$_GET['gender']:""; 
        $date = isset($_GET['date'])?$_GET['date']:""; 
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
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitcontest"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $filtering = array();
            $order = -1;
            $dtorder="update";
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
            if($gender!="")
            {
                $filtering = array_merge($filtering, array("gender"=>$gender));
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
                $filtering = array_merge($filtering, array("tag" => $filter));
            }
            if($type!="")
            {
                $sSearch=(string) trim($type);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
                $filtering = array_merge($filtering, array("typesearch" => $filter));
            }
            if($category!="")
            {
                $sSearch=(string) trim($category);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
                $filtering = array_merge($filtering, array("categorysearch" => $filter));
            }
            if($date!="")
            {
                $filtering = array_merge($filtering, 
                        array(
                            "begin" => array('$gte'=>$date),
                            "end" => array('$lte'=> $this->mongo_db->time(strtotime(date('Y-m-d H:i:s'))) ),
                            )
                        );
            }
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Contest"); 
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
                        "votestate" => $dt['votestate'],
                        "valid" => $dt['valid'],
                        "update" => date('Y-m-d H:i:s',$dt['update']->sec),
                        "end" => date('Y-m-d H:i:s',$dt['end']->sec),
                        "begin" => date('Y-m-d H:i:s',$dt['begin']->sec),
                        "tag" => $dt['tag'],
                        "state" => $dt['state'],
                        "rewards" => $dt['rewards'],
                        "name" => $dt['name'],
                        "order" => $dt['order'],
                        "info" => $dt['info'],
                        "gender" => $dt['gender'],
                        "description" => $dt['description'],
                        "brand_id" => $dt['brand_id'],
                        "code" => $dt['code'],
                        "count" => (int)$dt['count'],
                        "requireds" => $dt['requireds'],                    
                        "url_imageicon" => $this->config->item('path_asset_img') . "preview_images/" . $dt['imageicon'],
                        "imageicon" => $dt['imageicon'],
                        "url_imagebanner" => $this->config->item('path_asset_img') . "preview_images/" . $dt['imagebanner'],
                        "imagebanner" => $dt['imagebanner'],
                        "url_imagecontent" => $this->config->item('path_asset_img') . "preview_images/" . $dt['imagecontent'],
                        "imagecontent" => $dt['imagecontent'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}