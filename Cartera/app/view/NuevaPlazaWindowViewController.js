/*
 * File: app/view/NuevaPlazaWindowViewController.js
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

Ext.define('cartera.view.NuevaPlazaWindowViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.nuevaplazawindow',

    onbtnGuardar: function(button, e, eOpts) {
        //console.log("HOLIS");
        var ctrl = this,
            nuevaPlazaForm = ctrl.view.down("#formPlaza").getForm(),
            plazasPanel = Ext.getCmp("plazasPanel"),
            plazasStore = Ext.getStore("PlazasStore"),
            record;

        if (nuevaPlazaForm.isValid()) {
            record = new MiProyecto.model.PlazaModel(
            nuevaPlazaForm.getFieldValues()
            );
            plazasStore.add(record);
            plazasStore.sync();
            ctrl.view.destroy();
        }

    },

    onbtnCancelar: function(button, e, eOpts) {

    }

});
