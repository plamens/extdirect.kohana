/**
 * Ext.Direct DataGrid, DirectStore, RowEditor with Kohana ORM Example
 * 
 * @author     Fady Khalife
 */

// Add our api
Ext.Direct.addProvider(Ext.app.REMOTING_API);

// Record object for use with the JsonStore and when the RowEditor adds a new record
var userRecord =  Ext.data.Record.create([{
    name: 'id'
}, {
    name: 'email',
    type: 'string',
    allowBlank: false
}, {
    name: 'first',
    type: 'string',
    allowBlank: false
},{
    name: 'last',
    type: 'string',
    allowBlank: false
}]);

// The grid panel
UserGridUi = Ext.extend(Ext.grid.GridPanel, {
    title: 'Users',
    height: 350,
    width: 500,
    frame: true,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true 
    }),
    initComponent: function() {
        this.columns = [
            {
                xtype: 'gridcolumn',
                header: 'ID',
                sortable: true,
                resizable: true,
                width: 40,
                dataIndex: 'id'
            },
            {
                xtype: 'gridcolumn',
                header: 'Email',
                sortable: true,
                resizable: true,
                width: 160,
                dataIndex: 'email',
                editor: {
                    xtype: 'textfield',
                    allowBlank: false,
                    vtype: 'email'
                }
            },
            {
                xtype: 'gridcolumn',
                header: 'First',
                sortable: true,
                resizable: true,
                width: 125,
                dataIndex: 'first',
                editor: {
                    xtype: 'textfield',
                    allowBlank: false
                }
            },
            {
                xtype: 'gridcolumn',
                header: 'Last',
                sortable: true,
                resizable: true,
                width: 125,
                dataIndex: 'last',
                editor: {
                    xtype: 'textfield',
                    allowBlank: false
                }
            }
        ];
        this.tbar = {
            xtype: 'toolbar',
            id: 'UserToolbar',
            items: [
                {
                    xtype: 'button',
                    text: 'Add User',
                    id: 'add-btn',
                    iconCls: 'silk-add'
                },
                {
                    xtype: 'button',
                    text: 'Delete User',
                    id: 'delete-btn',
                    iconCls: 'silk-delete'
                }
            ]
        };
        this.bbar = new Ext.PagingToolbar({
            id: 'paging-tb',
            pageSize: 10,
            store: this.store,
            displayInfo: true,
            displayMsg: 'Displaying Users {0} - {1} of {2}',
            emptyMsg: "No users to display",
        })
        UserGridUi.superclass.initComponent.call(this);
    }
});
UserGrid = Ext.extend(UserGridUi, {
    initComponent: function() {
        UserGrid.superclass.initComponent.call(this);
    }
});

// Reader
UserReader = Ext.extend(Ext.data.JsonReader, {
    constructor: function(cfg) {
        cfg = cfg || {};
        UserReader.superclass.constructor.call(this, Ext.apply({
            idProperty: 'id',
            root: 'data',
            totalProperty: 'total',
            successProperty: 'success',
            messageProperty: 'message',
            fields: userRecord
        }, cfg));
    }
});

// Writer
UserWriter = Ext.extend(Ext.data.JsonWriter, {
    encode: false
});

// We will use the DirectStore with the 'api' paramater
UserStore = Ext.extend(Ext.data.DirectStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        UserStore.superclass.constructor.call(this, Ext.apply({
            remoteSort: true,
            paramHash: false,
            reader: new UserReader(),
            writer: new UserWriter(),
            api: {
                read: UserAction.get,
                create: UserAction.add,
                update: UserAction.update,
                destroy: UserAction.delete
            },
            sortInfo: {
                field: 'first',
                direction: 'ASC'
            },
            paramOrder: ['start', 'limit', 'sort', 'dir'],
			baseParams: {
				start: 0,
				limit: 10,
                sort: 'first',
                dir: 'ASC'
			}
        }, cfg));
    }
});

// Init
Ext.onReady(function() {
    Ext.QuickTips.init();

    // use RowEditor for editing
    var editor = new Ext.ux.grid.RowEditor({
        saveText: 'Update',
        errorSummary: false
    });
    
    // Create the grid
    var userGrid = new UserGrid({
        renderTo: 'user-grid',
        plugins: [editor],
        store: new UserStore({
            listeners: {
                load: function() {
                    sm.selectFirstRow();
                }
            }
        })
    });
    
    // Load the data
    userGrid.store.load();
    
    userGrid.store.on({
       'write': function(proxy, action, result, res, rs) {
            if(action != 'update' || action == 'destroy') {
                userGrid.store.reload(); // reload to update paging for create/destroy
            }
       } 
    });
    
    var sm = userGrid.getSelectionModel();
    var tb = userGrid.getTopToolbar();
    
    // Add User toolbar button action
    tb.findById('add-btn').on({
        click: function(btn, e) {
            //btn.disable();
            var u = new userRecord({
                email: '',
                first: '',
                last: ''
            });
            editor.stopEditing();
            userGrid.store.insert(0, u);
            sm.selectRow(0);
            editor.startEditing(0);
        }
    });
    
    // Delete User toolbar button action
    tb.findById('delete-btn').on({
        click: function(btn, e) {
            editor.stopEditing();
            if(sm.hasSelection()) {
                var row = sm.getSelected();
                var rowIndex = row.store.indexOf(row);
                var r = userGrid.store.getAt(rowIndex);
                userGrid.store.remove(r);  
            }                  
        }
    });
    
    // If a user cancels a new recore, we will remove it so there are no blank rows
    editor.on({
        'canceledit': function(editor) {
            var r = userGrid.store.getAt(0);
            if(r.phantom) {
                userGrid.store.remove(r);
                sm.selectFirstRow();
            }
        }
    });
    
    // Create panel to show logging information
    var out = new Ext.form.DisplayField({
        cls: 'x-form-text',
        id: 'out'
    });
    var p = new Ext.Panel({
        renderTo: 'listener-panel',
        title: 'DataProxy Events',
        frame:true,
		width: 500,
		height: 130,
		layout:'fit',
		items:[out]
	});
     
    // Listen to all DataProxy beforewrite events
    Ext.data.DataProxy.addListener('beforewrite', function(proxy, action) {
        out.append('<p>Before ' + action + '</p>');
        out.el.scroll('b', 100000, true);
    });
    // All write events
    Ext.data.DataProxy.addListener('write', function(proxy, action, result, res, rs) {
        out.append('<p>' + action + ' : ' + res.result.message + '</p>');
        out.el.scroll('b', 100000, true);
    });
    // All exception events
    Ext.data.DataProxy.addListener('exception', function(proxy, type, action, options, res) {
         out.append('<p>' + action + ' : ' + res.message + '</p>');
         out.el.scroll('b', 100000, true);
    });

});