<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $( "#generate , #generate2" ).click(function() { 
        var id_html =  Math.floor(Math.random() * 10000);//Math.random().toString(36).substring(7)
        $("#brand_id").val(id_html); 
        $("#brand_id2").val(id_html);
        return false; 
    });
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
    $('#fileimagebanner').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagebanner"); ?>',
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
                $("#txtfileimgnamecontent").val(data.result.name);
                $("#filepreviewbanner").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimagebanner2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagebanner"); ?>',
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
                $("#txtfileimgnamecontent2").val(data.result.name);
                $("#filepreviewbanner2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("brand/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, {"sClass" : "text-center","bSortable": false}, {"bSortable": false}, {"bSortable": false}, { "sClass" : "text-center","bSortable": false }]
    });
    $(".resetform").click(function(){
        $("input, textarea").val("");
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
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
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreviewbanner").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
                        $("#filepreviewbanner2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
function ubahdata(id,brand_id,name,fileimage,filecontent,descriptions)
{
    $('#txtid2').val(id);
    $('#brand_id2').val(brand_id);
    $('#name2').val(name);
    $('#txtfileimgname2').val(fileimage);
    $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+fileimage);
    $('#txtfileimgnamecontent2').val(filecontent);
    $("#filepreviewbanner2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+filecontent);
    $('#descriptions2').val(descriptions);
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>brand/delete/'+id;
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
function changestate(id,state)
{
    var url='<?php echo base_url(); ?>brand/changestate/' + id + '/' + state;
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
</script>
<?php 
if($this->m_checking->actions("Brand","module2","Export",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <h1>Store</h1>
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("brand/import/html"); ?>" target="_blank">HTML File (*.html)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("brand/import/doc"); ?>" target="_blank">Word File (*.doc)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("brand/import/xls"); ?>" target="_blank">Excel File (*.xlsx)</a></li>
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
                    <h4>Store</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Stores</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Store</a></li>
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
                            <div class="table-responsive table-flipscroll">
                                <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">ID</th>
                                            <th width="20%">Name</th>
                                            <th width="15%">Picture Header</th>
                                            <th width="15%">Picture Poster</th>
                                            <th width="10%">State</th>
                                            <th width="15%">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6">No Data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST"  enctype="multipart/form-data" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("brand/cruid_brand"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                                    <input type="hidden" name="txtfileimgnamecontent" id="txtfileimgnamecontent" value="" />
                                    <label for="brand_id" class="col-sm-3 control-label">ID</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="brand_id" id="brand_id" value="" maxlength="255" placeholder="ID" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="generate" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of store!</p>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="descriptions" class="col-sm-3 control-label">Descriptions</label>
                                    <div class="col-sm-6">
                                        <textarea name="descriptions" id="descriptions" class="form-control" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of store!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimage" class="col-sm-3 control-label">Header Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimage" id="fileimage" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image Header of store!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimagebanner" class="col-sm-3 control-label">Poster Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimagebanner" id="fileimagebanner" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreviewbanner" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image Poster of store!</p>
                                    </div>
                                </div>                                                              
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Brand","module2","Add",TRUE,FALSE,"home"))
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
                <?php
                $this->load->view("form_data_brand"); 
                ?>
            </div>
        </div>
    </div>
</div>