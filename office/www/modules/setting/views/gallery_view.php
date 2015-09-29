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
    $('.select').select2({width: 'resolve'});
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("setting/gallery/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, { "sClass" : "text-center"}, null, { "sClass" : "text-center","bSortable": false }]
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
function ubahdata(id,title,keyword,imgurl)
{
    $("#txtid2").val(id);
    $("#title2").val(title);
    $("#searchcategory2").val(keyword);
    $("#imageurl2").val(imgurl);
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>setting/gallery/delete/'+id;
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
                    <h4>Gallery</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Gallery</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Gallery</a></li>
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
                                        <th width="35%">Title</th>
                                        <th width="30%">Image</th>
                                        <th width="25%">Category</th>
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
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("setting/gallery/cruid_gallery"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="title" class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-9">
                                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="Title" class="form-control {required:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="searchcategory" class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-9">
                                        <select id="searchcategory" name="searchcategory" class="select {required:true}" style="width:100%">
                                            <option value=''>&nbsp;</option>
                                            <?php 
                                            foreach($searchcategory as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
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
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Gallery","module6","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("setting/gallery/cruid_gallery"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td><input type="hidden" name="txtid" id="txtid2" value="" /><label for="title2">Title</label></td>
                            <td><input type="text" name="title" id="title2" value="" maxlength="255" placeholder="Title" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="searchcategory2">Category</label></td>
                            <td>
                                <select id="searchcategory2" name="searchcategory" class="form-control {required:true}">
                                    <option value=''>&nbsp;</option>
                                    <?php 
                                    foreach($searchcategory as $dt)
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
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
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Gallery","module6","Edit",TRUE,FALSE,"home"))
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