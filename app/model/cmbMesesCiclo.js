/*
 * File: app/model/cmbMesesCiclo.js
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

Ext.define('cartera.model.cmbMesesCiclo', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String'
    ],

    fields: [
        {
            type: 'int',
            allowNull: true,
            name: 'id_mes'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'orden'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'mes'
        }
    ]
});