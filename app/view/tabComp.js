/*
 * File: app/view/tabComp.js
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

Ext.define('cartera.view.tabComp', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.tabComp',

    requires: [
        'cartera.view.tabArtLisViewModel2',
        'cartera.view.tabArtLisViewController2',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.RadioGroup',
        'Ext.form.field.Radio',
        'Ext.grid.Panel',
        'Ext.grid.column.Number'
    ],

    controller: 'tabcomp',
    viewModel: {
        type: 'tabcomp'
    },
    height: 630,
    id: 'tabComp',
    itemId: 'tabComp',
    width: 829,
    layout: 'fit',
    closable: true,
    title: 'Comparativa de Inventario Fsico Vs Kardex',

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    height: 50,
                    id: 'btnImpComp',
                    itemId: 'btnImpComp',
                    width: 50,
                    icon: 'resources/images/pdf.png',
                    iconAlign: 'top',
                    scale: 'large',
                    listeners: {
                        click: 'onBtnImpCompClick'
                    }
                },
                {
                    xtype: 'button',
                    height: 50,
                    id: 'btnExcel2',
                    itemId: 'btnExcel',
                    width: 50,
                    icon: 'resources/images/excel.png',
                    iconAlign: 'bottom',
                    scale: 'large',
                    listeners: {
                        click: 'onBtnExcel2Click'
                    }
                },
                {
                    xtype: 'radiogroup',
                    width: 400,
                    fieldLabel: 'Imprimir',
                    items: [
                        {
                            xtype: 'radiofield',
                            id: 'rdGlobal',
                            boxLabel: 'Global',
                            checked: true,
                            listeners: {
                                change: 'onRdGlobalChange'
                            }
                        },
                        {
                            xtype: 'radiofield',
                            id: 'rdDif',
                            boxLabel: 'Solo Diferencias',
                            listeners: {
                                change: 'onRdDifChange'
                            }
                        }
                    ]
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'gridpanel',
            id: 'GridArtComp',
            store: 'ComparativaStore',
            columns: [
                {
                    xtype: 'numbercolumn',
                    hidden: true,
                    dataIndex: 'id_articulo',
                    menuDisabled: true,
                    text: 'id_articulo',
                    format: '0'
                },
                {
                    xtype: 'numbercolumn',
                    dataIndex: 'consec',
                    menuDisabled: true,
                    text: 'Consec',
                    format: '000000'
                },
                {
                    xtype: 'gridcolumn',
                    width: 200,
                    dataIndex: 'gpo_descrip',
                    menuDisabled: true,
                    text: 'Grupo'
                },
                {
                    xtype: 'gridcolumn',
                    width: 170,
                    dataIndex: 'codigob',
                    menuDisabled: true,
                    text: 'Cod. Barra'
                },
                {
                    xtype: 'gridcolumn',
                    width: 300,
                    dataIndex: 'descrip',
                    menuDisabled: true,
                    text: 'Artículo'
                },
                {
                    xtype: 'gridcolumn',
                    menuDisabled: true,
                    text: 'Kardex',
                    columns: [
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'existe',
                            menuDisabled: true,
                            text: 'Existencia'
                        },
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'costo',
                            menuDisabled: true,
                            text: 'Costo'
                        }
                    ]
                },
                {
                    xtype: 'gridcolumn',
                    menuDisabled: true,
                    text: 'Fisico',
                    columns: [
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'exi_fis',
                            menuDisabled: true,
                            text: 'Existencia'
                        },
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'cto_fis',
                            menuDisabled: true,
                            text: 'Costo'
                        }
                    ]
                },
                {
                    xtype: 'gridcolumn',
                    menuDisabled: true,
                    text: 'Faltante',
                    columns: [
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'faltante_exi',
                            menuDisabled: true,
                            text: 'Existencia'
                        },
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'faltante_cto',
                            menuDisabled: true,
                            text: 'Costo'
                        }
                    ]
                },
                {
                    xtype: 'gridcolumn',
                    menuDisabled: true,
                    text: 'Sobrante',
                    columns: [
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'sobrante_exi',
                            menuDisabled: true,
                            text: 'Existencia'
                        },
                        {
                            xtype: 'numbercolumn',
                            align: 'end',
                            dataIndex: 'sobrante_cto',
                            menuDisabled: true,
                            text: 'Costo'
                        }
                    ]
                }
            ],
            listeners: {
                afterrender: 'onGridcolumnAfterRender'
            }
        }
    ],
    listeners: {
        afterrender: 'onTabCompAfterRender'
    }

});