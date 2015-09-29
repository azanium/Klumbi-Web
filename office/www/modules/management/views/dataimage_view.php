<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    var elf = $('#file-manager').elfinder({
        url : '<?php echo $this->template_admin->link("management/dataimage/list_data"); ?>',
        uiOptions : {
            toolbar : [
                    ['back', 'forward'],
                    ['reload'],
                    ['home', 'up'],
                    ['info'],
                    ['quicklook'],
                    ['mkdir','mkfile','upload'],
                    ['open', 'download', 'getfile'],
                    ['copy', 'cut', 'paste'],
                    ['rm'],                    
                    ['duplicate','rename','edit'],                    
                    ['view'],
                    ['search']
            ]
        },
        contextmenu : {
          cwd : ['reload', 'delim', 'info'], 
          files : ['select', 'open']
        }
    }).elfinder('instance');
});
</script>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Upload</li>
        <li class="active">File Manager</li>
    </ol>
    <h1>File Manager</h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>File Manager</h4>
                </div>
                <div class="panel-body collapse in">
                    <div id="file-manager" class="well"></div>
                </div>
            </div>
        </div>
    </div>
</div>