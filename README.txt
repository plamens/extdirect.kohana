Original Ext.Direct PHP library by Tommy Maintz
Based on Kohana 3 port by devolonter (Artur Bikmullin) http://github.com/devolonter/extdirect.kohana
This module aims to be non-bloated, fast and clear

Usage:

Plug to your application bootstrap.php like
    'extdirect'     => MODPATH.'extdirect'

Then API will be at http://yoursite.com/extdirect/api,
Router can be accessed via http://yoursite.com/extdirect/router
And examples (try & look at them first) - at http://yoursite.com/extdirect/examples
(don't forget to turn them off on production site)

To provide Ext.Direct access to your classes - place them in
    APPPATH/classes/extdirect/myclass
Definition should be like
    class ExtDirect_MyClass extends Controller_ExtDirect
Methods should be defined as
    public function direct_mymethod()
note that if your class name is CamelCased (or just contains uppercase letters, other than first) - you will need to
    return 'MyClass';
at the end of your file, to provide Javascript access in that the same CamelCased manner

