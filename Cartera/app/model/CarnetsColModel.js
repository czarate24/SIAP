/*
 * File: app/model/CarnetsColModel.js
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

Ext.define('cartera.model.CarnetsColModel', {
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
            name: 'id_carnet_colegiatura'
        },
        {
            type: 'int',
            name: 'id_ciclo_escolar'
        },
        {
            type: 'int',
            name: 'id_status_ciclo'
        },
        {
            type: 'int',
            name: 'id_institucion'
        },
        {
            type: 'int',
            name: 'id_mes1'
        },
        {
            type: 'int',
            name: 'id_mes2'
        },
        {
            type: 'int',
            name: 'id_cuota_colegiatura'
        },
        {
            type: 'int',
            name: 'id_status_alumno'
        },
        {
            type: 'int',
            name: 'grupo'
        },
        {
            type: 'string',
            name: 'nivel_educativo'
        },
        {
            type: 'string',
            name: 'concepto'
        },
        {
            type: 'string',
            name: 'descripcion'
        },
        {
            type: 'int',
            name: 'id_nivel_educativo'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'referencia'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'fecha_vencimiento'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'id_edtatus_carnet'
        }
    ]
});