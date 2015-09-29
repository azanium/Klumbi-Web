<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #iframeimagemanager{
        display: none;
        position: absolute;
        width: 670px;
        height: 400px;
        background: #e0dfde;
        border: 2px solid #3687e2;
        border-radius: 6px;
        -moz-border-radius: 6px;
        -webkit-border-radius: 6px;
        padding: 1px;
    }
</style>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("setting/slideshow/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center"}, null, { "sClass" : "text-center","bSortable": false }]
    });
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $( "#browsebutton , #browsebutton2" ).click(function() { 
        openKCFinder();
        return false; 
    });
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
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
                            $("input, textarea").val("");
                            $('#editdata').modal('hide');
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
function ubahdata(id)
{
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>setting/slideshow/detail/'+id,
        data:'',
        dataType: "json",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                    '<i class="process">Wait a minute, Your request being processed</i>' +
                                    '</div>').slideDown(100);
        },
        success: function (data, textStatus) {
            if(data['success']==true)
            {
                $('#txtid2').val(data['_id']);
                $('#ordered2').val(data['no']);
                $('#title2').val(data['title']);
                $('#imageurl2').val(data['image']);
                $('#linkdata2').val(data['link']);
                $('#description2').val(data['description']);
            }
            else
            {
                $.pnotify({
                    title: "Fail",
                    text: data['message'],
                    type: 'info'
                });
            }
                $("#loadingprocess").slideUp(100);
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>setting/slideshow/delete/'+id;
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
function openKCFinder() 
{
    window.KCFinder = {
        callBack: function(url) {
            var newurl = location.protocol + '//' + location.hostname + url;
            $("#imageurl").val(newurl);
            $("#imageurl2").val(newurl);          
            window.KCFinder = null;
        }
    };
    window.open('<?php echo base_url(); ?>resources/plugin/kcfinder/browse.php?type=images&dir=bundels/public', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}
</script>
<div id="iframeimagemanager">&nbsp;</div>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Slideshow</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Slideshow</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Slideshow</a></li>
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
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="5%">Ordered</th>
                                        <th width="35%">Title</th>
                                        <th width="25%">Image</th>
                                        <th width="25%">Link</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("setting/slideshow/cruid_slideshow"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="ordered" class="col-sm-3 control-label">Ordered</label>
                                    <div class="col-sm-9"><input type="number" name="ordered" id="ordered" value="" step="1" min="0" max="1000" maxlength="255" placeholder="Ordered" class="form-control {number:true}" /></div>
                                </div>
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-9">
                                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="Title" class="form-control {required:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="imageurl" class="col-sm-3 control-label">Url Image</label>
                                    <div class="col-sm-7">
                                        <input type="url" name="imageurl" id="imageurl" value="" maxlength="255" placeholder="http://" class="form-control {required:true,url:true}" />
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" id="browsebutton" class="btn-orange btn"> <i class="icon-search"></i> <span>Browse</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="linkdata" class="col-sm-3 control-label">Link Site</label>
                                    <div class="col-sm-9">
                                        <input type="url" name="linkdata" id="linkdata" value="" maxlength="255" placeholder="http://" class="form-control {url:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                      <textarea name="description" id="description" class="form-control {required:true}" cols="15" rows="20"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Slideshow","module6","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("setting/slideshow/cruid_slideshow"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="ordered2">Ordered</label>
                            </td>
                            <td>
                                <input type="number" name="ordered" id="ordered2" value="" step="1" min="0" max="1000" maxlength="255" placeholder="Ordered" class="form-control {number:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="title2">Title</label></td>
                            <td><input type="text" name="title" id="title2" value="" maxlength="255" placeholder="Title" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="imageurl2">Url Image</label></td>
                            <td>
                                <div class="col-sm-9">
                                    <input type="url" name="imageurl" id="imageurl2" value="" maxlength="255" placeholder="http://" class="form-control {required:true,url:true}" />
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="browsebutton2" class="btn-orange btn"> <i class="icon-search"></i> <span>Browse</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="linkdata2">Link Site</label></td>
                            <td><input type="url" name="linkdata" id="linkdata2" value="" maxlength="255" placeholder="http://" class="form-control {url:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="description2">Description</label></td>
                            <td><textarea name="description" id="description2" class="form-control {required:true}" cols="15" rows="20"></textarea></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Slideshow","module6","Edit",TRUE,FALSE,"home"))
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