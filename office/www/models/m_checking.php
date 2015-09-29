<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_checking extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
    function module($menu="",$modulename="",$button=FALSE,$redirect=FALSE,$location="home")
    {
        $result=FALSE;
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("trMenu");
        $filtersearch=array(
            "Module"=>$modulename,
            "Menu"=>$menu,
            "Group"=>$this->session->userdata('group'),
        );
        $checkdatamodule=$this->mongo_db->findOne($filtersearch);
        if($checkdatamodule)
        {
            if((bool)$checkdatamodule['IsActive']==TRUE)
            {
                $result=TRUE;
            }
        }
        if($result==FALSE)
        {
            $databack = "<i class='error'>Sorry, You don't have access to use this page.</i>";
            if($button)
            {
                return $result;
            }
            else
            {
                if(IS_AJAX)
                {
                   echo json_encode(array(
                            'message'=>$databack,
                            "success"=>FALSE,
                        ));
                }
                else
                {
                    if($redirect)
                    {
                        redirect($location); 
                    }
                    else
                    {
                        echo json_encode(array(
                            'message'=>$databack,
                            "success"=>FALSE,
                        ));
                    }
                }
            }
            exit;
        }
        return $result;
    }
    function actions($menu="",$modulename="",$action="",$button=FALSE,$redirect=FALSE,$location="home")
    {
        $result=FALSE;
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("trActions");
        $filtersearch=array(
            "Module"=>$modulename,
            "Menu"=>$menu,
            "Actions"=>  $action,
            "Group"=>$this->session->userdata('group'),
        );
        $checkdatamodule=$this->mongo_db->findOne($filtersearch);
        if($checkdatamodule)
        {
            if((bool)$checkdatamodule['IsActive']==TRUE)
            {
                $result=TRUE;
            }
        }
        if($result==FALSE)
        {
            $databack = "<i class='error'>Sorry, You don't have access to use this module.</i>";
            if($button)
            {
                return FALSE;
            }
            else
            {
                if(IS_AJAX)
                {
                   echo json_encode(array(
                       'message'=>$databack,
                       "success"=>FALSE,
                   ));
                }
                else
                {
                    if($redirect)
                    {
                        redirect($location); 
                    }
                    else
                    {
                        echo $databack;
                    }
                }
            }
            exit;
        }
        return $result;
    }
}