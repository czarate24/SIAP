/*
 * File: app/view/InstitucionesWinViewController1.js
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

Ext.define('cartera.view.InstitucionesWinViewController1', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.cuotascolegiaturawin',

    onNivelcmbCCChange: function(field, newValue, oldValue, eOpts) {
        var lengthRecord = field.lastSelectedRecords;
        //console.log(lengthRecord);
        if(lengthRecord !== null){
            Ext.getStore('GradosStore').load({
                params:{
                    'id_ins': Ext._id_institucion,
                    'id_nivel_educativo': lengthRecord[0].data.id_nivel_educativo
                },
                callback: function (records, operation, success){
                    if(success === true){
                        if (records[0].data.id_nivel_educativo != 1){

                            Ext.getCmp('gradocmbCC').disabled = true;
                            Ext.getCmp('gradocmbCC').setValue('');
                        }else{

                            Ext.getCmp('gradocmbCC').disabled = false;
                        }
                    }
                    else
                    {
                        // Ext.getCmp('cicloCmb').setStore('CiclosEscolaresStore');
                        Ext.getCmp('gradocmbCC').disabled = true;
                        Ext.getCmp('gradocmbCC').setValue('');
                        //Ext.getCmp('tabPrin').reload();

                    }
                }
            });
        }
    },

    ondescripcionCCChange: function(field, newValue, oldValue, eOpts) {
        field.setValue(newValue.toUpperCase());
    },

    onbtnGuardarCi: function(button, e, eOpts) {
        var win = button.up('window'),
            form   = Ext.getCmp('formCuotaColegiatura'),
            record = form.getRecord(),
            values = form.getValues();

        //console.log(form);
        console.log(values.id_cuota_colegiatura);
        if(form.isValid()){
            if(values.id_cuota_colegiatura>0){
                //Modificar Forma de Pago.
                //var recordLogin = Ext.getStore('StoreLogin').data.items[0];
                //values.id_usuarioAct = recordLogin.data.id_usuario;
                // record.set(values);
                // Ext.getStore('FormasPagoStore').sync();
                form.submit({
                    params: Ext.JSON.encode(values),
                    url: 'api/cuotascolegiatura/actualiza',
                    waitMsg: 'Procesando información...',
                    success: function() {


                        Ext.MessageBox.show({
                            title : 'Información',
                            buttons : Ext.MessageBox.OK,
                            msg : 'Cuota de Colegiatura para '+values.descripcion+' ha sido actualizada.',
                            icon : Ext.Msg.INFO

                        });
                        Ext.getStore('CuotasColegiaturaStore').load({
                            params:{
                                'id_ins': Ext._id_institucion,
                                'id_ciclo_escolar': Ext._id_ciclo_escolar
                            },
                        });

                        win.close();
                    },
                    failure: function() {
                        console.log('FAILURE CAPTURA FORMA DE PAGO');
                    }
                });
            }else{
                //Agregar Institución.
                if(Ext.getStore('CuotasColegiaturaStore').findExact('descripcion', values.descripcion)!=-1){
                    Ext.MessageBox.show({
                        title : 'Advertencia',
                        buttons : Ext.MessageBox.OK,
                        msg : 'Ya Existe Cuota para: '+values.descripcion+'. Verifique...',
                        icon : Ext.Msg.ERROR
                    });
                }else{
                    // var recordLogin = Ext.getStore('StoreLogin').data.items[0];
                    //values.id_usuarioAct = recordLogin.data.id_usuario;
                    record = Ext.create('cartera.model.CuotasColegiaturaModel');
                    console.log(record);
                    record.set(values);
                    //Ext.getStore('FormasPagoStore').add(record);
                    //Ext.getStore('FormasPagoStore').sync();
                    Ext.getCmp('id_insCC').setValue(Ext._id_institucion);
                    form.submit({
                        params: Ext.JSON.encode(values),
                        url: 'api/cuotascolegiatura/create',
                        waitMsg: 'Procesando información...',
                        success: function() {


                            Ext.MessageBox.show({
                                title : 'Información',
                                buttons : Ext.MessageBox.OK,
                                msg : 'Cuota de Colegiatura para '+values.descripcion+' ha sido agregada.',
                                icon : Ext.Msg.INFO

                            });
                            Ext.getStore('CuotasColegiaturaStore').load({
                                params:{
                                    'id_ins': Ext._id_institucion,
                                    'id_ciclo_escolar': Ext._id_ciclo_escolar
                                },
                            });

                            win.close();
                        },
                        failure: function() {
                            console.log('FAILURE CAPTURA INSTITUCION');
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

    onbtnCancelarCi: function(button, e, eOpts) {
        button.up('window').close();
    },

    onCuotasColegiaturaWinAfterRender: function(component, eOpts) {
        Ext.getCmp('id_insCC').setValue(Ext._id_institucion);
        Ext.getCmp('id_ciclo_escolarCC').setValue(Ext._id_ciclo_escolar);
        Ext.getStore('NivelesEducativosStore').load({
            params:{
                'id_ins': Ext._id_institucion
            },
        });
    }

});