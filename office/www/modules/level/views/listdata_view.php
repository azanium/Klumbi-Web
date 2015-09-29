<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('#txtfileimport').fileupload({
        url: $("#brandfrmimport").attr('action'),
        dataType: 'json',
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                dttable.fnClearTable(0);
                dttable.fnDraw();
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
        "bFilter": true,
        "sScrollX": "100%",        
//        "bScrollInfinite": true,
//        "bScrollCollapse": true,
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "100%",
        "sScrollY": "400px",      
        "bAutoWidth": false,
        "bJQueryUI": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("level/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, null, null, null, {"sClass" : "text-center","bSortable": false }, {"sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"brandviews", "value":$("#brandviews").val()});
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
    $("#brandfrm").validate();
    $('.select').select2({width: 'resolve'});
    $('#txttag').select2({width: "resolve", tags:<?php echo json_encode($this->tambahan_fungsi->list_tag()); ?>});
});
function reloaddatatable(id)
{
    dttable=$('#datatable').dataTable( {
        "bFilter": true,
        "sScrollX": "100%",
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "110%",
        "sScrollY": "400px",      
        "bAutoWidth": false,
        "sScrollXInner": "110%",
        "bJQueryUI": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("level/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, null, null, null, {"sClass" : "text-center","bSortable": false }, {"sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"brandviews", "value":$("#brandviews").val()});
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
function lihatdetail(id)
{
    var url='<?php echo base_url(); ?>level/detail/'+id;
    $.ajax({
        type: "POST",
        url: url,
        data:"",
        dataType: "html",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                        '<strong>Warning!</strong> ' +
                                        '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                        '<i class="process">Wait a minute, Your request being processed</i>' +
                                        '</div>').slideDown(100);
        },
        success: function (data, textStatus) {
            $("#loadingprocess").slideUp(100);
            $("#contentleveledit").html(data);                   
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
            var url='<?php echo base_url(); ?>level/delete/'+id;
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
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Level</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Levels</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Level</a></li>
                          <li><a href="#importdata" data-toggle="tab"><i class="icon-cloud-upload"></i> Import Level</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata">
                            <p align="right">
                                <?php 
                                if($this->m_checking->actions("Level","module6","Export",TRUE,FALSE,"home"))
                                {
                                    echo '<a href="'.$this->template_admin->link("level/export").'" target="_blank" class="btn btn-sm btn-brown" ><i class="icon-cloud-download"></i> Export Data</a>&nbsp;&nbsp;'; 
                                }
                                ?>
                                <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                            </p>
                            <?php
                            if($this->session->userdata('brand')=="")
                            {
                            ?>
                            <p align="right">
                                <select id="brandviews" name="brandviews" onchange="reloaddatatable(this.value)" class="inputbox lengkung_4 pading_5">
                                    <option value="">All</option>
                                    <?php 
                                    foreach($listbrand as $dt)
                                    {
                                        echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </p>
                            <?php
                            }
                            ?>                            
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Name</th>
                                        <th width="15%">Store</th>
                                        <th width="15%">Category</th>
                                        <th width="15%">Preview</th>
                                        <th width="15%">Download</th>
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
                            <form method="POST" class="form-horizontal" enctype="multipart/form-data" id="brandfrm" action="<?php echo $this->template_admin->link("level/add_new"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="lvlname" class="col-sm-3 control-label">Level Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="lvlname" id="lvlname" value="" maxlength="255" placeholder="Level Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Level!</p>
                                    </div>
                                </div>
                                <?php
                                if($this->session->userdata('brand')=="")
                                {
                                ?>
                                <div class="form-group">
                                    <label for="brand" class="col-sm-3 control-label">Store</label>
                                    <div class="col-sm-6">
                                        <select id="brand" name="brand" class="form-control">
                                            <option value="">&nbsp;</option>
                                            <?php 
                                            foreach($listbrand as $dt)
                                            {
                                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Store of Level!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>                                
                                <div class="form-group">
                                    <label for="txtcategory" class="col-sm-3 control-label">Category Content</label>
                                    <div class="col-sm-6">
                                        <select id="txtcategory" name="txtcategory" class="form-control">
                                            <option value="">&nbsp;</option>
                                            <?php 
                                            foreach($listcategory as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Category Content of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="filelvl" class="col-sm-3 control-label">File Level Web</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="filelvl" id="filelvl" class="{required:true}" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Packed Web of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="filelvl_ios" class="col-sm-3 control-label">File Level iOS</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="filelvl_ios" id="filelvl_ios" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Packed iOS of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="filelvl_android" class="col-sm-3 control-label">File Level Android</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="filelvl_android" id="filelvl_android" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Packed Android of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileskybox" class="col-sm-3 control-label">File Skybox Web</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileskybox" id="fileskybox" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Skybox Web of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileskybox_ios" class="col-sm-3 control-label">File Skybox iOS</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileskybox_ios" id="fileskybox_ios" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Skybox iOS of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileskybox_android" class="col-sm-3 control-label">File Skybox Android</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileskybox_android" id="fileskybox_android" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Skybox Android of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileaudio" class="col-sm-3 control-label">File Audio Web</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileaudio" id="fileaudio" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Audio Web of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileaudio_ios" class="col-sm-3 control-label">File Audio iOS</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileaudio_ios" id="fileaudio_ios" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Audio iOS of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileaudio_android" class="col-sm-3 control-label">File Audio Android</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileaudio_android" id="fileaudio_android" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Audio Android of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimg" class="col-sm-3 control-label">File Image</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimg" id="fileimg" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Image of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttag" class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-6">
                                        <textarea name="txttag" id="txttag" style="width:100%" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Tag of Level!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtserver" class="col-sm-3 control-label">Server Address</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="txtserver" id="txtserver" value="" maxlength="255" placeholder="IP Address" class="form-control" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">IP Address of server!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtport" class="col-sm-3 control-label">Server PORT</label>
                                    <div class="col-sm-6">
                                      <input type="number" name="txtport" id="txtport" value="" step="1" min="0" max="10000" maxlength="255" placeholder="PORT" class="form-control {number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Port number of server!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtchanel" class="col-sm-3 control-label">Number of Channels</label>
                                    <div class="col-sm-6">
                                      <input type="number" name="txtchanel" id="txtchanel" value="" step="1" min="0" max="1000" maxlength="255" placeholder="Number of Channels" class="form-control {number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Number Channels of server!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtccu" class="col-sm-3 control-label">Max CCU per Channel</label>
                                    <div class="col-sm-6">
                                      <input type="number" name="txtccu" id="txtccu" value="" step="1" min="0" max="1000" maxlength="255" placeholder="Max CCU per Channel" class="form-control {number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Max CCU of server!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">World Size</label>
                                    <div class="col-sm-5">
                                      <input type="number" name="txtwordsizex" id="txtwordsizex" value="1000" step="1" min="0" max="1000" maxlength="255" placeholder="X" class="form-control {number:true}" />
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" name="txtwordsizey" id="txtwordsizey" value="1000" step="1" min="0" max="1000" maxlength="255" placeholder="Y" class="form-control {number:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Interest Area</label>
                                    <div class="col-sm-5">
                                      <input type="number" name="txtintareax" id="txtintareax" value="1000" step="1" min="0" max="1000" maxlength="255" placeholder="X" class="form-control {number:true}" />
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" name="txtintareay" id="txtintareay" value="1000" step="1" min="0" max="1000" maxlength="255" placeholder="Y" class="form-control {number:true}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Level","module6","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="importdata">
                            <?php 
                            if($this->m_checking->actions("Level","module6","Import",TRUE,FALSE,"home"))
                            {
                            ?>
                            <form method="POST" class="form-horizontal" id="brandfrmimport" enctype="multipart/form-data" action="<?php echo $this->template_admin->link("level/import"); ?>">
                                <div class="form-group">
                                    <label for="txtfileimport" class="col-sm-3 control-label">File Import</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="txtfileimport" id="txtfileimport" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File Import!</p>
                                    </div>
                                </div>
                            </form>
                            <?php
                            }
                            else
                            {
                                echo "<p class='error'>Sorry, You are not allowed to access this page</p>";
                            }
                            ?>
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
            <div id="contentleveledit" class="modal-body">&nbsp;</div>
        </div>
    </div>
</div>