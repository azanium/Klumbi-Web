<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable,dttableuser,dttablebrand,dttableavaitem,dttableavamix;
$(document).ready(function() {
    $("#adddialogid").click(function(){
        tambahconfigrequired();
    });
    $("#adddialogid2").click(function(){
        tambahconfigrequired2('dt',"","");
    });
    $('.cpicker').colorpicker();
    $('textarea.autosize').autosize({append: "\n"});
    $('.select').select2({width: 'resolve'});
    $('#txttag , #txttag2').select2({width: "resolve", tags:<?php echo json_encode($this->tambahan_fungsi->list_tag()); ?>});
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
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
    $( "#generate , #generate2" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $( "#getrewardid_1 , #getrewardid_12" ).click(function() { 
        $("#innerframedialogreward").modal('show');
        return false; 
    });
    $( "#getrewardid_2, #getrewardid_22" ).click(function() { 
        $("#innerframedialogreward2").modal('show');
        return false; 
    });
    $('#fileimageicon').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimageicon"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txticonname").val(data.result.name);
                $("#filepreviewiconr").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimageicon2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimageicon"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txticonname2").val(data.result.name);
                $("#filepreviewiconr2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimagebanner').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagebanner"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtbannername").val(data.result.name);
                $("#filepreviewbanner").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimagebanner2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagebanner"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtbannername2").val(data.result.name);
                $("#filepreviewbanner2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimagecontent').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagecontent"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtcontentname").val(data.result.name);
                $("#filepreviewcontent").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimagecontent2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagecontent"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtcontentname2").val(data.result.name);
                $("#filepreviewcontent2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $( "#getrewardadd" ).click(function() { 
        var jml=$("#countreward").val();
        var kode=$("#cmbtiperewardcode").val();
        if(jml!="" && kode!=null)
        {
            var id="rnd_"+Math.floor((Math.random()*10000)+1)+"reward";
            var content="<p id='"+id+"'>";
            content +="<input type='hidden' name='rewardpilih[]' value='"+kode+","+jml+"' />";
            content +="<a href='#' onclick='removeoption(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
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
            content +="<a href='#' onclick='removeoption(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
            content +="&nbsp;&nbsp;&nbsp;"+kode+","+jml;
            content +="</p>";
            $("#listrewardadd2").append(content);
            $("#countreward2").val('1');
        }
        return false; 
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
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("contest/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }]
    });
    dttableuser = $('#datatableuser').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("services/user/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": true,
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, {"bSortable": false}, { "sClass" : "text-center","bSortable": false }]
    });
    dttablebrand = $('#datatablebrand').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("services/brand/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": true,
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    dttableavaitem = $('#datatableavaitem').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("services/avatar/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": true,
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    dttableavamix = $('#datatablemix').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("services/browse/list_data_mix"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": true,
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
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
    $('#reloaduser').click(function(){
        dttableuser.fnClearTable(0);
	dttableuser.fnDraw();
    });
    $('#reloadbrand').click(function(){
        dttablebrand.fnClearTable(0);
	dttablebrand.fnDraw();
    });
    $('#reloadavataritem').click(function(){
        dttableavaitem.fnClearTable(0);
	dttableavaitem.fnDraw();
    });
    $('#reloadavatarmix').click(function(){
        dttableavamix.fnClearTable(0);
	dttableavamix.fnDraw();
    });
    $(".resetform").click(function(){
        $("#brandfrm input, #brandfrm textarea").val("");
        $("#filepreviewiconr").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreviewbanner").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreviewcontent").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
                        $("#listdtvalcontest").html("");
                        $("#brandfrm input, #brandfrm textarea").val("");
                        $("#filepreviewiconr").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreviewbanner").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreviewcontent").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
                        $('#editdata').modal('hide');
                        $("#listdtvalcontest2").html("");
                        $("#editbrandfrm input, #editbrandfrm textarea").val("");
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
    $( "#tblseturl" ).click(function() {
        $("#tipefrmurlset").validate({
            submitHandler: function(form) {
                var textvalue = $("#txturldestination").val();
                $("#txttyperequired" + $("#txturlid").val() ).val($("#txttype").val());
                $("#txtvaluerequired" + $("#txturlid").val() ).val(textvalue);
                $('#collectionsdatavalueurl').modal('hide');
            }  
        });
    });
    $("#tblsetinviteuser").click(function() {
        $("#tipefrminviteset").validate({
            submitHandler: function(form) {
                var textvalue = $("#txtnuminvite").val();
                $("#txttyperequired" + $("#txtinvid").val() ).val($("#txttypeinv").val());
                $("#txtvaluerequired" + $("#txtinvid").val() ).val(textvalue);
                $('#datavalinvite').modal('hide');
            }  
        });
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
});
function setvalurl(idval)
{
    $("#collectionsdatavalueurl").modal('show');
    $("#txturldestination").val('');
    $("#txturlid").val(idval);
    $("#txttype").val("url");
}
function setvalinvite(idval)
{
    $("#datavalinvite").modal('show');
    $("#txtnuminvite").val('');
    $("#txtinvid").val(idval);
    $("#txttypeinv").val("inviteuser");
}
function setvaluser(idval)
{
    $("#listdtuser").modal('show');
    $("#txtpiluserid").val(idval);
    $("#txttypepiluser").val("followuser");
}
function setvalbrand(idval,dttypeval)
{
    $("#listdtbrand").modal('show');
    $("#txtpilbrandid").val(idval);
    $("#txttypepilbrand").val(dttypeval);
}
function setvalavaitem(idval,dttypeval)
{
    $("#listdtavataritem").modal('show');
    $("#txtpilavaitemid").val(idval);
    $("#txttypepilavaitem").val(dttypeval);
}
function setvalavamix(idval,dttypeval)
{
    $("#collectionsdatavaluemix").modal('show');
    $("#txtpilavamixid").val(idval);
    $("#txttypepilavamix").val(dttypeval);
}
function getdatauserval(iduser)
{
    $("#txttyperequired" + $("#txtpiluserid").val() ).val($("#txttypepiluser").val());
    $("#txtvaluerequired" + $("#txtpiluserid").val() ).val(iduser);
    $('#listdtuser').modal('hide');
}
function getdatabrandval(brand_id)
{
    $("#txttyperequired" + $("#txtpilbrandid").val() ).val($("#txttypepilbrand").val());
    $("#txtvaluerequired" + $("#txtpilbrandid").val() ).val(brand_id);
    $('#listdtbrand').modal('hide');
}
function getavataritemval(id)
{
    $("#txttyperequired" + $("#txtpilavaitemid").val() ).val($("#txttypepilavaitem").val());
    $("#txtvaluerequired" + $("#txtpilavaitemid").val() ).val(id);
    $('#listdtavataritem').modal('hide');
}
function getdatamix(id)
{
    $("#txttyperequired" + $("#txtpilavamixid").val() ).val($("#txttypepilavamix").val());
    $("#txtvaluerequired" + $("#txtpilavamixid").val() ).val(id);
    $("#collectionsdatavaluemix").modal('hide');
}
function tambahconfigrequired()
{
    var tagid = "_" + Math.random().toString(36).substring(7) + Math.floor(Math.random() * 10) + "Lst";
    var newsetvalreq = "<div class='form-group' id='" + tagid + "'>";
    newsetvalreq += "<div class='col-sm-3' align='right'>";
    newsetvalreq += "<button type='button' class='btn-danger btn' onclick='removeoption(\""+tagid+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button>";
    newsetvalreq += "</div>";
    newsetvalreq += "<div class='col-sm-2'>"; 
    newsetvalreq += "<div class='btn-toolbar'>";
    newsetvalreq += "<div class='btn-group-vertical'>";
    newsetvalreq += "<button type='button' class='btn btn-default' onclick='setvalurl(\"" + tagid + "\");return false;'><i class='icon-link'></i> URL</button>";
    newsetvalreq += "<button type='button' class='btn btn-danger' onclick='setvalinvite(\"" + tagid + "\");return false;'><i class='icon-envelope-alt'></i> Invite</button>";
    newsetvalreq += "<button type='button' class='btn btn-sky' onclick='setvaluser(\"" + tagid + "\");return false;'><i class='icon-user'></i> Follow</button>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";    
    newsetvalreq += "<div class='col-sm-2'>"; 
    newsetvalreq += "<div class='btn-toolbar'>";
    newsetvalreq += "<div class='btn-group-vertical'>";
    newsetvalreq += "<button type='button' class='btn btn-green' onclick='setvalbrand(\"" + tagid + "\",\"lovebrand\");return false;'><i class='icon-heart'></i> Brand</button>";    
    newsetvalreq += "<button type='button' class='btn btn-green' onclick='setvalbrand(\"" + tagid + "\",\"commentbrand\");return false;'><i class='icon-comment-alt'></i> Brand</button>";
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"sendavataritem\");return false;'><i class='icon-gift'></i> Send Avatar Item</button>";
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"loveavataritem\");return false;'><i class='icon-heart'></i> Avatar Item</button>";    
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"commentavataritem\");return false;'><i class='icon-comment-alt'></i> Avatar Item</button>";
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"useavataritem\");return false;'><i class='icon-ok'></i> Use Avatar Item</button>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";    
    newsetvalreq += "<div class='col-sm-2'>"; 
    newsetvalreq += "<div class='btn-toolbar'>";
    newsetvalreq += "<div class='btn-group-vertical'>";
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"sendavatarmix\");return false;'><i class='icon-gift'></i> Send Avatar Mix</button>";
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"loveavatarmix\");return false;'><i class='icon-heart'></i> Avatar Mix</button>";    
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"commentavatarmix\");return false;'><i class='icon-comment-alt'></i> Avatar Mix</button>";
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"useavatarmix\");return false;'><i class='icon-ok'></i> Use Avatar Mix</button>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";
    newsetvalreq += "<div class='col-sm-3'>";
    newsetvalreq += "<textarea name='txtvaluerequired[]' id='txtvaluerequired" + tagid + "' class='form-control' readonly='true' cols='55' rows='3'></textarea>";
    newsetvalreq += "<input type='hidden' name='txttyperequired[]' id='txttyperequired" + tagid + "' value='' />";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";
    $("#listdtvalcontest").append(newsetvalreq);
}
function tambahconfigrequired2(etag,keyvaltype,keyvaldata)
{
    var tagid = "edit_" + Math.random().toString(36).substring(7) + Math.floor(Math.random() * 10) + "Lst" + etag;
    var newsetvalreq = "<table id='" + tagid + "'>";
    newsetvalreq += "<tr>";
    newsetvalreq += "<td width='5%' align='right'>";
    newsetvalreq += "<button type='button' class='btn-danger btn' onclick='removeoption(\""+tagid+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button>";
    newsetvalreq += "</td>";
    newsetvalreq += "<td width='50%'>"; 
    newsetvalreq += "<div class='btn-toolbar'>";
    newsetvalreq += "<div class='btn-group-vertical'>";
    newsetvalreq += "<button type='button' class='btn btn-default' onclick='setvalurl(\"" + tagid + "\");return false;'><i class='icon-link'></i> URL</button>";
    newsetvalreq += "<button type='button' class='btn btn-danger' onclick='setvalinvite(\"" + tagid + "\");return false;'><i class='icon-envelope-alt'></i> Invite</button>";
    newsetvalreq += "<button type='button' class='btn btn-sky' onclick='setvaluser(\"" + tagid + "\");return false;'><i class='icon-user'></i> Follow</button>";
    newsetvalreq += "<button type='button' class='btn btn-green' onclick='setvalbrand(\"" + tagid + "\",\"lovebrand\");return false;'><i class='icon-heart'></i> Brand</button>";    
    newsetvalreq += "<button type='button' class='btn btn-green' onclick='setvalbrand(\"" + tagid + "\",\"commentbrand\");return false;'><i class='icon-comment-alt'></i> Brand</button>";
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"sendavataritem\");return false;'><i class='icon-gift'></i> Send Avatar Item</button>";
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"loveavataritem\");return false;'><i class='icon-heart'></i> Avatar Item</button>";    
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"commentavataritem\");return false;'><i class='icon-comment-alt'></i> Avatar Item</button>";
    newsetvalreq += "<button type='button' class='btn btn-midnightblue' onclick='setvalavaitem(\"" + tagid + "\",\"useavataritem\");return false;'><i class='icon-ok'></i> Use Avatar Item</button>";
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"sendavatarmix\");return false;'><i class='icon-gift'></i> Send Avatar Mix</button>";
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"loveavatarmix\");return false;'><i class='icon-heart'></i> Avatar Mix</button>";    
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"commentavatarmix\");return false;'><i class='icon-comment-alt'></i> Avatar Mix</button>";
    newsetvalreq += "<button type='button' class='btn btn-magenta' onclick='setvalavamix(\"" + tagid + "\",\"useavatarmix\");return false;'><i class='icon-ok'></i> Use Avatar Mix</button>";
    newsetvalreq += "</div>";
    newsetvalreq += "</div>";
    newsetvalreq += "</td>";
    newsetvalreq += "<td width='45%'>";
    newsetvalreq += "<textarea name='txtvaluerequired[]' id='txtvaluerequired" + tagid + "' class='form-control' readonly='true' cols='55' rows='3'>" + keyvaldata + "</textarea>";
    newsetvalreq += "<input type='hidden' name='txttyperequired[]' id='txttyperequired" + tagid + "' value='" + keyvaltype + "' />";
    newsetvalreq += "</td>";
    newsetvalreq += "</tr>";
    newsetvalreq += "</table>";
    $("#listdtvalcontest2").append(newsetvalreq);
}
function removeoption(divid)
{
    $('#' + divid).remove();  
}
function generatecodernd()
{
    $.ajax({
        type: "GET",
        url: '<?php echo $this->template_admin->link("contest/generatecode"); ?>',
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
function ubahdata(id)
{
    $.ajax({
        type: "POST",
        url: "<?php echo $this->template_admin->link("contest/detail"); ?>",
        data:{txtid:id},
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
                $('#txtid2').val(data['id']);
                $('#txticonname2').val(data['imageicon']);
                $('#txtbannername2').val(data['imagebanner']);
                $('#txtcontentname2').val(data['imagecontent']);
                $('#code2').val(data['code']);
                $('#name2').val(data['name']);
                $('#cmbstate2').val(data['state']);
                $('#txtbgcolor2').val(data['bgcolor']);
                $('#txtcolor2').val(data['textcolor']);
                $('#cmbgender2').val(data['gender']);
                $('#descriptions2').val(data['description']);
                $('#infodetail2').val(data['info']);
                $('#txtdatebegin2').val(data['begindate']);
                $('#txttimebegin2').val(data['begintime']);
                $('#txtdateend2').val(data['enddate']);
                $('#txttimeend2').val(data['timeend']);
                $('#count2').val(data['count']);
                $('#brand2').val(data['brand_id']);
                $('#cmblistdata2').val(data['order']);
                $('#cmbstatevote2').val(data['votestate']);                
                $('#txttag2').val(data['tag']);
                $('#searctype2').val(data['typesearch']);
                $('#searchcategory2').val(data['categorysearch']);
                $("#filepreviewiconr2").attr("src", '<?php echo $this->config->item('path_asset_img')."preview_images/"; ?>' + data['imageicon']);
                $("#filepreviewbanner2").attr("src", '<?php echo $this->config->item('path_asset_img')."preview_images/"; ?>' + data['imagebanner']);
                $("#filepreviewcontent2").attr("src", '<?php echo $this->config->item('path_asset_img')."preview_images/"; ?>' + data['imagecontent']);
                $('#rewards2').val(data['rewards']);
                $("#listdtvalcontest2").html("");
                for(var i=0; i<data['requireds'].length;i++)
                {
                    tambahconfigrequired2('es',data['requireds'][i]['type'],data['requireds'][i]['value']);
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
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>contest/delete/'+id;
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
                            $("#brandfrm input, #brandfrm textarea").val("");
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
        return false;
    });
}
function setvalid(id,newval,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo $this->template_admin->link("contest/setvalid"); ?>';
            $.ajax({
                    type: "POST",
                    url: url,
                    data:{id:id, newval:newval},
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
        return false;
    });
}
function reload_codereward(id)
{
    var url='<?php echo base_url(); ?>quest/get_required_id/'+id;
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
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Contest</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Contest</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Add Contest</a></li>
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
                                        <th width="5%">Code</th>
                                        <th width="15%">Name</th>
                                        <th width="10%">State</th>
                                        <th width="10%">Gender</th>
                                        <th width="15%">Begin Date</th>
                                        <th width="15%">End Date</th>
                                        <th width="5%">Max Entry</th>
                                        <th width="10%">Image</th>
                                        <th width="10%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="10">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("contest/cruid_contest"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txticonname" id="txticonname" value="" />
                                    <input type="hidden" name="txtbannername" id="txtbannername" value="" />
                                    <input type="hidden" name="txtcontentname" id="txtcontentname" value="" />
                                    <label for="code" class="col-sm-3 control-label">Code</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="code" id="code" value="" maxlength="255" placeholder="Code" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                    <button id="generate" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbstate" class="col-sm-3 control-label">State</label>
                                    <div class="col-sm-6">
                                        <select id='cmbstate' name='cmbstate' class='form-control'>
                                            <?php
                                            foreach($this->tambahan_fungsi->state_contest() as $dt=>$value)
                                            {
                                                echo "<option value='".$dt."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">State of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtbgcolor" class="col-sm-3 control-label">Background Color</label>
                                    <div class="col-sm-6">
                                        <select id="txtbgcolor" name="txtbgcolor" class="form-control {required:true}">
                                            <?php
                                            foreach($colorls as $dtcolor)
                                            {
                                                echo "<option value='".$dtcolor['name']."'>".$dtcolor['name']." ".$dtcolor['color']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Background Color of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtcolor" class="col-sm-3 control-label">Text Color</label>
                                    <div class="col-sm-6">
                                        <select id="txtcolor" name="txtcolor" class="form-control {required:true}">
                                            <?php
                                            foreach($colorls as $dtcolor)
                                            {
                                                echo "<option value='".$dtcolor['name']."'>".$dtcolor['name']." ".$dtcolor['color']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Text Color of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbgender" class="col-sm-3 control-label">Gender</label>
                                    <div class="col-sm-6">
                                      <select id="cmbgender" name="cmbgender" class="form-control">
                                        <?php
                                        foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                        {
                                            echo "<option value='".$dt."'>".$value."</option>";
                                        }
                                        ?>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Gender of Contest!</p>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="descriptions" class="col-sm-3 control-label">Descriptions</label>
                                    <div class="col-sm-6">
                                        <textarea name="descriptions" id="descriptions" class="form-control autosize" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="infodetail" class="col-sm-3 control-label">Detail Info</label>
                                    <div class="col-sm-6">
                                        <textarea name="infodetail" id="infodetail" class="form-control autosize" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Detail Info of Contest!</p>
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
                                    <label for="count" class="col-sm-3 control-label">Max Entry</label>
                                    <div class="col-sm-6">                                      
                                      <input type="number" name="count" id="count" value="0" step="1" min="0" max="1000000" maxlength="255" class="form-control {required:true, number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Max Entry of Contest!</p>
                                    </div>
                                </div>
                                <?php
                                if($this->session->userdata('brand')=="")
                                {
                                ?>
                                <div class="form-group">
                                    <label for="brand" class="col-sm-3 control-label">Brand</label>
                                    <div class="col-sm-6">
                                        <select id="brand" name="brand" class="select" style="width:100%">
                                            <option value="">&nbsp;</option>
                                            <?php 
                                            foreach($brand as $dt)
                                            {
                                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Brand of Contest!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label for="cmblistdata" class="col-sm-3 control-label">List Data Winner</label>
                                    <div class="col-sm-6">
                                        <select id='cmblistdata' name='cmblistdata' class='form-control'>
                                            <?php
                                            foreach($this->tambahan_fungsi->state_cmbwinner() as $dt=>$value)
                                            {
                                                echo "<option value='".$dt."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">List Winner of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbstatevote" class="col-sm-3 control-label">Voting</label>
                                    <div class="col-sm-6">
                                        <select id='cmbstatevote' name='cmbstatevote' class='form-control'>
                                            <option value="on">On</option>
                                            <option value="off">Off</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">State voting of Contest!</p>
                                    </div>
                                </div>
                                <div id="listdtvalcontest">&nbsp;</div>
                                <div class="form-group">
                                    <label for="listrequired" class="col-sm-3 control-label">Required</label>
                                    <div class="col-sm-6">
                                        <button type="button" id="adddialogid" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Required</span></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Required of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rewards" class="col-sm-3 control-label">Rewards</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="rewards" id="rewards" value="" maxlength="255" placeholder="Rewards" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="getrewardid_1" type="button" class="btn-orange btn"><i class="icon-unlock"></i> <span>Val</span></button>
                                        <button id="getrewardid_2" type="button" class="btn-inverse btn"><i class="icon-legal"></i> <span>Item</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimageicon" class="col-sm-3 control-label">Icon Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimageicon" id="fileimageicon" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreviewiconr" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image icon of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimagebanner" class="col-sm-3 control-label">Banner Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimagebanner" id="fileimagebanner" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreviewbanner" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image file of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimagecontent" class="col-sm-3 control-label">Content Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimagecontent" id="fileimagecontent" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreviewcontent" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image content of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttag" class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-6">
                                        <textarea name="txttag" id="txttag" style="width:100%" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Tag of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="searctype" class="col-sm-3 control-label">Search Type Keyword</label>
                                    <div class="col-sm-6">
                                        <select id="searctype" name="searctype" class="select {required:true}" style="width:100%">
                                            <?php 
                                            foreach($searchtype as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Search type of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="searchcategory" class="col-sm-3 control-label">Search Category Keyword</label>
                                    <div class="col-sm-6">
                                        <select id="searchcategory" name="searchcategory" class="select {required:true}" style="width:100%">
                                            <?php 
                                            foreach($searchcategory as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Search category of Contest!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Contest","module8","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("contest/cruid_contest"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <input type="hidden" name="txticonname" id="txticonname2" value="" />
                                <input type="hidden" name="txtbannername" id="txtbannername2" value="" />
                                <input type="hidden" name="txtcontentname" id="txtcontentname2" value="" />
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
                            <td>
                                <label for="name2">Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cmbstate2">State</label>
                            </td>
                            <td>
                                <select id='cmbstate2' name='cmbstate' class='form-control'>
                                    <?php
                                    foreach($this->tambahan_fungsi->state_contest() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtbgcolor2">Background Color</label>
                            </td>
                            <td>
                                <select id="txtbgcolor2" name="txtbgcolor" class="form-control {required:true}">
                                    <?php
                                    foreach($colorls as $dtcolor)
                                    {
                                        echo "<option value='".$dtcolor['name']."'>".$dtcolor['name']." ".$dtcolor['color']."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtcolor2">Text Color</label>
                            </td>
                            <td>
                                <select id="txtcolor2" name="txtcolor" class="form-control {required:true}">
                                    <?php
                                    foreach($colorls as $dtcolor)
                                    {
                                        echo "<option value='".$dtcolor['name']."'>".$dtcolor['name']." ".$dtcolor['color']."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cmbgender2">Gender</label>
                            </td>
                            <td>
                                <select id="cmbgender2" name="cmbgender" class="form-control">
                                    <?php
                                    foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="descriptions2">Descriptions</label>
                            </td>
                            <td>
                                <textarea name="descriptions" id="descriptions2" class="form-control autosize" cols="55" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="infodetail2">Detail Info</label>
                            </td>
                            <td>
                                <textarea name="infodetail" id="infodetail2" class="form-control autosize" cols="55" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtdatebegin2">Date Begin</label>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txtdatebegin" id="txtdatebegin2" value="" maxlength="255" placeholder="Date Begin" data-type="dateIso" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="datepickerbeginbtn2"><i class="icon-calendar"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txttimebegin2">Time Begin</label>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txttimebegin" id="txttimebegin2" value="" maxlength="255" placeholder="Time Begin" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="timepickerbeginbtn2"><i class="icon-time"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtdateend2">Date End</label>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txtdateend" id="txtdateend2" value="" maxlength="255" placeholder="Date End" data-type="dateIso" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="datepickerendbtn2"><i class="icon-calendar"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txttimeend2">Time End</label>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="txttimeend" id="txttimeend2" value="" maxlength="255" placeholder="Time End" class="form-control {required:true}" />
                                    <span class="input-group-addon" id="timepickerendbtn2"><i class="icon-time"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="count2">Max Entry</label>
                            </td>
                            <td>
                                <input type="number" name="count" id="count2" value="0" step="1" min="0" max="1000000" maxlength="255" class="form-control {required:true, number:true}" />
                            </td>
                        </tr>
                        <?php
                        if($this->session->userdata('brand')=="")
                        {
                        ?>
                        <tr>
                            <td><label for="brand2">Brand</label></td>
                            <td>
                                <select id="brand2" name="brand" class="form-control">
                                    <option value="">&nbsp;</option>
                                    <?php 
                                    foreach($brand as $dt)
                                    {
                                        echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td>
                                <label for="cmblistdata2">List Data Winner</label>
                            </td>
                            <td>
                                <select id='cmblistdata2' name='cmblistdata' class="form-control">
                                    <?php
                                    foreach($this->tambahan_fungsi->state_cmbwinner() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cmbstatevote2">Voting</label>
                            </td>
                            <td>
                                <select id='cmbstatevote2' name='cmbstatevote' class="form-control">
                                    <option value="on">On</option>
                                    <option value="off">Off</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="listdtvalcontest2">&nbsp;</div></td>
                        </tr>
                        <tr>
                            <td><label>Required</label></td>
                            <td><button type="button" id="adddialogid2" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Required</span></button></td>
                        </tr>
                        <tr>
                            <td><label for="rewards2">Rewards</label></td>
                            <td>
                                <div class="col-sm-5">
                                    <input type="text" name="rewards" id="rewards2" value="" maxlength="255" placeholder="Rewards" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-7">
                                    <div class="btn-group">
                                        <button id="getrewardid_12" type="button" class="btn-orange btn"><i class="icon-unlock"></i> <span>Val</span></button>
                                        <button id="getrewardid_22" type="button" class="btn-inverse btn"><i class="icon-legal"></i> <span>Item</span></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="fileimageicon2">Icon Picture</label>
                            </td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimageicon" id="fileimageicon2" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <img id="filepreviewiconr2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="fileimagebanner2">Banner Picture</label>
                            </td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimagebanner" id="fileimagebanner2" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <img id="filepreviewbanner2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="fileimagecontent2">Content Picture</label>
                            </td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimagecontent" id="fileimagecontent2" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <img id="filepreviewcontent2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txttag2">Tags</label>
                            </td>
                            <td>
                                <textarea name="txttag" id="txttag2" style="width:100%" cols="55" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="searctype2">Search Type Keyword</label>
                            </td>
                            <td>
                                <select id="searctype2" name="searctype" class="form-control">
                                    <?php 
                                    foreach($searchtype as $dt)
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="searchcategory2">Search Category Keyword</label>
                            </td>
                            <td>
                                <select id="searchcategory2" name="searchcategory" class="form-control">
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
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Contest","module8","Edit",TRUE,FALSE,"home"))
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
<div id="collectionsdatavalueurl" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <form method="POST" id="tipefrmurlset" action="#">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">URL Value</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="txturlid" id="txturlid" value="" />
                            <input type="hidden" name="txttype" id="txttype" value="" />
                            <input type="url" name="txturldestination" id="txturldestination" value="" maxlength="255" placeholder="http://" class="form-control {required:true, url:true}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">                
                    <button id="tblseturl" type="submit" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="datavalinvite" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <form method="POST" id="tipefrminviteset" action="#">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Invite Friends Value</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="txtinvid" id="txtinvid" value="" />
                            <input type="hidden" name="txttypeinv" id="txttypeinv" value="" />
                            <input type="number" name="txtnuminvite" id="txtnuminvite" value="1" step="1" min="1" max="1000" maxlength="255" placeholder="Number Friends want to invite" class="form-control {required:true, number:true}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">                
                    <button id="tblsetinviteuser" type="submit" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="listdtuser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">User want to Follow</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloaduser" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
                <input type="hidden" name="txtpiluserid" id="txtpiluserid" value="" />
                <input type="hidden" name="txttypepiluser" id="txttypepiluser" value="" />
                <table id="datatableuser" width="100%" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Name</th>
                            <th width="40%">Email</th>
                            <th width="20%">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">No Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="listdtbrand" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Select Brand</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloadbrand" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
                <input type="hidden" name="txtpilbrandid" id="txtpilbrandid" value="" />
                <input type="hidden" name="txttypepilbrand" id="txttypepilbrand" value="" />
                <table id="datatablebrand" width="100%" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Brand ID</th>
                            <th width="40%">Name</th>
                            <th width="20%">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">No Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="listdtavataritem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Select Avatar Item</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloadavataritem" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
                <input type="hidden" name="txtpilavaitemid" id="txtpilavaitemid" value="" />
                <input type="hidden" name="txttypepilavaitem" id="txttypepilavaitem" value="" />
                <table id="datatableavaitem" width="100%" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="5%">Code</th>
                            <th width="15%">Name</th>
                            <th width="15%">Type</th>
                            <th width="10%">Gender</th>
                            <th width="10%">Category</th>
                            <th width="10%">Payment</th>
                            <th width="10%">Brand</th>
                            <th width="10%">Size</th>
                            <th width="5%">Color</th>
                            <th width="5%">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="11">No Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="collectionsdatavaluemix" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Avatar Mix Value</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloadavatarmix" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
                <input type="hidden" name="txtpilavamixid" id="txtpilavamixid" value="" />
                <input type="hidden" name="txttypepilavamix" id="txttypepilavamix" value="" />
                <table id="datatablemix" width="100%" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Title</th>
                            <th width="20%">Gender</th>
                            <th width="15%">Size</th>
                            <th width="10%">Picture</th>
                            <th width="25%">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">No Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
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
                            <label for="brand" class="control-label">Brand</label>
                            <select id='brand' name='brand' class='form-control'>
                                <option value="">All Brand</option>
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