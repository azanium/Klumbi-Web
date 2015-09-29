<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $( "#daftarmenu" ).sortable({
        placeholder: "ui-state-highlight",
        delay: 300,
        distance: 15
    });
});
</script>
<style type="text/css">
.nolist{list-style: none;}
#daftarmenu li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.0em;}
.daftarmenuli { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.0em; height: 30px; }
#daftarmenu li span { position: absolute; margin-left: -1.3em; }
</style>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Access</li>
        <li class="active">Menu Admin</li>
    </ol>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("management/cruid_management"); ?>">
                <div class="panel panel-warning">
                    <div class="alert alert-info">
                        <p><span class="label label-info">Note</span> Rename Menu name, drag and drop menu for orderring.</p>
                    </div>
                    <div class="panel-heading">
                        <h4>Edit Menu</h4>
                        <div class="options">
                            <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="panel-body collapse in">                    
                        <div class="dd" id="nestable_list_3">
                        <?php
                        if($listmenu)
                        {
                            echo "<ol id='daftarmenu' class='nolist'>";
                            $index=0;
                            foreach($listmenu as $dt=>$isidata)
                            {
                                echo "<li class='ui-state-default' data-id='".$index."'>";
                                echo "<span class='ui-icon ui-icon-arrowthick-2-n-s'></span>";
                                echo "<input type='hidden' name='txtid[]'  value='".$isidata['Code']."' />";
                                echo "<img src='".base_url()."resources/image/icon/".$isidata['Image']."' alt='".$isidata['Image']."' />";                           
                                echo "<input type='text' name='headmenu[]' value='".$isidata['Name']."' maxlength='255' placeholder='Type Menu Name' class='inputbox lengkung_4 pading_5 {required:true}' />";
                                echo "<script type='text/javascript'>";
                                echo "$(document).ready(function() {";
                                echo "$( '#daftarchiledmenu_".$index."' ).sortable({placeholder: 'ui-state-highlight',delay: 300,distance: 15});";
                                echo "});";
                                echo "</script>";
                                echo "<hr />";
                                echo "<p>List Menu Childs : </p>";
                                echo "<ul id='daftarchiledmenu_".$index."' class='nolist'>";
                                $j=0;
                                $this->mongo_db->select_db("Game");
                                $this->mongo_db->select_collection("Menu");
                                $templistdt=$this->mongo_db->find(
                                        array(
                                            'module'=>$isidata['Code']
                                            ),0,0,array('Order'=>1));
                                foreach($templistdt as $ChildMenu)
                                {
                                    echo "<li class='ui-state-default daftarmenuli' data-id='".$index."_".$j."'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>";
                                    echo "<input type='hidden' name='txtchildid[]'  value='".$ChildMenu['_id']."' />";
                                    echo "<img src='".base_url()."resources/image/icon/".$ChildMenu['Image']."' alt='".$ChildMenu['Image']."' />";
                                    echo "<input type='text' name='chilemenu[]' value='".$ChildMenu['Name']."' maxlength='255' placeholder='Type Child Menu Name' class='inputbox lengkung_4 pading_5 {required:true}' />";
                                    echo "</li>";
                                    $j++;
                                }
                                echo "</ul>";
                                echo "</li>";
                                $index++;
                            }
                            echo "</ol>";
                        }
                        ?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>