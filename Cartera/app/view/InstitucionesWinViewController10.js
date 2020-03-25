/*
 * File: app/view/InstitucionesWinViewController10.js
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

Ext.define('cartera.view.InstitucionesWinViewController10', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.asigcamionwin',

    onTransporteACChange: function(field, newValue, oldValue, eOpts) {
        var lengthRecord = field.lastSelectedRecords;
        var cuota = field.lastSelectedRecords[0].data.cuota;
        var id_cuota = field.lastSelectedRecords[0].data.id_cuota_transporte;
        if(lengthRecord !== null){
            Ext.getCmp('txtCuotaAC').setValue(cuota);
            Ext.getCmp('id_cuota_transporteAC').setValue(id_cuota);


        }

    },

    onbtnGuardarB: function(button, e, eOpts) {
        var win = button.up('window'),
            form   = Ext.getCmp('formAsigCamion'),
            record = form.getRecord(),
            values = form.getValues();

        if(form.isValid()){
            if(values.id_banco>0){
                //Modificar Forma de Pago.

                form.submit({
                    params: Ext.JSON.encode(values),
                    url: 'api/transportealumno/actualiza',
                    waitMsg: 'Procesando información...',
                    success: function() {


                        Ext.MessageBox.show({
                            title : 'Información',
                            buttons : Ext.MessageBox.OK,
                            msg : 'Estatus '+values.nombre+' ha sido actualizada.',
                            icon : Ext.Msg.INFO

                        });
                        Ext.getStore('BancosStore').load();

                        win.close();
                    },
                    failure: function() {
                    }
                });
            }else{
                //Agregar Estatus Ingreso.
                if(Ext.getStore('TransporteAlumnoStore').findExact('id_alumno', parseInt(values.id_alumno))!=-1){
                    Ext.MessageBox.show({
                        title : 'Advertencia',
                        buttons : Ext.MessageBox.OK,
                        msg : 'Ya existen datos para ese alumno. Verifique...',
                        icon : Ext.Msg.ERROR
                    });
                }else{
                    record = Ext.create('cartera.model.TransporteAlumnoModel');
                    record.set(values);
                    form.submit({
                        params: Ext.JSON.encode(values),
                        url: 'api/transportealumno/create',
                        waitMsg: 'Procesando información...',
                        success: function() {


                            Ext.MessageBox.show({
                                title : 'Información',
                                buttons : Ext.MessageBox.OK,
                                msg : 'El transporte ha sido agregado.',
                                icon : Ext.Msg.INFO

                            });

                            win.close();

                            Ext.getStore('TransporteAlumnoStore').load({
                                params:{
                                    'id_alumno': record.data.id_alumno
                                }
                            });
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

    onbtnCancelarB: function(button, e, eOpts) {
        button.up('window').close();
    },

    onFormasPagoWinAfterRender: function(component, eOpts) {
        Ext.getStore('CamionesAlumnosStore').load({
            params:{
                'id_alumno': Ext._id_alumno
            },
        });

        Ext.getStore('TransporteAlumnoStore').load({
            params:{
                'id_alumno': Ext._id_alumno
            },
        });

        Ext.getStore('MesesCicloStore').load({
            params:{
                'id_alumno': Ext._id_alumno
            },
        });


        Ext.getStore('cmbTransporteStore').load({
            params:{
                'id_ins': Ext._id_institucion,
                'id_ciclo_escolar': Ext._id_ciclo_escolar
            },
        });


    }

});
