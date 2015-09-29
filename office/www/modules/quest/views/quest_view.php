<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
var dttablechile;
$(document).ready(function() {
    $( "#generate , #generate2" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $('textarea.autosize').autosize({append: "\n"});
    $("#txtdatebegin, #txtdatebegin2").datepicker({
        dateFormat:"yy-mm-dd",
        selectOtherMonths: true,
        yearRange: '2013:2020',
        defaultDate: +7,
        autoSize: true,
        appendText: '(yyyy-mm-dd)'
    });
    $("#txtdatebegin , #txtdatebegin2").change(function() {
        var test = $(this).datepicker('getDate');
        var testm = new Date(test.getTime());
        testm.setDate(testm.getDate());
        $("#txtdateend").datepicker("option", "minDate", testm);
        $("#txtdateend2").datepicker("option", "minDate", testm);
        $( "#txtdatebegin" ).focus();
        $( "#txtdatebegin2" ).focus();
    });
    $( "#txtdateend , #txtdateend2" ).datepicker({
        selectOtherMonths: true,
        appendText: '(yyyy-mm-dd)',
        dateFormat: 'yy-mm-dd'
    });
    $('#datepickerbeginbtn').click(function () {
        $('#txtdatebegin').datepicker("show");
    });
    $('#datepickerbeginbtn2').click(function () {
        $('#txtdatebegin2').datepicker("show");
    });
    $('#datepickerendbtn').click(function () {
        $('#txtdateend').datepicker("show");
    });
    $('#datepickerendbtn2').click(function () {
        $('#txtdateend2').datepicker("show");
    });
    $("#txttimebegin , #txttimeend , #txttimebegin2 , #txttimeend2").timepicker({
        hours: { starts: 4, ends: 22 },
        minutes: { interval: 5 },
        rows: 3,
        showPeriodLabels: true,
        showPeriod: true,
        showLeadingZero: true,
        timeFormat: 'H:i:s',
        minuteText: 'Min'
    });
    $('#timepickerbeginbtn').click(function () {
        $('#txttimebegin').timepicker("show");
    });
    $('#timepickerbeginbtn2').click(function () {
        $('#txttimebegin2').timepicker("show");
    });
    $('#timepickerendbtn').click(function () {
        $('#txttimeend').timepicker("show");
    });
    $('#timepickerendbtn2').click(function () {
        $('#txttimeend2').timepicker("show");
    });
    $( "#getquestid , #getquestid2" ).click(function() { 
        showlist_quest();
        return false; 
    });
    $( "#getrequiredid , #getrequiredid2" ).click(function() { 
        list_source_required();
        return false; 
    });
    $( "#getrewardid , #getrewardid2" ).click(function() { 
        list_source_reward();
        return false; 
    });
    $( "#getrewardid_2, #getrewardid_22" ).click(function() { 
        list_source_avatar();
        return false; 
    });
    $( "#getrequiredadd" ).click(function() { 
        var jml=$("#countrequired").val();
        var kode=$("#cmbtipecode").val();
        if(jml!="" && kode!=null)
        {
            var id="rnd_"+Math.floor((Math.random()*10000)+1)+"akhir";
            var content="<p id='"+id+"'>";
            content +="<input type='hidden' name='requiredpilih[]' value='"+kode+","+jml+"' />";
            content +="<a href='#' onclick='removerequired(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
            content +="&nbsp;&nbsp;&nbsp;"+kode+","+jml;
            content +="</p>";
            $("#listrequiredadd").append(content);
            $("#countrequired").val('1');
        }
        return false; 
    });
    $( "#getrewardadd" ).click(function() { 
        var jml=$("#countreward").val();
        var kode=$("#cmbtiperewardcode").val();
        if(jml!="" && kode!=null)
        {
            var id="rnd_"+Math.floor((Math.random()*10000)+1)+"reward";
            var content="<p id='"+id+"'>";
            content +="<input type='hidden' name='rewardpilih[]' value='"+kode+","+jml+"' />";
            content +="<a href='#' onclick='removerequired(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
            content +="&nbsp;&nbsp;&nbsp;"+kode+","+jml;
            content +="</p>";
            $("#listrewardadd").append(content);
            $("#countreward").val('1');
        }
        return false; 
    });
    $( "#getrewardadd2" ).click(function() { 
        var jml=$("#countreward2").val();
        var kode="x:"+$("input[name='avatarid']:checked").val();
        if(jml!="" && kode!=null)
        {
            var id="rnd_"+Math.floor((Math.random()*10000)+1)+"reward2";
            var content="<p id='"+id+"'>";
            content +="<input type='hidden' name='rewardpilih2[]' value='"+kode+","+jml+"' />";
            content +="<a href='#' onclick='removerequired(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
            content +="&nbsp;&nbsp;&nbsp;"+kode+","+jml;
            content +="</p>";
            $("#listrewardadd2").append(content);
            $("#countreward2").val('1');
        }
        return false; 
    });
    $("#tblsetrequired").click(function(){
        var valuetext="";
        $('input[name="requiredpilih[]"]').each(function(){                                
            valuetext=valuetext+$(this).val()+"|";
          });
        valuetext=valuetext.substring(0,valuetext.length-1);
        $("#requireditem").val(valuetext);
        $("#requireditem2").val(valuetext);
        $("#listrequiredadd").html('');
        $("#countrequired").val('1');
        $("#innerframedialogrequired").modal('hide');
    });
    $("#tblsetreward").click(function(){
        var valuetext="";
        $('input[name="rewardpilih[]"]').each(function(){                                
            valuetext=valuetext+$(this).val()+"|";
          });
        valuetext=valuetext.substring(0,valuetext.length-1);
        var texttamp=$.trim($("#rewards").val());
        if(texttamp!='')
        {
            $("#rewards").val(texttamp+"|"+valuetext);
            $("#rewards2").val(texttamp+"|"+valuetext);
        }
        else
        {
            $("#rewards").val(valuetext);
            $("#rewards2").val(valuetext);
        }
        $("#listrewardadd").html('');
        $("#countreward").val('1')
        $("#innerframedialogreward").modal('hide');
    });
    $("#tblsetreward_2").click(function(){
        var valuetext="";
        $('input[name="rewardpilih2[]"]').each(function(){                                
            valuetext=valuetext+$(this).val()+"|";
          });
        valuetext=valuetext.substring(0,valuetext.length-1);
        var texttamp=$.trim($("#rewards").val());
        if(texttamp!='')
        {
            $("#rewards").val(texttamp+"|"+valuetext);
            $("#rewards2").val(texttamp+"|"+valuetext);
        }
        else
        {
            $("#rewards").val(valuetext);
            $("#rewards2").val(valuetext);
        }                            
        $("#listrewardadd2").html('');
        $("#countreward2").val('1')
        $("#innerframedialogreward2").modal('hide');
    });
    dttablechile=$('#datatablechile').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": false,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/list_data_child"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight" }, null, null, { "sClass" : "text-center" }],
        "bAutoWidth": false,
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "sScrollY": "400px",        
        "sScrollXInner": "100%"
    });
    $("#filtersearch").click(function(){
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->template_admin->link("services/api/get_list_avatar_bysearch"); ?>',
            data: {'brand':$("#brand").val(), 'tipe':$("#tipe").val(), 'gender':$("#gender").val(), 'size':$("#size").val()},
            dataType: 'json',
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
            },
            success: function(data, textStatus) {
                $("#loadingprocess").slideUp(100);
                if(data['success']==true)
                {                        
                    if(data['data'].length>0)
                    {
                        var texthtml="";
                        var pathdownload="<?php echo $this->config->item('path_asset_img'); ?>preview_images/";
                        for(var i=0; i<data['data'].length;i++)
                        {
                            texthtml += "<input type='radio' name='avatarid' value='" + data['data'][i]._id + "' id='radio" + i + "' />";
                            texthtml += "<label for='radio" + i + "' >";
                            texthtml += data['data'][i].name + "<br />";
                            texthtml += "<img src='" + pathdownload + data['data'][i].preview_image + "' alt='" + data['data'][i].name + "' width='70' height='90'/>";
                            texthtml += "</label>";
                        }
                        $("#listdataavatar").html(texthtml);
                    }
                    else
                    {
                        $("#listdataavatar").html(data['message']);
                    }
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
                    $("#loadingprocess").slideUp(100);
                    $.pnotify({
                        title: textStatus + " " + xhr.status,
                        text: (errorThrown ? errorThrown : xhr.status),
                        type: 'error'
                    });
                }
        });
        return false;
    });
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "text-center","bSortable": false }, { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
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
    $("#frmduplikat").validate({
        submitHandler: function(form) {
            var url=$("#frmduplikat").attr('action');
            var datapost=$("#frmduplikat").serialize();
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
                        $('#inneropenduplikat').modal('hide');
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
                        document.forms["brandfrm"].reset();
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
                        document.forms["editbrandfrm"].reset();
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
function generatecodernd()
{
    $.ajax({
        type: "GET",
        url: '<?php echo $this->template_admin->link("quest/generatecode"); ?>',
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
                $("#code").val(data['data']); 
                $("#code2").val(data['data']);
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
function showlist_quest()
{
    dttablechile.fnClearTable(0);
    dttablechile.fnDraw();
    $("#innerframedialog").modal('show');
}
function list_source_required()
{
    $("#innerframedialogrequired").modal('show');
}
function list_source_reward()
{
    $("#innerframedialogreward").modal('show');
}
function list_source_avatar()
{
    $("#innerframedialogreward2").modal('show');
}
function selectthisitem(kodeitem)
{
    $('#requirementquest').val(kodeitem);
    $('#requirementquest2').val(kodeitem);
    $("#innerframedialog").modal('hide');
}
function reload_code(id)
{
    var url='<?php echo base_url(); ?>services/listreward/get_required_id/'+id;
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
            $("#cmbtipecode").html("");
        },
        success: function (data, textStatus) {
            $("#loadingprocess").slideUp(100);  
            $("#cmbtipecode").html(data);
            $("#countrequired").val('1');
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
function reload_codereward(id)
{
    var url='<?php echo base_url(); ?>services/listreward/get_required_id/'+id;
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
            $("#cmbtiperewardcode").html("");
        },
        success: function (data, textStatus) {
            $("#loadingprocess").slideUp(100); 
            $("#cmbtiperewardcode").html(data);
            $("#countreward").val('1');
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
function removerequired(id)
{
    $("#"+id).remove();
}
function ubahdata(id)
{
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>quest/detail/'+id,
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
            $('#txtid2').val(data['_id']);
            $('#code2').val(data['ID']);
            $('#title2').val(data['Description']);
            $('#descriptionnormal2').val(data['DescriptionNormal']);
            $('#descriptionactive2').val(data['DescriptionActive']);
            $('#descriptiondone2').val(data['DescriptionDone']);
            $('#txtdatebegin2').val(data['StartDate']);
            $('#txttimebegin2').val(data['StartTime']);
            $('#txtdateend2').val(data['EndDate']);
            $('#txttimeend2').val(data['EndTime']);
            $('#requirementquest2').val(data['Requirement']);
            $('#energyrequirement2').val(data['RequiredEnergy']);
            $('#requireditem2').val(data['RequiredItem']);
            $('#rewards2').val(data['Rewards']);
            if(data['IsActive']=="true")
            {
                $('input:radio[name=radioactive]').filter('[value="true"]').attr('checked', 'checked');
            }
            else
            {
                $('input:radio[name=radioactive]').filter('[value="false"]').attr('checked', 'checked');
            }
            if(data['IsDone']=="true")
            {
                $('input:radio[name=radiodone]').filter('[value="true"]').attr('checked', 'checked');
            }
            else
            {
                $('input:radio[name=radiodone]').filter('[value="false"]').attr('checked', 'checked');
            }
            if(data['IsReturn']=="true")
            {
                $('input:radio[name=radioreturn]').filter('[value="true"]').attr('checked', 'checked');
            }
            else
            {
                $('input:radio[name=radioreturn]').filter('[value="false"]').attr('checked', 'checked');
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
            var url='<?php echo base_url(); ?>quest/delete/'+id;
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
        }
    });
}
function duplikat(id)
{
    $("#inneropenduplikat").modal('show');
    $("#txtidduplikat").val(id);
   return false;
}
</script>
<?php
if($this->m_checking->actions("Quests","module5","Import Exl",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("quest/excell"); ?>" target="_blank">Excell file</a></li>
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
                    <h4>Quest</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Quests</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Quest</a></li>
                          <?php 
                          if($this->m_checking->actions("Quests","module5","Import Txt",TRUE,FALSE,"home"))
                          {
                          ?>
                          <li><a href="#importdata" data-toggle="tab"><i class="icon-cloud-upload"></i> Import Quest</a></li>
                          <?php 
                          }
                          ?>
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
                            <form method="GET" target="_blank" action="<?php echo $this->template_admin->link("quest/processexport"); ?>">
                                <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                        <tr>
                                            <th width="2%">No</th>
                                            <th width="3%">&nbsp;</th>
                                            <th width="3%">ID</th>
                                            <th width="7%">Quest Title</th>
                                            <th width="22%">Description Normal</th>
                                            <th width="22%">Description Active</th>                      
                                            <th width="22%">Description Done</th>
                                            <th width="3%">Active</th>
                                            <th width="3%">Done</th>
                                            <th width="3%">Return</th>
                                            <th width="10%">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="11">No Data</td>
                                        </tr>
                                    </tbody>                                
                                </table>
                                <?php 
                                if($this->m_checking->actions("Quests","module5","Import Txt",TRUE,FALSE,"home"))
                                {
                                ?>
                                <p>
                                    <button type="submit" class="btn-midnightblue btn"> <i class="icon-hdd"></i> <span>Export Selected</span></button>
                                </p>
                                <?php 
                                }
                                ?>
                            </form>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("quest/cruid_quest"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="code" class="col-sm-3 control-label">Code</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="code" id="code" value="" maxlength="255" placeholder="Code" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                    <button id="generate" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="Quest Title" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Title of Quest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descriptionnormal" class="col-sm-3 control-label">Description Normal</label>
                                    <div class="col-sm-6">
                                        <textarea name="descriptionnormal" id="descriptionnormal" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description Normal of Quest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descriptionactive" class="col-sm-3 control-label">Description Active</label>
                                    <div class="col-sm-6">
                                        <textarea name="descriptionactive" id="descriptionactive" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description Active of Quest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descriptiondone" class="col-sm-3 control-label">Description Done</label>
                                    <div class="col-sm-6">
                                        <textarea name="descriptiondone" id="descriptiondone" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description Done of Quest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtdatebegin" class="col-sm-3 control-label">Date Begin</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txtdatebegin" id="txtdatebegin" value="" maxlength="255" placeholder="Date Begin" data-type="dateIso" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="datepickerbeginbtn"><i class="icon-calendar"></i></span>
                                        </div>                                      
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">format yyyy-mm-dd</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttimebegin" class="col-sm-3 control-label">Time Begin</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txttimebegin" id="txttimebegin" value="" maxlength="255" placeholder="Time Begin" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="timepickerbeginbtn"><i class="icon-time"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Format H:i:s</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtdateend" class="col-sm-3 control-label">Date End</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txtdateend" id="txtdateend" value="" maxlength="255" placeholder="Date End" data-type="dateIso" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="datepickerendbtn"><i class="icon-calendar"></i></span>
                                        </div>                                      
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">format yyyy-mm-dd</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttimeend" class="col-sm-3 control-label">Time End</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txttimeend" id="txttimeend" value="" maxlength="255" placeholder="Time End" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="timepickerendbtn"><i class="icon-time"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Format H:i:s</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="requirementquest" class="col-sm-3 control-label">Requirement (Quest ID)</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="requirementquest" id="requirementquest" value="-1" maxlength="255" placeholder="Requirement (Quest ID)" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="getquestid" type="button" class="btn-warning btn"><i class="icon-list"></i> <span>Find</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="energyrequirement" class="col-sm-3 control-label">Energy Requirement</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="energyrequirement" id="energyrequirement" value="" maxlength="255" placeholder="Energy Requirement" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Energy Requirement of Quest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="requireditem" class="col-sm-3 control-label">Required Item</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="requireditem" id="requireditem" value="" maxlength="255" placeholder="Required Item" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="getrequiredid" type="button" class="btn-midnightblue btn"><i class="icon-key"></i> <span>Key</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rewards" class="col-sm-3 control-label">Rewards</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="rewards" id="rewards" value="" maxlength="255" placeholder="Rewards" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="getrewardid" type="button" class="btn-success btn"><i class="icon-unlock"></i> <span>Unlocked</span></button>
                                        <button id="getrewardid_2" type="button" class="btn-success btn"><i class="icon-gift"></i> <span>Item</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Is Done</label>
                                    <div class="col-sm-2">
                                      <label class="radio-inline">
                                        <input id="radiodone1" name="radiodone" value="false" type="radio" checked="checked"> False<span class='ui-icon ui-icon-check'></span>
                                      </label>
                                      <label class="radio-inline">
                                        <input id="radiodone2" name="radiodone" value="true" type="radio"> True<span class='ui-icon ui-icon-closethick'></span>
                                      </label>
                                    </div>
                                    <label class="col-sm-3 control-label">Is Active</label>
                                    <div class="col-sm-2">
                                      <label class="radio-inline">
                                        <input id="radioactive1" name="radioactive" value="false" type="radio" checked="checked"> False<span class='ui-icon ui-icon-check'></span>
                                      </label>
                                      <label class="radio-inline">
                                        <input id="radioactive2" name="radioactive" value="true" type="radio"> True<span class='ui-icon ui-icon-closethick'></span>
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Is Return</label>
                                    <div class="col-sm-2">
                                      <label class="radio-inline">
                                        <input id="radioreturn1" name="radioreturn" value="false" type="radio"> False<span class='ui-icon ui-icon-check'></span>
                                      </label>
                                      <label class="radio-inline">
                                        <input id="radioreturn2" name="radioreturn" value="true" type="radio" checked="checked"> True<span class='ui-icon ui-icon-closethick'></span>
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Quests","module5","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                        if($this->m_checking->actions("Quests","module5","Import Txt",TRUE,FALSE,"home"))
                        {
                        ?>
                        <div class="tab-pane" id="importdata">
                            <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $this->template_admin->link("quest/processimport"); ?>">
                                <div class="form-group">
                                    <label for="fileimport" class="col-sm-3 control-label">File</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimport" id="fileimport" maxlength="255" />
                                        </span>                                      
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File format (*.txt)</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn-green btn"> <i class="icon-cloud-upload"></i> <span>Process Import</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                        }
                        ?>
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("quest/cruid_quest"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="code2">Code</label>
                            </td>
                            <td>
                                <div class="col-sm-8">
                                    <input type="text" name="code" id="code2" value="" maxlength="255" placeholder="Code" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="generate2" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                </div>
                            </td>
                        </tr>            
                        <tr>
                            <td><label for="title2">Title</label></td>
                            <td><input type="text" name="title" id="title2" value="" maxlength="255" placeholder="Quest Title" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="descriptionnormal2">Description Normal</label></td>
                            <td><textarea name="descriptionnormal" id="descriptionnormal2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="descriptionactive2">Description Active</label></td>
                            <td><textarea name="descriptionactive" id="descriptionactive2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="descriptiondone2">Description Done</label></td>
                            <td><textarea name="descriptiondone" id="descriptiondone2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="txtdatebegin2">Date Begin</label></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txtdatebegin" id="txtdatebegin2" value="" maxlength="255" placeholder="Date Begin" data-type="dateIso" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="datepickerbeginbtn2"><i class="icon-calendar"></i></span>
                                </div> 
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txttimebegin2">Time Begin</label></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txttimebegin" id="txttimebegin2" value="" maxlength="255" placeholder="Time Begin" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="timepickerbeginbtn2"><i class="icon-time"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtdateend2">Date End</label></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txtdateend" id="txtdateend2" value="" maxlength="255" placeholder="Date End" data-type="dateIso" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="datepickerendbtn2"><i class="icon-calendar"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txttimeend2">Time End</label></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txttimeend" id="txttimeend2" value="" maxlength="255" placeholder="Time End" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="timepickerendbtn2"><i class="icon-time"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="requirementquest2">Requirement (Quest ID)</label></td>
                            <td>
                                <div class="col-sm-8">
                                    <input type="text" name="requirementquest" id="requirementquest2" value="-1" maxlength="255" placeholder="Requirement (Quest ID)" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="getquestid2" type="button" class="btn-warning btn"><i class="icon-list"></i> <span>Find</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="energyrequirement2">Energy Requirement</label></td>
                            <td><input type="text" name="energyrequirement" id="energyrequirement2" value="" maxlength="255" placeholder="Energy Requirement" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="requireditem2">Required Item</label></td>
                            <td>
                                <div class="col-sm-8">
                                    <input type="text" name="requireditem" id="requireditem2" value="" maxlength="255" placeholder="Required Item" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="getrequiredid2" type="button" class="btn-midnightblue btn"><i class="icon-key"></i> <span>Key</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="rewards2">Rewards</label></td>
                            <td>
                                <div class="col-sm-5">
                                    <input type="text" name="rewards" id="rewards2" value="" maxlength="255" placeholder="Rewards" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-7">
                                    <div class="btn-group">
                                        <button id="getrewardid2" type="button" class="btn-success btn"><i class="icon-unlock"></i> <span>Unlocked</span></button>
                                        <button id="getrewardid_22" type="button" class="btn-success btn"><i class="icon-gift"></i> <span>Item</span></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Is Done</label></td>
                            <td>
                                  <label>
                                    <input id="radiodone12" name="radiodone" value="false" type="radio" checked="checked"> False<span class='ui-icon ui-icon-check'></span>
                                  </label>
                                  <label>
                                    <input id="radiodone22" name="radiodone" value="true" type="radio"> True<span class='ui-icon ui-icon-closethick'></span>
                                  </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Is Active</label></td>
                            <td>
                                  <label>
                                    <input id="radioactive12" name="radioactive" value="false" type="radio" checked="checked"> False<span class='ui-icon ui-icon-check'></span>
                                  </label>
                                  <label>
                                    <input id="radioactive22" name="radioactive" value="true" type="radio"> True<span class='ui-icon ui-icon-closethick'></span>
                                  </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Is Return</label></td>
                            <td>
                                  <label>
                                    <input id="radioreturn12" name="radioreturn" value="false" type="radio"> False<span class='ui-icon ui-icon-check'></span>
                                  </label>
                                  <label>
                                    <input id="radioreturn22" name="radioreturn" value="true" type="radio" checked="checked"> True<span class='ui-icon ui-icon-closethick'></span>
                                  </label>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Quests","module5","Edit",TRUE,FALSE,"home"))
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
<div id="innerframedialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Quest Saved</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <table id="datatablechile" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">ID</th>
                                    <th width="85%">Description</th>
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
                </div>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="innerframedialogrequired" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Requirements</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-4">
                        <select id='cmbtipeview' name='cmbtipeview' onchange='reload_code(this.value);' class='form-control'>
                            <option value="">Pilih Tipe</option>
                            <?php
                            foreach($data as $dt)
                            { 
                                echo "<option value='".$dt['_id']."'>".$dt['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select id='cmbtipecode' name='cmbtipecode' class='form-control'></select>
                    </div>
                    <div class="col-sm-2">
                        <input type="number" name="countrequired" id="countrequired" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                    </div>
                    <div class="col-sm-2">
                        <button type="button" id="getrequiredadd" class="btn btn-default"><i class="icon-plus"></i> Add</button>
                    </div>
                </div>
                <div id="listrequiredadd"></div>
            </div>
            <div class="modal-footer">          
                <button id="tblsetrequired" type="button" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="innerframedialogreward" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Reward</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-4">
                        <select id='cmbtiperewardview' name='cmbtiperewardview' onchange='reload_codereward(this.value);' class='form-control'>
                            <option value="">Pilih Tipe</option>
                            <?php
                            foreach($datareward as $dt)
                            { 
                                echo "<option value='".$dt['_id']."'>".$dt['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select id='cmbtiperewardcode' name='cmbtiperewardcode' class='form-control'></select>
                    </div>
                    <div class="col-sm-2">
                        <input type="number" name="countreward" id="countreward" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                    </div>
                    <div class="col-sm-2">
                        <button type="button" id="getrewardadd" class="btn btn-default"><i class="icon-plus"></i> Add</button>
                    </div>
                </div>
                <div id="listrewardadd"></div>
            </div>
            <div class="modal-footer">          
                <button id="tblsetreward" type="button" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="innerframedialogreward2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Avatar Item</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="gender" class="control-label">Gender</label>
                            <select id='gender' name='gender' class='form-control'>
                                <option value="">All Gender</option>
                                <?php
                                foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                {
                                    echo "<option value='".$dt."'>".$value."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="tipe" class="control-label">Type</label>
                            <select id='tipe' name='tipe' class='form-control'>
                                <option value="">All Avatar Type</option>
                                <?php 
                                foreach($tipe as $dt)
                                { 
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("AvatarBodyPart");
                                    $listtipe=$this->mongo_db->find(array("parent"=>$dt['name']),0,0,array('name'=>1));
                                    if($listtipe->count()>0)
                                    {
                                        echo "<optgroup label='".$dt['name']."'>";
                                        foreach($listtipe as $dt2)
                                        {
                                            echo "<option value='".$dt2['name']."'>".$dt2['name']."</option>";
                                        }
                                        echo "</optgroup>";
                                    }
                                    else 
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="brand" class="control-label">Store</label>
                            <select id='brand' name='brand' class='form-control'>
                                <option value="">All Store</option>
                                <?php
                                foreach($brand as $dt)
                                {
                                    echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                }
                                ?>
                            </select>
                        </div>                                                
                    </div>
                    <div class="row">
                        <button id="filtersearch" type="button" class="btn-midnightblue btn col-sm-12 text-center"> <i class="icon-filter"></i> <span>Filter and Show</span></button>
                    </div>
                    <div class="row" style="margin-top: 30px;">
                        <div id="listdataavatar" class="col-sm-12 text-center" style="padding: 20px;">&nbsp;</div>
                    </div>
                    <div class="row" style="margin-top: 30px;">                        
                        <div class="col-sm-8">
                            <input type="number" name="countreward2" id="countreward2" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                        </div>
                        <div class="col-sm-4">
                            <button type="button" id="getrewardadd2" class="btn btn-default"><i class="icon-plus"></i> Add</button>
                        </div>
                    </div>                    
                </div>
                <div id="listrewardadd2"></div>
            </div>
            <div class="modal-footer">          
                <button id="tblsetreward_2" type="button" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="inneropenduplikat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="frmduplikat" action="<?php echo $this->template_admin->link("quest/duplicate"); ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Input Count duplikat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="txtidduplikat" id="txtidduplikat" value="" />
                            <input type="number" name="countduplikat" id="countduplikat" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">          
                    <button type="submit" class="btn btn-success"><i class="icon-ok-circle"></i> Process</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>