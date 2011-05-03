<style type="text/css">
    #out {
        padding: 5px;
        overflow:auto;
        border-width:0;
    }
    #out b {
        color:#555;
    }
    #out xmp {
        margin: 5px;
    }
    #out p {
        margin:0;
    }
</style>
<p>The js is not minified so it is readable. See <a href="<?php echo URL::base().$media_route->uri(array('file' => 'js/direct-grid-orm.js')); ?>">direct-grid-orm.js</a>.</p>
<p>To make use this example, ensure you have:<br />
&nbsp;&nbsp;&nbsp;&nbsp;<strong>*</strong> Created a "sampledata" database<br />
&nbsp;&nbsp;&nbsp;&nbsp;<strong>*</strong> Enabled the DB and ORM modules and setup your connection config<br />
&nbsp;&nbsp;&nbsp;&nbsp;<strong>*</strong> Optionally, install the supplied sampledata.sql<br />
</p>
<br />
<div id="listener-panel"></div>
<div id="user-grid"></div>