<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="headerbar">
    <div class="container">
        <div class="row">
        <?php
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Bigmenu");
        $data = $this->mongo_db->find(array("Admin"=>FALSE,"State" =>TRUE),0,0,array('Order'=>1));
        if($data)
        {
            $arraythema = array("tiles-grape","tiles-brown","tiles-primary","tiles-inverse","tiles-green","tiles-success");
            $indexth=0;
            foreach($data as $dt_menubig)
            {
                if($indexth>6)
                {
                    $indexth=0;
                }
                echo "<div class='col-xs-6 col-sm-2'>";
                echo "<a href='".$this->template_admin->link($dt_menubig['Path'])."' class='shortcut-tiles ".$arraythema[$indexth]."'>";
                echo "<div class='tiles-body'>";
                echo "<div class='pull-left'><i class='".$dt_menubig['class']."'></i></div>";
//                    echo "<div class='pull-right'><span class='badge'>10</span></div>";
                echo "</div>";
                echo "<div class='tiles-footer'>";
                echo $dt_menubig['Alias'];
                echo "</div>";
                echo "</a>";
                echo "</div>";
                $indexth++;
            }
        }
        ?> 
        </div>
    </div>
</div>           