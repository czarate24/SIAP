/*
 * File: app/view/LoginWindowsViewController.js
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

Ext.define('cartera.view.LoginWindowsViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.loginwindows',

    onBtnIngresarClick1: function(button, e, eOpts) {

        var me = this,
            mainViewport,
            loginForm;

        var usu = Ext.getCmp('txtUsuario').getValue();
        var pass = Ext.getCmp('txtContra').getValue();
        loginForm= me.view.down("#loginForm");
        loginForm= loginForm.getForm();

        if (loginForm.isValid()){
            me.view.mask("Iniciando Sesión...Espere!!");

            Ext.getStore('LoginStore').load({
                params:{
                    'usu': usu,
                    'pass': pass

                },

                callback: function (records, operation, success){

                    if(success === true){
                        //  if(records.length === 1){
                        //    console.log("CA");
                        if(records[0].data.tipo != 'A'){
                            Ext.MessageBox.show({
                                title: 'Login',
                                icon: Ext.MessageBox.WARNING,
                                msg: 'Acceso denegado, Módulo exclusivo para Auditoria...',
                                buttons: Ext.MessageBox.OK
                            });
                            me.view.unmask();

                        }else{
                            records = Ext.getStore('LoginStore').data.items[0];
                            Ext._nombre = records.data.nombre_completo;
                            Ext._tipo   = records.data.tipo;
                            Ext._idusr   = records.data.id_usuario;
                            Ext._conteo  = 1;
                            mainViewport =  new cartera.view.MainViewport();
                            mainViewport.show();
                            me.view.destroy();


                        }

                    }else{
                        Ext.MessageBox.show({
                            title: 'Login',
                            icon: Ext.MessageBox.INFO,
                            msg: 'Usuario o contraseña incorrectos.',
                            buttons: Ext.MessageBox.OK
                        });

                        me.view.unmask();

                    }

                }

            });


            /*loginForm.submit({

            success: function(form, action){
            me.view.unmask();
            Ext._.usuario =action.result.records;
            mainViewport =  new invcaf.view.MainViewport();
            mainViewport.show();
            me.view.destroy();
            },

            failure: function(form, action){
            var mensaje;

            me.view.unmask();
            if (action.result){
            //si la respuesta dle servidor es valida use mensaje dle servidor
            mensaje = action.result.message;
        } else {
            //si la respuesta del servidor es invalida se usa el mensaje general
            mensaje = "Error al inciar sesion";
        }


        Ext.Msg.show({
            title: 'Mensaje del Sistema',
            message: mensaje,
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.ERROR
        });
    }

}); */

//  console.log(Ext._.usuario);
}

    }

});