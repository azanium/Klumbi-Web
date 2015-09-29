<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Listreward extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    function get_required_id($id="")
    {
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("RequiredRewards");
        $data=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($data)
        {
            if($data['self_value']=="Yes")
            {
                foreach($data['options'] as $dt)
                {
                    echo "<option value='".$data['code'].":".$dt."'>".$dt."</option>";
                }
            }
            else
            {
                $this->mongo_db->select_db("Assets");        
                $this->mongo_db->select_collection($data['table']);
                $data2=$this->mongo_db->find(array(),0,0,array());
                if($data2)
                {
                    foreach($data2 as $dt)
                    {
                        echo "<option value='".$data['code'].":".$dt[$data['options'][0]]."'>".$dt[$data['options'][1]]."</option>";
                    }
                }
            }
        }
    }
}
