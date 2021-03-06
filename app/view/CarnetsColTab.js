/*
 * File: app/view/CarnetsColTab.js
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

Ext.define('cartera.view.CarnetsColTab', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.carnetscoltab',

    requires: [
        'cartera.view.InstitucionesTabViewModel18',
        'cartera.view.InstitucionesTabViewController18',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.field.Text',
        'Ext.grid.Panel',
        'Ext.grid.column.Number',
        'Ext.grid.column.Action'
    ],

    controller: 'carnetscoltab',
    viewModel: {
        type: 'carnetscoltab'
    },
    height: 592,
    id: 'CarnetsColTab',
    itemId: 'CarnetsColTab',
    width: 1002,
    closable: true,
    title: 'Asignar Carnets Colegiatura',

    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    items: [
        {
            xtype: 'panel',
            flex: 1,
            layout: 'fit',
            items: [
                {
                    xtype: 'panel',
                    height: 500,
                    id: 'FamiliasACPanel1',
                    width: 2000,
                    layout: 'fit',
                    title: 'Alumnos',
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnActualizarFamiliasAC1',
                                    itemId: 'btnActualizarFamilias',
                                    width: 45,
                                    icon: 'resources/images/actualizar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnActualizarFamiliasACClick'
                                    }
                                },
                                {
                                    xtype: 'textfield',
                                    id: 'txtBusAlumno',
                                    width: 356,
                                    fieldLabel: 'Buscar Alumno',
                                    listeners: {
                                        change: 'onTextfieldChange'
                                    }
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            id: 'GridAlumnosCCol',
                            split: true,
                            store: 'CarnetsAlumnosStore',
                            columns: [
                                {
                                    xtype: 'gridcolumn',
                                    hidden: true,
                                    width: 120,
                                    dataIndex: 'id_nivel_educativo',
                                    text: 'id_nivel_educativo'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    hidden: true,
                                    width: 120,
                                    dataIndex: 'id_status_ciclo',
                                    text: 'id_status_ciclo'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 120,
                                    dataIndex: 'matricula',
                                    text: 'Matrícula'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'ap_paterno',
                                    text: 'Apellido Paterno'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'ap_materno',
                                    text: 'Apellido Materno'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'nombre',
                                    text: 'Nombre'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'nivel_educativo',
                                    text: 'Nivel Educativo'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 150,
                                    dataIndex: 'grado',
                                    text: 'Grado'
                                }
                            ],
                            listeners: {
                                afterrender: 'onGridcolumnAfterRender',
                                select: 'onGridFamiliasSelect'
                            }
                        }
                    ]
                }
            ]
        },
        {
            xtype: 'panel',
            flex: 1,
            id: 'PpalPanel5',
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'panel',
                    flex: 1,
                    id: 'AlumnosACPanel1',
                    layout: 'fit',
                    title: 'Carnets Colegiatura',
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnAgregarCarnetsCol',
                                    itemId: 'btnAgregarCarnetsCol',
                                    width: 45,
                                    icon: 'resources/images/agregar.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnAgregarCarnetsColClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 45,
                                    id: 'btnCorreoCol',
                                    itemId: 'btnCorreoCol',
                                    width: 45,
                                    icon: 'resources/images/correo.png',
                                    iconAlign: 'bottom',
                                    scale: 'large',
                                    listeners: {
                                        click: 'onBtnbtnCorreoColClick'
                                    }
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            id: 'GridCarnetsCol',
                            width: 1000,
                            store: 'CarnetsColStore',
                            columns: [
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
                                    dataIndex: 'id_institucion',
                                    menuDisabled: true,
                                    text: 'id_institucion',
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
                                    xtype: 'numbercolumn',
                                    hidden: true,
                                    dataIndex: 'matri',
                                    menuDisabled: true,
                                    text: 'Mat. Anterior',
                                    format: '0'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 131,
                                    dataIndex: 'referencia',
                                    text: 'Referencia'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 239,
                                    dataIndex: 'concepto',
                                    text: 'Concepto'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    dataIndex: 'fecha_vencimiento',
                                    text: 'Fecha Vencimiento'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 170,
                                    dataIndex: 'descripcion',
                                    text: 'Estatus'
                                },
                                {
                                    xtype: 'actioncolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        this.items[0].icon =  record.get('id_estatus_carnet') <=2 ? "resources/images/ok2.png" : "resources/images/eliminar.png";
                                        return value;
                                    },
                                    width: 140,
                                    dataIndex: 'nombre',
                                    text: 'Carnet',
                                    items: [
                                        {
                                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                                records = Ext.getCmp('GridAlumnosCCol').getSelectionModel().getSelection();

                                                if(records[0].data.id_status_ciclo===2) {
                                                    Ext.MessageBox.show({
                                                        title : 'Advertencia',
                                                        buttons : Ext.MessageBox.OK,
                                                        msg : 'No se puede imprimir carnets de ciclos cerrados.',
                                                        icon : Ext.Msg.ERROR
                                                    });

                                                }else{

                                                    var id_institucion = Ext._id_institucion;
                                                    var id_alumno =records[0].data.id_alumno;
                                                    var id_status_ciclo =records[0].data.id_status_ciclo;
                                                    var id_ciclo_escolar=Ext._id_ciclo_escolar;
                                                    var grado=records[0].data.grado;
                                                    var id_nivel_educativo=records[0].data.id_nivel_educativo;
                                                    var concepto=record.data.concepto;
                                                    var id_carnet_colegiatura=record.data.id_carnet_colegiatura;


                                                    new Ext.Window({
                                                        title : "CARNET DE COLEGIATURA",
                                                        width : 990,
                                                        maximizable: true,
                                                        modal: true,
                                                        height: 700,
                                                        layout : 'fit',
                                                        items : [{
                                                            xtype : "component",
                                                            autoEl : {
                                                                tag : "iframe",
                                                                src : "pdf/pdf_CarnetColegiatura.php?id_institucion="+id_institucion+"&id_ciclo_escolar="+id_ciclo_escolar+"&id_carnet_colegiatura="+id_carnet_colegiatura+"&concepto="+concepto+"&id_alumno="+id_alumno+"&id_status_ciclo="+id_status_ciclo+"&grado="+grado+"&id_nivel_educativo="+id_nivel_educativo

                                                            }
                                                        }]
                                                    }).show();
                                                }

                                            }
                                        }
                                    ]
                                }
                            ],
                            listeners: {
                                afterrender: 'onGridcolumnAfterRender11'
                            }
                        }
                    ]
                }
            ]
        }
    ]

});