<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $('#fileimage , #fileimage2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimage"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileimgname").val(data.result.name);
                $("#txtfileimgname2").val(data.result.name);
                $("#filepreview").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileweb , #fileweb2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileweb"); ?>',
        dataType: 'json',
        formData: {
            folder: 'animations/web/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#nmfileweb").text(data.result.name);
                $("#nmfileweb2").text(data.result.name);
                $("#txtfileweb").val(data.result.name);
                $("#txtfileweb2").val(data.result.name);
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
    $('#fileios , #fileios2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileios"); ?>',
        dataType: 'json',
        formData: {
            folder: 'animations/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#nmfileios").text(data.result.name);
                $("#nmfileios2").text(data.result.name);
                $("#txtfileios").val(data.result.name);
                $("#txtfileios2").val(data.result.name);
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
    $('#fileandroid , #fileandroid2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileandroid"); ?>',
        dataType: 'json',
        formData: {
            folder: 'animations/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#nmfileandroid").text(data.result.name);
                $("#nmfileandroid2").text(data.result.name);
                $("#txtfileandroid").val(data.result.name);
                $("#txtfileandroid2").val(data.result.name);
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
        "sAjaxSource": "<?php echo $this->template_admin->link("inventory/animation/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, {"sClass" : "text-center", "bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"cmbgenderview", "value":$("#cmbgenderview").val()});
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
        $("#nmfileweb").text("");
        $("#nmfileweb2").text("");
        $("#nmfileios").text("");
        $("#nmfileios2").text("");
        $("#nmfileandroid").text("");
        $("#nmfileandroid2").text("");
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/noavatar2.png');
        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/noavatar2.png');
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
                        $("#nmfileweb").text("");
                        $("#nmfileweb2").text("");
                        $("#nmfileios").text("");
                        $("#nmfileios2").text("");
                        $("#nmfileandroid").text("");
                        $("#nmfileandroid2").text("");
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/noavatar2.png');
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
                        $("#nmfileweb").text("");
                        $("#nmfileweb2").text("");
                        $("#nmfileios").text("");
                        $("#nmfileios2").text("");
                        $("#nmfileandroid").text("");
                        $("#nmfileandroid2").text("");
                        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/noavatar2.png');
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
function ubahdata(id,name,keterangan,gender,permision,fileimage,fileweb,fileios,fileandroid)
{
    $('#txtid2').val(id);
    $('#name2').val(name);
    $('#description2').val(keterangan);
    $('#txtfileweb2').val(fileweb);
    $('#txtfileios2').val(fileios);
    $('#txtfileandroid2').val(fileandroid);
    $("#nmfileweb2").text(fileweb);
    $("#nmfileios2").text(fileios);
    $("#nmfileandroid2").text(fileandroid);
    $('#txtfileimgname2').val(fileimage);
    $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+fileimage);
    $('#cmbgender2').val(gender);
    $('#cmbpayment2').val(permision);
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>inventory/animation/delete/'+id;
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
                            $("#nmfileweb").text("");
                            $("#nmfileweb2").text("");
                            $("#nmfileios").text("");
                            $("#nmfileios2").text("");
                            $("#nmfileandroid").text("");
                            $("#nmfileandroid2").text("");
                            $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/noavatar2.png');
                            $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/noavatar2.png');
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
        "sAjaxSource": "<?php echo $this->template_admin->link("inventory/animation/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, {"sClass" : "text-center", "bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"cmbgenderview", "value":$("#cmbgenderview").val()});
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
<?php
if($this->m_checking->actions("Animation","module6","Export",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("inventory/animation/processexport/html"); ?>" target="_blank">Compress (*.zip)</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
}
?>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Animation</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Animations</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Animation</a></li>
                          <?php
                          if($this->m_checking->actions("Animation","module6","Import",TRUE,FALSE,"home"))
                          {    
                          ?>
                          <li><a href="#importdata" data-toggle="tab"><i class="icon-cloud-upload"></i> Import Animation</a></li>
                          <?php
                          }    
                          ?>
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
                                <select id="cmbgenderview" name="cmbgenderview" onchange="reloaddatatable(this.value);" class="inputbox lengkung_4 pading_5">
                                    <?php
                                    foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                    {
                                        $isicombo=$dt;
                                        if($value=="Unisex")
                                        {
                                            $isicombo="";
                                        }
                                        echo "<option value='".$isicombo."'>".$value."</option>";
                                    }
                                    ?>
                                </select>
                            </p>
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Name</th>
                                        <th width="15%">Permision</th>
                                        <th width="15%">Gender</th>
                                        <th width="20%">Description</th>
                                        <th width="20%">Image</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("inventory/animation/cruid_animation"); ?>" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                                    <input type="hidden" name="txtfileweb" id="txtfileweb" value="" />
                                    <input type="hidden" name="txtfileios" id="txtfileios" value="" />
                                    <input type="hidden" name="txtfileandroid" id="txtfileandroid" value="" />
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Animation!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-6">
                                        <textarea name="description" id="description" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of Animation!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbgender" class="col-sm-3 control-label">Gender</label>
                                    <div class="col-sm-6">
                                      <select id="cmbgender" name="cmbgender" class="form-control">
                                        <?php
                                        foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                        {
                                            echo "<option value='".$dt."'>".$value."</option>";
                                        }
                                        ?>
                                          <option value="">Unisex</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Gender for Animation!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbpayment" class="col-sm-3 control-label">Payment</label>
                                    <div class="col-sm-6">
                                        <select id="cmbpayment" name="cmbpayment" class="form-control">
                                            <?php 
                                            foreach($payment as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Payment of Animation!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileweb" class="col-sm-3 control-label">File Web</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Animation file...</span>
                                            <input type="file" name="fileweb" id="fileweb" />
                                        </span>
                                        <span id="nmfileweb" class="label label-danger"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Animation for Web!</p>
                                    </div>
                                </div>
                                <hr />
                                <!--<div class="form-group">
                                    <label for="fileios" class="col-sm-3 control-label">File iOS</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Animation file...</span>
                                            <input type="file" name="fileios" id="fileios" />
                                        </span>
                                        <span id="nmfileios" class="label label-danger"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Animation for iOS!</p>
                                    </div>
                                </div>
                                <hr />-->
                                <div class="form-group">
                                    <label for="fileandroid" class="col-sm-3 control-label">File Android</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Animation file...</span>
                                            <input type="file" name="fileandroid" id="fileandroid" />
                                        </span>
                                        <span id="nmfileandroid" class="label label-danger"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Animation for Android!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileimage" class="col-sm-3 control-label">Image</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimage" id="fileimage" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/noavatar2.png" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image file of Animation!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Animation","module6","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                        if($this->m_checking->actions("Animation","module6","Import",TRUE,FALSE,"home"))
                        {
                        ?>
                        <div class="tab-pane" id="importdata">
                            <form method="POST" action="<?php echo $this->template_admin->link("inventory/animation/processimport"); ?>" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="fileimport" class="col-sm-3 control-label">File Export</label>
                                    <div class="col-sm-3">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimport" id="fileimport" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn-blue btn"> <i class="icon-cloud-upload"></i> <span>Process Import</span></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File must be format (*.txt)!</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                        }
                        ?>
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("inventory/animation/cruid_animation"); ?>" enctype="multipart/form-data">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <input type="hidden" name="txtfileimgname" id="txtfileimgname2" value="" />
                                <input type="hidden" name="txtfileweb" id="txtfileweb2" value="" />
                                <input type="hidden" name="txtfileios" id="txtfileios2" value="" />
                                <input type="hidden" name="txtfileandroid" id="txtfileandroid2" value="" />
                                <label for="name2">Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="description2">Description</label></td>
                            <td><textarea name="description" id="description2" class="form-control {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="cmbgender2">Gender</label></td>
                            <td>
                                <select id="cmbgender2" name="cmbgender" class="form-control">
                                    <?php
                                    foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                    <option value="">Unisex</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="cmbpayment2">Payment</label></td>
                            <td>
                                <select id="cmbpayment2" name="cmbpayment" class="form-control">
                                    <?php 
                                    foreach($payment as $dt)
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="fileweb2">File Web</label></td>
                            <td><input type="file" name="fileweb" id="fileweb2" /><span id="nmfileweb2" class="label label-info"></span></td>
                        </tr>
                        <!--<tr>
                            <td><label for="fileios2">File iOS</label></td>
                            <td><input type="file" name="fileios" id="fileios2" /><span id="nmfileios2" class="label label-info"></span></td>
                        </tr>-->
                        <tr>
                            <td><label for="fileandroid2">File Android</label></td>
                            <td><input type="file" name="fileandroid" id="fileandroid2" /><span id="nmfileandroid2" class="label label-info"></span></td>
                        </tr>
                        <tr>
                            <td><label for="fileimage2">Image</label></td>
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
                            <td><img id="filepreview2" src="<?php echo base_url(); ?>resources/image/noavatar2.png" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Animation","module6","Edit",TRUE,FALSE,"home"))
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