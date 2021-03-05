var codRemotoSelecionado = 0
var codLicencaSelecionada = 0
var codNocSelecionado = 0
var nomeRemotoSelecionado = ''
var statusLicenca = 0
							
function gerenciarLicenca(){
	var clientesStore   
	var clientesStoreColunas
	var mRemoteListingEditorGrid
	var MonitorRemoteListingWindow
	var chkDataInicial
	var chkDataFinal
	var dataInicio
	var dataFim

	function formatoData(value){
		novaData = new Date(value)
		return novaData.dateFormat('d/m/Y')
	}

	licencaStore = new Ext.data.Store({
		id: 'licencaStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/gerenciamento/gerenciar_licenca.php',
			method: 'POST'
		}),
		baseParams:{task: "listar_licenca", codRemoto: 0, tipoListagem: 2 }, 
		reader: new Ext.data.JsonReader({
			root: 'results',
			totalProperty: 'total',
			id: ['codLicenca']
		},[ 
			{name: 'codLicenca', type: 'int', mapping: 'codLicenca'},
			{name: 'tipoLicenca', type: 'string', mapping: 'tipoLicenca'},
			{name: 'dataInicio', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataInicio'},
			{name: 'dataFim', type:'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataFim'},
			{name: 'responsavel', type: 'string', mapping: 'responsavel'},
			{name: 'qtdHost', type: 'string', mapping: 'qtdHost'},
			{name: 'ativo', type: 'int', mapping: 'ativo'}

		]),
			sortInfo:{field: 'ativo', direction: "DESC"}
	}) 

	licencaStoreColunas = new Ext.grid.ColumnModel(
		[{
			header: 'Licenca',
			dataIndex: 'codLicenca', 
			width: 50,
			readOnly: true
		},{	
			id:'Tipo',
			header: 'Tipo',
			dataIndex: 'tipoLicenca', 
			width: 40
		},{	
			header: 'Data Inicio',
			dataIndex: 'dataInicio', 
			width: 110,
			renderer: formatoData
	    },{	
			header: 'Data Fim',
			dataIndex: 'dataFim', 
			width: 110,
			renderer: formatoData
	    },{	
			id:'responsavel',
			header: 'Responsavel',
			dataIndex: 'responsavel', 
			width: 80
	    },{	
			id:'qtdHost',
			header: 'Hosts',
			dataIndex: 'qtdHost', 
			width: 40
	    }
	])	
	licencaStoreColunas.defaultSortable = true	


	clienteStore = new Ext.data.Store({
		id: 'clientesStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/gerenciamento/gerenciar_licenca.php',      // File to connect to
			method: 'POST'
		}),
		baseParams:{task: "listar_clientes"}, // this parameter asks for listing
		reader: new Ext.data.JsonReader({   // we tell the datastore where to get his data from
			root: 'results',
			totalProperty: 'total',
			id: ['codNoc','codRemoto']
		},[ 
			{name: 'codNoc', type: 'string', mapping: 'codNoc'},
			{name: 'codRemoto', type: 'int', mapping: 'codRemoto'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'nomeBase', type: 'string', mapping: 'nomeBase'}
		]),
			sortInfo:{field: 'nome', direction: "ASC"}
	}) 
	
	clienteStoreColunas = new Ext.grid.ColumnModel(
		[{
			header: 'Cód',
			dataIndex: 'codRemoto', 
			width: 30,
			readOnly: true
		},{	
			id:'codNoc',
			header: 'NOC',
			dataIndex: 'codNoc', 
			width: 80,
			hidden: true
		},{	
			id:'Nome',
			header: 'Nome',
			dataIndex: 'nome', 
			width: 80
		},{	
			id:'nome Base',
			header: 'Nome Base',
			dataIndex: 'nomeBase', 
			width: 80
	    }
	])	
	clienteStoreColunas.defaultSortable = true	

	function MudaCor(row, index) {
      if (row.data.change < 0) { // change é o nome do campo usado como referência
         return 'cor'
      }
   }
	
	
	
	var gridClientes = new Ext.FormPanel({
        id: 'gridClientes',
        frame: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
            columnWidth: 0.3,
            layout: 'fit',
			style: {
                "margin-right": "10px"
            },
            items: {
	            xtype: 'grid',
	            ds: clienteStore,
	            cm: clienteStoreColunas,
	            sm: new Ext.grid.RowSelectionModel({
	                singleSelect: true,
	                listeners: {
		                rowselect: function(sm, row, rec) {
							Ext.getCmp("gridClientes").getForm().loadRecord(rec)
							codNocSelecionado = rec.data.codNoc
							codRemotoSelecionado = rec.data.codRemoto
							nomeRemotoSelecionado = rec.data.nome
							
							licencaStore.baseParams = {
								task: "listar_licenca", 
								codNoc: codNocSelecionado,
								codRemoto: codRemotoSelecionado
							}

							licencaStore.reload()
							
	                    }			
	                }
	            }),
	            autoExpandColumn: 'Nome',
	            height: 417,
	            title:'Clientes',
	            border: true,
		        listeners: {
		        	render: function(g) {
		        		g.getSelectionModel().selectRow(0)
		        	},
		        	delay: 10
		        }
        	}
        },{
            columnWidth: 0.7,
            layout: 'fit',
			id: 'gridLicencas',
			
            items: {
	            xtype: 'grid',
	            ds: licencaStore,
	            cm: licencaStoreColunas,
				viewConfig: {
					forceFit: true,
					getRowClass: function(record, rowIndex, rp, ds){ // rp = rowParams
						if(record.data.ativo == 0){
							return 'licencaDesativada'
						}
					}
				},   
	            sm: new Ext.grid.RowSelectionModel({
	                singleSelect: true,
	                listeners: {
		                rowselect: function(sm, row, rec) {
							
	                    }		
	                }
	            })
				,
	            autoExpandColumn: 'Tipo',
	            height: 417,
	            title:'Gerenciamento de Ativos',
	            border: true,
		        listeners: {
		        	render: function(g) {
		        		g.getSelectionModel().selectRow(0)
		        	},
		        	delay: 10
		        }
        	}
        }],	
		bbar: [
			
			botaoNovo = new Ext.Button({
				id: 'botaoNovo',
				text: 'Gerenciar Ativos',
				tooltip: 'Gerenciar Ativos',
				iconCls:'ic_gerenciar_ativos',
				handler: function(){
					if(!document.getElementById('janelaCadastrarLicenca')){
						cadastrarLicenca()
					}else{
						janelaCadastrarLicenca.close()
						cadastrarLicenca()
					}
				}
					
			})
		],				
        renderTo: 'cadastro'
    })
	clienteStore.load()



	var janelaGerenciarLicenca = new Ext.Window({
		title: 'Controlar Bases',
		id: 'janelaGerenciarLicenca',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: true,
		width: 850,
		anchor: 50,
		height: 490,
		iconCls: 'ic_licenca',
		items:[gridClientes]
	})
	janelaGerenciarLicenca.show()

}


