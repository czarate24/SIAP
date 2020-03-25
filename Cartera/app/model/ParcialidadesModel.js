/*
 * File: app/model/ParcialidadesModel.js
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

Ext.define('cartera.model.ParcialidadesModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_alumno'
        },
        {
            type: 'int',
            name: 'id_institucion'
        },
        {
            type: 'int',
            name: 'matri'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'matricula'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'concepto'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'f_andes'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'total'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'nombre'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'ap_paterno'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'ap_materno'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'nivel'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'grado'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'grupo'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'referencia'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'importe'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'descuento'
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
            name: 'id_dato_facturacion'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'usoCFDI'
        }
    ]
});