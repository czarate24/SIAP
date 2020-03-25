/*
 * File: app/view/DatosFactWin.js
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

Ext.define('cartera.view.DatosFactWin', {
    extend: 'Ext.window.Window',
    alias: 'widget.datosfactwin',

    requires: [
        'cartera.view.InstitucionesWinViewModel8',
        'cartera.view.InstitucionesWinViewController8',
        'Ext.form.Panel',
        'Ext.form.field.Number',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Checkbox',
        'Ext.toolbar.Toolbar',
        'Ext.toolbar.Spacer',
        'Ext.button.Button'
    ],

    controller: 'datosfactwin',
    viewModel: {
        type: 'datosfactwin'
    },
    modal: true,
    autoShow: true,
    height: 551,
    id: 'DatosFactWin',
    itemId: 'DatosFactWin',
    resizable: false,
    width: 527,
    title: 'Agregar Datos Facturación',

    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    dockedItems: [
        {
            xtype: 'form',
            dock: 'top',
            height: 517,
            id: 'formDatosFact',
            itemId: 'formDatosFact',
            width: 881,
            bodyPadding: 10,
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'numberfield',
                    hidden: true,
                    id: 'id_dato_facturacion',
                    fieldLabel: 'id_dato_facturacion',
                    name: 'id_dato_facturacion',
                    hideTrigger: true
                },
                {
                    xtype: 'numberfield',
                    hidden: true,
                    id: 'id_familia',
                    fieldLabel: 'id_familia',
                    name: 'id_familia',
                    hideTrigger: true
                },
                {
                    xtype: 'textfield',
                    id: 'txtRfcDF',
                    fieldLabel: 'RFC',
                    name: 'rfc',
                    allowBlank: false,
                    validateBlank: true,
                    listeners: {
                        change: 'onTxtRfcDFChange',
                        blur: 'onTxtRfcDFBlur'
                    }
                },
                {
                    xtype: 'textfield',
                    id: 'txtRazonsocialDF1',
                    fieldLabel: 'Razón Social',
                    name: 'razon_social',
                    allowBlank: false,
                    validateBlank: true,
                    listeners: {
                        change: 'onAfiliacionBChange1'
                    }
                },
                {
                    xtype: 'combobox',
                    id: 'cmdUsocfdiDF',
                    fieldLabel: 'Uso CDFI:',
                    name: 'uso',
                    editable: false,
                    displayField: 'uso',
                    queryMode: 'local',
                    store: 'UsoCFDIDfStore',
                    valueField: 'uso',
                    listeners: {
                        change: 'onCmdUsocfdiDFChange'
                    }
                },
                {
                    xtype: 'textfield',
                    id: 'txtClaveCFDIDF',
                    width: 638,
                    fieldLabel: 'Clave CDFI:',
                    name: 'usoCFDI',
                    readOnly: false,
                    editable: false
                },
                {
                    xtype: 'textfield',
                    hidden: true,
                    id: 'txtTipoPersonaDF',
                    fieldLabel: 'Tipo persona:',
                    name: 'tipo_persona',
                    readOnly: false,
                    editable: false
                },
                {
                    xtype: 'textfield',
                    hidden: true,
                    id: 'predeterminado',
                    fieldLabel: 'predeterminado',
                    name: 'predeterminado',
                    readOnly: false,
                    editable: false
                },
                {
                    xtype: 'textfield',
                    id: 'txtCalle',
                    fieldLabel: 'Calle',
                    name: 'calle',
                    validateBlank: true,
                    listeners: {
                        change: 'onTxtCalleChange'
                    }
                },
                {
                    xtype: 'container',
                    width: 438,
                    layout: 'table',
                    items: [
                        {
                            xtype: 'textfield',
                            id: 'txtNExterior',
                            width: 271,
                            fieldLabel: 'N. Exterior',
                            name: 'numero_exterior',
                            validateBlank: true
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtNInterior',
                            width: 232,
                            fieldLabel: 'N. Interior',
                            labelAlign: 'right',
                            name: 'numero_interior',
                            validateBlank: true,
                            listeners: {
                                change: 'onTxtNInteriorChange'
                            }
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    id: 'txtColonia',
                    fieldLabel: 'Colonia',
                    name: 'colonia',
                    validateBlank: true,
                    listeners: {
                        change: 'onAfiliacionBChange2'
                    }
                },
                {
                    xtype: 'container',
                    width: 438,
                    layout: 'table',
                    items: [
                        {
                            xtype: 'textfield',
                            id: 'txtEstado',
                            width: 267,
                            fieldLabel: 'Estado',
                            name: 'estado',
                            validateBlank: true,
                            listeners: {
                                change: 'onTxtEstadoChange'
                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtMunicipio',
                            width: 234,
                            fieldLabel: 'Municipio',
                            labelAlign: 'right',
                            name: 'municipio',
                            validateBlank: true,
                            listeners: {
                                change: 'onTxtMunicipioChange'
                            }
                        }
                    ]
                },
                {
                    xtype: 'container',
                    width: 438,
                    layout: 'table',
                    items: [
                        {
                            xtype: 'textfield',
                            id: 'txtLocalidad',
                            width: 266,
                            fieldLabel: 'Localidad',
                            name: 'localidad',
                            validateBlank: true,
                            listeners: {
                                change: 'onTxtLocalidadChange'
                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtCPostal',
                            width: 235,
                            fieldLabel: 'C. Postal ',
                            labelAlign: 'right',
                            name: 'codigo_postal',
                            validateBlank: true
                        }
                    ]
                },
                {
                    xtype: 'container',
                    width: 438,
                    layout: 'table',
                    items: [
                        {
                            xtype: 'textfield',
                            id: 'txtPais',
                            width: 263,
                            fieldLabel: 'País',
                            name: 'pais',
                            validateBlank: true,
                            listeners: {
                                change: 'onTxtPaisChange'
                            }
                        },
                        {
                            xtype: 'checkboxfield',
                            id: 'chckPredeterminado',
                            fieldLabel: '  ',
                            boxLabel: 'Predeterminado    '
                        }
                    ]
                },
                {
                    xtype: 'combobox',
                    hidden: true,
                    id: 'cmbAplicaDF',
                    fieldLabel: 'Aplica para:',
                    name: 'aplica',
                    editable: false
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    height: 85,
                    width: 900,
                    items: [
                        {
                            xtype: 'tbspacer',
                            flex: 1,
                            dock: 'left'
                        },
                        {
                            xtype: 'button',
                            height: 45,
                            id: 'btnGuardarDF',
                            width: 100,
                            icon: 'resources/images/guardar.png',
                            iconAlign: 'top',
                            scale: 'large',
                            tooltip: 'Guardar',
                            listeners: {
                                click: 'onbtnGuardarDF'
                            }
                        },
                        {
                            xtype: 'button',
                            height: 45,
                            id: 'btnCancelarDF',
                            width: 100,
                            icon: 'resources/images/cancelar.png',
                            iconAlign: 'top',
                            scale: 'large',
                            tooltip: 'Cancelar',
                            listeners: {
                                click: 'onbtnCancelarDF'
                            }
                        }
                    ]
                }
            ]
        }
    ],
    listeners: {
        afterrender: 'onDatosFactWinAfterRender'
    }

});