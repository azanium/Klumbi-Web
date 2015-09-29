<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
echo "<table border='1'>";
echo "<tr>";
echo "<td>NO.</td>";
echo "<td>Name</td>";
echo "<td>Questions</td>";
echo "<td>Enabled</td>";
echo "<td>Count</td>";
echo "</tr>";
$i=1;
foreach($listdata as $dt)
{
    echo "<tr>";
    echo "<td>".$i."</td>";
    echo "<td>".(isset($dt['name'])?$dt['name']:"&nbsp;")."</td>";
    echo "<td>".(isset($dt['question'])?$dt['question']:"&nbsp;")."</td>";
    echo "<td>".(isset($dt['enabled'])?$dt['enabled']:"&nbsp;")."</td>";
    echo "<td>".(isset($dt['count'])?$dt['count']:"&nbsp;")."</td>";
    echo "</tr>";
    $i++;           
}  
echo "</table>";