/*
 * File: app/view/CapturaCobrosWin.js
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

Ext.define('cartera.view.CapturaCobrosWin', {
    extend: 'Ext.window.Window',
    alias: 'widget.capturacobroswin',

    requires: [
        'cartera.view.InstitucionesWinViewModel18',
        'cartera.view.InstitucionesWinViewController18',
        'Ext.form.Panel',
        'Ext.form.FieldSet',
        'Ext.button.Button',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Number',
        'Ext.form.field.Checkbox',
        'Ext.toolbar.Toolbar',
        'Ext.toolbar.Spacer'
    ],

    controller: 'capturacobroswin',
    viewModel: {
        type: 'capturacobroswin'
    },
    modal: true,
    autoShow: true,
    height: 806,
    id: 'winCapturaCobros',
    itemId: 'winCapturaCobros',
    resizable: false,
    width: 918,
    title: 'Agregar Cobro',

    items: [
        {
            xtype: 'form',
            height: 800,
            id: 'CobrosForm',
            itemId: 'CobrosForm',
            width: 455,
            layout: 'form',
            bodyPadding: 10,
            dockedItems: [
                {
                    xtype: 'fieldset',
                    dock: 'top',
                    height: 228,
                    width: 449,
                    title: 'Alumno',
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [
                        {
                            xtype: 'container',
                            dock: 'top',
                            height: 46,
                            width: 435,
                            layout: 'column',
                            items: [
                                {
                                    xtype: 'textfield',
                                    dock: 'bottom',
                                    height: 35,
                                    id: 'txtMatriculaA',
                                    width: 369,
                                    fieldLabel: 'Matrícula',
                                    name: 'matricula',
                                    allowBlank: false,
                                    maxLengthText: '',
                                    validateBlank: true,
                                    listeners: {
                                        specialkey: {
                                            fn: 'onTxtMatriculaASpecialkey',
                                            element: ''
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    height: 36,
                                    id: 'btnGuardarCobro2',
                                    margin: '',
                                    width: 54,
                                    arrowAlign: 'bottom',
                                    icon: 'resources/images/search.png',
                                    iconAlign: 'top',
                                    scale: 'large',
                                    tooltip: 'Buscar Alumno',
                                    listeners: {
                                        click: 'onbtnGuardarCobro1'
                                    }
                                }
                            ]
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtAlumnoA',
                            fieldLabel: 'Alumno',
                            name: 'nombre',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtNivelA',
                            fieldLabel: 'Nivel',
                            name: 'nivel',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'container',
                            flex: 1,
                            layout: 'column',
                            items: [
                                {
                                    xtype: 'textfield',
                                    dock: 'left',
                                    id: 'txtGrupoA',
                                    width: 219,
                                    fieldLabel: 'Grupo',
                                    name: 'grupo',
                                    allowBlank: false,
                                    editable: false,
                                    maxLengthText: '',
                                    validateBlank: true
                                },
                                {
                                    xtype: 'textfield',
                                    dock: 'bottom',
                                    id: 'txtGradoA',
                                    width: 204,
                                    fieldLabel: 'Grado',
                                    labelAlign: 'right',
                                    name: 'grado',
                                    allowBlank: false,
                                    editable: false,
                                    maxLengthText: '',
                                    validateBlank: true
                                }
                            ]
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    dock: 'top',
                    height: 232,
                    width: 449,
                    layout: 'form',
                    title: 'Carnet',
                    items: [
                        {
                            xtype: 'combobox',
                            id: 'cmbTipoC',
                            width: 419,
                            fieldLabel: 'Tipo Carnet',
                            name: 'rfc',
                            allowBlank: false,
                            editable: false,
                            maxLength: 13,
                            maxLengthText: '',
                            validateBlank: true,
                            displayField: 'movimiento',
                            store: 'TiposMovimientosStore',
                            valueField: 'id_tipo_movimiento',
                            listeners: {
                                change: 'onCmbTipoCChange'
                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtReferenciaC',
                            width: 419,
                            fieldLabel: 'No. Referencia ',
                            name: 'rfc',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtConcepto',
                            fieldLabel: 'Concepto',
                            name: 'concepto',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtVencimiento',
                            fieldLabel: 'Vencimiento',
                            name: 'vencimiento',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    dock: 'top',
                    height: 205,
                    width: 449,
                    layout: 'form',
                    title: 'Datos Facturación',
                    items: [
                        {
                            xtype: 'combobox',
                            id: 'cmbRFCF',
                            fieldLabel: 'RFC',
                            name: 'rfc',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true,
                            displayField: 'rfc',
                            store: 'cmbRFCStore',
                            valueField: 'rfc',
                            listeners: {
                                change: 'onCmbRFCFChange'
                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtRazonSocialF',
                            fieldLabel: 'Razón Social',
                            name: 'razon_social',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtUsoCFDIF',
                            fieldLabel: 'Uso CFDI',
                            name: 'uso_cfdi',
                            allowBlank: false,
                            editable: false,
                            validateBlank: true
                        }
                    ]
                }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'form',
            dock: 'right',
            height: 688,
            id: 'CobroFForm',
            itemId: 'CobroFForm',
            width: 450,
            layout: 'form',
            bodyPadding: 10,
            dockedItems: [
                {
                    xtype: 'fieldset',
                    dock: 'bottom',
                    height: 685,
                    width: 404,
                    title: 'Datos del Cobro',
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [
                        {
                            xtype: 'combobox',
                            id: 'cmbFormaPagoCo',
                            fieldLabel: 'Forma de Pago',
                            name: 'id_forma_pago',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true,
                            displayField: 'forma_pago',
                            store: 'cmbFormasPago',
                            valueField: 'id_forma_pago'
                        },
                        {
                            xtype: 'numberfield',
                            id: 'txtCuentaCo',
                            fieldLabel: 'No. Cuenta',
                            name: 'cuenta',
                            fieldStyle: 'text-align: right',
                            hideTrigger: true,
                            allowDecimals: false
                        },
                        {
                            xtype: 'textfield',
                            alignTarget: 'right',
                            defaultAlign: 'r-r',
                            id: 'txtImporteCo',
                            style: '',
                            fieldLabel: 'Importe',
                            name: 'colonia',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            hideTrigger: true,
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            hidden: true,
                            id: 'id_institucion_cobro',
                            width: 419,
                            fieldLabel: 'id_institucion_cobro',
                            name: 'id_institucion_cobro',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            hidden: true,
                            id: 'txtIdDatoFac',
                            fieldLabel: 'id_dato_fac',
                            name: 'id_dato_facturacion',
                            editable: false,
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            hidden: true,
                            id: 'txtCarnet',
                            width: 419,
                            fieldLabel: 'id_carnet',
                            name: 'id_carnet',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            hidden: true,
                            id: 'id_facturacion_transporte',
                            width: 419,
                            fieldLabel: 'id_transporte',
                            name: 'id_facturacion_transporte',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            hidden: true,
                            id: 'id_descuento_inscripcionCobro',
                            width: 419,
                            fieldLabel: 'id_descuento_inscripcion',
                            name: 'id_descuento_inscripcion',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            hidden: true,
                            id: 'txtId_alumnoA',
                            fieldLabel: 'id_alumno',
                            name: 'id_alumno',
                            allowBlank: false,
                            editable: false,
                            maxLengthText: '',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtDescuentoBeca',
                            fieldLabel: ' Beca/Promoción',
                            name: 'beca',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            editable: false,
                            hideTrigger: true,
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtTransporteCo',
                            width: 105,
                            fieldLabel: '( + ) Transporte',
                            name: 'transporte',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            editable: false,
                            hideTrigger: true,
                            validateBlank: true
                        },
                        {
                            xtype: 'checkboxfield',
                            id: 'chckFactura',
                            fieldLabel: 'Facturar por separado',
                            boxLabel: ''
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtSubtotalCo',
                            fieldLabel: 'Subtotal',
                            name: 'subtotal',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            editable: false,
                            hideTrigger: true,
                            validateBlank: true
                        },
                        {
                            xtype: 'numberfield',
                            id: 'txtDescuentoCo',
                            style: '',
                            fieldLabel: '( - ) Descuento',
                            name: 'descuento',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            hideTrigger: true,
                            validateBlank: true,
                            listeners: {
                                specialkey: 'onTxtDescuentoCoSpecialkey',
                                focusleave: 'onTxtDescuentoCoFocusleave'
                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtRecargoCo',
                            style: '',
                            fieldLabel: '( + ) Recargos',
                            name: 'recargo',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            editable: false,
                            hideTrigger: true,
                            validateBlank: true
                        },
                        {
                            xtype: 'numberfield',
                            id: 'txtSaldoCo',
                            style: '',
                            fieldLabel: 'Importe',
                            name: 'importe',
                            fieldStyle: 'text-align: right',
                            allowBlank: false,
                            editable: false,
                            hideTrigger: true,
                            validateBlank: true,
                            minText: '',
                            minValue: 0
                        }
                    ]
                }
            ]
        },
        {
            xtype: 'toolbar',
            dock: 'bottom',
            height: 70,
            items: [
                {
                    xtype: 'tbspacer',
                    flex: 1,
                    dock: 'left'
                },
                {
                    xtype: 'button',
                    height: 45,
                    id: 'btnGuardarCobro',
                    width: 100,
                    icon: 'resources/images/guardar.png',
                    iconAlign: 'top',
                    scale: 'large',
                    tooltip: 'Guardar',
                    listeners: {
                        click: 'onbtnGuardarCobro'
                    }
                },
                {
                    xtype: 'button',
                    height: 45,
                    id: 'btnCancelarCobro',
                    width: 100,
                    icon: 'resources/images/cancelar.png',
                    iconAlign: 'top',
                    scale: 'large',
                    tooltip: 'Cancelar',
                    listeners: {
                        click: 'onbtnCancelarCobro'
                    }
                }
            ]
        }
    ],
    listeners: {
        afterrender: 'onWinGen1AfterRender'
    }

});