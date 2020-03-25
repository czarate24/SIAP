/*
 * File: app/model/LoginModel.js
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

Ext.define('cartera.model.LoginModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_usuario'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'nombre_completo'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'usuario'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'tipo'
        }
    ]
});