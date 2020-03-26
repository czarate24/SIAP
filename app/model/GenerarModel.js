/*
 * File: app/model/GenerarModel.js
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

Ext.define('cartera.model.GenerarModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.String',
        'Ext.data.field.Number',
        'Ext.data.field.Date'
    ],

    fields: [
        {
            name: 'id'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'grupo'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'gpo_descrip'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'descrip'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'cod_barr'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'marca'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'existe'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'costo'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'pvta'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fec_alta'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'sta'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'exi_fis'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'cto_fis'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'f_cant'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'f_costo'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'cont_fis'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'codigob'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'consec'
        }
    ]
});