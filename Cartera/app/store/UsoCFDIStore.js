/*
 * File: app/store/UsoCFDIStore.js
 *
 * This file was generated by Sencha Architect
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.5.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.5.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('cartera.store.UsoCFDIStore', {
    extend: 'Ext.data.Store',

    requires: [
        'cartera.model.UsoCFDIModel',
        'Ext.data.proxy.Ajax',
        'Ext.data.writer.Json',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'UsoCFDIStore',
            model: 'cartera.model.UsoCFDIModel',
            proxy: {
                type: 'ajax',
                api: {
                    read: 'api/usocfdi/read',
                    create: 'api/usocfdi/create',
                    update: 'api/usocfdi/update',
                    destroy: 'api/usocfdi/delete'
                },
                writer: {
                    type: 'json',
                    writeAllFields: true,
                    allowSingle: false,
                    encode: true,
                    rootProperty: 'records'
                },
                reader: {
                    type: 'json',
                    messageProperty: 'message',
                    rootProperty: 'records',
                    metaProperty: 'metadata'
                }
            }
        }, cfg)]);
    }
});