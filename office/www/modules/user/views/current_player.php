<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": false,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("user/current_player/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }]
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
    getcount();
});
function getcount()
{
    var url='<?php echo $this->template_admin->link("user/current_player/count"); ?>';
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
                        $("#jmlcount").html(data['count']);
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
                    setTimeout("getcount()",10000);
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
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Users</li>
        <li class="active">Current Player</li>
    </ol>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Current Players</h4>
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
                                <th width="20%">Name</th>
                                <th width="20%">Birth Day</th>
                                <th width="20%">Gender</th>
                                <th width="15%">Room</th>
                                <th width="20%">Start Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6">No Data</td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
                <div class="panel-footer">
                    <div class="col-md-4 col-md-offset-4"><h1 id="jmlcount" align="center" style="font-size: 50px;"><?php echo $count; ?></h1></div>
                </div>
            </div>
        </div>
    </div>
</div>