/*
 * File: app/view/InstitucionesTabViewController3.js
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

Ext.define('cartera.view.InstitucionesTabViewController3', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.formaspagotab',

    onBtnAgregarFpClick: function(button, e, eOpts) {
        var FormFormasPago = Ext.create('cartera.view.FormasPagoWin');
        FormFormasPago.show();
    },

    onBtnBorrarFpClick: function(button, e, eOpts) {
        var record = Ext.getCmp('GridFormasPago').getSelectionModel().getSelection()[0];
        if(record !== undefined){
            var estatus = Ext.getCmp('GridFormasPago').getSelectionModel().getSelection()[0].data.estatus;
            var forma_pago = Ext.getCmp('GridFormasPago').getSelectionModel().getSelection()[0].data.forma_pago;
            console.log(estatus);
            if(estatus === 1){
                Ext.MessageBox.show({
                    title : 'Desactivar Forma de Pago',
                    buttons : Ext.MessageBox.YESNO,
                    buttonText:{
                        yes:'Sí',
                        no:'No',
                        cancel:'Cancelar'
                    },
                    msg : '¿Desea Desactivar a '+forma_pago+'?',
                    icon : Ext.Msg.WARNING,
                    fn : function(btn)
                    {
                        if (btn == 'yes')
                        {
                            var values= {'estatus': 0};
                            record.set(values);
                            Ext.getStore('FormasPagoStore').sync({
                                callback: function(records, operation, success)
                                {
                                    Ext.MessageBox.show({
                                        title : 'Desactivar Forma de pago',
                                        buttons : Ext.MessageBox.OK,
                                        msg : 'Forma de pago desactivada correctamente',
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
                    title : 'Activar Forma de Pago',
                    buttons : Ext.MessageBox.YESNO,
                    buttonText:{
                        yes:'Sí',
                        no:'No',
                        cancel:'Cancelar'
                    },
                    msg : '¿Desea Activar a '+forma_pago+'?',
                    icon : Ext.Msg.WARNING,
                    fn : function(btn)
                    {
                        if (btn == 'yes')
                        {
                            var values= {'estatus': 1};
                            record.set(values);
                            Ext.getStore('FormasPagoStore').sync({
                                callback: function(records, operation, success)
                                {
                                    Ext.MessageBox.show({
                                        title : 'Activar Forma de Pago',
                                        buttons : Ext.MessageBox.OK,
                                        msg : 'Forma de Pago activada correctamente',
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

    onBtnModificarFpClick: function(button, e, eOpts) {
        records = Ext.getCmp('GridFormasPago').getSelectionModel().getSelection();
        if(records.length > 0){
            var WinFormasPago = Ext.create('cartera.view.FormasPagoWin'),
                EditForm = WinFormasPago.down('form'),
                record = records[0],
                form   = Ext.getCmp('formFormaPago');

            WinFormasPago.setTitle('Modificar Forma de Pago');
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

    onBtnActualizarFpClick: function(button, e, eOpts) {
        Ext.getStore('FormasPagoStore').load({
            params:{
                'id_ins': Ext._id_institucion
            },
        });

    },

    onGridcolumnAfterRender: function(component, eOpts) {
        Ext.getStore('FormasPagoStore').load({
            params:{
                'id_ins': Ext._id_institucion
            },
        });
    }

});
