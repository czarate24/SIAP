/*
 * File: app/view/InstitucionesWinViewController6.js
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

Ext.define('cartera.view.InstitucionesWinViewController6', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.estatusingresowin',

    onDescripcionEIChange: function(field, newValue, oldValue, eOpts) {
        field.setValue(newValue.toUpperCase());
    },

    onbtnGuardarEI: function(button, e, eOpts) {
        var win = button.up('window'),
            form   = Ext.getCmp('formEstatusIngreso'),
            record = form.getRecord(),
            values = form.getValues();

        //console.log(form);
        console.log(values.id_estatus_ingreso);
        if(form.isValid()){
            if(values.id_estatus_ingreso>0){
                //Modificar Forma de Pago.
                //var recordLogin = Ext.getStore('StoreLogin').data.items[0];
                //values.id_usuarioAct = recordLogin.data.id_usuario;
                // record.set(values);
                // Ext.getStore('FormasPagoStore').sync();
                form.submit({
                    params: Ext.JSON.encode(values),
                    url: 'api/estatusgrid/actualiza',
                    waitMsg: 'Procesando información...',
                    success: function() {


                        Ext.MessageBox.show({
                            title : 'Información',
                            buttons : Ext.MessageBox.OK,
                            msg : 'Estatus '+values.descripcion+' ha sido actualizada.',
                            icon : Ext.Msg.INFO

                        });
                        Ext.getStore('EstatusIngresoGridStore').load();

                        win.close();
                    },
                    failure: function() {
                        // console.log('FAILURE CAPTURA ESTATUS');
                    }
                });
            }else{
                //Agregar Estatus Ingreso.
                if(Ext.getStore('EstatusIngresoGridStore').findExact('descripcion', values.descripcion)!=-1){
                    Ext.MessageBox.show({
                        title : 'Advertencia',
                        buttons : Ext.MessageBox.OK,
                        msg : 'Ya Existe Eestatus: '+values.descripcion+'. Verifique...',
                        icon : Ext.Msg.ERROR
                    });
                }else{
                    // var recordLogin = Ext.getStore('StoreLogin').data.items[0];
                    //values.id_usuarioAct = recordLogin.data.id_usuario;
                    record = Ext.create('cartera.model.EstatusIngresoGridModel');
                    console.log(record);
                    record.set(values);
                    //Ext.getStore('FormasPagoStore').add(record);
                    //Ext.getStore('FormasPagoStore').sync();
                    //Ext.getCmp('id_insFP').setValue(Ext._id_institucion);
                    form.submit({
                        params: Ext.JSON.encode(values),
                        url: 'api/estatusgrid/create',
                        waitMsg: 'Procesando información...',
                        success: function() {


                            Ext.MessageBox.show({
                                title : 'Información',
                                buttons : Ext.MessageBox.OK,
                                msg : 'Estatus '+values.descripcion+' ha sido agregado.',
                                icon : Ext.Msg.INFO

                            });
                            Ext.getStore('EstatusIngresoGridStore').load();

                            win.close();
                        },
                        failure: function() {
                            //console.log('FAILURE CAPTURA INSTITUCION');
                        }
                    });


                }
            }

        }else{
            //Si el formulario no es válido
            Ext.MessageBox.show({
                title : 'Atencion',
                buttons : Ext.MessageBox.OK,
                msg : 'Ingrese todos los campos obligatorios',
                icon : Ext.Msg.ERROR
            });
        }
    },

    onbtnCancelarEI: function(button, e, eOpts) {
        button.up('window').close();
    },

    onFormasPagoWinAfterRender: function(component, eOpts) {

    }

});
