function dadosBancariosRelacionar(codPessoa, nomePessoa){
	
	var codPessoaSelecionada;

	var storeBanco = new Ext.data.Store({
		id: 'storeBanco',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_dado_bancario.php',
			method: 'POST'
		}),
		baseParams:{acao: "bancoListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codBanco','nomeNumero']
		},[ 
			{name: 'codBanco', type: 'int'},
			{name: 'nomeNumero', type: 'string'}

		]),
		sortInfo:{field: 'nomeNumero', direction: "ASC"}
	}) 

	storeBanco.load()
		
	//Listar Pessoas
	var storeCliente = new Ext.data.Store({
		id: 'storeCliente',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/usuario/gerenciar_usuario.php',
			method: 'POST'
		}),
		baseParams:{acao: "pessoaVwListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codProfissao','nome']
		},[ 
			{name: 'codPessoa', type: 'int'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	
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

	cbBanco = new Ext.form.ComboBox({
		id: 'cbBanco',
		typeAhead: false,
		fieldLabel: '<b>Banco</b>',
		value: '',
		mode: 'local',
		editable: false,
		anchor: '98%',
		store: storeBanco,
		displayField: 'nomeNumero',
		valueField: 'codBanco',
		forceSelection: true,
		triggerAction: 'all',
		allowBlank : false,
		blankText: 'Selecione...'
	})

	tfConta = new Ext.form.TextField({
		fieldLabel: '<b>Conta</b>',
		name: 'conta',
		id: 'tfConta',
		allowBlank:false,
		blankText:"Por favor entre com a <b>conta</b>.",
		autoCreate:{tag:'input',type:'text',maxLength: '20'},
		anchor : '60%'
	})

	tfAgencia = new Ext.form.TextField({
		fieldLabel: '<b>Agência</b>',
		name: 'agencia',
		id: 'tfAgencia',
		allowBlank:false,
		blankText:"Por favor entre com a <b>Agência</b>.",
		autoCreate:{tag:'input',type:'text',maxLength: '20'},
		anchor : '60%'
	})

	txObservacoesDadoBancario = new Ext.form.TextArea({
		fieldLabel: '<b>Observações</b>',
		name: 'obs',
		id: 'txObservacoesDadoBancario',
		allowBlank: true,
		anchor : '98%',
		height : 70
	})

	var janelaDadoBancario = new Ext.FormPanel({
        id: 'janelaDadoBancario',
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
					items: [cbBanco]
				},{
					columnWidth: 1,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [tfAgencia]
				},{
					columnWidth: 1,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [tfConta]
				},{
					columnWidth: 1,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [txObservacoesDadoBancario]
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
		
//variável global q guarda o codigo do dado bancario se ele já existir para o usuario selecionado
var codDadoBancario;
codDadobancario = 0;

function carregarDadosBancarios(){
	if(codPessoaSelecionada != "" || codPessoaSelecionada != 0){
		Ext.Ajax.request({
			url: 'modulos/diversos/gerenciar_dado_bancario.php',
			params: {
				acao:'carregarDadosBancarios',
				codPessoa: codPessoaSelecionada
			},
			callback: function(options, success, response){
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success != false){
					codDadoBancario = retorno.resultado[0].codDadoBancario;
					Ext.getCmp("cbBanco").setValue(retorno.resultado[0].banco);
					Ext.getCmp("cbBanco").setRawValue(retorno.resultado[0].codBanco);
					Ext.getCmp("tfConta").setValue(retorno.resultado[0].conta);
					Ext.getCmp("tfAgencia").setValue(retorno.resultado[0].agencia);
					Ext.getCmp("txObservacoesDadoBancario").setValue(retorno.resultado[0].observacao);
				}else{
					return;
				}
			}
		})
	}
}

	
function salvar (){
	
	if(cbBanco.getValue() != '' && tfConta.getValue() != '' && tfAgencia.getValue() != '' ){
	
		if(codDadoBancario == ""){
			codDadoBancario = '0';
		}

		Ext.Ajax.request({
			url: 'modulos/diversos/gerenciar_dado_bancario.php',
			params: { 
				acao                        : 'dadosBancariosCadastrar',
				codDadoBancario		    : codDadoBancario,
				codPessoa                   : codPessoaSelecionada,
				codBanco		    : cbBanco.getValue(),
				tfConta			    : tfConta.getValue(),
				tfAgencia		    : tfAgencia.getValue(),
				txObservacoesDadoBancario   : txObservacoesDadoBancario.getValue()
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == false){
					msg('Informação','Problema ao cadastrar os dados!');
				}else{
					msg('Informação','Operação executada com sucesso!');
				}
			}
		})
	}else{
		msg('Informação','Existem campos obrigatórios em Branco!')
	}	
}

	win = new Ext.Window({
		title: 'Relacionar Dados Bancários',
		id: 'dadoBancarioRelacionar',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		width: 500,
		iconCls: 'manterUsuario',
		modal: true,
		items:[janelaDadoBancario]
	})
	win.show();
	carregarDadosBancarios();
}