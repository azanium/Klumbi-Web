<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<nav id="page-leftbar" role="navigation">
    <ul class="acc-menu" id="sidebar">
        <li id="search">
            <a href="javascript:;"><i class="icon-search opacity-control"></i></a>
             <form method="GET" action="<?php echo $this->template_admin->link("home/search"); ?>">
                <input type="text" name="q" class="search-query" placeholder="Search..." />
                <button type="submit"><i class="icon-search"></i></button>
            </form>
        </li>
        <li class="divider">&nbsp;</li>
        <?php
        if($this->session->userdata('login'))
        {
        ?>
        <li><a href="<?php echo $this->template_admin->link("home/index") ?>"><i class="icon-home"></i> <span>DASHBOARD</span></a></li>
        <!--li><a href="javascript:;"><i class="icon-home"></i> <span>Game</span></a>
            <ul class="acc-menu">
                <li><a href="<?php echo $this->template_admin->link("member/gplay/index") ?>">Play</a></li>
                <li><a href="<?php echo $this->template_admin->link("user/priviewavatar/index") ?>">My Mix</a></li>
                <li><a href="<?php echo $this->template_admin->link("user/collections/index") ?>">My Collections</a></li>
            </ul>
        </li-->
        <!--li><a href="<?php echo $this->template_admin->link("member/trophy/index"); ?>"><i class="icon-trophy"></i> <span>Trophy</span></a></li-->
        <?php
        }
        ?>
        <!--li><a href="<?php echo $this->template_admin->link("member/gallery/index") ?>"><i class="icon-picture"></i> <span>Gallery</span></a></li>
        <li class="divider">&nbsp;</li-->
        <?php 
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Group");
        $group=$this->session->userdata('group');
        $data=$this->mongo_db->findOne(array('Code'  =>$group));        
        if($data)
        {
            $this->mongo_db->select_collection("Module");
            $datamodule=$this->mongo_db->find(array(),0,0,array('Order'  =>1));
            $index=0;
            foreach ($datamodule as $dtmodule)
            {
                $this->mongo_db->select_collection("trModule");
                $filtersearch=array(
                    'Module'  =>$dtmodule["Code"],
                    'Group'  =>$group,
                    'IsActive'=>TRUE,
                );
                $checkdatamodule=$this->mongo_db->findOne($filtersearch);
                if($checkdatamodule)
                {
                    $this->mongo_db->select_collection("Menu");
                    $filtersearch=array(
                        'module'  =>$dtmodule["Code"]
                    );
                    $datamenu=$this->mongo_db->find($filtersearch,0,0,array('Order'  =>1));
                    if($datamenu)
                    {
                        $this->mongo_db->select_collection("trMenu");
                        $filtersearch = array(
                            'Module'  =>$dtmodule["Code"],
                            'Group'  =>$group,
                            'IsActive'=>TRUE,
                        );
                        $dttemp_jmlsubmenu = $this->mongo_db->count($filtersearch);
                        if($dttemp_jmlsubmenu>0)
                        {
                            echo "<li>";
                            echo "<a href='javascript:;'><i class='". $dtmodule['class'] ."'></i> <span>". $dtmodule['Name'] ."</span> <span class='badge ". $dtmodule['color'] ."'>". $dttemp_jmlsubmenu ."</span></a>";
                            echo "<ul class='acc-menu'>";
                            foreach($datamenu as $dtmenu)
                            {
                                $this->mongo_db->select_collection("trMenu");
                                $filtersearch=array(
                                    'Module'  =>$dtmodule["Code"],
                                    'Group'  =>$group,
                                    'Menu'  =>$dtmenu["Code"],
                                    'IsActive'=>TRUE,
                                );
                                $checkdatamenu=$this->mongo_db->findOne($filtersearch);
                                if($checkdatamenu)
                                {
                                    echo "<li>";
                                    echo "<a href='".$this->template_admin->link($dtmenu['Path'])."'>".$dtmenu['Name']."</a>";
                                    echo "</li>";
                                }                        
                            }
                            echo "</ul>";
                            echo "</li>";
                        }                        
                    }                    
                }
                if($index % 5==0 && $index>0)
                {
                    echo "<li class='divider'>&nbsp;</li>";
                }
                $index++;
            }
        }      
        ?>
    </ul>
</nav>