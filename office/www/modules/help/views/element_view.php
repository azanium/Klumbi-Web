<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="panel panel-success">
            <div class='panel-heading'><h4>HTML Help</h4></div>
            <div class='panel-body'>
                <div class="row">
                    <div class="col-md-4">                        
                        <div class="well well-sm">Content</div>
                        <pre class="prettyprint linenums"><?php echo htmlentities('<div class="well well-sm">Content.</div>'); ?></pre>
                    </div>
                    <div class="col-md-4">                        
                        <div class="well">Content.</div>
                        <pre class="prettyprint linenums"><?php echo htmlentities('<div class="well">Content.</div>'); ?></pre>
                    </div>
                    <div class="col-md-4">                        
                        <div class="well well-lg">Content</div>
                        <pre class="prettyprint linenums"><?php echo htmlentities('<div class="well well-lg">Content.</div>'); ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"> 
            <blockquote>
                <p>Description</p>
                <small>Subdetail <cite title="Description Title">Description</cite></small>
            </blockquote>
            <pre class="prettyprint linenums"><?php echo htmlentities('<blockquote>')."\r\n".htmlentities('<p>Description</p>')."\r\n".htmlentities('<small>Subdetail <cite title="Description Title">Description</cite></small>')."\r\n".htmlentities('</blockquote>'); ?></pre>
            <address>
                <strong>Klumbi, Inc.</strong><br />
                M-Stars Jl. Kemang, Suite 600<br />
                Jakarta, CA 94107<br />
                <a href="mailto:#">uda_rido@yahoo.com</a><br />
                <abbr title="Phone">Phone:</abbr> 083821639065
            </address>
            <pre class="prettyprint linenums"><?php echo htmlentities('<address>')."\r\n".htmlentities('<strong>Klumbi, Inc.</strong><br />')."\r\n".htmlentities('M-Stars Jl. Kemang, Suite 600<br />')."\r\n".htmlentities('Jakarta, CA 94107<br />')."\r\n".htmlentities('<a href="mailto:#">uda_rido@yahoo.com</a><br />')."\r\n".htmlentities('<abbr title="Phone">Phone:</abbr> 083821639065')."\r\n".htmlentities('</address>'); ?></pre>
            <div class="panel">
                <div class="list-group"> 
                    <a href="#" class="list-group-item"><span class="badge">201</span> <i class="icon-envelope"></i> Inbox </a> 
                    <a href="#" class="list-group-item"><span class="badge">5021</span> <i class="icon-eye-open"></i> Profile visits </a> 
                    <a href="#" class="list-group-item"><span class="badge">14</span> <i class="icon-phone"></i> Call </a> 
                    <a href="#" class="list-group-item"><span class="badge">20</span> <i class="icon-comments-alt"></i> Messages </a> 
                    <a href="#" class="list-group-item"><span class="badge">14</span> <i class="icon-bookmark"></i> Bookmarks </a> 
                    <a href="#" class="list-group-item"><span class="badge">30</span> <i class="icon-bell"></i> Notifications </a> 
                </div>
            </div>            
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="panel">')."\r\n".htmlentities('<div class="list-group">')."\r\n".htmlentities('<a href="#" class="list-group-item"><span class="badge">14</span> <i class="icon-phone"></i> Call </a>')."\r\n".htmlentities('<a href="#" class="list-group-item"><span class="badge">201</span> <i class="icon-envelope"></i> Inbox </a>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>'); ?></pre>
            <span class="tooltips" data-trigger="hover" data-original-title="Tooltips" data-placement="bottom">Tooltips</span>
            <pre class="prettyprint linenums"><?php echo htmlentities('<span class="tooltips" data-placement="bottom" data-trigger="hover" data-original-title="Tooltips">Tooltips</span>'); ?></pre>
            <dl class="dl-horizontal">
                <dt><code>bottom</code></dt><dd>Tooltips on Botton.</dd>
                <dt><code>left</code></dt><dd>Tooltips on Left.</dd>
                <dt><code>top</code></dt><dd>Tooltips on Top.</dd>
                <dt><code>right</code></dt><dd>Tooltips on Right.</dd>
            </dl>
            <span class="popovers" data-trigger="hover" data-toggle="popover" data-content="Data Information" data-original-title="Title">Ini Popover</span>
            <pre class="prettyprint linenums"><?php echo htmlentities('<span class="popovers" data-trigger="hover" data-toggle="popover" data-content="Data Information" data-original-title="Title">Ini Popover</span>'); ?></pre>
        </div>
        <div class="col-md-6">                        
            <div class="panel panel-info">
                <div class='panel-heading'><h4>List Of Class</h4></div>
                <div class='panel-body'>
                    <h3>List Class</h3>
                    <dl class="dl-horizontal">
                        <dt><code>list-unstyled</code></dt><dd>For List Unstyle.</dd>
                        <dt><code>pagination</code></dt><dd>List Pagination.</dd>
                        <dt><code>pagination-sm</code></dt><dd>Smaller Pagination.</dd>
                        <dt><code>pagination-lg</code></dt><dd>Bigger Pagination.</dd>
                        <dt><code>pager</code></dt><dd>Align Margin Side.</dd>
                        <dt><code>previous</code></dt><dd>li class.</dd>
                        <dt><code>next</code></dt><dd>li class.</dd>
                        <dt><code>disable</code></dt><dd>li class disable.</dd>
                        <dt><code>active</code></dt><dd>li class active.</dd>
                    </dl>
                    <h3>Text Class</h3>
                    <dl class="dl-horizontal">
                        <dt><code>list-unstyled</code></dt><dd>For List.</dd>
                        <dt><code>pull-right</code></dt><dd>pull-right.</dd>
                        <dt><code>pull-left</code></dt><dd>pull-left.</dd>
                        <dt><code>text-left</code></dt><dd>Left Text Align.</dd>
                        <dt><code>text-center</code></dt><dd>Center Text Align.</dd>
                        <dt><code>text-right</code></dt><dd>Right Text Align.</dd>                        
                        <dt><code>text-muted</code></dt><dd class="text-muted">Mute Text.</dd>
                        <dt><code>text-primary</code></dt><dd class="text-primary">text-primary Text.</dd>
                        <dt><code>text-success</code></dt><dd class="text-success">text-success Text.</dd>
                        <dt><code>text-warning</code></dt><dd class="text-warning">text-warning Text.</dd>
                        <dt><code>text-danger</code></dt><dd class="text-danger">text-danger Text.</dd>
                        <dt><code>pull-right</code></dt><dd>pull-right Text.</dd>                        
                        <dt><code>img-rounded</code></dt><dd>img-rounded Image.</dd>
                    </dl>
                    <h3>Image Class</h3>
                    <dl class="dl-horizontal">                      
                        <dt><code>img-rounded</code></dt><dd>img-rounded Image.</dd>
                        <dt><code>img-circle</code></dt><dd>img-circle Image.</dd>
                        <dt><code>img-thumbnail</code></dt><dd>img-thumbnail Image.</dd>
                        <dt><code>img-responsive</code></dt><dd>img-responsive Image.</dd>
                    </dl>
                    <h3>Chat</h3>
                    <dl class="dl-horizontal">                      
                        <dt><code>chat-primary</code></dt><dd>chat-primary chat.</dd>
                        <dt><code>chat-indigo</code></dt><dd>chat-indigo chat.</dd>
                        <dt><code>chat-midnightblue</code></dt><dd>chat-midnightblue chat.</dd>
                        <dt><code>me</code></dt><dd>me chat.</dd>
                        <dt><code>chat-success</code></dt><dd>chat-success chat.</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-danger">
                <div class='panel-heading'><h4>User Statistic</h4></div>
                <div class='panel-body'>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="#" class="shortcut-tiles tiles-info">
                                <div class="tiles-body">
                                    <div class="pull-left"><i class="icon-home"></i></div>
                                    <div class="pull-right"><span class="badge">2</span></div>
                                </div>
                                <div class="tiles-footer">Short Name</div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="info-tiles tiles-info" href="#">
                                <div class="tiles-heading">
                                    <div class="pull-left">Icon Title</div>
                                    <div class="pull-right"><i class="icon-caret-down"></i> 9.8%</div>
                                </div>
                                <div class="tiles-body">
                                    <div class="pull-left"><i class="icon-group"></i></div>
                                    <div class="pull-right">125</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-5">
                            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="col-md-2">')."\r\n".htmlentities('<a href="#" class="shortcut-tiles tiles-info">')."\r\n".htmlentities('<div class="tiles-body">')."\r\n".htmlentities('<div class="pull-left"><i class="icon-home"></i></div>')."\r\n".htmlentities('<div class="pull-right"><span class="badge">2</span></div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="tiles-footer">Short Name</div>')."\r\n".htmlentities('</a>')."\r\n".htmlentities('</div>'); ?></pre>
                            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="col-md-3">')."\r\n".htmlentities('<a href="#" class="info-tiles tiles-info">')."\r\n".htmlentities('<div class="tiles-heading">')."\r\n".htmlentities('<div class="pull-left">Icon Title</div>')."\r\n".htmlentities('<div class="pull-right"><i class="icon-caret-down"></i> 9.8%</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="tiles-body">')."\r\n".htmlentities('<div class="pull-left"><i class="icon-home"></i></div>')."\r\n".htmlentities('<div class="pull-right"><span class="badge">2</span></div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</a>')."\r\n".htmlentities('</div>'); ?></pre>
                        </div>
                        <div class="col-md-2">
                            <ol class="list-unstyled">
                                <li>tiles-info</li>
                                <li>tiles-brown</li>
                                <li>tiles-primary</li>
                                <li>tiles-indigo</li>
                                <li>tiles-inverse</li>
                                <li>tiles-warning</li>
                                <li>tiles-danger</li>
                                <li>tiles-orange</li>
                                <li>tiles-success</li>
                                <li>tiles-magenta</li>
                                <li>tiles-midnightblue</li>
                                <li>tiles-green</li>
                                <li>tiles-inverse</li>
                                <li>tiles-grape</li>
                                <li>tiles-sky</li>
                                <li>tiles-purple</li>
                            </ol>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contextual-progress">
                <div class="clearfix">
                    <div class="progress-title">Proses</div>
                    <div class="progress-percentage">25%</div>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-info" style="width: 25%"></div>
                </div>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="contextual-progress">')."\r\n".htmlentities('<div class="clearfix">')."\r\n".htmlentities('<div class="progress-title">Proses</div>')."\r\n".htmlentities('<div class="progress-percentage">25%</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="progress">')."\r\n".htmlentities('<div class="progress-bar progress-bar-info" style="width: 25%"></div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>'); ?></pre>
        </div>
        <div class="col-md-5">
            <div class="progress">
                  <div class="progress-bar progress-bar-success" style="width: 35%"></div>
                  <div class="progress-bar progress-bar-warning" style="width: 20%"></div>
                  <div class="progress-bar progress-bar-danger" style="width: 10%"></div>
            </div>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-inverse" style="width: 55%"></div>
            </div>            
            <div class="progress">
              <div class="progress-bar progress-bar-inverse" style="width: 40%"></div>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="progress">')."\r\n".htmlentities('<div class="progress-bar progress-bar-inverse" style="width: 40%"></div>')."\r\n".htmlentities('</div>'); ?></pre>
        </div>
        <div class="col-md-2">
            <ol class="list-unstyled">
                <li>progress</li>
                <li>progress-striped</li>
                <li>progress-striped active</li>
                <li>progress-bar-info</li>
                <li>progress-bar-primary</li>
                <li>progress-bar-warning</li>
                <li>progress-bar-danger</li>
                <li>progress-bar-success</li>
                <li>progress-bar-inverse</li>                
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="50%">Classes</th>
                        <th width="50%">Badges</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>No modifiers</td>
                        <td><span class="badge">42</span></td>
                    </tr>
                    <tr>
                        <td><code>.badge-primary</code></td>
                        <td><span class="badge badge-primary">1</span></td>
                    </tr>
                    <tr>
                        <td><code>.badge-success</code></td>
                        <td><span class="badge badge-success">22</span></td>
                    </tr>
                    <tr>
                        <td><code>.badge-info</code></td>
                        <td><span class="badge badge-info">30</span></td>
                    </tr>
                    <tr>
                        <td><code>.badge-warning</code></td>
                        <td><span class="badge badge-warning">412</span></td>
                    </tr>
                    <tr>
                        <td><code>.badge-danger</code></td>
                        <td><span class="badge badge-danger">999</span></td>
                    </tr>
                </tbody>
            </table>   
            <code><?php echo htmlentities('<span class="badge badge-danger">5</span>'); ?></code>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="50%">Classes</th>
                        <th width="50%">Labels</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>.label-default</code></td>
                        <td><span class="label label-default">Default</span></td>
                    </tr>
                    <tr>
                        <td><code>.label-primary</code></td>
                        <td><span class="label label-primary">Primary</span></td>
                    </tr>
                    <tr>
                        <td><code>.label-success</code></td>
                        <td><span class="label label-success">Success</span></td>
                    </tr>
                    <tr>
                        <td><code>.label-info</code></td>
                        <td><span class="label label-info">Info</span></td>
                    </tr>
                    <tr>
                        <td><code>.label-warning</code></td>
                        <td><span class="label label-warning">Warning</span></td>
                    </tr>
                    <tr>
                        <td><code>.label-danger</code></td>
                        <td><span class="label label-danger">Danger</span></td>
                    </tr>
                </tbody>
            </table>
            <code><?php echo htmlentities('<span class="label label-default">New</span>'); ?></code>            
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4><i class="icon-cloud"></i> Header</h4>
                <div class="options">&nbsp;</div>
              </div>
              <div class="panel-body collapse in">Isi Content</div>
              <div class="panel-footer">Footer</div>
            </div>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4><i class="icon-cloud"></i> Header</h4>
                <div class="options">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-desktop"></i> Tab 1</a></li>
                        <li><a href="#tab2" data-toggle="tab"><i class="icon-code"></i>  Tab 2</a></li>
                    </ul>
                </div>
              </div>
              <div class="panel-body collapse in">
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab1">
                          Isi Content tab 1
                      </div>
                      <div class="tab-pane" id="tab2">
                          Isi Content tab 2
                      </div>
                  </div>
                  </div>
              <div class="panel-footer">Footer</div>
            </div>
        </div>        
        <div class="col-md-6">
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="panel panel-primary">')."\r\n".htmlentities('<div class="panel-heading">')."\r\n".htmlentities('<h4><i class="icon-cloud"></i> Header</h4>')."\r\n".htmlentities('<div class="options">&nbsp;</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="panel-body collapse in">Isi Content</div>')."\r\n".htmlentities('<div class="panel-footer">Footer</div>')."\r\n".htmlentities('</div>'); ?></pre>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="panel panel-primary">')."\r\n".htmlentities('<div class="panel-heading">')."\r\n".htmlentities('<h4><i class="icon-cloud"></i> Header</h4>')."\r\n".htmlentities('<div class="options">')."\r\n".htmlentities('<ul class="nav nav-tabs">')."\r\n".htmlentities('<li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-desktop"></i> Tab 1</a></li>')."\r\n".htmlentities('<li><a href="#tab2" data-toggle="tab"><i class="icon-code"></i>  Tab 2</a></li>')."\r\n".htmlentities('</ul>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="panel-body collapse in">')."\r\n".htmlentities('<div class="tab-content">')."\r\n".htmlentities('<div class="tab-pane active" id="tab1">Isi Content tab 1</div>')."\r\n".htmlentities('<div class="tab-pane" id="tab2">Isi Content tab 2</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="panel-footer">Footer</div>')."\r\n".htmlentities('</div>'); ?></pre>
        </div>
        <div class="col-md-2">
            <ol class="list-unstyled">
                <li>panel-primary</li>
                <li>panel-success</li>
                <li>panel-info</li>
                <li>panel-danger</li>
                <li>panel-indigo</li>
                <li>panel-orange</li>
                <li>panel-brown</li>
                <li>panel-sky</li>
                <li>panel-midnightblue</li>         
                <li>panel-magenta</li>  
                <li>panel-purple</li>  
                <li>panel-green</li> 
                <li>gray</li> 
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <link rel="stylesheet" type='text/css' href='<?php echo base_url(); ?>resources/css/according.css' />
            <div id="accordioninpanel" class="accordion-group">
                <div class="accordion-item">
                    <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein1"><h4>Collapsible Group Item #1</h4></a>
                    <div id="collapsein1" class="collapse">
                        <div class="accordion-body">Content According 1.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein2"><h4>Collapsible Group Item #1</h4></a>
                    <div id="collapsein2" class="collapse">
                        <div class="accordion-body">Content According 2.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <pre class="prettyprint linenums"><?php echo htmlentities('<link rel="stylesheet" type="text/css" href="'.base_url().'resources/css/according.css" />')."\r\n".htmlentities('<div id="accordioninpanel" class="accordion-group">')."\r\n".htmlentities('<div class="accordion-item">')."\r\n".htmlentities('<a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein1"><h4>Collapsible Group Item #1</h4></a>')."\r\n".htmlentities('<div id="collapsein1" class="collapse">')."\r\n".htmlentities('<div class="accordion-body">Content According 1.</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="accordion-item">')."\r\n".htmlentities('<a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein2"><h4>Collapsible Group Item #1</h4></a>')."\r\n".htmlentities('<div id="collapsein2" class="collapse">')."\r\n".htmlentities('<div class="accordion-body">Content According 2.</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>'); ?></pre>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">            
            <a href="#" class="btn btn-default btn-xs">Extra Small</a>
            <pre class="prettyprint linenums"><?php echo htmlentities('<a href="#" class="btn btn-default btn-xs">Extra Small</a>'); ?></pre>
            <div class="btn-group">
                <a href="#" class="btn btn-default">Another Menu</a>
                <a href="#" class="btn btn-default dropdown-toggle alt-border" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="btn-group">')."\r\n".htmlentities('<a href="#" class="btn btn-default">Another Menu</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default dropdown-toggle alt-border" data-toggle="dropdown">Dropdown <span class="caret"></span></a>')."\r\n".htmlentities('<ul class="dropdown-menu" role="menu">')."\r\n".htmlentities('<li><a href="#">Action</a></li>')."\r\n".htmlentities('<li><a href="#">Another action</a></li>')."\r\n".htmlentities('<li><a href="#">Something else here</a></li>')."\r\n".htmlentities('<li class="divider"></li>')."\r\n".htmlentities('<li><a href="#">Separated link</a></li>')."\r\n".htmlentities('</ul>')."\r\n".htmlentities('</div>'); ?></pre>
            <div class="btn-group">
                <a href="#" class="btn btn-default"><i class="icon-align-left"></i></a>
                <a href="#" class="btn btn-default"><i class="icon-align-center"></i></a>
                <a href="#" class="btn btn-default"><i class="icon-align-right"></i></a>
                <a href="#" class="btn btn-default"><i class="icon-align-justify"></i></a>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="btn-group">')."\r\n".htmlentities('<a href="#" class="btn btn-default"><i class="icon-align-left"></i></a>')."\r\n".htmlentities('<a href="#" class="btn btn-default"><i class="icon-align-center"></i></a>')."\r\n".htmlentities('<a href="#" class="btn btn-default"><i class="icon-align-right"></i></a>')."\r\n".htmlentities('<a href="#" class="btn btn-default"><i class="icon-align-justify"></i></a>')."\r\n".htmlentities('</div>'); ?></pre>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <a href="#" class="btn btn-default">1</a>
                    <a href="#" class="btn btn-default">2</a>
                </div>
                <div class="btn-group">
                    <a href="#" class="btn btn-default">3</a>
                    <a href="#" class="btn btn-default">4</a>
                </div>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="btn-toolbar">')."\r\n".htmlentities('<div class="btn-group">')."\r\n".htmlentities('<a href="#" class="btn btn-default">1</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default">2</a>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('<div class="btn-group">')."\r\n".htmlentities('<a href="#" class="btn btn-default">3</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default">4</a>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>'); ?></pre>
            <div class="btn-group-vertical">    
                <a href="#" class="btn btn-default-alt">Button 1</a>
                <a href="#" class="btn btn-default-alt">Button 2</a>
                <a href="#" class="btn btn-default-alt">Button 3</a>
                <a href="#" class="btn btn-default-alt dropdown-toggle" data-toggle="dropdown">Dropdown <i class="icon-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Dropdown link</a></li>
                    <li><a href="#">Dropdown link</a></li>
                </ul>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="btn-group-vertical">')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Button 1</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Button 2</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Button 3</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt dropdown-toggle" data-toggle="dropdown">Dropdown <i class="icon-caret-down"></i></a>')."\r\n".htmlentities('<ul class="dropdown-menu">')."\r\n".htmlentities('<li><a href="#">Dropdown link</a></li>')."\r\n".htmlentities('<li><a href="#">Dropdown link</a></li>')."\r\n".htmlentities('</ul>')."\r\n".htmlentities('</div>'); ?></pre>
            <div class="btn-toolbar">
                <div class="btn-group-vertical">   
                    <a href="#" class="btn btn-default-alt">Page 1</a>
                    <a href="#" class="btn btn-default-alt">Page 2</a>
                    <a href="#" class="btn btn-default-alt">Page 3</a>
                    <a href="#" class="btn btn-default-alt">Page 4</a>
                </div>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="btn-toolbar">')."\r\n".htmlentities('<div class="btn-group-vertical"> ')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Page 1</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Page 2</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Page 3</a>')."\r\n".htmlentities('<a href="#" class="btn btn-default-alt">Page 4</a>')."\r\n".htmlentities('</div>')."\r\n".htmlentities('</div>'); ?></pre>
        </div>
        <div class="col-md-2">
            <ol class="list-unstyled">
                <li>btn-xs</li>
                <li>btn-sm</li>
                <li>btn-lg</li>
                <li>btn-default</li>
                <li>btn-default-alt</li>
                <li>btn-primary</li>
                <li>btn-primary-alt</li>
                <li>btn-success</li>
                <li>btn-success-alt</li>         
                <li>btn-info</li>  
                <li>btn-info-alt</li>  
                <li>btn-warning</li> 
                <li>btn-warning-alt</li> 
                <li>btn-danger</li>
                <li>btn-danger-alt</li>
                <li>btn-inverse</li>
                <li>btn-inverse-alt</li>
                <li>btn-brown</li>
                <li>btn-brown-alt</li>
                <li>btn-sky</li>
                <li>btn-sky-alt</li>
                <li>btn-midnightblue</li>         
                <li>btn-midnightblue-alt</li>  
                <li>btn-orange</li>  
                <li>btn-orange-alt</li> 
                <li>btn-green</li>
                <li>btn-green-alt</li>
                <li>btn-magenta</li>
                <li>btn-magenta-alt</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div id="carousel-example-captions" class="carousel slide bs-docs-carousel-example">
                <ol class="carousel-indicators">
                  <li data-target="#carousel-example-captions" data-slide-to="0" class=""></li>
                  <li data-target="#carousel-example-captions" data-slide-to="1" class=""></li>
                  <li data-target="#carousel-example-captions" data-slide-to="2" class="active"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="item">
                    <img src="<?php echo base_url(); ?>resources/image/no-avatar.png" alt="Demolition" />
                    <div class="carousel-caption">
                      <h3>First slide label</h3>
                      <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                    </div>
                  </div>
                  <div class="item">
                    <img src="<?php echo base_url(); ?>resources/image/noavatar2.png" alt="River" />
                    <div class="carousel-caption">
                      <h3>Second slide label</h3>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                  </div>
                  <div class="item active">
                    <img src="<?php echo base_url(); ?>resources/image/no_avatar_avalible.png" alt="Fountain" />
                    <div class="carousel-caption">
                      <h3>Third slide label</h3>
                      <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                    </div>
                  </div>
                </div>
                <a class="left carousel-control" href="#carousel-example-captions" data-slide="prev">
                  <span class="icon-prev"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-captions" data-slide="next">
                  <span class="icon-next"></span>
                </a>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities('')."\r\n".htmlentities(''); ?></pre> 
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
              <li><a href="#">Home</a></li>
              <li>Library</li>
              <li class="active">Data</li>
            </ol>
            <pre class="prettyprint linenums"><?php echo htmlentities('<ol class="breadcrumb">')."\r\n".htmlentities('<li><a href="#">Home</a></li>')."\r\n".htmlentities('<li>Library</li>')."\r\n".htmlentities('<li class="active">Data</li>')."\r\n".htmlentities('</ol>'); ?></pre>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>Notify Alert!</h3> 
            <pre class="prettyprint linenums"><?php echo htmlentities('<script type="text/javascript">')."\r\n".htmlentities('$(document).ready(function(){')."\r\n".htmlentities('$.pnotify({')."\r\n".htmlentities('title: "New Thing",')."\r\n".htmlentities('text: "Just to let you know, something happened.",')."\r\n".htmlentities('type: "info",')."\r\n".htmlentities('hide: false,//optional')."\r\n".htmlentities('width: "500px",//optional')."\r\n".htmlentities('min_height: "400px",//optional')."\r\n".htmlentities('history: false,//optional')."\r\n".htmlentities('sticker: false,//optional')."\r\n".htmlentities('closer_hover: false,//optional')."\r\n".htmlentities('sticker_hover: false,//optional')."\r\n".htmlentities('mouse_reset: false,//optional')."\r\n".htmlentities('opacity: 0.75,//optional')."\r\n".htmlentities('closer: false,//optional')."\r\n".htmlentities('icon: "icon-spin icon-refresh",//optional')."\r\n".htmlentities('shadow: false,//optional')."\r\n".htmlentities('before_close: function(pnotify) {}')."\r\n".htmlentities('});')."\r\n".htmlentities('});')."\r\n".htmlentities('</script>'); ?></pre>
            <ol class="list-unstyled">
                <li>error</li>
                <li>success</li>
                <li>info</li>
            </ol>
        </div>
        <div class="col-md-6">
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h3>Well done!</h3> 
                <p>Content Information.</p><hr />
                <p><a class="btn btn-success" href="#">Okay</a></p>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="alert alert-dismissable alert-success">')."\r\n".htmlentities('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>')."\r\n".htmlentities('<h3>Well done!</h3>')."\r\n".htmlentities('<p>Content Information.</p><hr />')."\r\n".htmlentities('<p><a class="btn btn-success" href="#">Okay</a></p>')."\r\n".htmlentities('</div>'); ?></pre>
            <div class="alert alert-dismissable alert-success">
                <strong>Well done!</strong> Example alert message.
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
            <pre class="prettyprint linenums"><?php echo htmlentities('<div class="alert alert-dismissable alert-success">')."\r\n".htmlentities('<strong>Well done!</strong> Example alert message.')."\r\n".htmlentities('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>')."\r\n".htmlentities('</div>'); ?></pre>
            <ol class="list-unstyled">
                <li>alert-info</li>
                <li>alert-success</li>
                <li>alert-warning</li>
                <li>alert-danger</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            
        </div>
        <div class="col-md-6">
            
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    
});
</script>