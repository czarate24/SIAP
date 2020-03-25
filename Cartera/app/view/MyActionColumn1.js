/*
 * File: app/view/MyActionColumn1.js
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

Ext.define('cartera.view.MyActionColumn1', {
    extend: 'Ext.grid.column.Action',
    alias: 'widget.myactioncolumn11',

    align: 'center',
    dataIndex: 'logo',
    text: 'Estatus',

    items: [
        {
            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                console.log(record.data.estatus);
                var razon_social = record.data.razon_social;
                if(record.data.estatus === 1){
                    Ext.MessageBox.show({
                        title : 'Desactivar Institución',
                        buttons : Ext.MessageBox.YESNO,
                        buttonText:{
                            yes:'Sí',
                            no:'No',
                            cancel:'Cancelar'
                        },
                        msg : '¿Desea Desactivar a '+razon_social+'?',
                        icon : Ext.Msg.WARNING,
                        fn : function(btn)
                        {
                            if (btn == 'yes')
                            {
                                var values= {'estatus': 0};
                                record.set(values);
                                Ext.getStore('DatosStore').sync({
                                    callback: function(records, operation, success)
                                    {
                                        Ext.MessageBox.show({
                                            title : 'Desactivar Institución',
                                            buttons : Ext.MessageBox.OK,
                                            msg : 'Institución desactivada correctamente',
                                            icon : Ext.Msg.INFO
                                        });
                                    }
                                });
                                Ext.getStore('DatosStore').load();
                            }
                        }
                    });
                }
                else{
                    Ext.MessageBox.show({
                        title : 'Activar Institución',
                        buttons : Ext.MessageBox.YESNO,
                        buttonText:{
                            yes:'Sí',
                            no:'No',
                            cancel:'Cancelar'
                        },
                        msg : '¿Desea Activar a '+razon_social+'?',
                        icon : Ext.Msg.WARNING,
                        fn : function(btn)
                        {
                            if (btn == 'yes')
                            {
                                var values= {'estatus': 1};
                                record.set(values);
                                Ext.getStore('DatosStore').sync({
                                    callback: function(records, operation, success)
                                    {
                                        Ext.MessageBox.show({
                                            title : 'Activar Institución',
                                            buttons : Ext.MessageBox.OK,
                                            msg : 'Instutición Activada correctamente',
                                            icon : Ext.Msg.INFO
                                        });
                                    }
                                });

                                Ext.getStore('DatosStore').load();
                            }
                        }
                    });
                }
            }
        }
    ],

    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
        this.items[0].icon = record.get('estatus') == 1 ? "resources/images/ok2.png" : "resources/images/eliminar.png";
        return value;
    }

});