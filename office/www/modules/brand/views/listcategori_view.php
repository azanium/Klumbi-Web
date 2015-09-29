<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('#fileimage').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploader"); ?>',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileimgname").val(data.result.name);
                $("#filepreview").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
            }
            else
            {
                $.pnotify({
                    title: "Error Upload file",
                    text: data.result.message,
                    type: 'error'
                });
            }
        }
    });
    $('#fileimage2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploader"); ?>',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileimgname2").val(data.result.name);
                $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
            }
            else
            {
                $.pnotify({
                    title: "Error Upload file",
                    text: data.result.message,
                    type: 'error'
                });
            }
        }
    });
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bDestroy": true,
        "bProcessing": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("brand/categori/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"cmbtipeview", "value":$("#cmbtipeview").val()});
            $.ajax({
                dataType: "json",
                type: "GET",
                data: aoData,
                url: sSource,        
                success: fnCallback
            });
        }
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $(".resetform").click(function(){
        $("input, textarea").val("");
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
    });
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
    });
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $('.select').select2({width: 'resolve'});
    $("#brandfrm").validate({
        submitHandler: function(form) {
            var url=$("#brandfrm").attr('action');
            var datapost=$("#brandfrm").serialize();
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
                        $("input, textarea").val("");
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                    }
                    else
                    {
                        $.pnotify({
                            title: "Fail",
                            text: data['message'],
                            type: 'info'
                        });
                    }
                    dttable.fnClearTable(0);
                    dttable.fnDraw();                       
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
        }
    });
    $("#editbrandfrm").validate({
        submitHandler: function(form) {
            var url=$("#editbrandfrm").attr('action');
            var datapost=$("#editbrandfrm").serialize();
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
                        $('#editdata').modal('hide');
                        $("input, textarea").val("");
                        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                    }
                    else
                    {
                        $.pnotify({
                            title: "Fail",
                            text: data['message'],
                            type: 'info'
                        });
                    }
                    dttable.fnClearTable(0);
                    dttable.fnDraw();                       
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
        }
    });
});
function ubahdata(id,tipe,name,fileimage)
{
    $('#txtid2').val(id);
    $('#cmbtipe2').val(tipe);
    $('#name2').val(name);
    $('#txtfileimgname2').val(fileimage);
    $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+fileimage);
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>brand/categori/delete/'+id;
            $.ajax({
                    type: "POST",
                    url: url,
                    data:"",
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
                            $("input, textarea").val("");
                            $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                            $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        }
                        else
                        {
                            $.pnotify({
                                title: "Fail",
                                text: data['message'],
                                type: 'info'
                            });
                        }
                        dttable.fnClearTable(0);
                        dttable.fnDraw();                       
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
        }
        return false;
    });
}
function reloaddatatable(id)
{
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bDestroy": true,
        "bProcessing": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("brand/categori/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"cmbtipeview", "value":$("#cmbtipeview").val()});
            $.ajax({
                dataType: "json",
                type: "GET",
                data: aoData,
                url: sSource,        
                success: fnCallback
            });
        }
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
}
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Category</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Categories</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Category</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata">
                            <p align="right">
                                <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                            </p>
                            <p align="right">
                                <select id="cmbtipeview" name="cmbtipeview" onchange="reloaddatatable(this.value)" class="inputbox lengkung_4 pading_5">
                                    <option value=''>All</option>
                                    <?php
                                    foreach($type as $dt)
                                    { 
                                        $this->mongo_db->select_db("Assets");
                                        $this->mongo_db->select_collection("AvatarBodyPart");
                                        $listtipe=$this->mongo_db->find(array("parent"=>$dt['name']),0,0,array('name'=>1));
                                        if($listtipe->count()>0)
                                        {
                                            echo "<optgroup label='".$dt['name']."'>";
                                            foreach($listtipe as $dt2)
                                            {
                                                echo "<option value='".$dt2['name']."'>".$dt2['name']."</option>";
                                            }
                                            echo "</optgroup>";
                                        }
                                        else 
                                        {
                                            echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </p>
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="50%">Name</th>
                                        <th width="15%">Avatar Body Part</th>
                                        <th width="15%">Image</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST"  enctype="multipart/form-data" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("brand/categori/cruid_categori"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                                    <label for="cmbtipe" class="col-sm-3 control-label">Avatar Body Part</label>
                                    <div class="col-sm-6">
                                      <select id="cmbtipe" name="cmbtipe" class="select" style="width:100%">
                                        <?php
                                        foreach($type as $dt)
                                        { 
                                            $this->mongo_db->select_db("Assets");
                                            $this->mongo_db->select_collection("AvatarBodyPart");
                                            $listtipe=$this->mongo_db->find(array("parent"=>$dt['name']),0,0,array('name'=>1));
                                            if($listtipe->count()>0)
                                            {
                                                echo "<optgroup label='".$dt['name']."'>";
                                                foreach($listtipe as $dt2)
                                                {
                                                    echo "<option value='".$dt2['name']."'>".$dt2['name']."</option>";
                                                }
                                                echo "</optgroup>";
                                            }
                                            else 
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Avatar Body Part Type of Category!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Category Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Category!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimage" class="col-sm-3 control-label">Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimage" id="fileimage" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image file of Category!</p>
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Category","module6","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("brand/categori/cruid_categori"); ?>" enctype="multipart/form-data">
                    <table width="100%" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="15%">
                                <label for="cmbtipe2">Avatar Body Part</label>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <input type="hidden" name="txtfileimgname" id="txtfileimgname2" value="" />
                            </td>
                            <td width="85%">
                                <select id="cmbtipe2" name="cmbtipe" class="form-control">
                                    <?php
                                    foreach($type as $dt)
                                    { 
                                        $this->mongo_db->select_db("Assets");
                                        $this->mongo_db->select_collection("AvatarBodyPart");
                                        $listtipe=$this->mongo_db->find(array("parent"=>$dt['name']),0,0,array('name'=>1));
                                        if($listtipe->count()>0)
                                        {
                                            echo "<optgroup label='".$dt['name']."'>";
                                            foreach($listtipe as $dt2)
                                            {
                                                echo "<option value='".$dt2['name']."'>".$dt2['name']."</option>";
                                            }
                                            echo "</optgroup>";
                                        }
                                        else 
                                        {
                                            echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="name2">Name</label></td>
                            <td><input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Category Name" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="fileimage2">Picture</label></td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimage" id="fileimage2" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><img id="filepreview2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                            <?php 
                            if($this->m_checking->actions("Category","module6","Edit",TRUE,FALSE,"home"))
                            {
                                echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>&nbsp;&nbsp;'; 
                            }
                            ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>