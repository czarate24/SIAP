/*
 * File: app/view/CarnetsInsTab.js
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

Ext.define('cartera.view.CarnetsInsTab', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.carnetsinstab',

    requires: [
        'cartera.view.InstitucionesTabViewModel11',
        'cartera.view.InstitucionesTabViewController11',
        'Ext.toolbar.Toolbar',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.grid.column.Number',
        'Ext.grid.column.Action'
    ],

    controller: 'karnetstab',
    viewModel: {
        type: 'karnetstab'
    },
    height: 630,
    id: 'CarnetsInsTab',
    itemId: 'CarnetsInsTab',
    width: 829,
    closable: true,
    title: 'Carnets Inscripción',
    defaultListenerScope: true,

    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    items: [
        {
            xtype: 'panel',
            flex: 1,
            id: 'PpalPanel2',
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'panel',
                    flex: 1,
                    flex: 1,
                    id: 'CarnetsPanel',
                    layout: 'fit',
                    title: '',
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'textfield',
                                    id: 'txtBuscarAlumno',
                                    width: 432,
                                    fieldLabel: 'Buscar alumno',
                                    name: 'txtBuscarAlumno',
                                    listeners: {
                                        change: {
                                            fn: 'onTxtBuscarAlumnoChange',
                                            scope: 'controller'
                                        },
                                        keypress: 'onTxtBuscarAlumnoKeypress'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnAgregarCarnets',
                                    itemId: 'btnAgregarCarnets',
                                    width: 45,
                                    icon: 'resources/images/agregar.png',
                                    iconAlign: 'top',
                                    scale: 'large',
                                    tooltip: 'Agregar Carnet',
                                    listeners: {
                                        click: {
                                            fn: 'onBtnAgregarCarnetsClick',
                                            scope: 'controller'
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    hidden: true,
                                    id: 'btnBorrarCarnets',
                                    itemId: 'btnBorrarCarnets',
                                    width: 45,
                                    icon: 'resources/images/eliminar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: {
                                            fn: 'onBtnBorrarCarnetsClick',
                                            scope: 'controller'
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    hidden: true,
                                    id: 'btnModificarCarnets',
                                    itemId: 'btnModificarCarnets',
                                    width: 45,
                                    icon: 'resources/images/modificar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: {
                                            fn: 'onBtnModificarCarnetsClick',
                                            scope: 'controller'
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnActualizarCarnets',
                                    itemId: 'btnActualizarCarnets',
                                    width: 45,
                                    icon: 'resources/images/actualizar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    tooltip: 'Actualizar',
                                    listeners: {
                                        click: {
                                            fn: 'onBtnActualizarCarnetsClick',
                                            scope: 'controller'
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnActualizarCorreo',
                                    itemId: 'btnActualizarCorreo',
                                    width: 45,
                                    icon: 'resources/images/correo.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    tooltip: 'Enviar Correo',
                                    listeners: {
                                        click: {
                                            fn: 'onBtnActualizarCoreoClick',
                                            scope: 'controller'
                                        }
                                    }
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            id: 'GridCarnetsIns',
                            store: 'CarnetsInsStore',
                            columns: [
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_alumno',
                                    menuDisabled: true,
                                    text: 'id_alumno',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_status_ciclo',
                                    menuDisabled: true,
                                    text: 'id_status_ciclo',
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
                                    dataIndex: 'id_carnet_inscripcion',
                                    menuDisabled: true,
                                    text: 'id_carnet_inscripcion',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_carnet_colegiatura',
                                    menuDisabled: true,
                                    text: 'id_carnet_colegiatura',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_familia',
                                    menuDisabled: true,
                                    text: 'id_familia',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_mes2',
                                    menuDisabled: true,
                                    text: 'id_institucion',
                                    format: '0'
                                },
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
                                    dataIndex: 'id_ciclo_escolar_colegiatura',
                                    menuDisabled: true,
                                    text: 'id_ciclo_escolar_colegiatura',
                                    format: '0'
                                },
                                {
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'id_status_alumno',
                                    menuDisabled: true,
                                    text: 'id_status_alumno',
                                    format: '0'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 116,
                                    dataIndex: 'matri',
                                    menuDisabled: true,
                                    text: 'Mat. Anterior'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    align: 'center',
                                    dataIndex: 'matricula',
                                    text: 'Matrícula'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    dataIndex: 'ap_paterno',
                                    text: 'Apellido Paterno'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    dataIndex: 'ap_materno',
                                    text: 'Apellido Materno'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    dataIndex: 'nombre',
                                    text: 'Nombre'
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
                                    width: 170,
                                    dataIndex: 'nivel_educativo',
                                    hideable: false,
                                    text: 'Nivel Educativo'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    align: 'center',
                                    dataIndex: 'grado',
                                    hideable: false,
                                    text: 'Grado'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    hidden: true,
                                    width: 170,
                                    dataIndex: 'id_status_alumno',
                                    text: 'Estatus'
                                },
                                {
                                    xtype: 'actioncolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        this.items[0].icon =  record.get('id_carnet_inscripcion') > 1 && record.get('id_ciclo_escolar') == Ext._id_ciclo_escolar ? "resources/images/ok2.png" : "resources/images/eliminar.png";
                                        return value;
                                    },
                                    handler: function(view, rowIndex, colIndex, item, e, record, row) {

                                        if(record.get('id_status_ciclo')===2) {
                                            Ext.MessageBox.show({
                                                title : 'Advertencia',
                                                buttons : Ext.MessageBox.OK,
                                                msg : 'No se puede imprimir carnets de ciclos cerrados.',
                                                icon : Ext.Msg.ERROR
                                            });

                                        }else{


                                            if(record.get('id_ciclo_escolar') == Ext._id_ciclo_escolar && record.get('id_carnet_inscripcion') > 1 ){

                                                var id_institucion = Ext._id_institucion;
                                                var id_alumno =record.data.id_alumno;
                                                var id_status_ciclo =record.data.id_status_ciclo;
                                                var id_ciclo_escolar=Ext._id_ciclo_escolar;
                                                var grado=record.data.grado;
                                                var id_nivel_educativo=record.data.id_nivel_educativo;


                                                new Ext.Window({
                                                    title : "CARNET DE INSCRIPCIÓN",
                                                    width : 990,
                                                    maximizable: true,
                                                    modal: true,
                                                    height: 700,
                                                    layout : 'fit',
                                                    items : [{
                                                        xtype : "component",
                                                        autoEl : {
                                                            tag : "iframe",
                                                            src : "pdf/pdf_CarnetInscripcion.php?id_institucion="+id_institucion+"&id_ciclo_escolar="+id_ciclo_escolar+"&id_alumno="+id_alumno+"&id_status_ciclo="+id_status_ciclo+"&grado="+grado+"&id_nivel_educativo="+id_nivel_educativo

                                                        }
                                                    }]
                                                }).show();
                                            }
                                        }

                                    },
                                    width: 150,
                                    align: 'center',
                                    dataIndex: 'id_carnet_colegiatura',
                                    text: 'Carnet Inscripción'
                                }
                            ],
                            listeners: {
                                afterrender: {
                                    fn: 'onGridcolumnAfterRender11',
                                    scope: 'controller'
                                }
                            }
                        }
                    ]
                }
            ]
        }
    ],

    onTxtBuscarAlumnoKeypress: function(textfield, e, eOpts) {
        var busqueda = Ext.getCmp('txtBuscarAlumno').getValue().toUpperCase();

        if(newValue === ''){

            Ext.getStore('CarnetsStore').clearFilter();

        }else {
            Ext.getStore('CarnetsStore').clearFilter();
            Ext.getStore('CarnetsStore').filter({
                filterFn: function(f) {
                     return f.get('ap_paterno').indexOf(busqueda) > -1;
                }
            });
        }
    }

});