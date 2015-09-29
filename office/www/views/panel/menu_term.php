<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<nav id="page-leftbar" role="navigation">
    <ul class="acc-menu" id="sidebar">
        <?php
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Article");
        $tempmenu=$this->mongo_db->find(array('state_document'=>'publish'),0,0,array('title'=>1));
        if($tempmenu)
        {
            foreach($tempmenu as $dt)
            {
                $judul = $dt["title"];
                if (strpos($judul,"<span>") !== false) 
                {
                    $judul = $dt["title"];
                }
                else
                {
                    $judul = "<span>".$dt["title"]."</span>";
                }
                echo "<li><a href='".$this->template_admin->link("home/term/index/".$dt["alias"])."'>".$judul."</a></li>";
            }
        }
        ?>
    </ul>
</nav>