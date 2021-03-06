/*
 * File: app/view/InstitucionesTabViewController16.js
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

Ext.define('cartera.view.InstitucionesTabViewController16', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.anualidadestab',

    onBtnAgregarAnualidadesClick: function(button, e, eOpts) {
        var FormCobros = Ext.create('cartera.view.AnualidadesWin');
        FormCobros.show();
    },

    onBtnBorrarAnualidadesClick: function(button, e, eOpts) {
        var record = Ext.getCmp('GridCobros').getSelectionModel().getSelection()[0];
        if(record !== undefined){
            var estatus = Ext.getCmp('GridCobros').getSelectionModel().getSelection()[0].data.id_estatus_cobro;
            var descripcion = Ext.getCmp('GridCobros').getSelectionModel().getSelection()[0].data.nombre;
            console.log(estatus);
            if(estatus === 1){
                Ext.MessageBox.show({
                    title : 'Desactivar Cobro',
                    buttons : Ext.MessageBox.YESNO,
                    buttonText:{
                        yes:'Sí',
                        no:'No',
                        cancel:'Cancelar'
                    },
                    msg : '¿Desea Desactivar a '+descripcion+'?',
                    icon : Ext.Msg.WARNING,
                    fn : function(btn)
                    {
                        if (btn == 'yes')
                        {
                            var values= {'id_estatus_cobro': 0};
                            record.set(values);
                            Ext.getStore('CapturaCobrosStore').sync({
                                callback: function(records, operation, success)
                                {
                                    Ext.MessageBox.show({
                                        title : 'Desactivar Cobro',
                                        buttons : Ext.MessageBox.OK,
                                        msg : 'Cobro desactivado correctamente',
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
                    title : 'Activar Cobro',
                    buttons : Ext.MessageBox.YESNO,
                    buttonText:{
                        yes:'Sí',
                        no:'No',
                        cancel:'Cancelar'
                    },
                    msg : '¿Desea Activar a '+descripcion+'?',
                    icon : Ext.Msg.WARNING,
                    fn : function(btn)
                    {
                        if (btn == 'yes')
                        {
                            var values= {'id_estatus_cobro': 1};
                            record.set(values);
                            Ext.getStore('CapturaCobrosStore').sync({
                                callback: function(records, operation, success)
                                {
                                    Ext.MessageBox.show({
                                        title : 'Activar Cobro',
                                        buttons : Ext.MessageBox.OK,
                                        msg : 'Cobro activado correctamente',
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

    onBtnModificarAnualidadesClick: function(button, e, eOpts) {
        records = Ext.getCmp('GridPromos').getSelectionModel().getSelection();
        if(records.length > 0){
            var WinCuotasInscripcion = Ext.create('cartera.view.PromocionesInsWin'),
                EditForm = WinCuotasInscripcion.down('form'),
                record = records[0],
                form = Ext.getCmp('formPromocionesIns');

            WinCuotasInscripcion.setTitle('Modificar Promoción');
            form.getForm().loadRecord(record);
            //Ext.getCmp('institucionescmb').disabled=true;

        }else{
            Ext.MessageBox.show({
                title : 'Advertencia',
                buttons : Ext.MessageBox.OK,
                msg : 'Seleccione registro.',
                icon : Ext.Msg.ERROR

            });
        }
    },

    onBtnActualizarAnualidadesClick: function(button, e, eOpts) {
        Ext.getStore('AnualidadStore').load({
            params:{
                'id_institucion': Ext._id_institucion,
                'id_ciclo_escolar': Ext._id_ciclo_escolar
            },
        });
    },

    onTxtFechaAfterRender: function(component, eOpts) {

    },

    onGridcolumnAfterRender11: function(component, eOpts) {
        Ext.getStore('AnualidadStore').load({
            params:{
                'id_institucion': Ext._id_institucion,
                'id_ciclo_escolar': Ext._id_ciclo_escolar
            },
        });

    }

});
