<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() { 
    $('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bFilter": true,
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
    });    
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $('#reload').click(function(){
        reloaddatatable();
    });
    $("#startdate").datepicker({
			showOtherMonths: true,
			selectOtherMonths: true,
                        showButtonPanel: true,
                        changeMonth: true,
			changeYear: true,
                        defaultDate: "+1w",
                        dateFormat:"m/d/yy",
			yearRange: '2012:2020'
    });
    dttable=$('#datatableplayer').dataTable( {
        "bJQueryUI": true,
        "bFilter": false,
        "bDestroy": true,
        "bProcessing": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("user/statistik/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [{"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "alignRight","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"tglshow", "value":$("#startdate").val()});
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
});
function lihatdetail(room)
{
    var url='<?php echo base_url(); ?>user/statistik/chart/'+room;
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
            $("#responsemenu").html(data);   
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
function reloaddatatable()
{
    dttable=$('#datatableplayer').dataTable( {
        "bJQueryUI": true,
        "bFilter": false,
        "bDestroy": true,
        "bProcessing": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("user/statistik/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "alignRight","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"tglshow", "value":$("#startdate").val()});
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
<div id="responsemenu">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Statistik Rooms Player</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab-1" data-toggle="tab"><i class="icon-hospital"></i> Room</a></li>
                          <li><a href="#tab-2" data-toggle="tab"><i class="icon-dribbble"></i> Player</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-1">
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="90%">Name</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i=1;
                                    foreach($listdt['values'] as $dt)
                                    {
                                        $priview_current="";
                                        if($this->m_checking->actions("Player Room Statistics","module2","Reporting Graph",TRUE,FALSE,"home"))
                                        {
                                            $priview_current=$this->template_icon->detail_onclick("lihatdetail('".$dt."')","",'Result',"chart_line.png","","linkdelete");
                                        } 
                                        echo "<tr>";
                                        echo "<td>".$i."</td>";
                                        echo "<td>".$dt."</td>";
                                        echo "<td>".$priview_current."</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab-2">
                            <p align="right">
                                <input type="text" name="startdate" id="startdate" value="" maxlength="255" class="inputbox lengkung_4 pading_5" />&nbsp;&nbsp;
                                <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                            </p>
                            <table id="datatableplayer" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="15%">Full Name</th>
                                        <th width="15%">Gender</th>
                                        <th width="15%">Twitter</th>
                                        <th width="15%">Handphone</th>
                                        <th width="20%">Room</th>
                                        <th width="10%">Date</th>
                                        <th width="10%">Visit</th>
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
    </div>
</div>