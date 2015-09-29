<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Search</li>
        <li class="active">index</li>
    </ol>
    <h1>Search Page</h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">                        
            <div class="panel">
                <div class="panel-heading">
                    <h4>Search Results</h4>
                </div>
                <div class="panel-body">
                    <div class="search-classic">
                        <form class="form-inline"  method="GET" action="<?php echo $this->template_admin->link("home/search"); ?>">
                           <div class="input-group well">
                               <input placeholder="Search..." name="q" value="<?php echo (isset($_GET['q'])?$_GET['q']:""); ?>" class="form-control" type="text">
                              <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="icon-search"></i> Search</button>
                              </span>
                           </div>
                        </form>
                        <h3>Displaying results people</h3><hr />
                        <?php 
                        foreach($datalist as $dt)
                        {                            
                            $tempdataaccount = $this->m_userdata->user_account_byid($dt['lilo_id']);
                            $linkopen = $this->template_admin->link("home/account/index/".$tempdataaccount["username"]);
                            echo "<div class='search-result'>";
                            echo "<h4><a href='".$linkopen."'>".$dt['fullname']."</a></h4>";
                            echo "<a href='".$linkopen."'>".$linkopen."</a>";
                            echo "<p>";
                            echo "Avatar Name : ".$dt['avatarname']."<br />";
                            echo "Body Type : ".$dt['bodytype']."<br />";
                            echo "Location : ".$dt['location']."<br />";
                            echo "</p>";
                            echo "</div>";
                            echo "<hr />";
                        }
                        ?>
                    </div>
                </div>                 
            </div>
        </div>
    </div>
</div>