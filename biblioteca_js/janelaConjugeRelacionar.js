function conjugeRelacionar(codPessoa, nomePessoa){
	
	var codPessoaSelecionada;

	var storeProfissao = new Ext.data.Store({
		id: 'storeProfissao',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/usuario/gerenciar_usuario.php',
			method: 'POST'
		}),
		baseParams:{acao: "profissaoListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codProfissao','nome']
		},[ 
			{name: 'codProfissao', type: 'int'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	storeProfissao.load()

	tfPessoaPrincipal = new Ext.form.TextField({
		fieldLabel: '<b>Pessoa</b>',
		name: 'nome',
		value: nomePessoa,
		id: 'tfPessoaPrincipal',
		allowBlank:false,
		disabled: true,
		maxLength: 100,
		anchor : '98%'
	})
	
	tfNomeConjuge = new Ext.form.TextField({
		fieldLabel: '<b>Nome Conjuge</b>',
		name: 'nome',
		id: 'tfNomeConjuge',
		allowBlank:false,
		blankText:"Por favor entre com o nome do Conjuge.",
		autoCreate:{tag:'input', type:'text',maxLength:'200'},
		anchor : '98%'
	})

	tfNacionalidadeConjuge = new Ext.form.TextField({
		fieldLabel: '<b>Nacionalidade</b>',
		name: 'nacionalidade',
		id: 'tfNacionalidadeConjuge',
		allowBlank:false,
		blankText:"Por favor entre com a nacionalidade.",
		autoCreate:{tag:'input',type:'text',maxLength: '100'},
		anchor : '98%'
	})

	tfCpfConjuge = {
		xtype:'masktextfield',
		fieldLabel: '<b>CPF</b>',
		name: 'cpf',
		id: 'tfCpfConjuge',
		enableKeyEvents: true,
		allowBlank:false,
		blankText:"Por favor entre com o CPF do conjuge.",
		autoCreate:{tag:'input',type:'text',maxLength: '14'},
		mask: '999.999.999-99',
		money: false,
		anchor : '98%'
	}

	dtDataNascimentoConjuge = new Ext.form.DateField({
		id: 'dtDataNascimentoConjuge',
		name: 'dataNascimentoConjuge',
		fieldLabel: '<b>Data Nascimento</b>',
		allowBlank: false,
		blankText:"Por favor insira a <b>DATA DE NASCIMENTO</b> do conjuge!",
		format : 'd/m/Y',
		anchor: '98%'
	})

	tfIdentidadeConjuge = new Ext.form.TextField({
		fieldLabel: '<b>Identidade</b>',
		name: 'rg',
		id: 'tfIdentidadeConjuge',
		allowBlank:false,
		blankText:"Por favor entre com o RG do usuário.",
		autoCreate:{tag:'input',type:'text',maxLength: '45'},
		anchor : '98%'
	})

	tfOrgaoExpedidorConjuge = new Ext.form.TextField({
		fieldLabel: '<b>Orgão Exp.</b>',
		name: 'orgaoExpedidor',
		id: 'tfOrgaoExpedidorConjuge',
		allowBlank:false,
		blankText:"Por favor entre com o orgão expedidor.",
		autoCreate:{tag:'input',type:'text',maxLength: '10'},
		anchor : '98%'
	})

	tfRendaConjuge = {
		xtype: 'masktextfield',
		mask: 'R$ #9.999.990,00',
		money: true,
		fieldLabel: '<b>Renda</b>',
		name: 'renda',
		id: 'tfRendaConjuge',
		allowBlank:false,
		blankText:"Por favor entre com a renda do usuário.",
		autoCreate:{tag:'input',type:'text',maxLength:'9'},
		anchor : '98%'
	}

	tfOutroRendimentoConjuge = {
		xtype: 'masktextfield',
		mask: 'R$ #9.999.990,00',
		money: true,
		fieldLabel: '<b>Outro Rendimento</b>',
		name: 'outroRendimento',
		id: 'tfOutroRendimentoConjuge',
		allowBlank:false,
		blankText:"Por favor entre com a outra renda do Representante.",
		autoCreate:{tag:'input',type:'text',maxLength: '9'},
		anchor : '98%'
	}

	cbProfissaoConjuge = new Ext.form.ComboBox({
		id: 'cbProfissaoConjuge',
		typeAhead: false,
		fieldLabel: '<b>Profissão</b>',
		value: '',
		mode: 'local',
		editable: false,
		anchor: '98%',
		store: storeProfissao,
		displayField: 'nome',
		valueField: 'codProfissao',
		forceSelection: true,
		triggerAction: 'all'

	})

	var janelaConjuge = new Ext.FormPanel({
        id: 'janelaConjuge',
        frame: true,
		autoHeight: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
        	columnWidth: 1,
            xtype: 'panel',
			frame: true,
			bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
            items:[{
				layout:'column',
				width: '100%',
				border: false,
				items: [{
					columnWidth: 1,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [tfPessoaPrincipal]
				},{
					columnWidth: 1,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [tfNomeConjuge]
				},{
					columnWidth: 0.5,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [cbProfissaoConjuge]
				},{
					columnWidth: 0.5,
					labelWidth: 90,
					layout: 'form',
					border: false,
					items: [tfNacionalidadeConjuge]
				},{
					columnWidth: 0.6,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [dtDataNascimentoConjuge]
				},{
					columnWidth: 0.4,
					labelWidth: 45,
					layout: 'form',
					items: [tfCpfConjuge]
				},{
					columnWidth: 0.5,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [tfIdentidadeConjuge]
				},{
					columnWidth: 0.5,
					labelWidth: 80,
					layout: 'form',
					items: [tfOrgaoExpedidorConjuge]
				},{
					columnWidth: 0.5,
					labelWidth: 110,
					layout: 'form',
					items: [tfRendaConjuge]
				},{
					columnWidth: 0.5,
					labelWidth: 120,
					layout: 'form',
					items: [tfOutroRendimentoConjuge]
				}]
			}]
				
        }],
		bbar: [
			botaoSalvar= new Ext.Button({
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
    })

	codPessoaSelecionada = codPessoa

	Ext.Ajax.request({
		url: 'modulos/usuario/gerenciar_usuario.php',
		params: { 
			acao: 'conjugeUnicoListar',
			codPessoa:  codPessoaSelecionada
		},
		callback: function(options, success, response) {
			
			var retorno = Ext.decode(response.responseText);

			if(retorno.resultado != "")
			{
				Ext.getCmp("tfNomeConjuge").setValue(retorno.resultado[0].nome);
				Ext.getCmp("cbProfissaoConjuge").setValue(retorno.resultado[0].codProfissao);
				Ext.getCmp("cbProfissaoConjuge").setRawValue(retorno.resultado[0].profissao);
				Ext.getCmp("tfNacionalidadeConjuge").setRawValue(retorno.resultado[0].nacionalidade);
				Ext.getCmp("tfCpfConjuge").setValue(retorno.resultado[0].cpf);
				Ext.getCmp("tfIdentidadeConjuge").setRawValue(retorno.resultado[0].rg);
				Ext.getCmp("tfOrgaoExpedidorConjuge").setRawValue(retorno.resultado[0].orgaoExpedidor);
				Ext.getCmp("tfRendaConjuge").setValue(separarVirgula(retorno.resultado[0].renda));
				Ext.getCmp("dtDataNascimentoConjuge").setValue(retorno.resultado[0].dataNascimento);
				Ext.getCmp("tfOutroRendimentoConjuge").setValue(separarVirgula(retorno.resultado[0].outroRendimento));
			}

		}
	})

function salvar (){
	if(	
		tfNomeConjuge.getValue() != '' &&
		cbProfissaoConjuge.getValue() != "" &&
		dtDataNascimentoConjuge.getValue() != '' &&
		tfNacionalidadeConjuge.getValue() != '' &&
		tfIdentidadeConjuge.getValue() != '' &&
		tfOrgaoExpedidorConjuge.getValue() != '' &&
		tfOrgaoExpedidor.getValue() != '' 
	){
		
		Ext.Ajax.request({
			url: 'modulos/usuario/gerenciar_usuario.php',
			params: { 
				acao                        : 'conjugeCadastrar',
				codPessoa                   : codPessoaSelecionada,
				nome                        : tfNomeConjuge.getValue(),
				codProfissao                : cbProfissaoConjuge.getValue(),
				dataNascimento              : dtDataNascimentoConjuge.getValue(),
				cpf                         : document.getElementById("tfCpfConjuge").value,
				nacionalidade               : tfNacionalidadeConjuge.getValue(),	
				rg                          : tfIdentidadeConjuge.getValue(),
				orgaoExpedidor              : tfOrgaoExpedidorConjuge.getValue(),
				renda                       : document.getElementById("tfRendaConjuge").value,
				outroRendimento             : document.getElementById("tfOutroRendimentoConjuge").value
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == false){
					msg('Informação','Problema ao cadastrar o Conjuge!');
					return false;
				}else if(retorno.renda == false){
					msg("informação", "Informe uma renda válida");
					return false;
				}else if(retorno.cpf == false){
					msg('Informação','CPF inválido');
					return false;
				}else{
					msg('Informação','Operação executada com sucesso!')
				}
			}
		})


	}else{
		msg('Informação','Existem campos obrigatórios em Branco!')
	}	
}

	win = new Ext.Window({
		title: 'Relacionar Conjuge',
		id: 'conjugeRelacionar',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		width: 500,
		iconCls: 'manterUsuario',
		modal: true,
		items:[janelaConjuge]
	})
	win.show()
	//ds.load({params:{start:0, limit:10}});
}