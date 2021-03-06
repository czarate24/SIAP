/*
 * File: app/view/EditarPlazaWindow.js
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

Ext.define('cartera.view.EditarPlazaWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.editarplazawindow',

    requires: [
        'cartera.view.EditarPlazaWindowViewModel',
        'cartera.view.EditarPlazaWindowViewController',
        'Ext.form.Panel',
        'Ext.form.field.Number',
        'Ext.form.field.ComboBox',
        'Ext.form.CheckboxGroup',
        'Ext.form.field.Checkbox',
        'Ext.button.Button'
    ],

    controller: 'editarplazawindow',
    viewModel: {
        type: 'editarplazawindow'
    },
    modal: true,
    height: 600,
    itemId: 'EditarPlazaWindow',
    width: 500,
    title: 'Editar Plaza',

    items: [
        {
            xtype: 'form',
            itemId: 'formPlaza',
            bodyPadding: 10,
            items: [
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Clave:',
                    name: 'plaza',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Plaza:',
                    name: 'ciudad',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Direccion:',
                    name: 'direccion_sucursal',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Permiso:',
                    name: 'permiso',
                    allowBlank: false
                },
                {
                    xtype: 'numberfield',
                    anchor: '100%',
                    fieldLabel: 'Total Clientes:',
                    name: 'clientes_estacionario',
                    allowBlank: false,
                    hideTrigger: true,
                    minValue: 0
                },
                {
                    xtype: 'combobox',
                    anchor: '100%',
                    fieldLabel: 'Empresa:',
                    name: 'empresa_id',
                    allowBlank: false,
                    editable: false,
                    forceSelection: true,
                    store: [
                        [
                            1,
                            'GASPASA'
                        ],
                        [
                            2,
                            'DIESGAS'
                        ]
                    ]
                },
                {
                    xtype: 'checkboxgroup',
                    items: [
                        {
                            xtype: 'checkboxfield',
                            name: 'otorga_puntos',
                            boxLabel: 'Aplica SUMA PUNTOS',
                            inputValue: 'true',
                            uncheckedValue: 'false'
                        }
                    ]
                },
                {
                    xtype: 'button',
                    text: 'GUARDAR:',
                    listeners: {
                        click: 'onbtnGuardar'
                    }
                },
                {
                    xtype: 'button',
                    text: 'CANCELAR',
                    listeners: {
                        click: 'onbtnCancelar'
                    }
                }
            ]
        }
    ]

});