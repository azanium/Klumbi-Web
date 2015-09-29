<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable, dttablequest;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/report/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "sScrollX": "100%",
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "100%",              
        "bAutoWidth": true,
        'aoColumns': [ null, null, null, null, null, { "sClass" : "text-center" }, { "sClass" : "text-center" }]
    });
    dttablequest=$('#datatablequest').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/report/list_dataquest"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "sScrollX": "100%",
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "100%",              
        "bAutoWidth": true,
        'aoColumns': [ null, null, null, null, { "sClass" : "text-center" }, { "sClass" : "text-center" }]
    });
    $('#datatablequestdrjumlah , #datatablequestdractive').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "sScrollX": "100%",
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "100%",  
        "bScrollCollapse": true,
        "aaSortingFixed": [ [0, 'desc'] ],
        "aaSorting": [[ 0, 'desc' ]],
        "aoColumnDefs": [
			{ "bVisible": false, "aTargets": [0] }
		],
        "bAutoWidth": true,
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ null, null, null, null, null, { "sClass" : "text-center" }],
        "fnDrawCallback": function ( oSettings ) {
                if ( oSettings.aiDisplay.length == 0 )
                {
                        return;
                }
                var nTrs = $('tbody tr', oSettings.nTable);
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = "";
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                        var iDisplayIndex = oSettings._iDisplayStart + i;
                        var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
                        if ( sGroup != sLastGroup )
                        {
                                var nGroup = document.createElement( 'tr' );
                                var nCell = document.createElement( 'td' );
                                nCell.colSpan = iColspan;
                                nCell.className = "group";
                                nCell.innerHTML = sGroup;
                                nGroup.appendChild( nCell );
                                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                                sLastGroup = sGroup;
                        }
                }
        }
    });
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
    });
    $('#reloadquest').click(function(){
        dttablequest.fnClearTable(0);
	dttablequest.fnDraw();
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
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
function lihatdetailquest(jsn,id)
{
    var url='<?php echo base_url(); ?>quest/report/quest_quest/'+jsn+'/'+id;
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
function detailquestjournal(id)
{
    var url='<?php echo base_url(); ?>quest/report/quest_journal/'+id;
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
<div id="responsemenu"></div>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Report Quest</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata-0" data-toggle="tab"><i class="icon-table"></i> Players</a></li>
                          <li><a href="#listdata-1" data-toggle="tab"><i class="icon-table"></i> Quests</a></li>
                          <li><a href="#listdata-2" data-toggle="tab"><i class="icon-table"></i> Completed Quest Per Players</a></li>
                          <li><a href="#listdata-3" data-toggle="tab"><i class="icon-table"></i> Active Quest Per Players</a></li>
                          <li><a href="#listdata-4" data-toggle="tab"><i class="icon-table"></i> Rank Quests</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata-0">
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
                        <div class="tab-pane active" id="listdata-1">
                            <p align="right">
                                <a id="reloadquest" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                            </p>
                            <table id="datatablequest" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="10%" rowspan="2">ID</th>
                                        <th width="40%" rowspan="2">Quest</th>
                                        <th width="20%" rowspan="2">Start Date</th>
                                        <th width="20%" rowspan="2">End Date</th>
                                        <th width="10%" colspan="2">Players</th>
                                    </tr>
                                    <tr>
                                        <th>Finish</th>
                                        <th>Current</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane active" id="listdata-2">
                            <table id="datatablequestdrjumlah" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th>Jumlah</th>
                                        <th width="25%">Full Name</th>
                                        <th width="25%">Email</th>
                                        <th width="25%">Twitter</th>
                                        <th width="15%">Handphone</th>                        
                                        <th width="15%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($countperjml as $dt=>$val)
                                    {
                                        $detail=$this->template_icon->detail_onclick("detailquestjournal('".$val['_id']."')","",'Detail',"grid.png","","linkdetail");            
                                        echo "<tr>";
                                        echo "<td>".$val['count']."</td>";
                                        echo "<td>".$val['fullname']."</td>";
                                        echo "<td>".$val['email']."</td>";
                                        echo "<td>".$val['twitter']."</td>";
                                        echo "<td>".$val['phone']."</td>";                        
                                        echo "<td align='center'>".$detail."</td>";
                                        echo "</tr>";
                                    }                    
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane active" id="listdata-3">
                            <table id="datatablequestdractive" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th>Jumlah</th>
                                        <th width="25%">Full Name</th>
                                        <th width="25%">Email</th>
                                        <th width="25%">Twitter</th>
                                        <th width="15%">Handphone</th>                        
                                        <th width="15%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($countperactive as $dt=>$val)
                                    {
                                        $detail=$this->template_icon->detail_onclick("detailquestjournal('".$val['_id']."')","",'Detail',"grid.png","","linkdetail");            
                                        echo "<tr>";
                                        echo "<td>".$val['count']."</td>";
                                        echo "<td>".$val['fullname']."</td>";
                                        echo "<td>".$val['email']."</td>";
                                        echo "<td>".$val['twitter']."</td>";
                                        echo "<td>".$val['phone']."</td>";                        
                                        echo "<td align='center'>".$detail."</td>";
                                        echo "</tr>";
                                    }                    
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane active" id="listdata-4">
                            <h2 align="center">Rank Quest Selesai Dimainkan</h2>
                            <table id="rankquestjournal" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="10%" rowspan="2">ID</th>
                                        <th width="40%" rowspan="2">Quest</th>
                                        <th width="20%" rowspan="2">Start Date</th>
                                        <th width="20%" rowspan="2">End Date</th>
                                        <th width="10%" colspan="2">Count Players</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rankjournal as $val)
                                        {
                                            echo "<tr>";
                                            echo "<td>".$val['ID']."</td>";
                                            echo "<td>".$val['Description']."</td>";
                                            echo "<td>".$val['tglawal']."</td>";
                                            echo "<td>".$val['tglakhir']."</td>";
                                            echo "<td>".$val['count']."</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                </tbody>
                            </table>
                            <h2 align="center">Rank Quest Sedang Dimainkan</h2>
                            <table id="rankquestactive" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="10%" rowspan="2">ID</th>
                                        <th width="40%" rowspan="2">Quest</th>
                                        <th width="20%" rowspan="2">Start Date</th>
                                        <th width="20%" rowspan="2">End Date</th>
                                        <th width="10%" colspan="2">Count Players</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rankactive as $val)
                                        {
                                            echo "<tr>";
                                            echo "<td>".$val['ID']."</td>";
                                            echo "<td>".$val['Description']."</td>";
                                            echo "<td>".$val['tglawal']."</td>";
                                            echo "<td>".$val['tglakhir']."</td>";
                                            echo "<td>".$val['count']."</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>