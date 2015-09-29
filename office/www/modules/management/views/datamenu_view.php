<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {  
    $('#cleardatainner').click(function(){
        $('#datarestrukturmenu').html(''); 
    });
    $("#tree").treeview({
        collapsed: true,
        animated: "medium",
        control:"#sidetreecontrol",
        persist: "location"
    });
    $("#strukturmenufrm").submit(function(){
        var url=$("#strukturmenufrm").attr('action');
        var datapost=$("#strukturmenufrm").serialize();
        $.ajax({
            type: "POST",
            url: url,
            data:datapost,
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
						'<strong>Warning!</strong> ' +
                                                '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                '<i class="process">Wait a minute, Your request being processed</i>' +
						'</div>').slideDown(100);
            },
            success: function (data, textStatus) {
                $("#loadingprocess").slideUp(100);
                if(data['success']==true)
                {                        
                    $.pnotify({
                        title: "Success",
                        text: data['message'],
                        type: 'success'
                    });
                    $('#datarestrukturmenu').html('');  
                }
                else
                {
                    $.pnotify({
                        title: "Fail",
                        text: data['message'],
                        type: 'info'
                    });
                }                                    
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#loadingprocess").slideUp(100);
                $.pnotify({
                            title: textStatus + " " + xhr.status,
                            text: (errorThrown ? errorThrown : xhr.status),
                            type: 'error'
                        });
            }
        });
        return false;
    });
});
</script>
<p align="right">
    <a id="cleardatainner" class="btn btn-sm btn-inverse"><i class="icon-ban-circle"></i> Clear</a>
</p>
<div class="alert alert-info">
    <p><span class="label label-info">Note</span> **Ceklist menu for active.</p>
</div>
<form method="POST" id="strukturmenufrm" action="<?php echo $this->template_admin->link("management/groups/cruid_menu"); ?>">
    <div class="lengkung_4 pading_5" style="border: 2px solid #0073ea;margin-bottom: 10px;background-color: #fff;">
        <ul id="tree" class="filetree treeview-famfamfam">
        <?php
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Group");
        $datacek=$this->mongo_db->findOne(array('_id'  =>$this->mongo_db->mongoid($idgroup)));
        if($datacek)
        {
            echo "<input type='hidden' name='group' value='".$datacek['Code']."' />";
            $this->mongo_db->select_collection("Module");
            $datamodule=$this->mongo_db->find(array(),0,0,array('Order'  =>1));
            foreach ($datamodule as $dtmodule)
            {
                $this->mongo_db->select_collection("trModule");
                $filtersearch=array(
                    'Module'  =>$dtmodule["Code"],
                    'Group'  =>$datacek['Code'],
                );
                $checkdatamodule=$this->mongo_db->findOne($filtersearch);
                if($checkdatamodule)
                {
                    $checked="";
                    if($checkdatamodule['IsActive'])
                    {
                        $checked="checked='true'";
                    }
                    echo "<li><span class='folder'><input type='checkbox' name='module[]' value='".$datacek['Code']."_".$dtmodule['Code']."' ".$checked." /><img src='".base_url()."resources/image/icon/".$dtmodule['Image']."' alt='".$dtmodule['Name']."' />&nbsp;".$dtmodule['Name']."</span>";
                    echo "<ul>";
                    $this->mongo_db->select_collection("Menu");
                    $filtersearch=array(
                        'module'  =>$dtmodule["Code"]
                    );
                    $datamenu=$this->mongo_db->find($filtersearch,0,0,array('Order'  =>1));
                    foreach($datamenu as $dtmenu)
                    {
                        $this->mongo_db->select_collection("trMenu");
                        $filtersearch=array(
                            'Module'  =>$dtmodule["Code"],
                            'Group'  =>$datacek['Code'],
                            'Menu'  =>$dtmenu["Code"],
                        );
                        $checkdatamenu=$this->mongo_db->findOne($filtersearch);
                        if($checkdatamenu)
                        {
                            $checkedmenu="";
                            if($checkdatamenu['IsActive'])
                            {
                                $checkedmenu="checked='true'";
                            }
                            $defaultjns="<input type='checkbox' name='menu[]' value='".$datacek['Code']."_".$dtmodule['Code']."_".$dtmenu['Code']."' ".$checkedmenu." />";
                            if($dtmenu['EditAble']==FALSE)
                            {
                                $defaultjns="<input type='hidden' name='menu[]' value='".$datacek['Code']."_".$dtmodule['Code']."_".$dtmenu['Code']."' />";
                            }            
                            $folder="folder";
                            if(!isset($dtmenu["ListActions"]))
                            {
                                $folder="file";
                            }
                            echo "<li><span class='".$folder."'>".$defaultjns."<img src='".base_url()."resources/image/icon/".$dtmenu['Image']."' alt='".$dtmenu['Name']."' />&nbsp;".$dtmenu['Name']."</span>";    
                            $this->mongo_db->select_collection("trActions");
                            $filtersearch=array(
                                'Module'  =>$dtmodule["Code"],
                                'Group'  =>$datacek['Code'],
                                'Menu'  =>$dtmenu["Code"],
                            );
                            $dataactions=$this->mongo_db->find($filtersearch,0,0,array());
                            if($dataactions)
                            {
                                echo "<ul>"; 
                                foreach($dataactions as $dtactions)
                                {
                                    $checkedactions="";
                                    if($dtactions['IsActive'])
                                    {
                                        $checkedactions="checked='true'";
                                    }
                                    echo "<li><span class='file'><input type='checkbox' name='actions[]' value='".$datacek['Code']."_".$dtmodule['Code']."_".$dtmenu['Code']."_".$dtactions['Actions']."' ".$checkedactions." />".$dtactions['Actions']."</span></li>";
                                }
                                echo "</ul>";
                            }
                            echo "</li>";                        
                        }  
                    }
                    echo "</ul>";
                    echo "</li>";
                }
            }
        }                       
        ?>            
        </ul>
    </div>
    <div class="form-group">
        <div class="col-sm-12"><button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button></div>
    </div>
</form>