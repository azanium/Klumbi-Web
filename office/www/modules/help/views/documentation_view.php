<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("help/documentation/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }]
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
});
function detaildata(id)
{
    var url='<?php echo base_url(); ?>help/documentation/detail/'+id;
    $.ajax({
            type: "POST",
            url: url,
            data:"",
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#iframedetaildt ,#iframedetaildt2").html('<div class="alert alert-dismissable alert-warning">' +
                                        '<strong>Warning!</strong> ' +
                                        '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                        '<i class="process">Wait a minute, Your request being processed</i>' +
                                        '</div>');
            },
            success: function (data, textStatus) {
                if(data['success']==true)
                {                        
                    $("#iframedetaildt , #iframedetaildt2").html(data['content']);
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
                $("#iframedetaildt, #iframedetaildt2").html("<h4>" + textStatus + " " + xhr.status + "</h4>"+ (errorThrown ? errorThrown : xhr.status));
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
<div id="page-heading">
    <h1>API Documentation</h1>
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("help/documentation/import/html"); ?>" target="_blank">HTML File (*.html)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("help/documentation/import/doc"); ?>" target="_blank">Word File (*.doc)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("help/documentation/import/xls"); ?>" target="_blank">Excel File (*.xlsx)</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>API Doc</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="40%">Name</th>
                                <th width="50%">Descriptions</th>
                                <th width="5%">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">No Data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div id="iframedetaildt2">&nbsp;</div>
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
                <h4 class="modal-title">Detail Data</h4>
            </div>
            <div class="modal-body">
                <div id="iframedetaildt">&nbsp;</div>
            </div>
        </div>
    </div>
</div>