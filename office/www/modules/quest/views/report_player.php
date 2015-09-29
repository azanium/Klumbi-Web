<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sScrollX": "100%",
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "100%",              
        "bAutoWidth": true,        
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/report/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ null, null, null, null, null, { "sClass" : "text-center" }, { "sClass" : "text-center" }]
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
function lihatdetail(jsn,id)
{
    var url='<?php echo base_url(); ?>quest/report/quest_player/'+jsn+'/'+id;
    $.ajax({
                type: "GET",
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
                    $('#responsemenu').html(data);                       
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
                    <h4>Players</h4>
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
                                <th width="20%" rowspan="2">User Name</th>
                                <th width="15%" rowspan="2">Email</th>
                                <th width="20%" rowspan="2">Full Name</th>                        
                                <th width="15%" rowspan="2">Twitter</th>
                                <th width="20%" rowspan="2">Handphone</th>
                                <th width="10%" colspan="2">Quest</th>
                            </tr>
                            <tr>
                                <th>Completed</th>
                                <th>Current</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7">No Data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>