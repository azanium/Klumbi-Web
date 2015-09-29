<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mongo_db 
{       
        private $CI;
	private $config_file = 'mongo_config';	
	private $connection;
	private $db;
	private $connection_string;	
	private $host;
	private $port;
	private $user;
	private $pass;
	private $dbname;
	private $persist;
	private $persist_key;	
	private $selects = array();
	private $wheres = array();
	private $sorts = array();	
	private $limit = 999999;
	private $offset = 0;
	public function __construct() 
        {
		if(!class_exists('Mongo'))
                {
			show_error("The MongoDB PECL extension has not been installed or enabled", 500);
                }
		$this->CI =& get_instance();
		$this->connection_string();
		$this->connect();
	}
	function select_db($dbname)
        {
            if(empty($dbname))
            {
               show_error("To switch MongoDB databases, a new database name must be specified", 500);
            }
            $this->dbname = $dbname;
            try 
            {
		$this->db = $this->connection->selectDB($dbname);
                return(TRUE);
            } 
            catch(Exception $e) 
            {
                show_error("Unable to switch Mongo Databases: {$e->getMessage()}", 500);
            }
        }
        function select_collection($collectionname)
        {
            $this->collection = $this->db->selectCollection($collectionname);
        }
        function find($array_parameter = array(), $skip=0, $limit = 10, $sort = array())
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
        function count($array_parameter = array())
        {
            $cursor = $this->collection->find($array_parameter);
            return $cursor->count();
	}
        function count2($array_parameter = array())
        {
            return $this->collection->count($array_parameter);
	}
        function close()
        {
            $this->db->close();
        }
        function remove($array_criteria, $array_options = array())
        {
            return $this->collection->remove($array_criteria, $array_options);
	}
        function insert($array_parameter)
        {
            $this->collection->insert($array_parameter);
            return $array_parameter['_id'];
	}
        function update($array_criteria, $array_newobj, $array_options = array("multiple" => true))
        {
            return $this->collection->update($array_criteria, $array_newobj, $array_options);
	}
        function mongoid($id)
        {
            return new MongoId($id);
	}
        function findOne($array_parameter = array(), $fields = array())
        {
            return $this->collection->findOne($array_parameter, $fields);
	}
        function update_set($array_criteria, $array_newobj, $array_options = array("multiple" => true))
        {
            return $this->collection->update($array_criteria, array('$set' => $array_newobj), $array_options);
	}
        function update_unset($array_criteria, $array_obj)
        {
            return $this->collection->update($array_criteria, array('$unset' => $array_obj));
	}
        function time($time)
        {
            return new MongoDate($time);
        }
        function regex($regex)
        {
            return new MongoRegex($regex);
        }
        function command_values($command_, $options_ = array())
        {
            return $this->db->command($command_, $options_);
	}
	public function remove_all_indexes($collection = "") 
        {
            $this->db->{$collection}->deleteIndexes();
            $this->clear();
            return($this);
	}
	public function list_indexes($collection = "") 
        {
            return($this->db->{$collection}->getIndexInfo());
	}
	private function connect() {
            $options = array();
            if($this->persist === TRUE)
            {
                $options['persist'] = isset($this->persist_key) && !empty($this->persist_key) ? $this->persist_key : 'ci_mongo_persist';
            }
            try 
            {
                $this->connection = new Mongo($this->connection_string,$options);
                $this->db = $this->connection->{$this->dbname};
                return($this);	
            } 
            catch(MongoConnectionException $e) 
            {
                if(!$this->CI->config->item('state_publist'))
                {
                    show_error("Unable to connect to MongoDB: {$e->getMessage()}", 500);
                }            
            }
	}
	private function connection_string() 
        {
            $this->CI->config->load($this->config_file);		
            $this->host = trim($this->CI->config->item('mongo_host'));
            $this->port = trim($this->CI->config->item('mongo_port'));
            $this->user = trim($this->CI->config->item('mongo_user'));
            $this->pass = trim($this->CI->config->item('mongo_pass'));
            $this->dbname = trim($this->CI->config->item('mongo_db'));
            $this->persist = trim($this->CI->config->item('mongo_persist'));
            $this->persist_key = trim($this->CI->config->item('mongo_persist_key'));		
            $connection_string = "mongodb://";		
            if(empty($this->host))
            {
                show_error("The Host must be set to connect to MongoDB", 500);
            }	
            if(empty($this->dbname))
            {
                show_error("The Database must be set to connect to MongoDB", 500);
            }
            if(!empty($this->user) && !empty($this->pass))
            {
                $connection_string .= "{$this->user}:{$this->pass}@";
            }
            if(isset($this->port) && !empty($this->port))
            {
                $connection_string .= "{$this->host}:{$this->port}@";
            }
            $connection_string .= "{$this->host}";		
            $this->connection_string = trim($connection_string);
	}
	private function clear() 
        {
            $this->selects = array();
            $this->wheres = array();
            $this->limit = NULL;
            $this->offset = NULL;
            $this->sorts = array();
	}
	private function where_init($param) 
        {
            if(!isset($this->wheres[$param]))
                    $this->wheres[$param] = array();
	}
}