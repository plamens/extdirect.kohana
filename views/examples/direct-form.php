<p>The js is not minified so it is readable. See <a href="<?php echo URL::base().$media_route->uri(array('file' => 'js/direct-form.js')); ?>">direct-form.js</a>.</p>
<p>The following example illustrates how to load a FormPanel or BasicForm through Ext.Direct.</p>
<p>Notice that Direct requests will batch together if they occur within the enableBuffer delay period (in milliseconds).</p>
<p>The Ext.Direct Form api also supports submit in addition to load. The server-side must mark the submit handler as a 'formHandler' and will not be batched.</p>