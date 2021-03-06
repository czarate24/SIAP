/*
 * File: app/view/InstitucionesWinViewController18.js
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

Ext.define('cartera.view.InstitucionesWinViewController18', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.capturacobroswin',

    onTxtMatriculaASpecialkey: function(field, e, eOpts) {




        var matricula = Ext.getCmp('txtMatriculaA').getValue();


        if (e.getKey() == e.ENTER) {
            Ext._id_alumno=0;
            Ext.getCmp('CobrosForm').getForm().reset();
            Ext.getCmp('CobroFForm').getForm().reset();

            Ext.getStore('CobrosAlumnoStore').load({
                params:{
                    'matricula': matricula

                },
                callback: function(records) {
                    if (records.length > 0) {
                        var datos= records[0].data;
                        Ext.getCmp('txtAlumnoA').setValue(datos.nombre);
                        Ext.getCmp('txtId_alumnoA').setValue(datos.id_alumno);
                        Ext.getCmp('txtNivelA').setValue(datos.nivel);
                        Ext.getCmp('txtMatriculaA').setValue(datos.matricula);
                        Ext.getCmp('txtGradoA').setValue(datos.grado);
                        Ext.getCmp('txtGrupoA').setValue(datos.grupo);
                        Ext.getCmp('cmbRFCF').setValue(datos.rfc);
                        Ext.getCmp('txtRazonSocialF').setValue(datos.razon_social);
                        Ext.getCmp('txtUsoCFDIF').setValue(datos.usoCFDI);
                        Ext.getCmp('txtIdDatoFac').setValue(datos.id_dato_facturacion);
                        Ext._id_alumno=datos.id_alumno;


                    }

                    Ext.getStore('cmbTipoMovimientoStore').load({
                        params:{
                            'id_ins': Ext._id_institucion,

                        }
                    });


                    Ext.getStore('cmbRFCStore').load({
                        params:{
                            'id_alumno': Ext._id_alumno
                        },
                        callback: function(records) {
                            if (records.length <= 0) {
                                Ext.getCmp('cmbRFCF').setValue('XAXX010101000');
                                Ext.getCmp('txtRazonSocialF').setValue(Ext.getCmp('txtAlumnoA').getValue());
                                Ext.getCmp('txtUsoCFDIF').setValue("D10");
                                Ext.getCmp('txtIdDatoFac').setValue("0");


                            }

                        }

                    });





                }
            });



        }


    },

    onbtnGuardarCobro1: function(button, e, eOpts) {

        var AlumnosWin = Ext.create('cartera.view.AlumnosTab');
        AlumnosWin.show();


    },

    onCmbTipoCChange: function(field, newValue, oldValue, eOpts) {
        if( Ext.getCmp('cmbTipoC').getValue()==1){

            Ext.getStore('CobrosCarnetStore').load({
                params:{
                    'id_institucion': Ext._id_institucion,
                    'id_ciclo_escolar': Ext._id_ciclo_escolar,
                    'id_alumno': Ext._id_alumno,

                },
                callback: function(records) {
                    if (records.length > 0) {
                        var datos= records[0].data;
                        console.log(datos.descuento);
                        Ext.getCmp('txtReferenciaC').setValue(datos.referencia);
                        Ext.getCmp('txtImporteCo').setValue(datos.cuota + '.00');
                        Ext.getCmp('txtCarnet').setValue(datos.id_carnet_inscripcion);
                        Ext.getCmp('txtConcepto').setValue(datos.concepto);
                        Ext.getCmp('txtDescuentoCo').setValue('0.00');
                        Ext.getCmp('txtRecargoCo').setValue('0.00');
                        Ext.getCmp('txtTransporteCo').setValue('0.00');
                        Ext.getCmp('txtDescuentoBeca').setValue(datos.descuento + '.00');
                        Ext.getCmp('txtVencimiento').setValue('0000-00-00');
                        Ext.getCmp('id_descuento_inscripcionCobro').setValue(datos.id_descuento_inscripcion);
                        Ext.getCmp('txtSubtotalCo').setValue(datos.cuota-Ext.getCmp('txtDescuentoBeca').getValue()+'.00');
                        Ext.getCmp('txtSaldoCo').setValue(datos.cuota-Ext.getCmp('txtDescuentoBeca').getValue() + '.00');
                        Ext.getCmp('id_institucion_cobro').setValue(Ext._id_institucion);
                        Ext.getCmp('id_facturacion_transporte').setValue('0');
                    }else{
                        Ext.getCmp('CobroFForm').getForm().reset();
                        Ext.getCmp('txtReferenciaC').setValue(" ");
                        Ext.getCmp('txtConcepto').setValue(" ");
                        Ext.getCmp('txtVencimiento').setValue("");

                    }
                }
            });

        }
        if( Ext.getCmp('cmbTipoC').getValue()==2){

            Ext.getStore('CobrosColegiaturaStore').load({
                params:{
                    'id_institucion': Ext._id_institucion,
                    'id_ciclo_escolar': Ext._id_ciclo_escolar,
                    'id_alumno': Ext._id_alumno,

                },
                callback: function(records) {
                    if (records.length > 0) {
                        var datos= records[0].data;
                        console.log(datos.fecha_vencimiento);
                        Ext.getCmp('txtReferenciaC').setValue(datos.referencia);
                        Ext.getCmp('txtImporteCo').setValue(datos.cuota);
                        Ext.getCmp('txtCarnet').setValue(datos.id_carnet_colegiatura);
                        Ext.getCmp('txtConcepto').setValue(datos.concepto);
                        Ext.getCmp('txtVencimiento').setValue(datos.fecha_vencimiento);
                        Ext.getCmp('txtDescuentoCo').setValue('0');
                        Ext.getCmp('txtRecargoCo').setValue(datos.recargos);
                        Ext.getCmp('txtDescuentoBeca').setValue(datos.beca + '.00');
                        Ext.getCmp('txtTransporteCo').setValue(datos.transporte + '.00');
                        Ext.getCmp('txtSubtotalCo').setValue(datos.cuota - datos.beca+datos.transporte + '.00');
                        Ext.getCmp('txtSaldoCo').setValue(datos.cuota - datos.beca + datos.transporte  +datos.recargos);
                        Ext.getCmp('id_descuento_inscripcionCobro').setValue('0');
                        Ext.getCmp('id_institucion_cobro').setValue(Ext._id_institucion);
                        Ext.getCmp('id_facturacion_transporte').setValue('0');



                    }else{
                        Ext.getCmp('CobroFForm').getForm().reset();
                        Ext.getCmp('txtReferenciaC').setValue(" ");

                        Ext.getCmp('txtConcepto').setValue(" ");
                        Ext.getCmp('txtVencimiento').setValue("");

                    }
                }
            });

        }

    },

    onCmbRFCFChange: function(field, newValue, oldValue, eOpts) {

        if(Ext._id_alumno>=1){
            datos = field.lastSelectedRecords[0].data;
            Ext.getCmp('txtRazonSocialF').setValue(datos.razon_social);
            Ext.getCmp('txtUsoCFDIF').setValue(datos.usoCFDI);
            Ext.getCmp('txtIdDatoFac').setValue(datos.id_dato_facturacion);
        }


    },

    onTxtDescuentoCoSpecialkey: function(field, e, eOpts) {
        if (e.getKey() == e.ENTER) {
            var descuento=parseInt(Ext.getCmp('txtDescuentoCo').getValue());
            var importe=parseInt(Ext.getCmp('txtSubtotalCo').getValue());
            var recargos=parseInt(Ext.getCmp('txtRecargoCo').getValue());
            var total=importe+recargos-descuento;
            Ext.getCmp('txtSaldoCo').setValue(total + '.00');
        }
    },

    onTxtDescuentoCoFocusleave: function(component, event, eOpts) {
        var descuento=parseInt(Ext.getCmp('txtDescuentoCo').getValue());
        var importe=parseInt(Ext.getCmp('txtSubtotalCo').getValue());
        var recargos=parseInt(Ext.getCmp('txtRecargoCo').getValue());
        var total=importe+recargos-descuento;
        Ext.getCmp('txtSaldoCo').setValue(total + '.00');
    },

    onbtnGuardarCobro: function(button, e, eOpts) {
        if(Ext.getCmp('chckFactura').getValue()===true){
            Ext.getCmp('id_facturacion_transporte').setValue("2");
        }else{
            Ext.getCmp('id_facturacion_transporte').setValue("1");
        }

        Ext.getCmp('id_institucion_cobro').setValue(Ext._id_institucion);

        var win = button.up('window'),
            form   = Ext.getCmp('CobroFForm'),
            record = form.getRecord(),
            values = form.getValues();


        if(Ext.getCmp('txtRazonSocialF').getValue()===""){
            //Si el formulario no es válido
            Ext.MessageBox.show({
                title : 'Atencion',
                buttons : Ext.MessageBox.OK,
                msg : 'Seleccione un RFC',
                icon : Ext.Msg.ERROR
            });
        }else{
            if(form.isValid()){
                record = Ext.create('cartera.model.CapturaCobrosModel');
                console.log(record);
                record.set(values);

                if( Ext.getCmp('cmbTipoC').getValue()==2){

                    form.submit({
                        params: Ext.JSON.encode(values),
                        url: 'api/capturacobros/create',
                        waitMsg: 'Procesando información...',
                        success: function() {


                            Ext.MessageBox.show({
                                title : 'Información',
                                buttons : Ext.MessageBox.OK,
                                msg : 'El cobro ha sido agregado.',
                                icon : Ext.Msg.INFO

                            });

                            Ext.getStore('CapturaCobrosStore').load({
                                params:{
                                    'id_institucion': Ext._id_institucion,
                                    'id_ciclo_escolar': Ext._id_ciclo_escolar
                                },
                            });

                            win.close();

                        },
                        failure: function() {
                            console.log('FAILURE CAPTURA INSTITUCION');
                        }
                    });

                }else{

                    form.submit({
                        params: Ext.JSON.encode(values),
                        url: 'api/capturacobros/createins',
                        waitMsg: 'Procesando información...',
                        success: function() {


                            Ext.MessageBox.show({
                                title : 'Información',
                                buttons : Ext.MessageBox.OK,
                                msg : 'El cobro ha sido agregado.',
                                icon : Ext.Msg.INFO

                            });

                            Ext.getStore('CapturaCobrosStore').load({
                                params:{
                                    'id_institucion': Ext._id_institucion,
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



            }else{
                //Si el formulario no es válido
                Ext.MessageBox.show({
                    title : 'Atencion',
                    buttons : Ext.MessageBox.OK,
                    msg : 'Ingrese todos los campos obligatorios',
                    icon : Ext.Msg.ERROR
                });
            }
        }
    },

    onbtnCancelarCobro: function(button, e, eOpts) {
        button.up('window').close();
    },

    onWinGen1AfterRender: function(component, eOpts) {


    }

});
