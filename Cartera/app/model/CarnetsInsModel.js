/*
 * File: app/model/CarnetsInsModel.js
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

Ext.define('cartera.model.CarnetsInsModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String',
        'Ext.data.field.Date'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_alumno'
        },
        {
            type: 'int',
            name: 'id_carnet_inscripcion'
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
            name: 'ciclo_escolar'
        },
        {
            type: 'int',
            name: 'id_institucion'
        },
        {
            type: 'int',
            name: 'id_tipo_movimiento'
        },
        {
            type: 'int',
            name: 'id_cuota_inscripcion'
        },
        {
            type: 'int',
            name: 'id_status_alumno'
        },
        {
            type: 'string',
            name: 'matri'
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
            type: 'int',
            name: 'id_nivel_educativo'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'matricula'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'referencia'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fecha'
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
        }
    ]
});