function cadastrarLicenca(){
		
	storeTipoLicenca = new Ext.data.Store({
		id: 'storeTipoLicenca',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/gerenciamento/listar_tipo_licenca.php',
			method: 'POST'
		}),
		baseParams:{},
		reader: new Ext.data.JsonReader({  
			root: 'rows',
			id: ['codTipoLicenca','tipo']
		},[ 
			{name: 'codTipoLicenca', type: 'int'},
			{name: 'tipo', type: 'string'}

		]),
		sortInfo:{field: 'codTipoLicenca', direction: "ASC"}
	}) 

	storeTipoLicenca.load()
		
	storeBase = new Ext.data.Store({
		id: 'storeNoc',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/gerenciamento/listar_remotos_noc_principal.php',      // File to connect to
			method: 'POST'
		}),
		baseParams:{task: "listar_noc"}, // this parameter asks for listing
		reader: new Ext.data.JsonReader({   // we tell the datastore where to get his data from
			root: 'rows',
			id: ['codRemoto','nome']
		},[ 
			{name: 'codRemoto', type: 'string'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	storeBase.load()

	function formatoData(value){
		novaData = new Date(value)
		return novaData.dateFormat('d/m/Y')
	}

	licencaStoreCadastrar = new Ext.data.Store({
		id: 'licencaStoreCadastrar',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/gerenciamento/gerenciar_licenca.php',
			method: 'POST'
		}),
		baseParams:{task: "listar_licenca", codNoc: codNocSelecionado, codRemoto: codRemotoSelecionado}, 
		reader: new Ext.data.JsonReader({
			root: 'results',
			totalProperty: 'total',
			id: ['codLicenca']
		},[ 
			{name: 'codLicenca', type: 'int', mapping: 'codLicenca'},
			{name: 'nomeBase', type: 'string', mapping: 'nomeBase'},
			{name: 'codTipoLicenca', type: 'int', mapping: 'codTipoLicenca'},
			{name: 'tipoLicenca', type: 'string', mapping: 'tipoLicenca'},
			{name: 'dataInicio', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataInicio'},
			{name: 'dataFim', type:'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataFim'},
			{name: 'responsavel', type: 'string', mapping: 'responsavel'},
			{name: 'qtdHost', type: 'string', mapping: 'qtdHost'},
			{name: 'ilimitado', type: 'boolean', mapping: 'ilimitado'},
			{name: 'periodo', type: 'boolean', mapping: 'periodo'},
			{name: 'ativo', type: 'boolean', mapping: 'ativo'}

		]),
		sortInfo:{field: 'ativo', direction: "DESC"}
	}) 

	licencaStoreColunasCadastrar = new Ext.grid.ColumnModel(
		[{
			header: 'Licenca',
			dataIndex: 'codLicenca', 
			width: 50,
			readOnly: true
		},{
			header: 'nomeBase',
			dataIndex: 'nomeBase', 
			width: 50,
			readOnly: true,
			hidden: true
		},{	
			id:'codTipo',
			header: 'codTipo',
			dataIndex: 'codTipoLicenca', 
			width: 40,
			hidden: true
		},{	
			id:'tipo',
			header: 'Tipo',
			dataIndex: 'tipoLicenca', 
			width: 40
		},{	
			header: 'Data Inicio',
			dataIndex: 'dataInicio', 
			width: 80,
			renderer: formatoData
	    },{	
			header: 'Periodo',
			dataIndex: 'periodo', 
			width: 80,
			hidden: true
	    },{	
			header: 'ilimitado',
			dataIndex: 'ilimitado', 
			width: 80,
			hidden: true
	    },{	
			header: 'Data Fim',
			dataIndex: 'dataFim', 
			width: 80,
			renderer: formatoData
	    },{	
			header: 'Hosts',
			dataIndex: 'qtdHost', 
			width: 40
	    }
	])	
	licencaStoreColunasCadastrar.defaultSortable = true	
		
	
	nomeBase = new Ext.form.ComboBox({
		xtype: 'combo',
		id: 'nomeBase',
		fieldLabel: 'Base',
		name: 'base',
		forceSelection: true,
		allowBlank:false,
		blankText:"Por favor Selecione a Base.",
		maxLength: 50,		
		triggerAction: 'all',
		store: storeBase,
		displayField: 'nome',
		valueField: 'codRemoto',
		editable: false,
		forceSelection : true,
		triggerAction: 'all',
		anchor:'100%'
	})

	
	tipoLicenca = new Ext.form.ComboBox({
		xtype: 'combo',
		id: 'tipoLicenca',
		fieldLabel: 'Tipo de Licenca',
		name: 'tipoLicenca',
		forceSelection: true,
		allowBlank:false,
		blankText:"Por favor Selecione o Tipo da Licença.",
		maxLength: 50,
		triggerAction: 'all',
		store: storeTipoLicenca,
		displayField: 'tipo',
		valueField: 'codTipoLicenca',
		editable: false,
		forceSelection : true,
		triggerAction: 'all',
		anchor:'100%'
	})

	dataInicio = new Ext.form.DateField({
		fieldLabel: 'Data Início',
		name: 'dataInicio',
		id: 'dataInicio',
		allowBlank:false,
		blankText: "Por favor insira a data de início da licença.",
		format : 'd/m/Y',
		anchor:'70%'
	})

	dataFim = new Ext.form.DateField({
		fieldLabel: 'Data Expiração',
		name: 'dataFim',
		id: 'dataFim',
		allowBlank:false,
		blankText:"Por favor insira a data de expiração da licença.",
		format : 'd/m/Y',
		anchor:'70%'
	})

	
	qtdHosts = new Ext.form.NumberField({
		fieldLabel: 'Qtd Hosts',
		name: 'qtdHost',
		id: 'qtdHosts',
		value: 0,
		allowBlank:false,
		blankText:"Por favor insira a quantidade de hosts",
		maxLength: 5,
		anchor : '50%',
		height : '20px'
	})
	
	ativo = new Ext.form.Checkbox({
		fieldLabel: 'Ativo',
		name: 'ativo',
		id: 'ativo',
		inputType: 'Checkbox',
		anchor : '100%'
	})

	periodo = new Ext.form.Checkbox({
		fieldLabel: 'Período Indeterminado',
		name: 'periodo',
		id: 'periodo',
		inputType: 'Checkbox',
		anchor : '100%'
	})

	ilimitado = new Ext.form.Checkbox({
		fieldLabel: 'Hosts Ilimitados',
		name: 'ilimitado',
		id: 'ilimitado',
		inputType: 'Checkbox',
		anchor : '100%'
	})

	var msgErro = function(title, msg){
        Ext.Msg.show({
            title: title, 
            msg: msg,
            minWidth: 200,
            modal: true,
            icon: Ext.Msg.INFO,
            buttons: Ext.Msg.OK,
			fn: function(){
				var redirect = 'login.php' 
				window.location = redirect
			}
        })
    }

	var msg = function(title, msg){
        Ext.Msg.show({
            title: title, 
            msg: msg,
            minWidth: 200,
            modal: true,
            icon: Ext.Msg.INFO,
            buttons: Ext.Msg.OK,
			fn: function(){
				licencaStoreCadastrar.baseParams = {
				task: "listar_licenca", 
					codNoc: codNocSelecionado,
					codRemoto: nomeBase.getValue()
				}
				licencaStoreCadastrar.load()
			}
        })
    }

	function novaLicenca(){
		codRemotoSelecionado = 0
		codNocSelecionado = ''
		codLicencaSelecionada = 0
		Ext.getCmp('botaoExcluir').hide()
		Ext.getCmp('botaoSalvar').show()
		Ext.getCmp('nomeBase').enable(true)
		Ext.getCmp('tipoLicenca').enable(true)
		Ext.getCmp('dataInicio').enable(true)
		Ext.getCmp('dataFim').enable(true)
		Ext.getCmp('periodo').enable(true)
		Ext.getCmp('ilimitado').enable(true)
		Ext.getCmp('qtdHosts').enable(true)
		Ext.getCmp('ativo').enable(true)
		gridCadastrarLicenca.getForm().reset()
	}
	
	function excluirLicenca(){
		Ext.Ajax.request({
			url: 'modulos/gerenciamento/gerenciar_licenca.php',
			params: { 
				task: 'EXCLUIR',
				codLicenca: codLicencaSelecionada
			},
			success: function(result){
				var mensagem = Ext.util.JSON.decode(result.responseText).mensagem			
				msg('Mensagem',mensagem)
			}  
		})
	}

	function salvarLicenca(){
		if(	!Ext.isEmpty(nomeBase.getValue()) &&
			!Ext.isEmpty(tipoLicenca.getValue()) &&
			!Ext.isEmpty(dataInicio.getValue()) &&
			!Ext.isEmpty(dataFim.getValue()) &&
			!Ext.isEmpty(qtdHosts.getValue())){
			
			chkDataInicial = dataInicio.getValue().format('Ymd')
			chkDataFinal = dataFim.getValue().format('Ymd')

			if(chkDataFinal >= chkDataInicial){
				Ext.Ajax.request({
					url: 'modulos/gerenciamento/gerenciar_licenca.php',
					params: { 
						task: 'SALVAR',
						codLicenca :codLicencaSelecionada,
						codBase : nomeBase.getValue(),
						tipoLicenca : tipoLicenca.getValue(),						
						dataInicio : dataInicio.getValue().format('Y-m-d'),
						dataFim : dataFim.getValue().format('Y-m-d'),
						qtdHosts : qtdHosts.getValue(),
						ilimitado : ilimitado.getValue(),
						periodo : periodo.getValue(),
						ativo : ativo.getValue()
					},
					success: function(result){
						var tipo = Ext.util.JSON.decode(result.responseText).tipo
						var mensagem = Ext.util.JSON.decode(result.responseText).mensagem
						
						//Tipo = 2 foi cadastrado mais que a quantidade permitida
						if(tipo == '2'){
							msgErro('Erro', mensagem)
						}else{
							//Tipo = 3 Licença gerada com sucesso
							if(tipo == '3'){
								msg('Mensagem',mensagem)
							}else{
								Ext.MessageBox.alert('Mensagem',mensagem)
							}
						}
					}  

				})
			}else{
				Ext.MessageBox.alert('Erro','Data de Expiração inferior a Data de Início.')
			}
		}else{
			Ext.MessageBox.alert('Erro','Existem campos obrigatórios em branco.')
		}
	}	

	
	var gridCadastrarLicenca = new Ext.FormPanel({
        id: 'gridCadastrarLicenca',
        frame: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
            columnWidth: 0.5,
            layout: 'fit',
			autoScroll: true,
            items: {
				xtype: 'grid',
				ds: licencaStoreCadastrar,
				cm: licencaStoreColunasCadastrar,
				viewConfig: {
					forceFit: true,
					getRowClass: function(record, rowIndex, rp, ds){ // rp = rowParams
						if(record.data.ativo == 0){
							return 'licencaDesativada'
						}
					}
				},   
				sm: new Ext.grid.RowSelectionModel({
					singleSelect: true,
					listeners: {
						rowselect: function(sm, row, rec) {
							Ext.getCmp("gridCadastrarLicenca").getForm().loadRecord(rec)
							codLicencaSelecionada = rec.data.codLicenca
							Ext.getCmp('nomeBase').setDisabled(true)
							Ext.getCmp('botaoExcluir').show(true)

							statusLicenca = rec.data.ativo;

							if(rec.data.ativo == 0){
								Ext.getCmp('tipoLicenca').setDisabled(true)
								Ext.getCmp('dataInicio').setDisabled(true)
								Ext.getCmp('dataFim').setDisabled(true)
								Ext.getCmp('periodo').setDisabled(true)
								Ext.getCmp('ilimitado').setDisabled(true)
								Ext.getCmp('qtdHosts').setDisabled(true)
								Ext.getCmp('ativo').setDisabled(true)
							}else{
								Ext.getCmp('tipoLicenca').enable(true)
								Ext.getCmp('dataInicio').enable(true)
								Ext.getCmp('dataFim').enable(true)
								Ext.getCmp('periodo').enable(true)
								Ext.getCmp('ilimitado').enable(true)
								Ext.getCmp('qtdHosts').enable(true)
								Ext.getCmp('ativo').enable(true)
							}

							nomeBase.setValue(rec.data.codRemoto)
							nomeBase.setRawValue(rec.data.nomeBase)

							tipoLicenca.setValue(rec.data.codTipoLicenca)
							tipoLicenca.setRawValue(rec.data.tipoLicenca)
						}			
					}
				}),			
	            autoExpandColumn: 'tipo',
	            height: 290,
	            border: true,
		        listeners: {
		        	render: function(g) {
		        		g.getSelectionModel().selectRow(0)
		        	},
		        	delay: 10
		        }
        	}
        },{
        	columnWidth: 0.5,
            xtype: 'fieldset',
            labelWidth: 130,
            defaults: {width: 140},	// Default config options for child items
            defaultType: 'textfield',
            autoHeight: true,
            bodyStyle: Ext.isIE ? 'padding:0 0 5px 15px' : 'padding:10px 15px',
            border: false,
            style: {
                "text-align": "left",
				"margin-left": "10px",
                "margin-right": Ext.isIE6 ? (Ext.isStrict ? "-10px" : "-13px") : "0" 
            },
            items: [nomeBase, tipoLicenca, dataInicio, dataFim, periodo, qtdHosts, ilimitado, ativo]
        }],	
		bbar: [
			
			botaoNovo = new Ext.Button({
				id: 'botaoNovo',
				text: 'Nova Licença',
				tooltip: 'Nova',
				iconCls:'new',
				handler: novaLicenca
			}),'-',
			
			botaoSalvar = new Ext.Button({
				id: 'botaoSalvar',
				text: 'Salvar',
				tooltip: 'Salvar',
				iconCls:'save',
				handler: salvarLicenca
			}),'-',

			botaoExcluir = new Ext.Button({
				id: 'botaoExcluir',
				text: 'Excluir',
				tooltip: 'Excluir',
				iconCls:'delete',
				handler: function(){
					if(statusLicenca == 0){
						msg('Mensagem','A licença não pode ser excluída, pois o status da mesma se encontra inativo');
					}else{
						excluirLicenca()
					}
				}
				
				
			})
		],				
        renderTo: 'cadastro'
    })
	licencaStoreCadastrar.load()
			
		
	var janelaCadastrarLicenca = new Ext.Window({
		title: 'Gerenciar Ativos',
		id: 'janelaCadastrarLicenca',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		width: 800,
		anchor: 50,
		height: 365,
		iconCls: 'ic_gerenciar_ativos',
		items:[gridCadastrarLicenca]
	})
	janelaCadastrarLicenca.show()


	if(codRemotoSelecionado != 0){
		nomeBase.setValue(codRemotoSelecionado)
		nomeBase.setRawValue(nomeRemotoSelecionado)
	}

	if(codRemotoSelecionado == 0){
		Ext.getCmp('nomeBase').enable(true)
		Ext.getCmp('botaoExcluir').hide(true)					
	}else{
		Ext.getCmp('nomeBase').setDisabled(true)	
	}

}