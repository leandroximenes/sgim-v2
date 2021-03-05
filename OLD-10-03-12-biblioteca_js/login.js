Ext.onReady(function(){

    Ext.QuickTips.init();

	
    var login = new Ext.FormPanel({
        labelWidth: 70,
        frame:true,
		iconCls: 'ic_seguro',
		url:'modulos/diversos/login.php', 
		style:'text-align: left;',
        title: 'Acesso Restrito',
        bodyStyle:'padding:10px 5px 5px 40px; text-align: left;',
        width: 350,
		defaults: {width: 150},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Login',
                name: 'login',
                allowBlank:false
            },{
                fieldLabel: 'Senha',
                name: 'senha',
				inputType: 'password',
				allowBlank:false
            }],
		buttons: [{
            text: 'Entrar',
			formBind: true,
			keys: new Ext.KeyMap(document, {
				key: Ext.EventObject.ENTER,
					fn: function() {
					logar()
				},
				scope: this
			}),
			handler:function(){ 
				logar()
			}
        }]
    });
    login.render(center);
	
	function logar(){
		login.getForm().submit({ 
			method:'POST', 
			waitTitle:'Connectando', 
			waitMsg:'Sending data...',
			success:function(){ 
				var redirect = 'sistema.php'; 
				window.location = redirect;
			},
			
			failure:function(form, action){ 
				Ext.Msg.alert('Erro!', action.result.file); 
				login.getForm().reset() 
			} 
		});
	}

});