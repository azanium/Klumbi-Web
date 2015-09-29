<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("setting/article/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, {"bSortable": false },{ "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $("#brandfrm, #editbrandfrm").validate({});
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
    });
});
function getlink(dtlink)
{
    BootstrapDialog.alert(dtlink, function(result){
        
    });
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>setting/article/delete/'+id;
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
function list_detail(url,postdata,type)
{
    $.ajax({
        type: 'POST',
        url: url.toString(),
        data: postdata,
        dataType: type,
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
						'<strong>Warning!</strong> ' +
                                                '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                '<i class="process">Wait a minute, Your request being processed</i>' +
						'</div>').slideDown(100);
        },
        success: function(result) {
            $("#loadingprocess").slideUp(100);
            $("#responsemenu").html(result);
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
</script>
<div id="confirmdlg">&nbsp;</div>
<div id="responsemenu">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>FAQ, Term &amp; Conditions</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Content</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Content</a></li>
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
                                        <th width="40%">Title</th>
                                        <th width="20%">Alias</th>
                                        <th width="20%">State</th>
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
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("setting/article/cruid_article"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="title" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="Title" class="form-control {required:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alias" class="col-sm-2 control-label">Alias</label>
                                    <div class="col-sm-10">
                                      <input type="text" name="alias" id="alias" value="" maxlength="255" placeholder="Alias" class="form-control {required:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="state" class="col-sm-2 control-label">State</label>
                                    <div class="col-sm-10">
                                        <select id="state" name="state" class="form-control">
                                            <?php 
                                            foreach($this->tambahan_fungsi->document_state() as $dt=>$value)
                                            {
                                                echo "<option value='".$dt."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descmobile" class="col-sm-2 control-label">Content Mobile</label>
                                    <div class="col-sm-10">
                                        <textarea name="descmobile" id="descmobile" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contentarticle" class="col-sm-2 control-label">Content Web</label>
                                    <div class="col-sm-10">
                                        <textarea name="contentarticle" id="<?php echo $classword; ?>" class="<?php echo $classword; ?> form-control {number:true}" cols="45" rows="10"></textarea>
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
<script type="text/javascript">
CKEDITOR.replace('<?php echo $classword; ?>',{
    fullPage : true
});
</script>