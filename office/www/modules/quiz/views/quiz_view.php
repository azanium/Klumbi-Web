<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable, dttablechile, dttablechile2;
var counter=1;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $( "#generate , #generate2" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $("#txtdatebegin , #txtdatebegin2").datepicker({
        dateFormat:"yy-mm-dd",
        selectOtherMonths: true,
        yearRange: '2013:2020',
        defaultDate: +7,
        autoSize: true,
        appendText: '(yyyy-mm-dd)'
    });
    $("#txtdatebegin").change(function() {
        var test = $(this).datepicker('getDate');
        var testm = new Date(test.getTime());
        testm.setDate(testm.getDate());
        $("#txtdateend").datepicker("option", "minDate", testm);
        $( "#txtdatebegin" ).focus();
    });
    $("#txtdatebegin2").change(function() {
        var test = $(this).datepicker('getDate');
        var testm = new Date(test.getTime());
        testm.setDate(testm.getDate());
        $("#txtdateend2").datepicker("option", "minDate", testm);
        $( "#txtdatebegin2" ).focus();
    });
    $( "#txtdateend , #txtdateend2" ).datepicker({
        selectOtherMonths: true,
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
        "sAjaxSource": "<?php echo $this->template_admin->link("quiz/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    datatablechile=$('#datatablechile').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/list_data_child"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": false,
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "sScrollY": "400px",        
        "sScrollXInner": "100%",
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }]        
    });
    dttablechile2=$('#datatablechile2').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quiz/list_data_child"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": false,
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "sScrollY": "400px",        
        "sScrollXInner": "100%",
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, null, null, null, { "sClass" : "text-center","bSortable": false }]
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
    $('#reloadquizsave').click(function(){
        dttablechile2.fnClearTable(0);
	dttablechile2.fnDraw();
    });
    $('#reloadquestsave').click(function(){
        datatablechile.fnClearTable(0);
	datatablechile.fnDraw();
    });    
    $("#resetid").click(function(){
        $('#txtid').val('');
        $('#txtid2').val('');
        $('#frmchildquestion').html('');
        $('#frmchildquestion2').html('');
        counter=1;
    });
    $( "#addquestionid , #addquestionid2" ).click(function() { 
        generatenewquestion("addnew",counter,"","",10,"","","");
        counter++;
        return false; 
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
                    $("#frmchildquestion").html("");
                    $("#frmchildquestion2").html("");
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
                    $("#frmchildquestion").html("");
                    $("#frmchildquestion2").html("");
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
function generatenewquestion(idadd,counter,questiontext,tipeanswer,timeout,dificult,fileimage,stringaddoption)
{
    var id = idadd + Math.floor((Math.random()*10000)+1) + "question";
    var texthtml="<div id='" + id + "'><hr />";
    var texthtml2="<table id='edit" + id + "' width='100%' style='border:1px solid #786756;pading:10px;'>";
    texthtml +=  "<div class='form-group'>";    
    texthtml +=  "<div class='col-sm-12 text-right'>";
    texthtml +=  "<input type='hidden' name='chiledcount[]' value='" + id + "' />";
    texthtml +=  "<button type='button' onclick='removerequired(\"" + id + "\");return false;' class='btn-danger btn'><i class='icon-trash'></i> <span>Remove</span></button>";
    texthtml +=  "</div>";
    texthtml +=  "</div>";
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td colspan='2'><input type='hidden' name='chiledcount[]' value='edit" + id + "' /><button type='button' onclick='removerequired(\"edit" + id + "\");return false;' class='btn-danger btn'><i class='icon-trash'></i> <span>Remove</span></button></td>";
    texthtml2 +=  "</tr>";  
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td width='30%'><label for='edittxt"+id+"'>Question ID</label></td>";
    texthtml2 +=  "<td width='70%'><input type='text' name='chiledquestionid[]' id='edittxt"+id+"' value='"+counter+"' maxlength='255' placeholder='Question ID' class='form-control {required:true}' /></td>";
    texthtml2 +=  "</tr>";     
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td><label for='editquestion"+id+"'>Question</label></td>";
    texthtml2 +=  "<td><textarea name='questiontext[]' id='editquestion"+id+"' class='form-control autosize {required:true}' cols='55' rows='3'>"+questiontext+"</textarea></td>";
    texthtml2 +=  "</tr>";
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td><label for='editcmbtype"+id+"'>Type Answer</label></td>";
    texthtml2 +=  "<td>";
    texthtml2 +=  "<select id='editcmbtype"+id+"' name='tipeanswer[]' class='form-control {required:true}'>";
    texthtml2 +=  "<option value='option'>Option Choices</option>";
    texthtml2 +=  "<option value='text'>Input Text</option>";
    texthtml2 +=  "</select>";
    texthtml2 +=  "</td>";
    texthtml2 +=  "</tr>";
    texthtml +=  "<div class='form-group'>";
    texthtml +=  "<label for='txt"+id+"' class='col-sm-3 control-label'>Question ID</label>";
    texthtml +=  "<div class='col-sm-9'>";
    texthtml +=  "<input type='text' name='chiledquestionid[]' id='txt"+id+"' value='"+counter+"' maxlength='255' placeholder='Question ID' class='form-control {required:true}' />";
    texthtml +=  "</div>";
    texthtml +=  "</div>";    
    texthtml +=  "<div class='form-group'>";
    texthtml +=  "<label for='question"+id+"' class='col-sm-3 control-label'>Question</label>";
    texthtml +=  "<div class='col-sm-9'>";
    texthtml +=  "<textarea name='questiontext[]' id='question"+id+"' class='form-control autosize {required:true}' cols='55' rows='3'>"+questiontext+"</textarea>";
    texthtml +=  "</div>";
    texthtml +=  "</div>";    
    texthtml +=  "<div class='form-group'>";
    texthtml +=  "<label for='cmbtype"+id+"' class='col-sm-3 control-label'>Type Answer</label>";
    texthtml +=  "<div class='col-sm-5'>";
    texthtml +=  "<select id='cmbtype"+id+"' name='tipeanswer[]' class='form-control {required:true}'>";
    texthtml +=  "<option value='option'>Option Choices</option>";
    texthtml +=  "<option value='text'>Input Text</option>";
    texthtml +=  "</select>";
    texthtml +=  "</div>";    
    texthtml +=  "<div class='col-sm-4'>";
    texthtml +=  "<p>Type Answer of Question</p>";
    texthtml +=  "</div>";
    texthtml +=  "</div>";
    texthtml +=  "<div class='form-group'>";
    texthtml +=  "<label for='cmbtimeout"+id+"' class='col-sm-3 control-label'>Time Count</label>";
    texthtml +=  "<div class='col-sm-1'>";
    texthtml +=  "<select id='cmbtimeout"+id+"' name='txttime[]' class='form-control {required:true}'>";    
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td><label for='editcmbtimeout"+id+"'>Time Count</label></td>";
    texthtml2 +=  "<td>";
    texthtml2 +=  "<select id='editcmbtimeout"+id+"' name='txttime[]' class='form-control {required:true}'>";
    for(var z=0; z<301;z+=5)
    {
        var terpilih="";
        if(z == timeout)
        {
            terpilih = "selected='selected'";
        }
        texthtml +=  "<option value='" + z + "' " + terpilih + ">" + z + "</option>";
        texthtml2 +=  "<option value='" + z + "' " + terpilih + ">" + z + "</option>";
    }
    texthtml2 +=  "</select>";
    texthtml2 +=  "</td>";
    texthtml2 +=  "</tr>";
    texthtml +=  "</select>";
    texthtml +=  "</div>";
    texthtml +=  "<div class='col-sm-2'>";
    texthtml +=  "Seconds";
    texthtml +=  "</div>";
    texthtml +=  "<label for='cmbdificult"+id+"' class='col-sm-2 control-label'>Difficulty Level</label>";
    texthtml +=  "<div class='col-sm-4'>";
    texthtml +=  "<select id='cmbdificult"+id+"' name='txtlevel[]' class='form-control {required:true}'>";
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td><label for='editcmbdificult"+id+"'>Difficulty Level</label></td>";
    texthtml2 +=  "<td>";
    texthtml2 +=  "<select id='editcmbdificult"+id+"' name='tipeanswer[]' class='form-control {required:true}'>";
    <?php        
    foreach($this->tambahan_fungsi->list_level() as $dt=>$value)
    {
        echo "texthtml +=  \"<option value='".$dt."'>".$value."</option>\";";
        echo "texthtml2 +=  \"<option value='".$dt."'>".$value."</option>\";";
    }
    ?>
    texthtml2 +=  "</select>";
    texthtml2 +=  "</td>";
    texthtml2 +=  "</tr>";
    texthtml +=  "</select>";
    texthtml +=  "</div>";
    texthtml +=  "</div>";
    texthtml +=  "<div class='form-group'>";
    texthtml +=  "<label for='fileimage"+id+"' class='col-sm-3 control-label'>File Image</label>";
    texthtml +=  "<div class='col-sm-7'>";
    texthtml +=  "<input type='url' name='chiledquestionimage[]' id='fileimage"+id+"' value='"+fileimage+"' maxlength='255' placeholder='File Image' class='form-control {url:true}' />";
    texthtml +=  "</div>";
    texthtml +=  "<div class='col-sm-2'>";
    texthtml +=  "<button type='button' onclick='openKCFinder(\"fileimage"+id+"\");return false;' class='btn-magenta btn'><i class='icon-search'></i> <span>Browse</span></button>";
    texthtml +=  "</div>";
    texthtml +=  "</div>"; 
    texthtml +=  "<div id='innerchile" + idadd + id + "'>"; 
    texthtml +=  stringaddoption;
    texthtml +=  "</div>";
    texthtml +=  "<div class='form-group'>";
    texthtml +=  "<label class='col-sm-3 control-label'>Answer Options</label>";
    texthtml +=  "<div class='col-sm-9'>";
    texthtml +=  "<button type='button' onclick='tambahoptions(\"innerchileopadd\",false,\""+id+"\",\"innerchile" + idadd + id +" \",\"\",\"\");return false;' class='btn-orange btn'><i class='icon-share'></i> <span>Add Option</span></button>";
    texthtml +=  "</div>";
    texthtml +=  "</div>";
    texthtml +=  "</div>";
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td><label for='editfileimage"+id+"'>File Image</label></td>";
    texthtml2 +=  "<td>";
    texthtml2 +=  "<div class='col-sm-8'>";
    texthtml2 +=  "<input type='url' name='chiledquestionimage[]' id='editfileimage"+id+"' value='"+fileimage+"' maxlength='255' placeholder='File Image' class='form-control {url:true}' />";
    texthtml2 +=  "</div>";
    texthtml2 +=  "<div class='col-sm-2'>";
    texthtml2 +=  "<button type='button' onclick='openKCFinder(\"editfileimage"+id+"\");return false;' class='btn-magenta btn'><i class='icon-search'></i> <span>Browse</span></button>";
    texthtml2 +=  "</div>";
    texthtml2 +=  "</td>";
    texthtml2 +=  "</tr>";
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td colspan='2'>";
    texthtml2 +=  "<div id='editinnerchile" + idadd + id + "'>"; 
    texthtml2 +=  stringaddoption;
    texthtml2 +=  "</div>";
    texthtml2 +=  "</td>";
    texthtml2 +=  "</tr>";    
    texthtml2 +=  "<tr>";
    texthtml2 +=  "<td><label>Answer Options</label></td>";
    texthtml2 +=  "<td><button type='button' onclick='tambahoptions(\"innerchileopedit\",false,\"edit"+id+"\",\"editinnerchile" + idadd + id +" \",\"\",\"\");return false;' class='btn-orange btn'><i class='icon-share'></i> <span>Add Option</span></button></td>";
    texthtml2 +=  "</tr>";     
    texthtml2 +=  "</table>";
    $("#frmchildquestion").append(texthtml);
    $("#frmchildquestion2").append(texthtml2);
    $("#cmbtype" + id).val(tipeanswer);
    $("#editcmbtimeout" + id).val(tipeanswer);
    $("#editcmbtype" + id).val(tipeanswer);
    $("#cmbdificult" + id).val(dificult);
    $("#editcmbdificult" + id).val(dificult);
}
function tambahoptions(idtabs,returnstringno,id,optionondiv,textquestion,optionchk)
{
    var id_rand= idtabs + Math.floor((Math.random()*10000)+1)+"akhir";
    var html ="<div id='"+id_rand+"'>";    
    html +=  "<div class='form-group'>";
    html +=  "<div class='col-sm-2 text-right'>";
    html +=  "<button type='button' onclick='removerequired(\"" + id_rand + "\");return false;' class='btn-primary btn'><i class='icon-remove-sign'></i></button>";
    html +=  "</div>";
    html +=  "<div class='col-sm-7'>";
    html +=  "<input type='text' name='optiontextof"+id+"[]' value='"+textquestion+"' maxlength='255' placeholder='Option Answer' class='form-control {required:true}' />";
    html +=  "</div>";
    html +=  "<div class='col-sm-3'>";
    html +=  "<select name='optionvalueof"+id+"[]' class='form-control'>";
    var terpilih = "selected";
    var tidakterpilih = "";
    if(optionchk=="false")
    {
        terpilih = "";
        tidakterpilih = "selected";
    }
    html +=  "<option value='true' "+terpilih+">TRUE</option>";
    html +=  "<option value='false' "+tidakterpilih+">FALSE</option>";
    html +=  "</select>";
    html +=  "</div>";
    html +=  "</div>";
    html += "</div>";
    if(returnstringno)
    {
        return html;
    }
    else
    {
        $("#"+optionondiv).append(html);
    }    
}
function openKCFinder(idtextlock) 
{
    window.KCFinder = {
        callBack: function(url) {
            var newurl = location.protocol + '//' + location.hostname + url;
            $("#"+idtextlock).val(newurl);        
            window.KCFinder = null;
        }
    };
    window.open('<?php echo base_url(); ?>resources/plugin/kcfinder/browse.php?type=images&dir=bundels/public', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
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
function removerequired(divid)
{
    $('#'+divid).remove();
}
function selectthisquiz(kodeitem)
{
    $('#txtquizid').val(kodeitem);
    $('#txtquizid2').val(kodeitem);
    $('#listdataquizsave').modal('hide');
}
function selectthisitem(kodeitem)
{
    $('#txtquestid').val(kodeitem);
    $('#txtquestid2').val(kodeitem);
    $('#listdataquestsave').modal('hide');
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
function ubahdata(id)
{
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>quiz/detail/'+id,
        data:'',
        dataType: "json",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                    '<i class="process">Wait a minute, Your request being processed</i>' +
                                    '</div>').slideDown(100);
            $("#pesanmsg2").html(""); 
        },
        success: function (data, textStatus) {
            if(data["success"])
            {
                $('#txtid2').val(data['_id']);
                $('#code2').val(data['ID']);
                $('#txttitle2').val(data['Title']);
                $('#txtdesc2').val(data['Description']);
                $('#brand2').val(data['BrandId']);
                $('#state2').val(data['State']);
                $('#txtcount2').val(data['Count']);
                $('#txtjml2').val(data['Number']);
                $('#txtdatebegin2').val(data['StartDate']);
                $('#txttimebegin2').val(data['StartTime']);
                $('#txtdateend2').val(data['EndDate']);
                $('#txttimeend2').val(data['EndTime']);
                $('#txtquizid2').val(data['RequiredQuiz']);
                $('#txtquestid2').val(data['RequiredQuest']);
                $('#requireditem2').val(data['RequiredItem']);
                $('#rewards2').val(data['Reward']);
            }
            $('#radioactive12').attr('checked', '');
            $('#radioactive22').attr('checked', '');
            if(data['IsRandom'] == "true")
            {
                $('#radioactive12').attr('checked', 'checked');
            }
            else
            {
                $('#radioactive22').attr('checked', 'checked');
            }            
            $('#frmchildquestion').html('');
            $('#frmchildquestion2').html('');
            for(var i=0; i< data['Questions'].length;i++)
            {
                var datastr = ""; 
                if(data['Questions'][i]["Options"].length>0)
                {
                    for(var j=0; j<data['Questions'][i]["Options"].length;j++)
                    {
                        if(data['Questions'][i]["Options"][j] != "undefined")
                        {
                            var tempdata = tambahoptions("Editt",true,i,"",data['Questions'][i]["Options"][j]["Answer"],data['Questions'][i]["Options"][j]["IsCorrect"]);
                            datastr += tempdata;
                        }
                    }
                }
                var rto = parseInt(data['Questions'][i]["QuestionTime"]);
                generatenewquestion("edithead",data['Questions'][i]["QuestionId"],data['Questions'][i]["Question"],data['Questions'][i]["Tipe"],rto,data['Questions'][i]["Difficulty"],data['Questions'][i]["Image"],datastr);
                counter++;
            }
            counter++;
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
            var url='<?php echo base_url(); ?>quiz/delete/'+id;
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
function generatecodernd()
{
    $.ajax({
        type: "GET",
        url: '<?php echo $this->template_admin->link("quiz/generatecode"); ?>',
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
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Quiz</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Quizs</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Quiz</a></li>
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
                                        <th width="10%">ID</th>
                                        <th width="20%">Title</th>
                                        <th width="20%">Description</th>
                                        <th width="10%">Store</th>
                                        <th width="10%">State</th>
                                        <th width="10%">Start Date</th>
                                        <th width="10%">End Date</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="9">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" enctype="multipart/form-data" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("quiz/cruid_quiz"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="code" class="col-sm-3 control-label">ID</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="code" id="code" value="" maxlength="255" placeholder="Quiz ID" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                    <button id="generate" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttitle" class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="txttitle" id="txttitle" value="" maxlength="255" placeholder="Quiz Title" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Title of Quiz!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtdesc" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-6">
                                      <textarea name="txtdesc" id="txtdesc" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of Quiz!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Random Question</label>
                                    <div class="col-sm-6">
                                        <label for="radioactive1" class="radio-inline">
                                        <input id="radioactive1" name="txtisrandom" value="false" type="radio"> False<span class='ui-icon ui-icon-check'></span>
                                      </label>
                                        <label for="radioactive2" class="radio-inline">
                                        <input id="radioactive2" name="txtisrandom" value="true" type="radio"  checked="checked"> True<span class='ui-icon ui-icon-closethick'></span>
                                      </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Set Question is Random!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtcount" class="col-sm-3 control-label">Count Show</label>
                                    <div class="col-sm-6">
                                        <select id="txtcount" name="txtcount" class="form-control {required:true}">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Count of Quiz want to show!</p>
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
                                            foreach($brand as $dt)
                                            {
                                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Store of Quiz!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
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
                                    <label for="txtquizid" class="col-sm-3 control-label">Required Quiz</label>
                                    <div class="col-sm-3">
                                      <input type="text" name="txtquizid" id="txtquizid" value="-1" maxlength="255" placeholder="Required Quiz" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="#listdataquizsave" data-toggle="modal" class="btn btn-midnightblue"><i class="icon-key"></i> Key</a>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Required of Quiz!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtquestid" class="col-sm-3 control-label">Required Quest(ID)</label>
                                    <div class="col-sm-3">
                                      <input type="text" name="txtquestid" id="txtquestid" value="-1" maxlength="255" placeholder="Required Quest(ID)" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="#listdataquestsave" data-toggle="modal" class="btn btn-sky"><i class="icon-stethoscope"></i> Key</a>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Quest(ID) of Quiz!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="requireditem" class="col-sm-3 control-label">Required Item</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="requireditem" id="requireditem" value="" maxlength="255" placeholder="Required Item" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="getrequiredid" type="button" class="btn-orange btn"><i class="icon-key"></i> <span>Key</span></button>
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
                                <div id="frmchildquestion"></div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Question</label>
                                    <div class="col-sm-6">                                      
                                      <button type="button" id="addquestionid" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Question</span></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Question of Quiz!</p>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label for="state" class="col-sm-3 control-label">State</label>
                                    <div class="col-sm-6">
                                        <select id="state" name="state" class="form-control">
                                            <?php 
                                            foreach($this->tambahan_fungsi->document_state() as $dt=>$value)
                                            {
                                                echo "<option value='".$dt."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">State Data of Quiz!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Quizes","module5","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" id="resetid" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
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
                <form method="POST" enctype="multipart/form-data" id="editbrandfrm" action="<?php echo $this->template_admin->link("quiz/cruid_quiz"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="code2">ID</label>
                            </td>
                            <td>
                                  <div class="col-sm-8">
                                    <input type="text" name="code" id="code2" value="" maxlength="255" placeholder="Quiz ID" class="form-control {required:true}" />
                                  </div>
                                  <div class="col-sm-4">
                                      <button id="generate2" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                  </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txttitle2">Title</label></td>
                            <td><input type="text" name="txttitle" id="txttitle2" value="" maxlength="255" placeholder="Quiz Title" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="txtdesc2">Description</label></td>
                            <td><textarea name="txtdesc" id="txtdesc2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label>Random Question</label></td>
                            <td>
                                  <label for="radioactive12" class="radio-inline">
                                    <input id="radioactive12" name="txtisrandom" value="false" type="radio"> False<span class='ui-icon ui-icon-check'></span>
                                  </label>
                                  <label for="radioactive22" class="radio-inline">
                                    <input id="radioactive22" name="txtisrandom" value="true" type="radio"  checked="checked"> True<span class='ui-icon ui-icon-closethick'></span>
                                  </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtcount2">Count Show</label></td>
                            <td>
                                <select id="txtcount2" name="txtcount" class="form-control {required:true}">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
                                </select>
                            </td>
                        </tr>
                        <?php
                        if($this->session->userdata('brand')=="")
                        {
                        ?>
                        <tr>
                            <td><label for="brand2">Store</label></td>
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
                            <td><label for="txtquizid2">Required Quiz</label></td>
                            <td>
                                <div class="col-sm-9">
                                  <input type="text" name="txtquizid" id="txtquizid2" value="-1" maxlength="255" placeholder="Required Quiz" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-3">
                                    <a href="#listdataquizsave" data-toggle="modal" class="btn btn-midnightblue"><i class="icon-key"></i> Key</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtquestid2">Required Quest(ID)</label></td>
                            <td>
                                <div class="col-sm-9">
                                  <input type="text" name="txtquestid" id="txtquestid2" value="-1" maxlength="255" placeholder="Required Quest(ID)" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-3">
                                    <a href="#listdataquestsave" data-toggle="modal" class="btn btn-sky"><i class="icon-stethoscope"></i> Key</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="requireditem2">Required Item</label></td>
                            <td>
                                <div class="col-sm-9">
                                  <input type="text" name="requireditem" id="requireditem2" value="" maxlength="255" placeholder="Required Item" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-3">
                                    <button id="getrequiredid2" type="button" class="btn-orange btn"><i class="icon-key"></i> <span>Key</span></button>
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
                                    <button id="getrewardid2" type="button" class="btn-success btn"><i class="icon-unlock"></i> <span>Unlocked</span></button>
                                    <button id="getrewardid_22" type="button" class="btn-success btn"><i class="icon-gift"></i> <span>Item</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="frmchildquestion2"></div></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><button type="button" id="addquestionid2" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Question</span></button></td>
                        </tr>
                        <tr>
                            <td><label for="state2">State</label></td>
                            <td>
                                <select id="state2" name="state" class="form-control">
                                    <?php 
                                    foreach($this->tambahan_fungsi->document_state() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Quizes","module5","Edit",TRUE,FALSE,"home"))
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
<div id="listdataquizsave" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Quiz Data Saved</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloadquizsave" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
                <table id="datatablechile2" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="5%">ID</th>
                            <th width="35%">Title</th>
                            <th width="50%">Description</th>
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
        </div>
    </div>
</div>
<div id="listdataquestsave" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Quest Data Saved</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloadquestsave" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
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
                            <label for="size" class="control-label">Size</label>
                            <select id='size' name='size' class='form-control'>
                                <option value="">All Size</option>
                                <?php
                                foreach($this->tambahan_fungsi->list_size() as $dt=>$value)
                                {
                                    echo "<option value='".$dt."'>".$value."</option>";
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