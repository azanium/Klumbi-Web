<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_avatar extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();     
    }
    function default_item($filtering=array())
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        $cekdatatemp = $this->mongo_db->findOne($filtering);
        if($cekdatatemp)
        {
            return array(
                "_id"=>(string)$cekdatatemp["_id"],                
                'element_web_thin' => $cekdatatemp["element_web_thin"],
                'material_web_thin' => $cekdatatemp["material_web_thin"],
                'element_web_medium' => $cekdatatemp["element_web_medium"],
                'material_web_medium' => $cekdatatemp["material_web_medium"],
                'element_web_fat' => $cekdatatemp["element_web_fat"],
                'material_web_fat' => $cekdatatemp["material_web_fat"],               
                'element_ios_thin' => $cekdatatemp["element_ios_thin"],
                'material_ios_thin' => $cekdatatemp["material_ios_thin"],
                'element_ios_medium' => $cekdatatemp["element_ios_medium"],
                'material_ios_medium' => $cekdatatemp["material_ios_medium"],
                'element_ios_fat' => $cekdatatemp["element_ios_fat"],
                'material_ios_fat' => $cekdatatemp["material_ios_fat"],               
                'element_android_thin' => $cekdatatemp["element_android_thin"],
                'material_android_thin' => $cekdatatemp["material_android_thin"],
                'element_android_medium' => $cekdatatemp["element_android_medium"],
                'material_android_medium' => $cekdatatemp["material_android_medium"],
                'element_android_fat' => $cekdatatemp["element_android_fat"],
                'material_android_fat' => $cekdatatemp["material_android_fat"],               
                'preview_image' => $cekdatatemp["preview_image"],
            );
        }
        else
        {
            return array(
                "_id"=>"",
                'element_web_thin' => "",
                'material_web_thin' => "",
                'element_web_medium' => "",
                'material_web_medium' => "",
                'element_web_fat' => "",
                'material_web_fat' => "",             
                'element_ios_thin' => "",
                'material_ios_thin' => "",
                'element_ios_medium' => "",
                'material_ios_medium' => "",
                'element_ios_fat' => "",
                'material_ios_fat' => "",              
                'element_android_thin' => "",
                'material_android_thin' => "",
                'element_android_medium' => "",
                'material_android_medium' => "",
                'element_android_fat' => "",
                'material_android_fat' => "",              
                'preview_image' => "",
            );
        }
    }
    function avatarconfig($avatardtconfig,$size = "medium",$tipedata = "android")
    {
        $configdatadb = array();
        $configava = array();
        $configava2 = array();
        $subconfigava = array();
        $subconfigava2 = array();
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Avatar");
        foreach($avatardtconfig as $dttempconf=>$newobj)
        {
            if($newobj['tipe'] === "Skin")
            {
                $configdatadb['skin'] = $newobj['color'];
                $configava[] = array('tipe' => "Skin", "color" => $newobj['color']);
                $configava2[] = array('tipe' => "Skin", "color" => $newobj['color']);
            }
            else if($newobj['tipe'] === "EyeBrows" || $newobj['tipe'] === "Eyes" || $newobj['tipe'] === "Lip")
            {
                $configdatadb[$newobj['tipe']] = $newobj['id'];
                $id_ava_item = isset($newobj['id'])?$newobj['id']:"";
                if($id_ava_item!="")
                {                    
                    $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id_ava_item)));
                    if($avaitem_tamp)
                    {
                        $tempsubdt = $this->data_unity_desc($avaitem_tamp,$newobj['tipe'],"medium",$tipedata);
                        $subconfigava[$tempsubdt['tipe']]= $tempsubdt["material"];  
                        $subconfigava["id_".$newobj['tipe']]= $newobj['id'];
                        $subconfigava2[$tempsubdt['tipe']]= $tempsubdt["material"]; 
                    }
                }
            }
            else if($newobj['tipe'] === "Face")
            {
                $configdatadb[$newobj['tipe']] = $newobj['id'];
                $id_ava_item = isset($newobj['id'])?$newobj['id']:"";
                if($id_ava_item!="")
                {                    
                    $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id_ava_item)));
                    if($avaitem_tamp)
                    {
                        $tempsubdt = $this->data_unity_desc($avaitem_tamp,$newobj['tipe'],$size,$tipedata);
                        $subconfigava['tipe']= $tempsubdt['tipe'];  
                        $subconfigava["element"]= $tempsubdt["element"];
                        $subconfigava["material"]= $tempsubdt["material"];
                        $subconfigava["id"]= $newobj['id'];
                        $subconfigava2['tipe']= $tempsubdt['tipe'];  
                        $subconfigava2["element"]= $tempsubdt["element"];
                        $subconfigava2["material"]= $tempsubdt["material"];
                    }
                }                
            }
            else
            {
                $configdatadb[$newobj['tipe']] = $newobj['id'];
                $id_ava_item = isset($newobj['id'])?$newobj['id']:"";
                if($id_ava_item!="")
                {                    
                    $avaitem_tamp = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id_ava_item)));
                    if($avaitem_tamp)
                    {
                        $tempsubdt = $this->data_unity_desc($avaitem_tamp,$newobj['tipe'],$size,$tipedata);
                        $configava[] = array('tipe' => $tempsubdt['tipe'], "element" => $tempsubdt["element"], "material" => $tempsubdt["material"], "id" => $newobj['id']);
                        $configava2[] = array('tipe' => $tempsubdt['tipe'], "element" => $tempsubdt["element"], "material" => $tempsubdt["material"]);
                    }
                }                    
            }
        }
        $configava[] = $subconfigava;
        $configava2[] = $subconfigava2;
        return array(
            'dataconf' => $configdatadb,
            'configurations' => $configava,
            'configurations2' => $configava2
        );
    }
    function data_unity_desc($avatdetail,$tipename,$size = "medium",$returntipe = "android")
    {
        $dataelement="";
        $datamaterial="";        
        if($tipename === "hair")
        {
            $tipename = ucfirst($tipename);
        }
        else if($tipename === "face_part_eyes")
        {
            $tipename = "eyes";
        }
        else if($tipename === "face_part_eye_brows")
        {
            $tipename = "eyeBrows";
        }
        else if($tipename === "face_part_lip")
        {
            $tipename = "lip";
        }
        else if($tipename === "body")
        {
            $tipename = "body";
        }
        else if($tipename === "shoes")
        {
            $tipename = "shoes";
        }
        else if($tipename === "pants")
        {
            $tipename = "pants";
        }
        else if($tipename === "hat")
        {
            $tipename = "hat";
        }
        else if($tipename === "face")
        {
            $tipename = "Face";
        }        
        else if($tipename === "EyeBrows")
        {
            $tipename = "eye_brows";
        }
        else if($tipename === "Eyes")
        {
            $tipename = "eyes";
        }
        else if($tipename === "Lip")
        {
            $tipename = "lip";
        }
        $returntipe = strtolower($returntipe);
        if($returntipe === "ios")
        {
            if( $size === "thin")
            {
                $dataelement=isset($avatdetail['element_ios_thin'])?$avatdetail['element_ios_thin']:"";
                $datamaterial=isset($avatdetail['material_ios_thin'])?$avatdetail['material_ios_thin']:"";
            }
            else if( $size === "medium")
            {
                $dataelement=isset($avatdetail['element_ios_medium'])?$avatdetail['element_ios_medium']:"";
                $datamaterial=isset($avatdetail['material_ios_medium'])?$avatdetail['material_ios_medium']:"";
            }
            else if( $size === "fat")
            {
                $dataelement=isset($avatdetail['element_ios_fat'])?$avatdetail['element_ios_fat']:"";
                $datamaterial=isset($avatdetail['material_ios_fat'])?$avatdetail['material_ios_fat']:"";
            }
        }        
        else if($returntipe === "web")
        {
            if( $size === "thin")
            {
                $dataelement=isset($avatdetail['element_web_thin'])?$avatdetail['element_web_thin']:"";
                $datamaterial=isset($avatdetail['material_web_thin'])?$avatdetail['material_web_thin']:"";
            }
            else if( $size === "medium")
            {
                $dataelement=isset($avatdetail['element_web_medium'])?$avatdetail['element_web_medium']:"";
                $datamaterial=isset($avatdetail['material_web_medium'])?$avatdetail['material_web_medium']:"";
            }
            else if( $size === "fat")
            {
                $dataelement=isset($avatdetail['element_web_fat'])?$avatdetail['element_web_fat']:"";
                $datamaterial=isset($avatdetail['material_web_fat'])?$avatdetail['material_web_fat']:"";
            } 
        }
        else
        {
            if( $size === "thin")
            {
                $dataelement=isset($avatdetail['element_android_thin'])?$avatdetail['element_android_thin']:"";
                $datamaterial=isset($avatdetail['material_android_thin'])?$avatdetail['material_android_thin']:"";
            }
            else if( $size === "medium")
            {
                $dataelement=isset($avatdetail['element_android_medium'])?$avatdetail['element_android_medium']:"";
                $datamaterial=isset($avatdetail['material_android_medium'])?$avatdetail['material_android_medium']:"";
            }
            else if( $size === "fat")
            {
                $dataelement=isset($avatdetail['element_android_fat'])?$avatdetail['element_android_fat']:"";
                $datamaterial=isset($avatdetail['material_android_fat'])?$avatdetail['material_android_fat']:"";
            } 
        }
        return array(
            'tipe'=>$tipename,
            "element" => str_replace(".unity3d", "", $dataelement),
            "material" => str_replace(".unity3d", "", $datamaterial)
        );
    }
}