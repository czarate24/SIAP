/*
 * File: app/view/ModAsigCamionWin.js
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

Ext.define('cartera.view.ModAsigCamionWin', {
    extend: 'Ext.window.Window',
    alias: 'widget.modasigcamionwin',

    requires: [
        'cartera.view.InstitucionesWinViewModel11',
        'cartera.view.InstitucionesWinViewController11',
        'Ext.form.Panel',
        'Ext.form.field.Number',
        'Ext.form.field.ComboBox',
        'Ext.toolbar.Toolbar',
        'Ext.toolbar.Spacer',
        'Ext.button.Button'
    ],

    controller: 'modasigcamionwin',
    viewModel: {
        type: 'modasigcamionwin'
    },
    modal: true,
    autoShow: true,
    height: 368,
    id: 'ModAsigCamionWin',
    itemId: 'ModAsigCamionWin',
    resizable: false,
    width: 463,
    title: 'Modificar Camión',

    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    dockedItems: [
        {
            xtype: 'form',
            dock: 'top',
            height: 320,
            id: 'formAsigCamion1',
            itemId: 'formAsigCamion',
            width: 459,
            layout: 'form',
            bodyPadding: 10,
            items: [
                {
                    xtype: 'panel',
                    width: 416,
                    items: [
                        {
                            xtype: 'numberfield',
                            hidden: true,
                            id: 'id_institucionAC1',
                            fieldLabel: 'id_institucion',
                            name: 'id_institucion',
                            hideTrigger: true
                        },
                        {
                            xtype: 'numberfield',
                            hidden: true,
                            id: 'id_alumnoAC1',
                            fieldLabel: 'id_alumno',
                            name: 'id_alumno',
                            hideTrigger: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtNombreAC1',
                            width: 402,
                            fieldLabel: 'Nombre',
                            name: 'nombre',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtApellidopAC1',
                            width: 402,
                            fieldLabel: 'Apellido Paterno',
                            name: 'ap_paterno',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtApellidomAC1',
                            width: 401,
                            fieldLabel: 'Apellido Materno',
                            name: 'ap_materno',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true
                        },
                        {
                            xtype: 'combobox',
                            id: 'cmbTransporteAC1',
                            width: 402,
                            fieldLabel: 'Transporte',
                            name: 'descripcion',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true,
                            displayField: 'descripcion',
                            store: 'cmbTransporteStore',
                            valueField: 'descripcion',
                            listeners: {
                                change: 'onTransporteACChange'
                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtCuotaAC1',
                            width: 401,
                            fieldLabel: 'Cuota',
                            name: 'cuota',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true
                        },
                        {
                            xtype: 'numberfield',
                            hidden: true,
                            id: 'id_cuota_transporteAC1',
                            fieldLabel: 'id_cuota_transporte',
                            name: 'id_cuota_transporte',
                            hideTrigger: true
                        }
                    ]
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    height: 70,
                    width: 459,
                    items: [
                        {
                            xtype: 'tbspacer',
                            flex: 1,
                            dock: 'left'
                        },
                        {
                            xtype: 'button',
                            height: 45,
                            id: 'btnGuardarB2',
                            width: 100,
                            icon: 'resources/images/guardar.png',
                            iconAlign: 'top',
                            scale: 'large',
                            tooltip: 'Guardar',
                            listeners: {
                                click: 'onbtnGuardarB'
                            }
                        },
                        {
                            xtype: 'button',
                            height: 45,
                            id: 'btnCancelarB2',
                            width: 100,
                            icon: 'resources/images/cancelar.png',
                            iconAlign: 'top',
                            scale: 'large',
                            tooltip: 'Cancelar',
                            listeners: {
                                click: 'onbtnCancelarB'
                            }
                        }
                    ]
                }
            ]
        }
    ],
    listeners: {
        afterrender: 'onFormasPagoWinAfterRender'
    }

});