/*
 * File: app/model/CobrosCarnetParcialidadModel.js
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

Ext.define('cartera.model.CobrosCarnetParcialidadModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String'
    ],

    fields: [
        {
            type: 'int',
            name: 'id_cuota_colegiatura'
        },
        {
            type: 'string',
            name: 'ciclo'
        },
        {
            type: 'int',
            name: 'cuota'
        },
        {
            type: 'int',
            name: 'recargos'
        },
        {
            type: 'int',
            name: 'id_descuento_inscripcion'
        },
        {
            type: 'int',
            name: 'descuento'
        }
    ]
});