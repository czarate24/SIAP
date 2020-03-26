/*
 * File: app/view/tabAjusteViewController.js
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

Ext.define('cartera.view.tabAjusteViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.tabajuste',

    onBtnOkAjusteClick: function(button, e, eOpts) {
        var FormAjustes = Ext.create('invcaf.view.windowAjustes');
        FormAjustes.show();
    },

    onBtnCancelAjusteClick: function(button, e, eOpts) {

    },

    onGridFaltantesAfterRender: function(component, eOpts) {
        Ext.getStore('FaltantesStore').load();
    },

    onGridSobrantesAfterRender: function(component, eOpts) {
        Ext.getStore('SobrantesStore').load();
    },

    onTabAjusteBeforeShow: function(component, eOpts) {

    },

    onTabAjusteAfterRender: function(component, eOpts) {
        Ext.getStore('ConfigStore').load({
            callback: function (records, operation, success){
                if(success === true){
                    Ext.MessageBox.show({
                        title: 'Validacion',
                        icon: Ext.MessageBox.INFO,
                        msg: 'No se ha generado ningún archivo físico para este mes',
                        buttons: Ext.MessageBox.OK,
                        fn : function(btn)
                        {
                            // alert(btn);
                            if (btn == 'ok')
                            {
                                Ext.getCmp('tabAjuste').close();

                            }
                        }
                    });
                    return false;

                }

            }


        });

    }

});