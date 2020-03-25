/*
 * File: app/model/MesesAnualidadModel.js
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

Ext.define('cartera.model.MesesAnualidadModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Number',
        'Ext.data.field.String'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_carnet_colegiatura'
        },
        {
            type: 'int',
            name: 'id_alumno'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'id_institucion'
        },
        {
            type: 'float',
            allowNull: true,
            name: 'id_ciclo_escolar'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'cuota'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'mes'
        }
    ]
});