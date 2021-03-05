function gerenciarImovel(){
	var codImovelSelecionado = 0


	var msg = function(title, msg){
		Ext.Msg.show({
			title: title, 
			msg: msg,
			minWidth: 200,
			modal: true,
			icon: Ext.Msg.INFO,
			buttons: Ext.Msg.OK
		});
	};

	//Tipo de Imóvel
	var storeTipoImovel = new Ext.data.Store({
		id: 'storeTipoImovel',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/imovel/gerenciar_imovel.php',
			method: 'POST'
		}),
		baseParams:{acao: "listarTipoImovel"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codTipoImovel','nome']
		},[ 
			{name: 'codTipoImovel', type: 'int'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	storeTipoImovel.load()
	
	//Proprietario
	var storeProprietario = new Ext.data.Store({
		id: 'storeProprietario',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/usuario/gerenciar_usuario.php',
			method: 'POST'
		}),
		baseParams:{acao: "proprietarioListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codPessoa','nome']
		},[ 
			{name: 'codPessoa', type: 'int'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 
	storeProprietario.load()

	var ufStore = new Ext.data.Store({
		id: 'ufStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_cidade.php',
			method: 'POST'
		}),
		baseParams:{acao: "UFListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codUf','uf']
		},[ 
			{name: 'codUf', type: 'int'},
			{name: 'uf', type: 'string'}
		]),
		sortInfo:{field: 'uf', direction: "ASC"}
	}) 
	ufStore.load()
		
	//Tipo de Serviço
	var storeTipoServico = new Ext.data.Store({
		id: 'storeTipoServico',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/servico/gerenciar_servico.php',
			method: 'POST'
		}),
		baseParams:{acao: "servicoListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codServico','nome']
		},[ 
			{name: 'codTipoServico', type: 'int'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	storeTipoServico.load()	

	function imovelEditar(value){
		return '<center><img src="img/ic_editar.png" /></center>'
	}

	function imovelExcluir(value){
		if(value == 1){
			return '<center><img src="img/ic_desativar.png" /></center>'
		}else{
			return '<center><img src="img/ic_ativar.png" /></center>'
		}
	}

	function verificarAtivo(value){
		if(value == true){
			return 'sim'
		}else{
			return 'não'
		}
	}

	imovelStore = new Ext.data.Store({
		id: 'imovelStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/imovel/gerenciar_imovel.php',
			method: 'POST'
		}),
		baseParams:{acao: "imovelListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codImovel']
		},[ 
			{name: 'codImovel', type: 'int', mapping: 'codImovel'},
			{name: 'codPessoa', type: 'int', mapping: 'codPessoa'},
			{name: 'codTipoImovel', type: 'int', mapping: 'codTipoImovel'},
			{name: 'codTipoServico', type: 'int', mapping: 'codTipoServico'},
			{name: 'tipoImovel', type: 'string', mapping: 'tipoImovel'},
			{name: 'tipoServico', type: 'string', mapping: 'tipoServico'},
			{name: 'endereco', type: 'string', mapping: 'endereco'},
			{name: 'bairro', type: 'string', mapping: 'bairro'},
			{name: 'cep', type: 'string', mapping: 'cep'},
			{name: 'cidade', type: 'string', mapping: 'cidade'},
			{name: 'proprietario', type: 'string', mapping: 'proprietario'},
			{name: 'valorBase', type: 'float', mapping: 'valorBase'},
			{name: 'latitude', type: 'string', mapping: 'latitude'},
			{name: 'longitude', type: 'string', mapping: 'longitude'},
			{name: 'condominio', type: 'float', mapping: 'condominio'},
			{name: 'valor', type: 'float', mapping: 'valor'},
			{name: 'dce', type: 'int', mapping: 'dce'},		
			{name: 'uf', type: 'char', mapping: 'uf'},		
			{name: 'qtdQuarto', type: 'int', mapping: 'qtdQuarto'},
			{name: 'qtdSuite', type: 'int', mapping: 'qtdSuite'},
			{name: 'qtdBanheiro', type: 'int', mapping: 'qtdBanheiro'},
			{name: 'qtdGaragem', type: 'int', mapping: 'qtdGaragem'},
			{name: 'areaPrivativa', type: 'float', mapping: 'areaPrivativa'},
			{name: 'areaComum', type: 'float', mapping: 'areaComum'},
			{name: 'qtdSuite', type: 'int', mapping: 'qtdSuite'},
			{name: 'nIptu', type: 'string', mapping: 'nIptu'},
			{name: 'nCeb', type: 'string', mapping: 'nCeb'},
			{name: 'nCaesb', type: 'string', mapping: 'nCaesb'},
			{name: 'telefoneSindico', type: 'string', mapping: 'telefoneSindico'},
			{name: 'telefoneCondominio', type: 'string', mapping: 'telefoneCondominio'},
			{name: 'observacao', type: 'string', mapping: 'observacao'},
			{name: 'status', type: 'int', mapping: 'status'}
		])
	}) 
	
	imovelColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'codImovel',
	        dataIndex: 'codImovel', 
	        width: 100,
			readOnly: false,
			hidden: true
	      },{
	        header: 'Tipo',
	        dataIndex: 'tipoImovel', 
	        width: 80,
			readOnly: false
	      },{
	        header: 'Endereço',
	        dataIndex: 'endereco', 
	        width: 100,
			readOnly: true
	      },{
	        header: 'bairro',
	        dataIndex: 'bairro', 
	        width: 100,
			readOnly: true
	      },{
	        header: 'Cep',
	        dataIndex: 'cep', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Cidade',
	        dataIndex: 'cidade', 
	        width: 100,
			readOnly: true
	      },{
	        header: 'Valor',
	        dataIndex: 'valorBase', 
	        width: 100,
			readOnly: true
	      },{
	        header: 'Latitude',
	        dataIndex: 'latitude', 
	        width: 100,
			readOnly: true
	      },{
	        header: 'Longitude',
	        dataIndex: 'longitude', 
	        width: 100,
			readOnly: true
	      },{
	        header: 'Condominio',
	        dataIndex: 'condominio', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Área Privativa',
	        dataIndex: 'areaPrivativa', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Área Comum',
	        dataIndex: 'areaComum', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Qtd Garagem',
	        dataIndex: 'qtdGaragem', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Qtd Quarto',
	        dataIndex: 'qtdQuarto', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Qtd Suíte',
	        dataIndex: 'qtdSuite', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Qtd Banheiro',
	        dataIndex: 'qtdBanheiro', 
	        width: 100,
			readOnly: true,
			hidden: true
	      },{
	        header: 'Excluir',
	        dataIndex: 'status', 
	        width: 50,
			renderer: imovelExcluir,
			readOnly: true
	      },{
	        header: 'Observação',
	        dataIndex: 'observacao', 
	        width: 50,
			hidden: true,
			readOnly: true
	      },{
	        header: 'Telefone Síndico',
	        dataIndex: 'telefoneSindico', 
	        width: 50,
			hidden: true,
			readOnly: true
	      },{
	        header: 'Telefone Condomínio',
	        dataIndex: 'telefoneCondominio', 
	        width: 50,
			hidden: true,
			readOnly: true
	      },{
	        header: 'Status',
	        dataIndex: 'status', 
	        width: 50,
			hidden: true,
			readOnly: true
	      }
	])	
	imovelColuna.defaultSortable = true

	cbTipoImovel = new Ext.form.ComboBox({
		id: 'cbTipoImovel',
		typeAhead: false,
		fieldLabel: '<b>Tipo de Imóvel</b>',
		mode: 'local',
		editable: false,
		anchor: '60%',
		store: storeTipoImovel,
		displayField: 'nome',
		valueField: 'codTipoImovel',
		forceSelection: true,
		triggerAction: 'all',
		emptyText:'Selecione...',
		allowBlank : false,
		blankText: 'Selecione...'
	})

	cbProprietario = new Ext.form.ComboBox({
		id: 'cbProprietario',
		typeAhead: false,
		fieldLabel: '<b>Proprietário</b>',
		//value: '',
		mode: 'local',
		editable: false,
		anchor: '98%',
		store: storeProprietario,
		displayField: 'nome',
		valueField: 'codPessoa',
		forceSelection: true,
		triggerAction: 'all',
		emptyText:'Selecione...',
		allowBlank : false,
		blankText: 'Selecione...'
	})

	cbTipoServico = new Ext.form.ComboBox({
		id: 'cbTipoServico',
		typeAhead: false,
		fieldLabel: '<b>Tipo de Serviço</b>',
		//value: '',
		mode: 'local',
		editable: false,
		anchor: '50%',
		store: storeTipoServico,
		displayField: 'nome',
		valueField: 'codTipoServico',
		forceSelection: true,
		triggerAction: 'all',
		emptyText:'Selecione...',
		allowBlank : false,
		blankText: 'Selecione...'

	})

	txEndereco = new Ext.form.TextArea({
		fieldLabel: '<b>Endereço</b>',
		name: 'endereco',
		id: 'txEndereco',
		allowBlank:false,
		blankText:"Por favor entre com o endereço do imóvel.",
		maxLength: 100,
		anchor : '98%',
		height : 50
	})

	txObservacoes = new Ext.form.TextArea({
		fieldLabel: '<b>Observações</b>',
		name: 'observacao',
		id: 'txObservacoes',
		allowBlank: true,
		maxLength: 100,
		anchor : '98%',
		height : 70
	})

	tfBairro = new Ext.form.TextField({
		fieldLabel: '<b>Bairro</b>',
		name: 'bairro',
		id: 'tfBairro',
		allowBlank:false,
		blankText:"Por favor insira o bairro.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
		anchor : '98%'
	})

	tfCep = {
		xtype:'masktextfield',
		mask: '99999-999',
		money: false,
		fieldLabel: '<b>Cep</b>',
		name: 'cep',
		id: 'tfCep',
		allowBlank:false,
		blankText:"Por favor insira o CEP.",
		//autoCreate:{tag:'input',type:'text',maxlength:8},
		anchor : '98%'
	}
		
	cbUf = new Ext.form.ComboBox({
		xtype: 'combo',
		id: 'cbUf',
		typeAhead: false,
		name: 'uf',
		fieldLabel: '<b>UF</b>',
		value: 'DF',
		mode: 'local',
		editable: false,
		store: ufStore,
		displayField: 'uf',
		valueField: 'codUf',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '95%',
		allowBlank : false,
		blankText: 'Selecione...'
	})
	
	cidadeStore = new Ext.data.Store({
		id: 'cidadeStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_cidade.php',
			method: 'POST'
		}),
		baseParams:{
				acao: "cidadeListar",
				codUf: cbUf.getValue()
		},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codCidade,cidade']
		},[ 
			{name: 'codCidade', type: 'int'},
			{name: 'cidade', type: 'string'},
		])
	}) 
	
	cbCidade = new Ext.form.ComboBox({
		xtype: 'combo',
		id: 'cbCidade',
		typeAhead: false,
		name: 'cidade',
		fieldLabel: '<b>Cidade</b>',
		value: '',
		mode: 'local',
		editable: false,
		store:cidadeStore,
		displayField: 'cidade',
		valueField: 'codCidade',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '95%',
		emptyText: "selecione.."
	})
	//carregar as cidades do df como default
	cidadeStore.load({params: {acao : "cidadeListar", codUf : 6}});
	
	//habilitar combo da cidade quando um uf for selecionado e listar as cidades
	cbUf.on('select', function() {
		if(cbUf.getValue() != 0){
			cbCidade.enable();
			cbCidade.reset();
			cidadeStore.load({params: {acao : "cidadeListar", codUf : cbUf.getValue()}});
		}else{
			tfCidade.disable();
		}
	});

	tfNIptu = new Ext.form.TextField({
		fieldLabel: '<b>Nº IPTU</b>',
		name: 'nIptu',
		id: 'tfNIptu',
		allowBlank:false,
		boxLabel: '(m2)',
		autoCreate: {tag: 'input', type: 'text', maxlength: '14'},
		blankText:"Por favor insira o número do IPTU.",
		anchor : '95%'
	})
		
	tfNCeb = new Ext.form.TextField({
		fieldLabel: '<b>Nº CEB</b>',
		name: 'nCeb',
		id: 'tfNCeb',
		allowBlank:false,
		boxLabel: '(m2)',
		autoCreate: {tag: 'input', type: 'text', maxlength: '14'},
		blankText:"Por favor insira o número da CEB.",
		anchor : '95%'
	})
		
	tfNCaesb = new Ext.form.TextField({
		fieldLabel: '<b>Nº CAESB</b>',
		name: 'nCaesb',
		id: 'tfNCaesb',
		allowBlank:false,
		boxLabel: '(m2)',
		autoCreate: {tag: 'input', type: 'text', maxlength: '14'},
		blankText:"Por favor insira o número da CAESB.",
		anchor : '95%'
	})
			
	tfAreaPrivativa = new Ext.form.NumberField({
		fieldLabel: '<b>Área Privativa</b>',
		name: 'areaPrivativa',
		id: 'tfAreaPrivativa',
		//autoCreate: {tag: 'input', type: 'text', maxlength: '8'},
		allowBlank:false,
		boxLabel: '(m2)',
		blankText:"Por favor insira a cidade.",
		anchor : '95%'
	})

	tfAreaComum = new Ext.form.NumberField({
		fieldLabel: '<b>Área Comum</b>',
		name: 'areaComum',
		id: 'tfAreaComum',
		allowBlank:false,
		boxLabel: '(m2)',
		//autoCreate: {tag: 'input', type: 'text', maxlength: '8'},
		blankText:"Por favor insira a cidade.",
		anchor : '95%'
	})

	tfDce = new Ext.form.NumberField({
		fieldLabel: '<b>DCE</b>',
		name: 'dce',
		id: 'tfDce',
		allowBlank:false,
		blankText:"Por favor insira a DCE.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '3'},
		anchor : '95%'
	})

	tfValor = {
		xtype: 'masktextfield',
		mask: 'R$ #9.999.990,00',
		money: true,
		fieldLabel: '<b>Valor</b>',
		name: 'valorBase',
		id: 'tfValor',
		allowBlank:false,
		blankText:"Por favor insira o valor.",
		anchor : '95%'
	}

	tfLatitude = new Ext.form.TextField({
		fieldLabel: '<b>Latitude</b>',
		name: 'latitude',
		id: 'tfLatitude',
		allowBlank:false,
		blankText:"Por favor insira a latitude.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
		anchor : '95%'
	})

	tfLongitude = new Ext.form.TextField({
		fieldLabel: '<b>Longitude</b>',
		name: 'longitude',
		id: 'tfLongitude',
		allowBlank:false,
		blankText:"Por favor insira a longitude.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
		anchor : '95%'
	})

	tfCondominio = {
		xtype: 'masktextfield',
		mask: 'R$ #9.999.990,00',
		money: true,
		fieldLabel: '<b>Condomínio</b>',
		name: 'condominio',
		id: 'tfCondominio',
		allowBlank:false,
		blankText:"Por favor insira o valor do condomínio.",
		anchor : '95%'
	}

	tfQuartos = new Ext.form.NumberField({
		fieldLabel: '<b>Nº de Quartos</b>',
		name: 'qtdQuarto',
		id: 'tfQuartos',
		allowBlank:false,
		autoCreate: {tag: 'input', type: 'text', maxlength: '3'},
		blankText:"Por favor insira a quantidade quartos.",
		anchor : '95%'
	})

	tfSuites = new Ext.form.NumberField({
		fieldLabel: '<b>Nº de Suítes</b>',
		name: 'qtdSuite',
		id: 'tfSuites',
		allowBlank:false,
		autoCreate: {tag: 'input', type: 'text', maxlength: '3'},
		blankText:"Por favor insira a quantidade suítes.",
		anchor : '95%'
	})

	tfBanheiros = new Ext.form.NumberField({
		fieldLabel: '<b>Banheiros</b>',
		name: 'qtdBanheiro',
		id: 'tfBanheiros',
		allowBlank:false,
		autoCreate: {tag: 'input', type: 'text', maxlength: '3'},
		blankText:"Por favor insira a quantidade banheiros.",
		anchor : '95%'
	})

	tfGaragem = new Ext.form.NumberField({
		fieldLabel: '<b>Vagas Garagem</b>',
		name: 'qtdGaragem',
		id: 'tfGaragem',
		allowBlank:false,
		autoCreate: {tag: 'input', type: 'text', maxlength: '3'},
		blankText:"Por favor insira a quantidade de vagas.",
		anchor : '95%'
	})

	tfTelefoneCondominio = {
		xtype: 'masktextfield',
		mask: '(99) 9999-9999',
		money: false,
		fieldLabel: '<b>Tel Condomínio</b>',
		name: 'telefoneCondominio',
		id: 'tfTelefoneCondominio',
		allowBlank:false,
		blankText:"Por favor insira o telefone do condomínio.",
		anchor : '95%'
	}

	tfTelefoneSindico = {
		xtype: 'masktextfield',
		mask: '(99) 9999-9999',
		money: false,
		fieldLabel: '<b>Tel Sindíco</b>',
		name: 'telefoneSindico',
		id: 'tfTelefoneSindico',
		allowBlank:false,
		blankText:"Por favor insira o telefone do síndico.",
		anchor : '95%'
	}

	function manterImovel(coluna,statusImovel,codImovel){
		if(coluna == 14){
			if(statusImovel == 1){
				if(confirm('Tem certeza que deseja desativar esse imóvel?')){
					Ext.Ajax.request({
						url: 'modulos/imovel/gerenciar_imovel.php',
						params: { 
							acao: 'imovelDesativar',
							codImovel:  codImovel
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								Ext.MessageBox.alert('ok')
							}else{
								imovelStore.reload()
								imovelGrid.getForm().reset()
							}
						}
					})
				}
			}else{
				if(confirm('Tem certeza que deseja ativar esse imóvel?')){
					Ext.Ajax.request({
						url: 'modulos/imovel/gerenciar_imovel.php',
						params: { 
							acao: 'imovelAtivar',
							codImovel:  codImovel
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								imovelStore.reload()
							}
						}
					})
				}
			}
		}
	
	}

	var gridListaImoveis = new Ext.grid.GridPanel({
		id: 'gridListaImoveis',
		ds: imovelStore,
		cm: imovelColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				//Verifica se é a coluna de exclusão
				var dados = grid.store.getAt(linha);
				var codImovel = dados.get('codImovel')
				var statusImovel = dados.get('status')
				manterImovel(coluna, statusImovel,codImovel)
				
			},
			rowcontextmenu: function(grid,rowIndex, e){
				e.stopEvent()
				var acao;
				var dados = grid.store.getAt( rowIndex );
				var codImovel = dados.get('codImovel')
				
				if(dados.get('status') == 1){
					acao = 'Desativar';
					status = 'botaoDesativar'; 
				}else{
					acao = 'Ativar';
					status = 'botaoAtivar'; 
				}
				
				var contextMenu = new Ext.menu.Menu();
				contextMenu.add({
					text: acao,
					iconCls: status,
					handler: function (){
						manterImovel('13', dados.get('status'),dados.get('codImovel'))
					}
				},{
					text: 'Procuração',
					iconCls:'botaoImprimir',
					handler: function (){
						window.location = 'modulos/contratos/Procuracao_Modelo_imovel.php?codImovel=' + codImovel;
					}
				},{
					text: 'Contrato de Administração',
					iconCls:'botaoImprimir',
					handler: function (){
						window.location = 'modulos/contratos/Contrato_Administracao_Modelo_imovel.php?codImovel=' + codImovel;
					}
				},{
					text: 'Contrato Administração Garantido',
					iconCls:'botaoImprimir',
					handler: function (){
						window.location = 'modulos/contratos/Contrato_Administracao_Garantia_Modelo_imovel.php?codImovel=' + codImovel;
					}
				});
				
				contextMenu.showAt(e.xy);
			}
		},
		viewConfig: {
			forceFit: true,
			getRowClass: function(record, rowIndex, rp, ds){
				if(record.data.status == '0'){
					return 'linhaDesativada'
				}
			}
		}, 
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: true,
			listeners: {
				rowselect: function(sm, row, rec) {
					codImovelSelecionado = rec.data.codImovel
					Ext.getCmp("imovelGrid").getForm().loadRecord(rec)
					Ext.getCmp("cbTipoImovel").setRawValue(rec.data.tipoImovel)
					Ext.getCmp("cbTipoImovel").setValue(rec.data.codTipoImovel)
					Ext.getCmp("cbTipoServico").setRawValue(rec.data.tipoServico)
					Ext.getCmp("cbTipoServico").setValue(rec.data.codTipoServico)
					Ext.getCmp("cbProprietario").setRawValue(rec.data.proprietario)
					Ext.getCmp("cbProprietario").setValue(rec.data.codPessoa)
				}			
			}
		}),
		autoExpandColumn: 'codImovel',
		height: 442,
		border: true
	})

	var imovelGrid = new Ext.FormPanel({
        id: 'imovelGrid',
        frame: true,
		autoHeight: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
			columnWidth: 0.5,
			layout: 'fit',
			items: [gridListaImoveis]
        },{
        	columnWidth: 0.50,
            xtype: 'fieldset',
			style: 'margin: 0px 5px 5px 5px;',
            title:'Cadastro de Imóvel', 
			bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
            items:[{
				layout:'column',
				width: '100%',
				border: false,
				items: [{	
					columnWidth: 1,
					labelWidth: 100,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [cbProprietario, cbTipoImovel, cbTipoServico, txEndereco]
				},{
					columnWidth: 0.75,
					labelWidth: 100,
					layout: 'form',
					border: false,
					items: [tfBairro]
				},{
					columnWidth: 0.25,
					labelWidth: 40,
					layout: 'form',
					border: false,
					items: [tfCep]
				},{
					columnWidth: 0.4,
					labelWidth: 100,
					layout: 'form',
					items: [cbUf]
				},{
					columnWidth: 0.60,
					labelWidth: 50,
					layout: 'form',
					border: false,
					items: [cbCidade]
				},{
					columnWidth: 0.4,
					labelWidth: 100,
					layout: 'form',
					border: false,
					items: [tfAreaPrivativa]
				},{
					columnWidth: 0.4,
					labelWidth: 90,
					layout: 'form',
					items: [tfAreaComum]
				},{
					columnWidth: 0.2,
					labelWidth: 30,
					layout: 'form',
					items: [tfDce]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfLatitude]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfLongitude]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfValor]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfCondominio]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfQuartos]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfSuites]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfBanheiros]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfGaragem]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfTelefoneCondominio]
				},{
					columnWidth: 0.5,
					labelWidth: 100,
					layout: 'form',
					items: [tfTelefoneSindico]
				},{
					columnWidth: 0.4,
					labelWidth: 100,
					layout: 'form',
					border: false,
					items: [tfNIptu]
				},{
					columnWidth: 0.3,
					labelWidth: 50,
					layout: 'form',
					items: [tfNCeb]
				},{
					columnWidth: 0.3,
					labelWidth: 65,
					layout: 'form',
					items: [tfNCaesb]
				},{
					columnWidth: 1,
					labelWidth: 100,
					layout: 'form',
					items: [txObservacoes]
				}]
			}]
				
        }],	
		bbar: ['->',
			
			botaoNovo = new Ext.Button({
				id: 'botaoNovo',
				text: 'Novo',
				tooltip: 'Novo',
				handler: novo,   
				iconCls:'botaoNovo'
			}),'-',

			botaoSalvar= new Ext.Button({
				id: 'botaoSalvar',
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
    })

function novo(){
	codImovelSelecionado = 0
	imovelStore.load()
	imovelGrid.getForm().reset()
}	

function salvar (){

	//validando cep
	var cep = document.getElementById("tfCep").value;
	cep = cep.replace("_","");
	
	if( cep.length < 9){
		alert("CEP inválido");
		return false;
	}	
		
	if( //verifica se algum valor é vazio
		cbTipoImovel.getValue() != "" &&
		cbTipoServico.getValue() != "" &&
		cbProprietario.getValue() != "" &&
		txEndereco.getValue() != "" &&
		tfBairro.getValue() != "" &&
		document.getElementById("tfCep").value &&
		cbCidade.getRawValue() != "" &&
		cbUf.getRawValue() != "" &&
		document.getElementById("tfAreaPrivativa").value &&
		document.getElementById("tfAreaComum").value &&
		document.getElementById("tfDce").value &&
		document.getElementById('tfValor').value != "" &&
		document.getElementById('tfCondominio').value != "" &&
		tfQuartos.getValue().toString() != "" &&
		tfSuites.getValue().toString() != "" &&
		tfBanheiros.getValue().toString() != "" &&
		document.getElementById('tfTelefoneCondominio').value != "" &&
		document.getElementById('tfTelefoneSindico').value != "" &&
		tfNIptu.getValue() != "" &&
		tfNCeb.getValue() != "" &&
		tfNCaesb.getValue() != "" &&
		tfGaragem.getValue().toString() != "" 
	){
		Ext.Ajax.request({
			url: 'modulos/imovel/gerenciar_imovel.php',
			params: { 
				acao			: 'imovelCadastrar',
				codImovel		: codImovelSelecionado,
				codTipoImovel 		: cbTipoImovel.getValue(),
				codTipoServico 		: cbTipoServico.getValue(),
				codProprietario		:  cbProprietario.getValue(),
				endereco		: txEndereco.getValue(),
				bairro			: tfBairro.getValue(),
				cep			: document.getElementById("tfCep").value,
				cidade			: cbCidade.getRawValue(),
				uf			: cbUf.getRawValue(),
				areaPrivativa		: document.getElementById("tfAreaPrivativa").value,
				areaComum		: document.getElementById("tfAreaComum").value,
				dce			: document.getElementById("tfDce").value,
				valor			: document.getElementById('tfValor').value,
				latitude		: document.getElementById('tfLatitude').value,
				longitude		: document.getElementById('tfLongitude').value,
				condominio		: document.getElementById('tfCondominio').value,
				quartos			: tfQuartos.getValue(),
				suites			: tfSuites.getValue(),
				banheiros		: tfBanheiros.getValue(),
				telefoneCondominio	: document.getElementById('tfTelefoneCondominio').value,			
				telefoneSindico   	: document.getElementById('tfTelefoneSindico').value,
				nIptu             	: tfNIptu.getValue(),
				nCeb              	: tfNCeb.getValue(),
				nCaesb            	: tfNCaesb.getValue(),
				garagem			: tfGaragem.getValue(),
				observacao		: txObservacoes.getValue()
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == true){
					msg('Informação','Operação executada com sucesso!');
					novo();
					codImovelSelecionado = 0;
					imovelGrid.getForm().reset();
				}else{
					msg('Informação','Problema ao cadastrar o imóvel!')
				}
			}
		})
	}else{
		msg('Informação','Existem campos obrigatórios em Branco!');
	}
}

	imovelStore.load({params: {start: 0, limit: 15}})
	
	var janelaCadastrarImovel = new Ext.Window({
		title: 'Gerenciar Imóvel',
		id: 'janelaCadastrarImovel',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: true,
		autoHeight: true,
		width: 1000,
		anchor: 50,
		height: 510,
		closeAction:'close',
		iconCls: 'ic_janela_base',
		modal: true,
		modal: true,
		items:[imovelGrid]
	})
	janelaCadastrarImovel.show()
}