/*
 * File: app/model/MostrarParcialidadesModel.js
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

Ext.define('cartera.model.MostrarParcialidadesModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_ciclo_escolar'
        },
        {
            type: 'int',
            name: 'id_contrato'
        },
        {
            type: 'int',
            name: 'id_institucion'
        },
        {
            type: 'string',
            name: 'referencia'
        },
        {
            type: 'string',
            name: 'concepto'
        },
        {
            type: 'string',
            name: 'nombre'
        },
        {
            type: 'string',
            name: 'grado'
        },
        {
            type: 'string',
            name: 'nivel'
        },
        {
            type: 'string',
            name: 'importe'
        },
        {
            type: 'string',
            name: 'numero_parcialidades'
        },
        {
            type: 'int',
            name: 'estatus'
        }
    ]
});