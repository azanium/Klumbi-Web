<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include (APPPATH.'libraries/felfinder/elFinderConnector.class.php');
include (APPPATH.'libraries/felfinder/elFinder.class.php');
include (APPPATH.'libraries/felfinder/elFinderVolumeDriver.class.php');
include (APPPATH.'libraries/felfinder/elFinderVolumeLocalFileSystem.class.php');
class Rd_elfinder
{
     public function __construct($config)
     {
         $connector = new elFinderConnector(new elFinder($config));
         $connector->run();
     } 
}