<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $("#cmbgender").change(function(){
        if($(this).val()=="")
        {
            $(".databygender").hide();
        }
        else
        {
            $(".databygender").show();
            $(".datacarakter").show();
        }
    });
    $("#cmbtipe").change(function(){
        var dataterpilih = $(this).val();
        if( dataterpilih == "watch" || dataterpilih == "shoes" || dataterpilih == "propertie"|| dataterpilih == "leg"|| dataterpilih == "helmet"|| dataterpilih == "head"|| dataterpilih == "hat"|| dataterpilih == "hand"|| dataterpilih == "hair"|| dataterpilih == "glass"|| dataterpilih == "gender"|| dataterpilih == "face")
        {
            $(".databygender").hide();
            $(".datacarakter").show();
        }
        else if( dataterpilih == "pants" || dataterpilih == "shoes")
        {
            $(".databygender").show();
            $(".datacarakter").show();
        }
        else if( dataterpilih == "face_part_eye_brows" || dataterpilih == "face_part_eyes"|| dataterpilih == "face_part_lip")
        {
            $(".databygender").hide();
            $(".datacarakter").hide();
        }
        else
        {
            $(".databygender").show();
            $(".datacarakter").show();
        }
    });
    $("#cmbtipe2").change(function(){
        var dataterpilih = $(this).val();
        if( dataterpilih == "watch" || dataterpilih == "shoes" || dataterpilih == "propertie"|| dataterpilih == "leg"|| dataterpilih == "helmet"|| dataterpilih == "head"|| dataterpilih == "hat"|| dataterpilih == "hand"|| dataterpilih == "hair"|| dataterpilih == "glass"|| dataterpilih == "gender"|| dataterpilih == "face")
        {
            $(".databygender2").hide();
            $(".datacarakter2").show();
        }
        else if( dataterpilih == "pants" || dataterpilih == "body")
        {
            $(".databygender2").show();
            $(".datacarakter2").show();
        }
        else if( dataterpilih == "face_part_eye_brows" || dataterpilih == "face_part_eyes"|| dataterpilih == "face_part_lip")
        {
            $(".databygender2").hide();
            $(".datacarakter2").hide();
        }
        else
        {
            $(".databygender2").show();
            $(".datacarakter2").show();
        }
    });
    $("#cmbgender2").change(function(){
        if($(this).val()=="")
        {
            $(".databygender2").hide();
        }
        else
        {
            $(".databygender2").show();
            $(".datacarakter2").show();
        }
    });
    $('.select').select2({width: 'resolve'});
    $( "#generate , #generate2" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $('#txttag , #txttag2').select2({width: "resolve", tags:<?php echo json_encode($this->tambahan_fungsi->list_tag()); ?>});
    $('.cpicker').colorpicker();
    $('textarea.autosize').autosize({append: "\n"});
    $('#fileimage , #fileimage2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploader"); ?>',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileimgname").val(data.result.name);
                $("#txtfileimgname2").val(data.result.name);
                $('#preview_fileimage').text(data.result.name);
                $('#preview_fileimage2').text(data.result.name);
                $("#filepreview").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
                $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileelemenwebthin , #fileelemenwebthin2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemenwebthin"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemenwebthin").val(data.result.name);
                $("#txtfileelemenwebthin2").val(data.result.name);
                $('#preview_fileelemenwebthin').text(data.result.name);
                $('#preview_fileelemenwebthin2').text(data.result.name);
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
    $('#filematerialwebthin , #filematerialwebthin2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialwebthin"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialwebthin").val(data.result.name);
                $("#txtfilematerialwebthin2").val(data.result.name);
                $('#preview_filematerialwebthin').text(data.result.name);  
                $('#preview_filematerialwebthin2').text(data.result.name);  
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
    $('#fileelemenwebmedium , #fileelemenwebmedium2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemenwebmedium"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemenwebmedium").val(data.result.name);
                $("#txtfileelemenwebmedium2").val(data.result.name);
                $('#preview_fileelemenwebmedium').text(data.result.name);
                $('#preview_fileelemenwebmedium2').text(data.result.name);
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
    $('#filematerialwebmedium , #filematerialwebmedium2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialwebmedium"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialwebmedium").val(data.result.name);
                $("#txtfilematerialwebmedium2").val(data.result.name);
                $('#preview_filematerialwebmedium').text(data.result.name); 
                $('#preview_filematerialwebmedium2').text(data.result.name); 
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
    $('#fileelemenwebfat , #fileelemenwebfat2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemenwebfat"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemenwebfat").val(data.result.name);
                $("#txtfileelemenwebfat2").val(data.result.name);
                $('#preview_fileelemenwebfat').text(data.result.name);
                $('#preview_fileelemenwebfat2').text(data.result.name);
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
    $('#filematerialwebfat , #filematerialwebfat2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialwebfat"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialwebfat").val(data.result.name);
                $("#txtfilematerialwebfat2").val(data.result.name);
                $('#preview_filematerialwebfat').text(data.result.name);
                $('#preview_filematerialwebfat2').text(data.result.name);
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
     $('#fileelemeniosthin , #fileelemeniosthin2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemeniosthin"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemeniosthin").val(data.result.name);
                $("#txtfileelemeniosthin2").val(data.result.name);
                $('#preview_fileelemeniosthin').text(data.result.name);
                $('#preview_fileelemeniosthin2').text(data.result.name);
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
    $('#filematerialiosthin , #filematerialiosthin2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialiosthin"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialiosthin").val(data.result.name);
                $("#txtfilematerialiosthin2").val(data.result.name);
                $('#preview_filematerialiosthin').text(data.result.name);
                $('#preview_filematerialiosthin2').text(data.result.name);
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
    $('#fileelemeniosmedium , #fileelemeniosmedium2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemeniosmedium"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemeniosmedium").val(data.result.name);
                $("#txtfileelemeniosmedium2").val(data.result.name);
                $('#preview_fileelemeniosmedium').text(data.result.name);
                $('#preview_fileelemeniosmedium2').text(data.result.name);
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
    $('#filematerialiosmedium , #filematerialiosmedium2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialiosmedium"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialiosmedium").val(data.result.name);
                $("#txtfilematerialiosmedium2").val(data.result.name);
                $('#preview_filematerialiosmedium').text(data.result.name);
                $('#preview_filematerialiosmedium2').text(data.result.name);
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
    $('#fileelemeniosfat , #fileelemeniosfat2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemeniosfat"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemeniosfat").val(data.result.name);
                $("#txtfileelemeniosfat2").val(data.result.name);
                $('#preview_fileelemeniosfat').text(data.result.name);
                $('#preview_fileelemeniosfat2').text(data.result.name);
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
    $('#filematerialiosfat , #filematerialiosfat2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialiosfat"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/iOS/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialiosfat").val(data.result.name);
                $("#txtfilematerialiosfat2").val(data.result.name);
                $('#preview_filematerialiosfat').text(data.result.name);   
                $('#preview_filematerialiosfat2').text(data.result.name);   
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
    $('#fileelemenandroidthin , #fileelemenandroidthin2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemenandroidthin"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemenandroidthin").val(data.result.name);
                $("#txtfileelemenandroidthin2").val(data.result.name);
                $('#preview_fileelemenandroidthin').text(data.result.name);
                $('#preview_fileelemenandroidthin2').text(data.result.name);
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
    $('#filematerialandroidthin , #filematerialandroidthin2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialandroidthin"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialandroidthin").val(data.result.name);
                $("#txtfilematerialandroidthin2").val(data.result.name);
                $('#preview_filematerialandroidthin').text(data.result.name);
                $('#preview_filematerialandroidthin2').text(data.result.name);
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
    $('#fileelemenandroidmedium , #fileelemenandroidmedium2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemenandroidmedium"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemenandroidmedium").val(data.result.name);
                $("#txtfileelemenandroidmedium2").val(data.result.name);
                $('#preview_fileelemenandroidmedium').text(data.result.name);
                $('#preview_fileelemenandroidmedium2').text(data.result.name);
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
    $('#filematerialandroidmedium , #filematerialandroidmedium2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialandroidmedium"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialandroidmedium").val(data.result.name);
                $("#txtfilematerialandroidmedium2").val(data.result.name);
                $('#preview_filematerialandroidmedium').text(data.result.name);
                $('#preview_filematerialandroidmedium2').text(data.result.name);
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
    $('#fileelemenandroidfat , #fileelemenandroidfat2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileelemenandroidfat"); ?>',
        dataType: 'json',
        formData: {
            folder: 'characters/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileelemenandroidfat").val(data.result.name);
                $("#txtfileelemenandroidfat2").val(data.result.name);
                $('#preview_fileelemenandroidfat').text(data.result.name);
                $('#preview_fileelemenandroidfat2').text(data.result.name);
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
    $('#filematerialandroidfat , #filematerialandroidfat2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/filematerialandroidfat"); ?>',
        dataType: 'json',
        formData: {
            folder: 'materials/Android/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfilematerialandroidfat").val(data.result.name);
                $("#txtfilematerialandroidfat2").val(data.result.name);
                $('#preview_filematerialandroidfat').text(data.result.name);
                $('#preview_filematerialandroidfat2').text(data.result.name);
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
        "bJQueryUI": true,
        "bFilter": true,
        "bDestroy": true,
        "bProcessing": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("avatar/avatar/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, { "sClass" : "text-center","bSortable": false }, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"tipeshow", "value":$("#cmbtipeview").val()});
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
    $(".resetform").click(function(){
        $("input, textarea").val("");
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
                        $("input, textarea").val("");
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#txtcolor").val("#5c19a3");
                        $("#txtharga").val("0");
                        $('#preview_fileelemenwebthin').text("");
                        $('#preview_filematerialwebthin').text("");               
                        $('#preview_fileelemenwebmedium').text("");
                        $('#preview_filematerialwebmedium').text("");            
                        $('#preview_fileelemenwebfat').text("");
                        $('#preview_filematerialwebfat').text("");             
                        $('#preview_fileelemeniosthin').text("");
                        $('#preview_filematerialiosthin').text("");
                        $('#preview_fileelemeniosmedium').text("");
                        $('#preview_filematerialiosmedium').text("");
                        $('#preview_fileelemeniosfat').text("");
                        $('#preview_filematerialiosfat').text("");                
                        $('#preview_fileelemenandroidthin').text("");
                        $('#preview_filematerialandroidthin').text("");
                        $('#preview_fileelemenandroidmedium').text("");
                        $('#preview_filematerialandroidmedium').text("");
                        $('#preview_fileelemenandroidfat').text("");
                        $('#preview_filematerialandroidfat').text("");
                        $('#preview_fileimage').text("");                        
                        $("#preview_fileimage2").text("");
                        $('#preview_fileelemenwebthin2').text("");
                        $('#preview_filematerialwebthin2').text("");               
                        $('#preview_fileelemenwebmedium2').text("");
                        $('#preview_filematerialwebmedium2').text("");               
                        $('#preview_fileelemenwebfat2').text("");
                        $('#preview_filematerialwebfat2').text("");                
                        $('#preview_fileelemeniosthin2').text("");
                        $('#preview_filematerialiosthin2').text("");
                        $('#preview_fileelemeniosmedium2').text("");
                        $('#preview_filematerialiosmedium2').text("");
                        $('#preview_fileelemeniosfat2').text("");
                        $('#preview_filematerialiosfat2').text("");            
                        $('#preview_fileelemenandroidthin2').text("");
                        $('#preview_filematerialandroidthin2').text("");
                        $('#preview_fileelemenandroidmedium2').text("");
                        $('#preview_filematerialandroidmedium2').text("");
                        $('#preview_fileelemenandroidfat2').text("");
                        $('#preview_filematerialandroidfat2').text("");
                        $("#cmbtipe").val("body");
                        $("#cmbgender").val("male");
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
                        $("input, textarea").val("");
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#txtcolor").val("#5c19a3");
                        $("#txtharga").val("0");
                        $('#preview_fileelemenwebthin').text("");
                        $('#preview_filematerialwebthin').text("");               
                        $('#preview_fileelemenwebmedium').text("");
                        $('#preview_filematerialwebmedium').text("");            
                        $('#preview_fileelemenwebfat').text("");
                        $('#preview_filematerialwebfat').text("");             
                        $('#preview_fileelemeniosthin').text("");
                        $('#preview_filematerialiosthin').text("");
                        $('#preview_fileelemeniosmedium').text("");
                        $('#preview_filematerialiosmedium').text("");
                        $('#preview_fileelemeniosfat').text("");
                        $('#preview_filematerialiosfat').text("");                
                        $('#preview_fileelemenandroidthin').text("");
                        $('#preview_filematerialandroidthin').text("");
                        $('#preview_fileelemenandroidmedium').text("");
                        $('#preview_filematerialandroidmedium').text("");
                        $('#preview_fileelemenandroidfat').text("");
                        $('#preview_filematerialandroidfat').text("");
                        $('#preview_fileimage').text("");                        
                        $("#preview_fileimage2").text("");
                        $('#preview_fileelemenwebthin2').text("");
                        $('#preview_filematerialwebthin2').text("");               
                        $('#preview_fileelemenwebmedium2').text("");
                        $('#preview_filematerialwebmedium2').text("");               
                        $('#preview_fileelemenwebfat2').text("");
                        $('#preview_filematerialwebfat2').text("");                
                        $('#preview_fileelemeniosthin2').text("");
                        $('#preview_filematerialiosthin2').text("");
                        $('#preview_fileelemeniosmedium2').text("");
                        $('#preview_filematerialiosmedium2').text("");
                        $('#preview_fileelemeniosfat2').text("");
                        $('#preview_filematerialiosfat2').text("");            
                        $('#preview_fileelemenandroidthin2').text("");
                        $('#preview_filematerialandroidthin2').text("");
                        $('#preview_fileelemenandroidmedium2').text("");
                        $('#preview_filematerialandroidmedium2').text("");
                        $('#preview_fileelemenandroidfat2').text("");
                        $('#preview_filematerialandroidfat2').text("");
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
function reloaddatatable(id)
{
    dttable = $('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bDestroy": true,
        "bProcessing": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("avatar/avatar/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, { "sClass" : "text-center","bSortable": false }, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }],
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push({"name":"tipeshow", "value":$("#cmbtipeview").val()});
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
function reloaddataoption(name,terpilih)
{
    $.ajax({
        type: "GET",
        url: '<?php echo base_url()."services/api/option_category/"; ?>' + name,
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
                $('#cmbtipe').val(name);
                $('#cmbtipe2').val(name);
                $("#cmbcategory").html(data['data']); 
                $("#cmbcategory2").html(data['data']);
                $('#cmbcategory').val(terpilih);
                $('#cmbcategory2').val(terpilih);
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
function generatecodernd()
{
    $.ajax({
        type: "GET",
        url: '<?php echo $this->template_admin->link("services/api/generatecode/8"); ?>',
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
                $("#IDCode").val('C' + data['data']); 
                $("#IDCode2").val('C' + data['data']);
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>avatar/avatar/delete/'+id;
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
                            $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                            $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
function remove_file(id,jns,tipedt,size)
{
    BootstrapDialog.confirm("Are you sure want to remove file " + tipedt + " " + jns + " (" + size + ")", function(result){
        if(result) 
        {
            if(jns=="picture")
            {
                $('#txtfileimgname2').val("");
                $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                $("#preview_fileimage2").text("");
                $("#htmldel_fileimage2").html("");
            }
            else 
            {
                $('#preview_file' + tipedt + jns + size + '2').text("");
                $('#txtfile' + tipedt + jns + size + '2').val("");
                $('#htmldel_' + tipedt + jns + size + '2').html("");
            }  
        }
        return false;
    });
}
function ubahdata(id)
{
    var url='<?php echo base_url(); ?>avatar/avatar/get_detail/'+id;
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
                $('#txtfileimgname2').val(data["image"]);
                $('#txtdesc2').val(data["descriptions"]);
                $('#txtfileelemenwebthin2').val(data["element_web_thin"]);
                $('#txtfilematerialwebthin2').val(data["material_web_thin"]);                
                $('#txtfileelemenwebmedium2').val(data["element_web_medium"]);
                $('#txtfilematerialwebmedium2').val(data["material_web_medium"]);                
                $('#txtfileelemenwebfat2').val(data["element_web_fat"]);
                $('#txtfilematerialwebfat2').val(data["material_web_fat"]);                
                $('#txtfileelemeniosthin2').val(data["element_ios_thin"]);
                $('#txtfilematerialiosthin2').val(data["material_ios_thin"]);
                $('#txtfileelemeniosmedium2').val(data["element_ios_medium"]);
                $('#txtfilematerialiosmedium2').val(data["material_ios_medium"]);
                $('#txtfileelemeniosfat2').val(data["element_ios_fat"]);
                $('#txtfilematerialiosfat2').val(data["material_ios_fat"]);                
                $('#txtfileelemenandroidthin2').val(data["element_android_thin"]);
                $('#txtfilematerialandroidthin2').val(data["material_android_thin"]);
                $('#txtfileelemenandroidmedium2').val(data["element_android_medium"]);
                $('#txtfilematerialandroidmedium2').val(data["material_android_medium"]);
                $('#txtfileelemenandroidfat2').val(data["element_android_fat"]);
                $('#txtfilematerialandroidfat2').val(data["material_android_fat"]); 
                if(data["image"]!="")
                {
                    $("#htmldel_fileimage2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"picture\",\"\",\"\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_web_thin"]!="")
                {   
                    $("#htmldel_elemenwebthin2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"web\",\"elemen\",\"thin\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_web_medium"]!="")
                {    
                    $("#htmldel_elemenwebmedium2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"web\",\"elemen\",\"medium\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_web_fat"]!="")
                {    
                    $("#htmldel_elemenwebfat2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"web\",\"elemen\",\"fat\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_web_thin"]!="")
                {    
                    $("#htmldel_materialwebthin2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"web\",\"material\",\"thin\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_web_medium"]!="")
                {    
                    $("#htmldel_materialwebmedium2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"web\",\"material\",\"medium\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_web_fat"]!="")
                {    
                    $("#htmldel_materialwebfat2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"web\",\"material\",\"fat\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_ios_thin"]!="")
                {    
                    $("#htmldel_elemeniosthin2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"ios\",\"elemen\",\"thin\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_ios_medium"]!="")
                {    
                    $("#htmldel_elemeniosmedium2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"ios\",\"elemen\",\"medium\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_ios_fat"]!="")
                {    
                    $("#htmldel_elemeniosfat2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"ios\",\"elemen\",\"fat\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_ios_thin"]!="")
                {    
                    $("#htmldel_materialiosthin2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"ios\",\"material\",\"thin\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_ios_medium"]!="")
                {    
                    $("#htmldel_materialiosmedium2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"ios\",\"material\",\"medium\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_ios_fat"]!="")
                {    
                    $("#htmldel_materialiosfat2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"ios\",\"material\",\"fat\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_android_thin"]!="")
                {    
                    $("#htmldel_elemenandroidthin2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"android\",\"elemen\",\"thin\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_android_medium"]!="")
                {    
                    $("#htmldel_elemenandroidmedium2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"android\",\"elemen\",\"medium\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["element_android_fat"]!="")
                {    
                    $("#htmldel_elemenandroidfat2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"android\",\"elemen\",\"fat\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_android_thin"]!="")
                {    
                    $("#htmldel_materialandroidthin2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"android\",\"material\",\"thin\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_android_medium"]!="")
                {    
                    $("#htmldel_materialandroidmedium2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"android\",\"material\",\"medium\");'><i class='icon-trash'></i> Delete File</a>");
                }
                if(data["material_android_fat"]!="")
                {    
                    $("#htmldel_materialandroidfat2").html("<a href='#' onclick='remove_file(\"" + data["_id"] + "\",\"android\",\"material\",\"fat\");'><i class='icon-trash'></i> Delete File</a>");
                }   
                $('#txtid2').val(data["_id"]);
                $('#IDCode2').val(data["code"]);
                $('#name2').val(data["name"]);                
                $('#cmbgender2').val(data["gender"]);
                if(data["gender"]=="")
                {
                    $(".databygender2").hide();
                }
                else
                {
                    $(".databygender2").show();
                }
                $('#cmbtipe2').val(data["tipe"]); 
                var dataterpilih = data["tipe"];
                if( dataterpilih == "watch" || dataterpilih == "shoes" || dataterpilih == "propertie"|| dataterpilih == "leg"|| dataterpilih == "helmet"|| dataterpilih == "head"|| dataterpilih == "hat"|| dataterpilih == "hand"|| dataterpilih == "hair"|| dataterpilih == "glass"|| dataterpilih == "gender"|| dataterpilih == "face")
                {
                    $(".databygender2").hide();
                    $(".datacarakter2").show();
                }
                else if( dataterpilih == "pants" || dataterpilih == "body")
                {
                    $(".databygender2").show();
                    $(".datacarakter2").show();
                }
                else if( dataterpilih == "face_part_eye_brows" || dataterpilih == "face_part_eyes"|| dataterpilih == "face_part_lip")
                {
                    $(".databygender2").hide();
                    $(".datacarakter2").hide();
                }
                else
                {
                    $(".databygender2").show();
                    $(".datacarakter2").show();
                }
                $('#brand2').val(data["brand_id"]);
                $('#cmbpayment2').val(data["payment"]);
                $('#txtcolor2').val(data["color"]);
                $('#txtharga2').val(data["price"]);
                $('#txttag2').val(data["tags"]);
                $('#searctype2').val(data["search_type"]);
                $('#searchcategory2').val(data["search_category"]);
                $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img')."preview_images/"; ?>' + data["image"]);
                $("#preview_fileimage2").text(data["image"]);
                reloaddataoption(data["tipe"],data["category"]);
                $('#preview_fileelemenwebthin2').text(data["element_web_thin"]);
                $('#preview_filematerialwebthin2').text(data["material_web_thin"]);                
                $('#preview_fileelemenwebmedium2').text(data["element_web_medium"]);
                $('#preview_filematerialwebmedium2').text(data["material_web_medium"]);                
                $('#preview_fileelemenwebfat2').text(data["element_web_fat"]);
                $('#preview_filematerialwebfat2').text(data["material_web_fat"]);                
                $('#preview_fileelemeniosthin2').text(data["element_ios_thin"]);
                $('#preview_filematerialiosthin2').text(data["material_ios_thin"]);
                $('#preview_fileelemeniosmedium2').text(data["element_ios_medium"]);
                $('#preview_filematerialiosmedium2').text(data["material_ios_medium"]);
                $('#preview_fileelemeniosfat2').text(data["element_ios_fat"]);
                $('#preview_filematerialiosfat2').text(data["material_ios_fat"]);                
                $('#preview_fileelemenandroidthin2').text(data["element_android_thin"]);
                $('#preview_filematerialandroidthin2').text(data["material_android_thin"]);
                $('#preview_fileelemenandroidmedium2').text(data["element_android_medium"]);
                $('#preview_filematerialandroidmedium2').text(data["material_android_medium"]);
                $('#preview_fileelemenandroidfat2').text(data["element_android_fat"]);
                $('#preview_filematerialandroidfat2').text(data["material_android_fat"]);
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
</script>
<?php
if($this->m_checking->actions("Avatar","module6","Export",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("avatar/avatar/processexport/html"); ?>" target="_blank">Compress (*.zip)</a></li>
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
                    <h4>Avatar Item</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Avatar Items</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Avatar Item</a></li>
                          <?php 
                          if($this->m_checking->actions("Avatar","module6","Import",TRUE,FALSE,"home"))
                          {
                          ?>
                          <li><a href="#importdata" data-toggle="tab"><i class="icon-cloud-upload"></i> Import</a></li>
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
                            <p align="right">
                                <select id="cmbtipeview" name="cmbtipeview" onchange="reloaddatatable(this.value)" class="select" style="width:50%">
                                    <option value=''>All</option>
                                    <?php
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("AvatarBodyPart");
                                    foreach($tipe as $dttipe)
                                    {
                                        $listtipe = $this->mongo_db->find(array("parent"=>$dttipe['name']),0,0,array('name'=>1));
                                        if($listtipe->count()>0)
                                        {
                                            echo "<optgroup label='".$dttipe['name']."'>";
                                            foreach($listtipe as $dttipe2)
                                            {
                                                echo "<option value='".$dttipe2['name']."'>".$dttipe2['name']."</option>";
                                            }
                                            echo "</optgroup>";
                                        }
                                        else 
                                        {
                                            echo "<option value='".$dttipe['name']."'>".$dttipe['name']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </p>
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="5%">ID</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Type</th>
                                        <th width="5%">Gender</th>
                                        <th width="10%">Category</th>
                                        <th width="5%">Payment</th>
                                        <th width="10%">Store</th>
                                        <th width="5%">Color</th>
                                        <th width="10%">Preview</th>
                                        <th width="10%">File</th>
                                        <th width="15%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="13">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST"  enctype="multipart/form-data" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("avatar/avatar/cruid_avatar"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />                                    
                                    <input type="hidden" name="txtfileelemenwebthin" id="txtfileelemenwebthin" value="" />
                                    <input type="hidden" name="txtfilematerialwebthin" id="txtfilematerialwebthin" value="" />
                                    <input type="hidden" name="txtfileelemenwebmedium" id="txtfileelemenwebmedium" value="" />
                                    <input type="hidden" name="txtfilematerialwebmedium" id="txtfilematerialwebmedium" value="" />
                                    <input type="hidden" name="txtfileelemenwebfat" id="txtfileelemenwebfat" value="" />
                                    <input type="hidden" name="txtfilematerialwebfat" id="txtfilematerialwebfat" value="" />                                    
                                    <input type="hidden" name="txtfileelemeniosthin" id="txtfileelemeniosthin" value="" />
                                    <input type="hidden" name="txtfilematerialiosthin" id="txtfilematerialiosthin" value="" />
                                    <input type="hidden" name="txtfileelemeniosmedium" id="txtfileelemeniosmedium" value="" />
                                    <input type="hidden" name="txtfilematerialiosmedium" id="txtfilematerialiosmedium" value="" />
                                    <input type="hidden" name="txtfileelemeniosfat" id="txtfileelemeniosfat" value="" />
                                    <input type="hidden" name="txtfilematerialiosfat" id="txtfilematerialiosfat" value="" />
                                    <input type="hidden" name="txtfileelemenandroidthin" id="txtfileelemenandroidthin" value="" />
                                    <input type="hidden" name="txtfilematerialandroidthin" id="txtfilematerialandroidthin" value="" />
                                    <input type="hidden" name="txtfileelemenandroidmedium" id="txtfileelemenandroidmedium" value="" />
                                    <input type="hidden" name="txtfilematerialandroidmedium" id="txtfilematerialandroidmedium" value="" />
                                    <input type="hidden" name="txtfileelemenandroidfat" id="txtfileelemenandroidfat" value="" />
                                    <input type="hidden" name="txtfilematerialandroidfat" id="txtfilematerialandroidfat" value="" />
                                    <label for="IDCode" class="col-sm-3 control-label">ID</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="IDCode" id="IDCode" value="" maxlength="255" placeholder="Avatar Item ID" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="generate" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Avatar Item Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Avatar Item Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Avatar Item!</p>
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
                                          <option value="">Unisex</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Gender of Avatar Item!</p>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label for="cmbtipe" class="col-sm-3 control-label">Avatar Body Part Type</label>
                                    <div class="col-sm-6">
                                      <select id="cmbtipe" name="cmbtipe" class="form-control" onchange="reloaddataoption(this.value)">
                                        <?php
                                        $this->mongo_db->select_db("Assets");
                                        $this->mongo_db->select_collection("AvatarBodyPart");
                                        foreach($tipe as $dt)
                                        {                                             
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
                                    <div class="col-sm-3">
                                        <p class="help-block">Avatar Body Part Type of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbcategory" class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-6">
                                        <select id="cmbcategory" name="cmbcategory" class="select" style="width:100%">
                                            <option value=''>&nbsp;</option>
                                            <?php 
                                            foreach($category as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Category of Avatar Item!</p>
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
                                        <p class="help-block">Brand of Avatar Item!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label for="cmbpayment" class="col-sm-3 control-label">Payment</label>
                                    <div class="col-sm-6">
                                        <select id="cmbpayment" name="cmbpayment" class="select" style="width:100%">
                                            <?php 
                                            foreach($payment as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Payment of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtcolor" class="col-sm-3 control-label">Color</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="txtcolor" id="txtcolor" class="form-control cpicker {required:true}" value="#5c19a3" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Color of Avatar Item!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group databygender">
                                    <label for="fileelemenwebthin" class="col-sm-3 control-label">File Web Thin</label>
                                    <div class="col-sm-4">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemenwebthin" id="fileelemenwebthin" />
                                        </span>
                                        <span id="preview_fileelemenwebthin" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialwebthin" id="filematerialwebthin" />
                                        </span>
                                        <span id="preview_filematerialwebthin" class="label label-info"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileelemenwebmedium" class="col-sm-3 control-label">File Web Medium</label>
                                    <div class="col-sm-4 datacarakter">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemenwebmedium" id="fileelemenwebmedium" />
                                        </span>
                                        <span id="preview_fileelemenwebmedium" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialwebmedium" id="filematerialwebmedium" />
                                        </span>
                                        <span id="preview_filematerialwebmedium" class="label label-info"></span>
                                    </div>
                                </div>
                                <div class="form-group databygender">
                                    <label for="fileelemenwebfat" class="col-sm-3 control-label">File Web Fat</label>
                                    <div class="col-sm-4">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemenwebfat" id="fileelemenwebfat" />
                                        </span>
                                        <span id="preview_fileelemenwebfat" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialwebfat" id="filematerialwebfat" />
                                        </span>
                                        <span id="preview_filematerialwebfat" class="label label-info"></span>
                                    </div>
                                </div>
                                <hr />
                                <!--<div class="form-group databygender">
                                    <label for="fileelemeniosthin" class="col-sm-3 control-label">File iOS Thin</label>
                                    <div class="col-sm-4">
                                        <span class="btn btn-orange fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemeniosthin" id="fileelemeniosthin" />
                                        </span>
                                        <span id="preview_fileelemeniosthin" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-orange fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialiosthin" id="filematerialiosthin" />
                                        </span>
                                        <span id="preview_filematerialiosthin" class="label label-info"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileelemeniosmedium" class="col-sm-3 control-label">File iOS Medium</label>
                                    <div class="col-sm-4 datacarakter">
                                        <span class="btn btn-orange fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemeniosmedium" id="fileelemeniosmedium" />
                                        </span>
                                        <span id="preview_fileelemeniosmedium" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-orange fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialiosmedium" id="filematerialiosmedium" />
                                        </span>
                                        <span id="preview_filematerialiosmedium" class="label label-info"></span>
                                    </div>
                                </div>
                                <div class="form-group databygender">
                                    <label for="fileelemeniosfat" class="col-sm-3 control-label">File iOS Fat</label>
                                    <div class="col-sm-4">
                                        <span class="btn btn-orange fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemeniosfat" id="fileelemeniosfat" />
                                        </span>
                                        <span id="preview_fileelemeniosfat" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-orange fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialiosfat" id="filematerialiosfat" />
                                        </span>
                                        <span id="preview_filematerialiosfat" class="label label-info"></span>
                                    </div>
                                </div>
                                <hr />-->
                                <div class="form-group databygender">
                                    <label for="fileelemenandroidthin" class="col-sm-3 control-label">File Android Thin</label>
                                    <div class="col-sm-4">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemenandroidthin" id="fileelemenandroidthin" />
                                        </span>
                                        <span id="preview_fileelemenandroidthin" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialandroidthin" id="filematerialandroidthin" />
                                        </span>
                                        <span id="preview_filematerialandroidthin" class="label label-info"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileelemenandroidmedium" class="col-sm-3 control-label">File Android Medium</label>
                                    <div class="col-sm-4 datacarakter">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemenandroidmedium" id="fileelemenandroidmedium" />
                                        </span>
                                        <span id="preview_fileelemenandroidmedium" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialandroidmedium" id="filematerialandroidmedium" />
                                        </span>
                                        <span id="preview_filematerialandroidmedium" class="label label-info"></span>
                                    </div>
                                </div>
                                <div class="form-group databygender">
                                    <label for="fileelemenandroidfat" class="col-sm-3 control-label">File Android Fat</label>
                                    <div class="col-sm-4">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Elemen file...</span>
                                            <input type="file" name="fileelemenandroidfat" id="fileelemenandroidfat" />
                                        </span>
                                        <span id="preview_fileelemenandroidfat" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose Material file...</span>
                                            <input type="file" name="filematerialandroidfat" id="filematerialandroidfat" />
                                        </span>
                                        <span id="preview_filematerialandroidfat" class="label label-info"></span>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileimage" class="col-sm-3 control-label">Preview Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-sky fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimage" id="fileimage" />
                                        </span>
                                        <span id="preview_fileimage" class="label label-info"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image file of Avatar Item!</p>
                                    </div>
                                </div> 
                                <hr />
                                <div class="form-group">
                                    <label for="txtdesc" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-6">
                                        <textarea name="txtdesc" id="txtdesc" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtharga" class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-6">
                                        <input type="number" name="txtharga" id="txtharga" value="0" maxlength="255" step="1" min="0" max="1000000" placeholder="Price" class="form-control {required:true, number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Price of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttag" class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-6">
                                        <textarea name="txttag" id="txttag" style="width:100%" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Tag of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="searctype" class="col-sm-3 control-label">Search Type Keyword</label>
                                    <div class="col-sm-6">
                                        <select id="searctype" name="searctype" class="select" style="width:100%">
                                            <option value=''>&nbsp;</option>
                                            <?php 
                                            foreach($searchtype as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Search type of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="searchcategory" class="col-sm-3 control-label">Business Category</label>
                                    <div class="col-sm-6">
                                        <select id="searchcategory" name="searchcategory" class="select" style="width:100%">
                                            <option value=''>&nbsp;</option>
                                            <?php 
                                            foreach($searchcategory as $dt)
                                            {
                                                echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Business category of Avatar Item!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Avatar","module6","Add",TRUE,FALSE,"home"))
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
                        if($this->m_checking->actions("Avatar","module6","Import",TRUE,FALSE,"home"))
                        {
                        ?>
                        <div class="tab-pane" id="importdata">
                            <form method="POST"  enctype="multipart/form-data" class="form-horizontal" action="<?php echo $this->template_admin->link("avatar/avatar/prosesimport"); ?>">
                                <div class="form-group">
                                    <label for="fileimportbrand" class="col-sm-3 control-label">File Import Brand</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file txt...</span>
                                            <input type="file" name="fileimportbrand" id="fileimportbrand" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File json Brand format *.txt!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileimportpayment" class="col-sm-3 control-label">File Import Payment</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file txt...</span>
                                            <input type="file" name="fileimportpayment" id="fileimportpayment" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File json Paymen format *.txt!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileimportcategory" class="col-sm-3 control-label">File Import Category</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file txt...</span>
                                            <input type="file" name="fileimportcategory" id="fileimportcategory" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File json Category format *.txt!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileimporttype" class="col-sm-3 control-label">File Import Type</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file txt...</span>
                                            <input type="file" name="fileimporttype" id="fileimporttype" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File json Type format *.txt!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label for="fileimportavatar" class="col-sm-3 control-label">File Import Avatar</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file txt...</span>
                                            <input type="file" name="fileimportavatar" id="fileimportavatar" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File json Avatar Item format *.txt!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn-green btn"> <i class="icon-cogs"></i> <span>Process Import</span></button>&nbsp;&nbsp;
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("avatar/avatar/cruid_avatar"); ?>" enctype="multipart/form-data">
                    <table width="100%" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="15%">
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <input type="hidden" name="txtfileimgname" id="txtfileimgname2" value="" />                                    
                                <input type="hidden" name="txtfileelemenwebthin" id="txtfileelemenwebthin2" value="" />
                                <input type="hidden" name="txtfilematerialwebthin" id="txtfilematerialwebthin2" value="" />
                                <input type="hidden" name="txtfileelemenwebmedium" id="txtfileelemenwebmedium2" value="" />
                                <input type="hidden" name="txtfilematerialwebmedium" id="txtfilematerialwebmedium2" value="" />
                                <input type="hidden" name="txtfileelemenwebfat" id="txtfileelemenwebfat2" value="" />
                                <input type="hidden" name="txtfilematerialwebfat" id="txtfilematerialwebfat2" value="" />                                    
                                <input type="hidden" name="txtfileelemeniosthin" id="txtfileelemeniosthin2" value="" />
                                <input type="hidden" name="txtfilematerialiosthin" id="txtfilematerialiosthin2" value="" />
                                <input type="hidden" name="txtfileelemeniosmedium" id="txtfileelemeniosmedium2" value="" />
                                <input type="hidden" name="txtfilematerialiosmedium" id="txtfilematerialiosmedium2" value="" />
                                <input type="hidden" name="txtfileelemeniosfat" id="txtfileelemeniosfat2" value="" />
                                <input type="hidden" name="txtfilematerialiosfat" id="txtfilematerialiosfat2" value="" />
                                <input type="hidden" name="txtfileelemenandroidthin" id="txtfileelemenandroidthin2" value="" />
                                <input type="hidden" name="txtfilematerialandroidthin" id="txtfilematerialandroidthin2" value="" />
                                <input type="hidden" name="txtfileelemenandroidmedium" id="txtfileelemenandroidmedium2" value="" />
                                <input type="hidden" name="txtfilematerialandroidmedium" id="txtfilematerialandroidmedium2" value="" />
                                <input type="hidden" name="txtfileelemenandroidfat" id="txtfileelemenandroidfat2" value="" />
                                <input type="hidden" name="txtfilematerialandroidfat" id="txtfilematerialandroidfat2" value="" />
                                <label for="IDCode2">ID</label>
                            </td>
                            <td width="85%">
                                <div class="col-sm-8">
                                  <input type="text" name="IDCode" id="IDCode2" value="" maxlength="255" placeholder="Avatar Item ID" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="generate2" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="name2">Avatar Item Name</label></td>
                            <td><input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Avatar Item Name" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="cmbgender2">Gender</label></td>
                            <td>
                                <select id="cmbgender2" name="cmbgender" class="form-control">
                                    <?php
                                    foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                    <option value="">Unisex</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="cmbtipe2">Avatar Body Part Type</label></td>
                            <td>
                                <select id="cmbtipe2" name="cmbtipe" class="form-control" onchange="reloaddataoption(this.value)">
                                    <?php
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("AvatarBodyPart");
                                    foreach($tipe as $dt)
                                    {                                             
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
                            </td>
                        </tr>
                        <tr>
                            <td><label for="cmbcategory2">Category</label></td>
                            <td>
                                <select id="cmbcategory2" name="cmbcategory" class="form-control">
                                    <option value=''>&nbsp;</option>
                                    <?php 
                                    foreach($category as $dt)
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
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
                            <td><label for="cmbpayment2">Payment</label></td>
                            <td>
                                <select id="cmbpayment2" name="cmbpayment" class="form-control">
                                    <?php 
                                    foreach($payment as $dt)
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtcolor2">Color</label></td>
                            <td><input type="text" name="txtcolor" id="txtcolor2" class="form-control cpicker {required:true}" value="#5c19a3" /></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="fileelemenwebthin2">Web Elemen file Thin</label></td>
                            <td><input type="file" name="fileelemenwebthin" id="fileelemenwebthin2" /><span id="preview_fileelemenwebthin2" class="label label-danger"></span><div id="htmldel_elemenwebthin2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="filematerialwebthin2">Web Material file Thin</label></td>
                            <td><input type="file" name="filematerialwebthin" id="filematerialwebthin2" /><span id="preview_filematerialwebthin2" class="label label-danger"></span><div id="htmldel_materialwebthin2"></div></td>
                        </tr>
                        <tr class="datacarakter2">
                            <td><label for="fileelemenwebmedium2">Web Elemen file Medium</label></td>
                            <td><input type="file" name="fileelemenwebmedium" id="fileelemenwebmedium2" /><span id="preview_fileelemenwebmedium2" class="label label-danger"></span><div id="htmldel_elemenwebmedium2"></div></td>
                        </tr>
                        <tr>
                            <td><label for="filematerialwebmedium2">Web Material file Medium</label></td>
                            <td><input type="file" name="filematerialwebmedium" id="filematerialwebmedium2" /><span id="preview_filematerialwebmedium2" class="label label-danger"></span><div id="htmldel_materialwebmedium2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="fileelemenwebfat2">Web Elemen file Fat</label></td>
                            <td><input type="file" name="fileelemenwebfat" id="fileelemenwebfat2" /><span id="preview_fileelemenwebfat2" class="label label-danger"></span><div id="htmldel_elemenwebfat2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="filematerialwebfat2">Web Material file Fat</label></td>
                            <td><input type="file" name="filematerialwebfat" id="filematerialwebfat2" /><span id="preview_filematerialwebfat2" class="label label-danger"></span><div id="htmldel_materialwebfat2"></div></td>
                        </tr>
                        <!--<tr class="databygender2">
                            <td><label for="fileelemeniosthin2">iOS Elemen file Thin</label></td>
                            <td><input type="file" name="fileelemeniosthin" id="fileelemeniosthin2" /><span id="preview_fileelemeniosthin2" class="label label-danger"></span><div id="htmldel_elemeniosthin2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="filematerialiosthin2">iOS Material file Thin</label></td>
                            <td><input type="file" name="filematerialiosthin" id="filematerialiosthin2" /><span id="preview_filematerialiosthin2" class="label label-danger"></span><div id="htmldel_materialiosthin2"></div></td>
                        </tr>
                        <tr class="datacarakter2">
                            <td><label for="fileelemeniosmedium2">iOS Elemen file Medium</label></td>
                            <td><input type="file" name="fileelemeniosmedium" id="fileelemeniosmedium2" /><span id="preview_fileelemeniosmedium2" class="label label-danger"></span><div id="htmldel_elemeniosmedium2"></div></td>
                        </tr>
                        <tr>
                            <td><label for="filematerialiosmedium2">iOS Material file Medium</label></td>
                            <td><input type="file" name="filematerialiosmedium" id="filematerialiosmedium2" /><span id="preview_filematerialiosmedium2" class="label label-danger"></span><div id="htmldel_materialiosmedium2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="fileelemeniosfat2">iOS Elemen file Fat</label></td>
                            <td><input type="file" name="fileelemeniosfat" id="fileelemeniosfat2" /><span id="preview_fileelemeniosfat2" class="label label-danger"></span><div id="htmldel_elemeniosfat2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="filematerialiosfat2">iOS Material file Fat</label></td>
                            <td><input type="file" name="filematerialiosfat" id="filematerialiosfat2" /><span id="preview_filematerialiosfat2" class="label label-danger"></span><div id="htmldel_materialiosfat2"></div></td>
                        </tr>-->
                        <tr class="databygender2">
                            <td><label for="fileelemenandroidthin2">Android Elemen file  Thin</label></td>
                            <td><input type="file" name="fileelemenandroidthin" id="fileelemenandroidthin2" /><span id="preview_fileelemenandroidthin2" class="label label-danger"></span><div id="htmldel_elemenandroidthin2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="filematerialandroidthin2">Android Material file  Thin</label></td>
                            <td><input type="file" name="filematerialandroidthin" id="filematerialandroidthin2" /><span id="preview_filematerialandroidthin2" class="label label-danger"></span><div id="htmldel_materialandroidthin2"></div></td>
                        </tr>
                        <tr class="datacarakter2">
                            <td><label for="fileelemenandroidmedium2">Android Elemen file Medium</label></td>
                            <td><input type="file" name="fileelemenandroidmedium" id="fileelemenandroidmedium2" /><span id="preview_fileelemenandroidmedium2" class="label label-danger"></span><div id="htmldel_elemenandroidmedium2"></div></td>
                        </tr>
                        <tr>
                            <td><label for="filematerialandroidmedium2">Android Material file Medium</label></td>
                            <td><input type="file" name="filematerialandroidmedium" id="filematerialandroidmedium2" /><span id="preview_filematerialandroidmedium2" class="label label-danger"></span><div id="htmldel_materialandroidmedium2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="fileelemenandroidfat2">Android Elemen file Fat</label></td>
                            <td><input type="file" name="fileelemenandroidfat" id="fileelemenandroidfat2" /><span id="preview_fileelemenandroidfat2" class="label label-danger"></span><div id="htmldel_elemenandroidfat2"></div></td>
                        </tr>
                        <tr class="databygender2">
                            <td><label for="filematerialandroidfat2">Android Material file Fat</label></td>
                            <td><input type="file" name="filematerialandroidfat" id="filematerialandroidfat2" /><span id="preview_filematerialandroidfat2" class="label label-danger"></span><div id="htmldel_materialandroidfat2"></div></td>
                        </tr>
                        <tr>
                            <td><label for="fileimage2">Preview Picture</label></td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimage" id="fileimage2" />
                                </span>
                                <span id="preview_fileimage2" class="label label-danger"></span>
                                <div id="htmldel_fileimage2"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><img id="filepreview2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" /></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtdesc2">Description</label>
                            </td>
                            <td>
                                <textarea name="txtdesc" id="txtdesc2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtharga2">Price</label></td>
                            <td><input type="number" name="txtharga" id="txtharga2" value="0" maxlength="255" step="1" min="0" max="1000000" placeholder="Price" class="form-control {required:true, number:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="txttag2">Tags</label></td>
                            <td><textarea name="txttag" id="txttag2" style="width:100%" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="searctype2">Search Type Keyword</label></td>
                            <td>
                                <select id="searctype2" name="searctype" class="form-control">
                                    <option value=''>&nbsp;</option>
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
                            <td><label for="searchcategory2">Business Category</label></td>
                            <td>
                                <select id="searchcategory2" name="searchcategory" class="form-control">
                                    <option value=''>&nbsp;</option>
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
                            if($this->m_checking->actions("Avatar","module6","Edit",TRUE,FALSE,"home"))
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