/*
 * File: app/view/CuotasInscripcionTab.js
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

Ext.define('cartera.view.CuotasInscripcionTab', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.cuotasinscripcionstab',

    requires: [
        'cartera.view.InstitucionesTabViewModel4',
        'cartera.view.InstitucionesTabViewController4',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.grid.column.Number',
        'Ext.grid.column.Action',
        'Ext.grid.column.Date'
    ],

    controller: 'cuotasinscripciontab',
    viewModel: {
        type: 'cuotasinscripciontab'
    },
    height: 630,
    id: 'CuotasInscripcionTab',
    itemId: 'CuotasInscripcionTab',
    width: 829,
    layout: 'fit',
    closable: true,
    title: 'Cuotas de Inscripción',

    items: [
        {
            xtype: 'panel',
            layout: {
                type: 'hbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'panel',
                    flex: 1,
                    id: 'CuotasPanel',
                    layout: 'fit',
                    title: 'Cuotas',
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnAgregarCuoIns',
                                    itemId: 'btnAgregarCiclo',
                                    width: 45,
                                    icon: 'resources/images/agregar.png',
                                    iconAlign: 'top',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnAgregarCuoInsClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnBorrarCuoIns',
                                    itemId: 'btnBorrarCiclo',
                                    width: 45,
                                    icon: 'resources/images/eliminar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnBorrarCuoInsClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnModificarCuoIns',
                                    itemId: 'btnModificarCiclo',
                                    width: 45,
                                    icon: 'resources/images/modificar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnModificarCuoInsClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnActualizarCuoIns',
                                    itemId: 'btnActualizarCuoIns',
                                    width: 45,
                                    icon: 'resources/images/actualizar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnActualizarCuoInsClick'
                                    }
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            id: 'GridCuoIns',
                            store: 'CuotasInscripcionStore',
                            columns: [
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_ciclo_escolar',
                                    menuDisabled: true,
                                    text: 'id_ciclo_escolar',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_cuota_inscripcion',
                                    menuDisabled: true,
                                    text: 'id_cuota_inscripcion',
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
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_nivel_educativo',
                                    menuDisabled: true,
                                    text: 'id_nivel_educativo',
                                    format: '0'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'descripcion',
                                    text: 'Descripción'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'grado',
                                    text: 'Grado'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'descrip_nivel',
                                    text: 'Nivel'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    width: 150,
                                    dataIndex: 'cuota',
                                    text: 'Cuota'
                                },
                                {
                                    xtype: 'actioncolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        this.items[0].icon ="resources/images/descuento.png";
                                        return value;
                                    },
                                    width: 150,
                                    align: 'center',
                                    dataIndex: 'estatus',
                                    text: 'Promociones',
                                    items: [
                                        {
                                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                                // console.log('HANDLER');
                                                view.select(record);
                                                records = Ext.getCmp('GridCuoIns').getSelectionModel().getSelection();
                                                if(records.length > 0){
                                                    var WinCuotasInscripcion = Ext.create('cartera.view.PromocionesInsWin'),
                                                        EditForm = WinCuotasInscripcion.down('form'),
                                                        record = records[0],
                                                        form = Ext.getCmp('formPromocionesIns');

                                                    WinCuotasInscripcion.setTitle('Agregar Promoción');
                                                    form.getForm().loadRecord(record);
                                                    //Ext.getCmp('institucionescmb').disabled=true;

                                                }else{
                                                    Ext.MessageBox.show({
                                                        title : 'Advertencia',
                                                        buttons : Ext.MessageBox.OK,
                                                        msg : 'Seleccione registro.',
                                                        icon : Ext.Msg.ERROR

                                                    });
                                                }
                                            }
                                        }
                                    ]
                                },
                                {
                                    xtype: 'actioncolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        this.items[0].icon = record.get('estatus') == 1 ? "resources/images/ok2.png" : "resources/images/eliminar.png";
                                        return value;
                                    },
                                    align: 'center',
                                    dataIndex: 'estatus',
                                    text: 'Estatus',
                                    items: [
                                        {
                                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                                console.log(record.data.estatus);
                                                var descripcion = record.data.descripcion;
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
                                                                Ext.getStore('CuotasInscripcionStore').sync({
                                                                    callback: function(records, operation, success)
                                                                    {
                                                                        Ext.MessageBox.show({
                                                                            title : 'Desactivar Cuota',
                                                                            buttons : Ext.MessageBox.OK,
                                                                            msg : 'Cuota desactivada correctamente',
                                                                            icon : Ext.Msg.INFO
                                                                        });
                                                                    }
                                                                });
                                                                Ext.getStore('CuotasInscripcionStore').load({
                                                                    params:{
                                                                        'id_ins': Ext._id_institucion,
                                                                        'id_ciclo_escolar': Ext._id_ciclo_escolar
                                                                    },
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                                else{
                                                    Ext.MessageBox.show({
                                                        title : 'Activar Cuota',
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
                                                                Ext.getStore('CuotasInscripcionStore').sync({
                                                                    callback: function(records, operation, success)
                                                                    {
                                                                        Ext.MessageBox.show({
                                                                            title : 'Activar Cuota',
                                                                            buttons : Ext.MessageBox.OK,
                                                                            msg : 'Cuota Activada correctamente',
                                                                            icon : Ext.Msg.INFO
                                                                        });
                                                                    }
                                                                });

                                                                Ext.getStore('CuotasInscripcionStore').load({
                                                                    params:{
                                                                        'id_ins': Ext._id_institucion,
                                                                        'id_ciclo_escolar': Ext._id_ciclo_escolar
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
                                afterrender: 'onGridcolumnAfterRender',
                                select: 'onGridCuoInsSelect'
                            }
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    flex: 1,
                    id: 'PromocionesPanel',
                    layout: 'fit',
                    title: 'Promociones de Inscripción',
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnAgregarPromo',
                                    itemId: 'btnAgregarPromo',
                                    width: 45,
                                    icon: 'resources/images/agregar.png',
                                    iconAlign: 'top',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnAgregarPromoClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnBorrarPromo',
                                    itemId: 'btnBorrarPromo',
                                    width: 45,
                                    icon: 'resources/images/eliminar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnBorrarPromoClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnModificarPromo',
                                    itemId: 'btnModificarPromo',
                                    width: 45,
                                    icon: 'resources/images/modificar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnModificarPromoClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnActualizarPromo',
                                    itemId: 'btnActualizarPromo',
                                    width: 45,
                                    icon: 'resources/images/actualizar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnActualizarPromoClick'
                                    }
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            id: 'GridPromos',
                            store: 'PromocionesInsStore',
                            columns: [
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_descuento_inscripcion',
                                    menuDisabled: true,
                                    text: 'id_descuento_inscripcion',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_cuota_inscripcion',
                                    menuDisabled: true,
                                    text: 'id_cuota_inscripcion',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_estatus_ingreso',
                                    menuDisabled: true,
                                    text: 'id_estatus_ingreso',
                                    format: '0'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    dataIndex: 'descripcion',
                                    text: 'Descripción'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    dataIndex: 'cuota',
                                    text: 'Cuota'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    dataIndex: 'descuento',
                                    text: '% Descuento'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    dataIndex: 'total',
                                    text: 'Total'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    text: 'Fecha',
                                    columns: [
                                        {
                                            xtype: 'datecolumn',
                                            width: 180,
                                            dataIndex: 'fecha_inicio',
                                            formatter: '',
                                            text: 'Inicial',
                                            format: 'F d, Y'
                                        },
                                        {
                                            xtype: 'datecolumn',
                                            width: 180,
                                            dataIndex: 'fecha_fin',
                                            text: 'Final',
                                            format: 'F d, Y'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'actioncolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        this.items[0].icon = record.get('estatus') == 1 ? "resources/images/ok2.png" : "resources/images/eliminar.png";
                                        return value;
                                    },
                                    align: 'center',
                                    dataIndex: 'estatus',
                                    text: 'Estatus',
                                    items: [
                                        {
                                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                                console.log(record.data.estatus);
                                                var descripcion = record.data.descripcion;
                                                if(record.data.estatus === 1){
                                                    Ext.MessageBox.show({
                                                        title : 'Desactivar Promoción',
                                                        buttons : Ext.MessageBox.YESNO,
                                                        buttonText:{
                                                            yes:'Sí',
                                                            no:'No',
                                                            cancel:'Cancelar'
                                                        },
                                                        msg : '¿Desea Desactivar Promoción?',
                                                        icon : Ext.Msg.WARNING,
                                                        fn : function(btn)
                                                        {
                                                            if (btn == 'yes')
                                                            {
                                                                var values= {'estatus': 0};
                                                                record.set(values);
                                                                Ext.getStore('PromocionesInsStore').sync({
                                                                    callback: function(records, operation, success)
                                                                    {
                                                                        Ext.MessageBox.show({
                                                                            title : 'Desactivar Promoción',
                                                                            buttons : Ext.MessageBox.OK,
                                                                            msg : 'Promoción desactivada correctamente',
                                                                            icon : Ext.Msg.INFO
                                                                        });
                                                                    }
                                                                });
                                                                Ext.getStore('PromocionesInsStore').load({
                                                                    params:{
                                                                        'id_ins': Ext._id_institucion,
                                                                        'id_ciclo_escolar': Ext._id_ciclo_escolar,
                                                                        'id_cuota_inscripcion': record.data.id_cuota_inscripcion
                                                                    },
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                                else{
                                                    Ext.MessageBox.show({
                                                        title : 'Activar Promoción',
                                                        buttons : Ext.MessageBox.YESNO,
                                                        buttonText:{
                                                            yes:'Sí',
                                                            no:'No',
                                                            cancel:'Cancelar'
                                                        },
                                                        msg : '¿Desea Activar a promoción?',
                                                        icon : Ext.Msg.WARNING,
                                                        fn : function(btn)
                                                        {
                                                            if (btn == 'yes')
                                                            {
                                                                var values= {'estatus': 1};
                                                                record.set(values);
                                                                Ext.getStore('PromocionesInsStore').sync({
                                                                    callback: function(records, operation, success)
                                                                    {
                                                                        Ext.MessageBox.show({
                                                                            title : 'Activar Promoción',
                                                                            buttons : Ext.MessageBox.OK,
                                                                            msg : 'Promoción Activada correctamente',
                                                                            icon : Ext.Msg.INFO
                                                                        });
                                                                    }
                                                                });

                                                                Ext.getStore('PromocionesInsStore').load({
                                                                    params:{
                                                                        'id_ins': Ext._id_institucion,
                                                                        'id_ciclo_escolar': Ext._id_ciclo_escolar,
                                                                        'id_cuota_inscripcion': record.data.id_cuota_inscripcion
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
                                afterrender: 'onGridcolumnAfterRender1'
                            }
                        }
                    ]
                }
            ]
        }
    ]

});