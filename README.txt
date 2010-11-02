This Kohana 3 module is a port of the Ext.Direct PHP - Maintained by tommymaintz (Tommy Maintz)

Built on Kohana v3.0.5

1. Extract the "extdirect" folder into your modules folder
2. Enable the module in your bootstrap
example: 'extdirect' => MODPATH.'extdirect'

The api JavaScript src will now become http://yourkohanasite/extdirect/api 
example: <script type="text/javascript" src="http://yourkohanasite/extdirect/api"></script>

The router can be accessed via http://yourkohanasite/extdirect/router

I have also ported over and included the Ext.Direct examples that come with 
ExtJS so you can see it working right away on your setup, to access them 
visit http://yourkohanasite/extdirect/examples

Settings are in the extdirect/config/extdirect.php file, override in your 
applications config folder

-----------------------------------------------------------------------------
CHANGELOG
-----------------------------------------------------------------------------
[10 June 2010]
-----------------------------------------------------------------------------
* Removed restful: true flag from DirectStore, not required
* Store only reloads on create/destroy actions now to update PagingToolbar
* enable_examples now unsets the UserActions example class as well when set
to FALSE
-----------------------------------------------------------------------------
[9 June 2010]
-----------------------------------------------------------------------------
* Added New example (Ext.Direct DataGrid, DirectStore, RowEditor with Kohana ORM)
* Fixed camelcase config variable names with _
* Merged the extdirect and api configs into one (Update existing setups if 
  overwriting a previous version)
* Added enable_examples flag so you can turn off examples in production sites
  Setting it to FALSE will disable the routes and unset the first 2 api
  config variables so that the example classes are not included in your api 
  output when overriding the config in your application.