/*
 * File: app/model/DatosModel.js
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

Ext.define('cartera.model.DatosModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String',
        'Ext.data.field.Date'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_institucion_dato'
        },
        {
            type: 'int',
            name: 'id_institucion'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'rfc'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'razon_social'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'calle'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'numero_exterior'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'numero_interior'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'colonia'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'codigo_postal'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'ruta_logo'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'estatus'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'logo'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fecha_alta'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'usuario_alta'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fecha_modificacion'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'usuario_modificacion'
        }
    ]
});