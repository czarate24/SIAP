/*
 * File: app/model/PlazaModel.js
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

Ext.define('cartera.model.PlazaModel', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Integer',
        'Ext.data.field.String',
        'Ext.data.field.Date',
        'Ext.data.field.Boolean'
    ],

    fields: [
        {
            name: 'id'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'empresa_id'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'plaza'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'plaza2'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'ciudad'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'estado'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'direccion_sucursal'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'telefono_pedido'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'telefono_queja'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'telefono_queja2'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'permiso'
        },
        {
            type: 'string',
            allowNull: true,
            name: 'oficio'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'precio_gas'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'precio_aditivo'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'precio_aditivoc'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'factor_control'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'factor_space'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'litros_vale'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'clientes_estacionario'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'clientes_portatil'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'limite_descarga'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fecha_lista',
            dateFormat: 'Y-m-d'
        },
        {
            type: 'boolean',
            allowNull: true,
            name: 'otorga_puntos'
        },
        {
            type: 'boolean',
            allowNull: true,
            name: 'estatus'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fecha_operacion',
            dateFormat: 'Y-m-d'
        },
        {
            type: 'date',
            allowNull: true,
            name: 'fecha_planta',
            dateFormat: 'Y-m-d'
        },
        {
            type: 'int',
            allowNull: true,
            name: 'tarifa_id'
        }
    ]
});