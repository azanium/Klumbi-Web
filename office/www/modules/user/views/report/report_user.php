<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
echo "<table border='1'>";
echo "<tr>";
echo "<td>NO.</td>";
echo "<td>Name</td>";
echo "<td>Email</td>";
echo "<td>Phone</td>";
echo "<td>Sex</td>";
echo "<td>Location</td>";
echo "</tr>";
$i=1;
$this->mongo_db->select_db("Users");
$this->mongo_db->select_collection("Account");
$listdata = $this->mongo_db->find(array(),0,0,array());   
foreach($listdata as $dt)
{
    echo "<tr>";
    echo "<td>".$i."</td>";
    echo "<td>".(isset($dt['username'])?$dt['username']:"&nbsp;")."</td>";
    echo "<td>".(isset($dt['email'])?$dt['email']:"&nbsp;")."</td>";
    $this->mongo_db->select_collection("Properties");
    $temp_data = $this->mongo_db->findOne(array('lilo_id' => (string)$dt['_id']));
    $phone = "&nbsp;";
    $sex = "&nbsp;";
    $location = "&nbsp;";
    if($temp_data)
    {
        $phone = (isset($temp_data['handphone'])?$temp_data['handphone']:"&nbsp;");
        $sex = (isset($temp_data['sex'])?$temp_data['sex']:"&nbsp;");
        $location = (isset($temp_data['location'])?$temp_data['location']:"&nbsp;");
    }
    echo "<td>".$phone."</td>";
    echo "<td>".$sex."</td>";
    echo "<td>".$location."</td>";
    echo "</tr>";
    $i++;           
}  
echo "</table>";