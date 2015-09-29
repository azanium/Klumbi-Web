<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse large-icons-nav" id="horizontal-navbar">
        <ul class="nav navbar-nav">
            <?php
            $activemenu = ($curentmenu==="")?"class='active'":"";
            if($this->session->userdata('login'))
            {
                echo "<li><a href='".$this->template_admin->link("home/index")."'><i class='icon-home'></i> <span>Dashboard</span></a></li>";
            }
            else
            {
                echo "<li><a href='".$this->template_admin->link("home/login")."'><i class='icon-group'></i> <span>Login</span></a></li>";
            }
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Bigmenu");
            $data = $this->mongo_db->find(array("Admin"=>$islogin,"State" =>TRUE),0,0,array('Order'=>1));
            if($data)
            {
                foreach($data as $dt_menubig)
                {
                    $activemenu = "";
                    if($dt_menubig['Name'] == $curentmenu)
                    {
                       $activemenu = "class='active'"; 
                    }
                    echo "<li ".$activemenu." ><a href='".$this->template_admin->link($dt_menubig['Path'])."'><i class='".$dt_menubig['class']."'></i> <span>".$dt_menubig['Alias']."</span></a></li>";
                }
            }
            ?>
        </ul>
    </div>
</nav>