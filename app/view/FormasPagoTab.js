/*
 * File: app/view/FormasPagoTab.js
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

Ext.define('cartera.view.FormasPagoTab', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.FormasPagoTab',

    requires: [
        'cartera.view.InstitucionesTabViewModel3',
        'cartera.view.InstitucionesTabViewController3',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.grid.column.Number',
        'Ext.grid.column.Action'
    ],

    controller: 'formaspagotab',
    viewModel: {
        type: 'formaspagotab'
    },
    height: 631,
    id: 'FormasPagoTab',
    itemId: 'FormasPagoTab',
    width: 829,
    layout: 'fit',
    closable: true,
    title: 'Formas de Pago',

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    height: 45,
                    id: 'btnAgregarFp',
                    itemId: 'btnAgregarFp',
                    width: 45,
                    icon: 'resources/images/agregar.png',
                    iconAlign: 'top',
                    scale: 'large',
                    listeners: {
                        click: 'onBtnAgregarFpClick'
                    }
                },
                {
                    xtype: 'button',
                    height: 45,
                    id: 'btnBorrarFp',
                    itemId: 'btnBorrarFp',
                    width: 45,
                    icon: 'resources/images/eliminar.png',
                    iconAlign: 'bottom',
                    scale: 'large',
                    listeners: {
                        click: 'onBtnBorrarFpClick'
                    }
                },
                {
                    xtype: 'button',
                    height: 45,
                    id: 'btnModificarFp',
                    itemId: 'btnModificarFp',
                    width: 45,
                    icon: 'resources/images/modificar.png',
                    iconAlign: 'bottom',
                    scale: 'large',
                    listeners: {
                        click: 'onBtnModificarFpClick'
                    }
                },
                {
                    xtype: 'button',
                    height: 45,
                    id: 'btnActualizarFp',
                    itemId: 'btnActualizarFp',
                    width: 45,
                    icon: 'resources/images/actualizar.png',
                    iconAlign: 'bottom',
                    scale: 'large',
                    listeners: {
                        click: 'onBtnActualizarFpClick'
                    }
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'gridpanel',
            id: 'GridFormasPago',
            store: 'FormasPagoStore',
            columns: [
                {
                    xtype: 'numbercolumn',
                    hidden: true,
                    itemId: 'id_forma_pago',
                    dataIndex: 'id_forma_pago',
                    menuDisabled: true,
                    text: 'Id',
                    format: '0'
                },
                {
                    xtype: 'numbercolumn',
                    hidden: true,
                    dataIndex: 'id_institucion',
                    menuDisabled: true,
                    text: 'id_institucion',
                    format: '0'
                },
                {
                    xtype: 'gridcolumn',
                    itemId: 'clave',
                    width: 100,
                    dataIndex: 'clave',
                    menuDisabled: true,
                    text: 'Clave'
                },
                {
                    xtype: 'gridcolumn',
                    itemId: 'forma_pago',
                    width: 250,
                    dataIndex: 'forma_pago',
                    menuDisabled: true,
                    text: 'Forma De Pago'
                },
                {
                    xtype: 'actioncolumn',
                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                        this.items[0].icon = record.get('estatus') == 1 ? "resources/images/ok2.png" : "resources/images/eliminar.png";
                        return value;
                    },
                    dataIndex: 'estatus',
                    text: 'Estatus',
                    items: [
                        {
                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                console.log(record.data.estatus);
                                var descripcion = record.data.forma_pago;
                                if(record.data.estatus === 1){
                                    Ext.MessageBox.show({
                                        title : 'Desactivar Cuota',
                                        buttons : Ext.MessageBox.YESNO,
                                        buttonText:{
                                            yes:'Sí',
                                            no:'No',
                                            cancel:'Cancelar'
                                        },
                                        msg : '¿Desea Desactivar a '+descripcion+'?',
                                        icon : Ext.Msg.WARNING,
                                        fn : function(btn)
                                        {
                                            if (btn == 'yes')
                                            {
                                                var values= {'estatus': 0};
                                                record.set(values);
                                                Ext.getStore('FormasPagoStore').sync({
                                                    callback: function(records, operation, success)
                                                    {
                                                        Ext.MessageBox.show({
                                                            title : 'Desactivar Forma de pago',
                                                            buttons : Ext.MessageBox.OK,
                                                            msg : 'Forma de pago desactivada correctamente',
                                                            icon : Ext.Msg.INFO
                                                        });
                                                    }
                                                });
                                                Ext.getStore('FormasPagoStore').load({
                                                    params:{
                                                        'id_ins': Ext._id_institucion

                                                    },
                                                });
                                            }
                                        }
                                    });
                                }
                                else{
                                    Ext.MessageBox.show({
                                        title : 'Activar Forma de pago',
                                        buttons : Ext.MessageBox.YESNO,
                                        buttonText:{
                                            yes:'Sí',
                                            no:'No',
                                            cancel:'Cancelar'
                                        },
                                        msg : '¿Desea Activar a '+descripcion+'?',
                                        icon : Ext.Msg.WARNING,
                                        fn : function(btn)
                                        {
                                            if (btn == 'yes')
                                            {
                                                var values= {'estatus': 1};
                                                record.set(values);
                                                Ext.getStore('FormasPagoStore').sync({
                                                    callback: function(records, operation, success)
                                                    {
                                                        Ext.MessageBox.show({
                                                            title : 'Activar Cuota',
                                                            buttons : Ext.MessageBox.OK,
                                                            msg : 'Forma de pago Activada correctamente',
                                                            icon : Ext.Msg.INFO
                                                        });
                                                    }
                                                });

                                                Ext.getStore('FormasPagoStore').load({
                                                    params:{
                                                        'id_ins': Ext._id_institucion

                                                    },
                                                });
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    ]
                }
            ],
            listeners: {
                afterrender: 'onGridcolumnAfterRender'
            }
        }
    ]

});