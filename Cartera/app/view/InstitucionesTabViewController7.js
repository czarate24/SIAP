/*
 * File: app/view/InstitucionesTabViewController7.js
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

Ext.define('cartera.view.InstitucionesTabViewController7', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.estatusingresotab',

    onBtnAgregarEIClick: function(button, e, eOpts) {
        var FormEstatusIngreso = Ext.create('cartera.view.EstatusIngresoWin');
        FormEstatusIngreso.show();
    },

    onBtnBorrarEIClick: function(button, e, eOpts) {
        var record = Ext.getCmp('GridEstatusIngreso').getSelectionModel().getSelection()[0];
        if(record !== undefined){
            var estatus = Ext.getCmp('GridEstatusIngreso').getSelectionModel().getSelection()[0].data.estatus;
            var estatus_ingreso = Ext.getCmp('GridEstatusIngreso').getSelectionModel().getSelection()[0].data.descripcion;
            console.log(estatus);
            if(estatus === 1){
                Ext.MessageBox.show({
                    title : 'Desactivar estatus Ingreso',
                    buttons : Ext.MessageBox.YESNO,
                    buttonText:{
                        yes:'Sí',
                        no:'No',
                        cancel:'Cancelar'
                    },
                    msg : '¿Desea Desactivar a '+estatus_ingreso+'?',
                    icon : Ext.Msg.WARNING,
                    fn : function(btn)
                    {
                        if (btn == 'yes')
                        {
                            var values= {'estatus': 0};
                            record.set(values);
                            Ext.getStore('EstatusIngresoGridStore').sync({
                                callback: function(records, operation, success)
                                {
                                    Ext.MessageBox.show({
                                        title : 'Desactivar Estatus Ingreso',
                                        buttons : Ext.MessageBox.OK,
                                        msg : 'Estatus ingreso desactivado correctamente',
                                        icon : Ext.Msg.INFO
                                    });
                                }
                            });
                        }
                    }
                });
            }
            else{
                Ext.MessageBox.show({
                    title : 'Activar Estatus Ingreso',
                    buttons : Ext.MessageBox.YESNO,
                    buttonText:{
                        yes:'Sí',
                        no:'No',
                        cancel:'Cancelar'
                    },
                    msg : '¿Desea Activar a '+estatus_ingreso+'?',
                    icon : Ext.Msg.WARNING,
                    fn : function(btn)
                    {
                        if (btn == 'yes')
                        {
                            var values= {'estatus': 1};
                            record.set(values);
                            Ext.getStore('EstatusIngresoGridStore').sync({
                                callback: function(records, operation, success)
                                {
                                    Ext.MessageBox.show({
                                        title : 'Activar Estatus Ingreso',
                                        buttons : Ext.MessageBox.OK,
                                        msg : 'Estatus ingreso activado correctamente',
                                        icon : Ext.Msg.INFO
                                    });
                                }
                            });
                        }
                    }
                });
            }
        }
        else{
            Ext.MessageBox.show({
                title : 'Información',
                buttons : Ext.MessageBox.OK,
                msg : 'Seleccione registro.',
                icon : Ext.Msg.INFO
            });

        }
    },

    onBtnModificarEIClick: function(button, e, eOpts) {
        records = Ext.getCmp('GridEstatusIngreso').getSelectionModel().getSelection();
        if(records.length > 0){
            var WinEstatusIngreso = Ext.create('cartera.view.EstatusIngresoWin'),
                EditForm = WinEstatusIngreso.down('form'),
                record = records[0],
                form   = Ext.getCmp('formEstatusIngreso');

            WinEstatusIngreso.setTitle('Modificar Estatus');
            form.getForm().loadRecord(record);



        }else{
            Ext.MessageBox.show({
                title : 'Advertencia',
                buttons : Ext.MessageBox.OK,
                msg : 'Seleccione registro.',
                icon : Ext.Msg.ERROR

            });
        }
    },

    onBtnActualizarEIClick: function(button, e, eOpts) {
        Ext.getStore('EstatusIngresoGridStore').load();

    },

    onGridcolumnAfterRender: function(component, eOpts) {
        Ext.getStore('EstatusIngresoGridStore').load();
    }

});