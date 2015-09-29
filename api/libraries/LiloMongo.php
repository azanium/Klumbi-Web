<?php
include_once('config/connection.php');
class LiloMongo
{
    var $connection;
    var $db;
    var $collection;
    function LiloMongo($args = NULL)
    {
        if(is_array($args))
        {
            extract($args);
        }
        if(!isset($mongoserver))
        {
            $mongoserver = MONGO_HOST;
            $username = MONGO_USER;
            $password = MONGO_PASS;
            $mongoserver = "mongodb://".$username.":".$password."@" . $mongoserver;
            if(trim('27017') != '')
            {
                $mongoserver = $mongoserver . ":" . '27017';
            }
        }
        $this->connection = new Mongo($mongoserver);
        if(isset($dbname))
        {
            $this->db = $this->connection->selectDB($dbname);
        }
        if(isset($collectionname))
        {
            $this->collection = $this->db->selectCollection($collectionname);
        }		
    }	
    function selectDB($dbname)
    {
        $this->db = $this->connection->selectDB($dbname);
    }

    function selectCollection($collectionname)
    {
        $this->collection = $this->db->selectCollection($collectionname);
    }

    function findOne($array_parameter = array(), $fields = array())
    {
        return $this->collection->findOne($array_parameter, $fields);
    }
    function find($array_parameter = array(), $limit = 0, $sort = array())
    {
        if(count($sort))
        {
            return $this->collection->find($array_parameter)->sort($sort)->limit($limit);
        }
        return $this->collection->find($array_parameter);
    }
    function find_pagging($array_parameter = array(), $skip=0, $limit = 10, $sort = array())
    {
        if(count($sort)>0)
        {
            return $this->collection->find($array_parameter)->skip($skip)->limit($limit)->sort($sort);
        }
        if($limit>0)
        {
            return $this->collection->find($array_parameter)->skip($skip)->limit($limit);
        }            
        return $this->collection->find($array_parameter)->sort($sort);
    }
    function command($command_, $options_ = array())
    {
        return $this->db->command($command_, $options_);
    }
    
    function time($time)
    {
        return new MongoDate($time);
    }
    
    function mongoid($id)
    {
        return new MongoId($id);
    }

    function command_values($command_, $options_ = array())
    {
        return $this->db->command($command_, $options_);
    }
    function count($array_parameter = array())
    {
        $cursor = $this->collection->find($array_parameter);
        return $cursor->count();
    }
    function insert($array_parameter)
    {
        $this->collection->insert($array_parameter);
        return $array_parameter['_id'];
    }
    function update($array_criteria, $array_newobj, $array_options = array("multiple" => true))
    {
        $this->collection->update($array_criteria, $array_newobj, $array_options);
    }
    function update_set($array_criteria, $array_newobj, $array_options = array("multiple" => true))
    {
        $this->collection->update($array_criteria, array('$set' => $array_newobj), $array_options);
    }
    function remove($array_criteria, $array_options = array())
    {
        $this->collection->remove($array_criteria, $array_options);
    }
    function delete($array_criteria, $array_options = array())
    {
        $this->collection->remove($array_criteria, $array_options);
    }
    function close()
    {
        $this->connection->close();
    }
}
?>