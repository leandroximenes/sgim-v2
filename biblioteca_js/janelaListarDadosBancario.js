function listarDadosBancario(Usuario){
	var nomeSelecionado = '' 
	var codContaSelecionada = 0
        var codPessoa = Usuario;

	function verificarAtivo(value){
		if(value == true){
			return 'sim'
		}else{
			return 'não'
		}
	}
 
 
 
        contaStore = new Ext.data.Store({
		id: 'contaStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/contaBancaria/gerenciar_usuario.php',
			method: 'POST'
		}),
		baseParams:{acao: "pessoaVwListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codPessoa','email','nome']
		},[ 
			{name: 'codPessoa', type: 'int', mapping: 'codPessoa'},
			{name: 'email', type: 'string', mapping: 'email'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'telefoneCelular', type: 'string', mapping: 'celular'},
			{name: 'telefoneResidencial', type: 'string', mapping: 'residencial'},
			{name: 'telefoneComercial', type: 'string', mapping: 'comercial'},
			{name: 'endereco', type: 'string', mapping: 'endereco'},
			{name: 'bairro', type: 'string', mapping: 'bairro'},
			{name: 'cep', type: 'string', mapping: 'cep'},
			{name: 'cidade', type: 'string', mapping: 'cidade'},
			{name: 'uf', type: 'string', mapping: 'uf'},
			{name: 'codTipoPessoa', type: 'int', mapping: 'codTipoPessoa'},
			{name: 'tipoPessoa', type: 'string', mapping: 'tipoPessoa'},
			{name: 'enderecoTrabalho', type: 'string', mapping: 'enderecoTrabalho'},
			{name: 'cidadeTrabalho', type: 'string', mapping: 'cidadeTrabalho'},
			{name: 'bairroTrabalho', type: 'string', mapping: 'bairroTrabalho'},
			{name: 'cepTrabalho', type: 'string', mapping: 'cepTrabalho'},
			{name: 'ufTrabalho', type: 'string', mapping: 'ufTrabalho'},
			{name: 'codProfissao', type: 'int', mapping: 'codProfissao'},
			{name: 'profissao', type: 'string', mapping: 'profissao'},
			{name: 'codEstadoCivil', type: 'int', mapping: 'codEstadoCivil'},
			{name: 'estadoCivil', type: 'string', mapping: 'estadoCivil'},
			{name: 'dataNascimento', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataNascimento'},
			{name: 'cpf', type: 'string', mapping: 'cpf'},
			{name: 'rg', type: 'string', mapping: 'rg'},
			{name: 'cnpj', type: 'string', mapping: 'cnpj'},
			{name: 'nacionalidade', type: 'string', mapping: 'nacionalidade'},
			{name: 'ie', type: 'int', mapping: 'ie'},
			{name: 'orgaoExpedidor', type: 'string', mapping: 'orgaoExpedidor'},
			{name: 'renda', type: 'float', mapping: 'renda'},
			{name: 'outroRendimento', type: 'float', mapping: 'outroRendimento'},
			{name: 'empresaTrabalho', type: 'string', mapping: 'empresaTrabalho'},
			{name: 'observacao', type: 'string', mapping: 'observacao'},
			{name: 'cpfRepresentante', type: 'string', mapping: 'cpfRepresentante'},
			{name: 'nomeRepresentante', type: 'string', mapping: 'nomeRepresentante'},
			{name: 'dataNascimentoRepresentante', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataNascimentoRepresentante'},
			{name: 'codProfissaoRepresentante', type: 'int', mapping: 'codProfissaoRepresentante'},
			{name: 'codEstadoCivilRepresentante', type: 'int', mapping: 'codEstadoCivilRepresentante'},
			{name: 'estadoCivilRepresentante', type: 'string', mapping: 'estadoCivilRepresentante'},
			{name: 'rendaRepresentante', type: 'float', mapping: 'rendaRepresentante'},
			{name: 'outroRendimentoRepresentante', type: 'float', mapping: 'outroRendimentoRepresentante'},
			{name: 'orgaoExpedidorRepresentante', type: 'string', mapping: 'orgaoExpedidorRepresentante'},
			{name: 'rgRepresentante', type: 'string', mapping: 'rgRepresentante'},
			{name: 'profissaoRepresentante', type: 'string', mapping: 'profissaoRepresentante'},
			{name: 'status', type: 'boolean', mapping: 'status'},
			{name: 'nacionalidadeConjuge', type: 'string', mapping: 'nacionalidadeConjuge'},
			{name: 'cpfConjuge', type: 'string', mapping: 'cpfConjuge'},
			{name: 'dataNascimentoConjuge', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataNascimentoConjuge'},
			{name: 'identidadeConjuge', type: 'string', mapping: 'identidadeConjuge'},
			{name: 'orgaoExpedidorConjuge', type: 'string', mapping: 'orgaoExpedidorConjuge'},
			{name: 'rendaConjuge', type: 'float', mapping: 'rendaConjuge'},
			{name: 'outroRendimentoConjuge', type: 'float', mapping: 'outroRendimentoConjuge'},       
			{name: 'nomeConjuge', type: 'string', mapping: 'nomeConjuge'},
			{name: 'codProfissaoConjuge', type: 'string', mapping: 'codProfissaoConjuge'},
			{name: 'profissaoConjuge', type: 'string', mapping: 'profissaoConjuge'}
		])
	})
        
        
 
	
	contaColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'codDadoBancario',
	        dataIndex: 'codDadoBancario', 
	        width: 100,
			readOnly: false,
			hidden: true
	      },{
	        header: 'Codigo do Banco',
	        dataIndex: 'codBanco', 
	        width: 200,
			readOnly: false
	      },{
	        header: 'Conta',
	        dataIndex: 'conta', 
	        width: 150,
			readOnly: false,
			renderer: txtMinusculo
	      },{
	        header: 'Agencia',
	        dataIndex: 'agencia', 
	        width: 80,
			readOnly: false,
			hidden: true
	      },{
	        header: 'Status',
	        dataIndex: 'status', 
	        width: 50,
			hidden: true,
			readOnly: true
	      }/*,{
	        header: 'Excluir',
	        dataIndex: 'status', 
	        width: 50,
			renderer: contaExcluir,
			readOnly: true
	      }*/
	])	
	contaColuna.defaultSortable = true

	function manterConta(coluna,statusConta,codConta){
		if(coluna == 6){
			if(statusConta == 1){
				if(confirm('Tem certeza que deseja desativar essa conta?')){
					Ext.Ajax.request({
						url: 'modulos/contaBancaria/gerenciar_conta.php',
						params: { 
							acao: 'contaDesativar',
							codImovel:  codConta
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								Ext.MessageBox.alert('ok')
							}else{
								pessoaStore.reload()
								usuarioGrid.getForm().reset()
							}
						}
					})
				}
			}else{
				if(confirm('Tem certeza que deseja ativar essa conta?')){
					Ext.Ajax.request({
						url: 'modulos/contaBancaria/gerenciar_imovel.php',
						params: { 
							acao: 'contaAtivar',
							codImovel:  codConta
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								pessoaStore.reload()
							}
						}
					})
				}
			}
		}
	
	}

	var gridListaConta = new Ext.grid.GridPanel({
		id: 'gridListaConta',
		ds: contaStore,
		cm: contaColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				//Verifica se é a coluna de exclusão
				var dados = grid.store.getAt(linha);
				var codConta = dados.get('codPessoa')
				//var statusConta = dados.get('status')
				manterImovel(coluna, statusConta,codConta)
				
			},
			rowcontextmenu: function(grid,rowIndex, e){
				e.stopEvent()
				var acao;
				var dados     = grid.store.getAt( rowIndex );
				var codConta  = dados.get('codConta')
				var conta      = dados.get('conta');
				
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
                                                dadosBancariosRelacionar(codPessoa, '123')
					}
				},{
					text: 'Relacionar Perfis',
					iconCls:'manterUsuario',
					handler: function (){
						perfisRelacionar(codPessoa,'123')
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
					codContaSelecionada = rec.data.codConta

					/*if(codEstadoCivil == '2'){
						Ext.getCmp("botaoConjuge").show(true)
					}else{
						Ext.getCmp("botaoConjuge").hide(true)
					}*/
						
					// Ext.getCmp("botaoDadoBancario").show(true)
						
					// Ext.getCmp("usuarioGrid").getForm().loadRecord(rec)
					// Ext.getCmp("cbTipoPessoa").setRawValue(rec.data.tipoPessoa)
					// Ext.getCmp("cbTipoPessoa").setValue(rec.data.codTipoPessoa)

					// Ext.getCmp("tfEnderecoDadosProfissionais").setValue (rec.data.enderecoTrabalho)
					// Ext.getCmp("tfEmpresa").setValue (rec.data.empresaTrabalho)
					// Ext.getCmp("tfBairroDadosProfissionais").setValue (rec.data.bairroTrabalho)
					// Ext.getCmp("cbCidadeDadosProfissionais").setValue (rec.data.cidadeTrabalho)
					// Ext.getCmp("tfCepDadosProfissionais").setValue (rec.data.cepTrabalho)
					// Ext.getCmp("cbUfDadosProfissionais").setValue (rec.data.ufTrabalho)

					// Ext.getCmp("cbProfissao").setValue(rec.data.codProfissao)
					// Ext.getCmp("cbProfissao").setRawValue(rec.data.profissao)
					// Ext.getCmp("cbProfissaoRepresentante").setValue(rec.data.codProfissaoRepresentante)
					// Ext.getCmp("cbProfissaoRepresentante").setRawValue(rec.data.profissaoRepresentante)

					// Ext.getCmp("cbEstadoCivil").setValue(rec.data.codEstadoCivil)
					// Ext.getCmp("cbEstadoCivil").setRawValue(rec.data.estadoCivil)
					
					// if(document.getElementById("cbEstadoCivil").value == "Casado"){
					//	Ext.getCmp('fieldConjuge').show(true);
					// }else{
					//	Ext.getCmp('fieldConjuge').hide(true);
					// }

					// Ext.getCmp("cbProfissaoConjuge").setValue(rec.data.codProfissaoConjuge);
					// Ext.getCmp("cbProfissaoConjuge").setRawValue(rec.data.profissaoConjuge);
					
					// Ext.getCmp("cbEstadoCivilRepresentante").setValue(rec.data.codEstadoCivilRepresentante)
					// Ext.getCmp("cbEstadoCivilRepresentante").setRawValue(rec.data.estadoCivilRepresentante)
					// verificarTipoPessoa()
				}			
			}
		}),
		autoExpandColumn: 'codConta',
		height: 316,
		border: true
	})

	var contaGrid = new Ext.FormPanel({
        id: 'contaGrid',
        frame: true,
		autoHeight: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
			columnWidth: 1,
			layout: 'fit',
			items: [gridListaConta]
        }]
    })




function novo(){
	Ext.getCmp('fieldPessoaFisica').hide(true)	
	Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
	Ext.getCmp('fieldPessoaJuridica').hide(true)
	Ext.getCmp('fieldRepresentanteLegal').hide(true)
	Ext.getCmp('fieldConjuge').hide(true)

	codPessoaSelecionada = 0
	nomeSelecionado = ''
	pessoaStore.load()
	usuarioGrid.getForm().reset()
}	


	
function salvar (){
			
		if(	(cbTipoPessoa.getValue() == '1' &&
			document.getElementById("tfNome").value != '' &&
			tfEmail.getValue() != '' &&
			tfEndereco.getValue() != '' &&
			tfBairro.getValue() != '' &&
			document.getElementById('tfCep').value != '' &&
			cbCidade.getRawValue() != '' &&
			cbUf.getRawValue() != '' &&
			txObservacoes.getValue() != '' &&
			cbEstadoCivil.getRawValue() != '' &&
			dtDataNascimento.getValue() != '' &&
			tfNacionalidade.getValue() != '' &&
			document.getElementById('tfCpf').value != '' &&
			cbProfissao.getValue() != '' && 	
			tfIdentidade.getValue() != '' &&
			tfOrgaoExpedidor.getValue() != '' &&
			document.getElementById('tfRenda').value != '') || 
			(cbTipoPessoa.getValue() == '2' &&
			tfNome.getValue() != '' &&
			tfEmail.getValue() != '' &&
			//cbCidadeDadosProfissionais.getValue() != '' &&
			tfEndereco.getValue() != '' &&
			tfBairro.getValue() != '' &&
			//tfCep.getValue() != '' &&
			txObservacoes.getValue() != '' &&
			document.getElementById('tfCnpj').value != '' &&
			tfInscricaoEstadual.getValue() != ''
			
		)){
			if(psSenha.getValue() != '' && psConfirmarSenha.getValue() != psSenha.getValue()){
			
				msg('Informação','As senhas são diferentes!')
			
			}else{
				if(tfEmail.getValue() != '' && tfEmail.isValid()){
					
					Ext.Ajax.request({
						url:'modulos/usuario/gerenciar_usuario.php',
						params:{
							acao : 	'emailVerificar',
							codPessoa : codPessoaSelecionada,
							email:	tfEmail.getValue()
						},
						callback: function(options, success, response){
							retorno = Ext.decode(response.responseText)

							if(retorno.success == true){
								msg('Atenção', 'E-mail já cadastrado, informe outro e-mail.');
								tfEmail.focus();
							}else{
								
								//valida o conjuge
								if(validarEstadoCivil() == false){
									msg('Informação','Dados do conjuge incompletos');
									return false;
								}
								
								Ext.Ajax.request({
									url: 'modulos/usuario/gerenciar_usuario.php',
									params: { 
										acao                        : 'pessoaCadastrar',
										codPessoa                   : codPessoaSelecionada,
										nome                        : document.getElementById("tfNome").value,
										email                       : tfEmail.getValue(),
										senha                       : psSenha.getValue(),
										confirmarSenha              : psConfirmarSenha.getValue(),
										endereco                    : tfEndereco.getValue(),
										telefoneResidencial			: document.getElementById("telefoneResidencial").value,
										telefoneCelular				: document.getElementById("telefoneCelular").value,
										telefoneComercial			: document.getElementById("telefoneComercial").value,						
										bairro                      : tfBairro.getValue(),
										cep                         : document.getElementById('tfCep').value,
										cidade                      : cbCidade.getRawValue(),
										uf                          : cbUf.getRawValue(),
										observacoes                 : txObservacoes.getValue(),
										codTipoPessoa               : cbTipoPessoa.getValue(),
										codProfissao                : cbProfissao.getValue(),
										empresaTrabalho             : tfEmpresa.getValue(), 
										enderecoTrabalho            : tfEnderecoDadosProfissionais.getValue(),
										bairroTrabalho              : tfBairroDadosProfissionais.getValue(),
										cepTrabalho                 : document.getElementById('tfCepDadosProfissionais').value, 
										cidadeTrabalho              : cbCidadeDadosProfissionais.getRawValue(),
										ufTrabalho                  : cbUfDadosProfissionais.getRawValue(),
										estadoCivil                 : cbEstadoCivil.getValue(),
										dataNascimento              : dtDataNascimento.getValue(),
										cpf                         : document.getElementById('tfCpf').value,
										nacionalidade               : tfNacionalidade.getValue(),	
										rg                          : tfIdentidade.getValue(),
										orgaoExpedidor              : tfOrgaoExpedidor.getValue(),
										renda                       : document.getElementById('tfRenda').value,
										outroRendimento             : document.getElementById('tfOutroRendimento').value,
										cnpj                        : document.getElementById('tfCnpj').value,
										ie                          : tfInscricaoEstadual.getValue(),
										nomeRepresentante           : tfNomeRepresentante.getValue(),
										estadoCivilRepresentante    : cbEstadoCivilRepresentante.getValue(),
										profissaoRepresentante      : cbProfissaoRepresentante.getValue(),
										dataNascimentoRepresentante : dtDataNascimentoRepresentante.getValue(),
										cpfRepresentante            : document.getElementById('tfCpfRepresentante').value,
										identidadeRepresentante     : tfIdentidadeRepresentante.getValue(),
										orgaoExpedidorRepresentante : tfOrgaoExpedidorRepresentante.getValue(),										
										rendaRepresentante          : document.getElementById('tfRendaRepresentante').value,
										outroRendimentorepresentante: document.getElementById('tfOutroRendimentoRepresentante').value,
										//dados do conjuge
										nomeConjuge				:	 document.getElementById("tfNomeConjuge").value,
										nacionalidadeConjuge    :    document.getElementById("tfNacionalidadeConjuge").value,
										cpfConjuge              :    document.getElementById("tfCpfConjuge").value,
										dataNascimentoConjuge   :    dtDataNascimentoConjuge.getValue(),
										identidadeConjuge       :    document.getElementById("tfIdentidadeConjuge").value,
										orgaoExpedidorConjuge   :    document.getElementById("tfOrgaoExpedidorConjuge").value,
										rendaConjuge            :    document.getElementById("tfRendaConjuge").value,
										outroRendimentoConjuge  :    document.getElementById("tfOutroRendimentoConjuge").value,
										profissaoConjuge        :    cbProfissaoConjuge.getValue()
										//dados do conjuge
						
									},
									callback: function(options, success, response) {
										var retorno = Ext.decode(response.responseText);
									
										if(retorno.failure == true){
											msg('Informação','Problema ao cadastrar o pessoa!');
											return false;
										}else if(retorno.cpf == false){
											msg('Informação','CPF inválido');
											return false;
										}
										else if(retorno.cpfRepresentante == false){
											msg('Informação','CPF do representante inválido');
											return false;
										}
										else if(retorno.cpfConjuge == false){
											msg('Informação','CPF do conjuge inválido');
											return false;
										}
										else if(retorno.success == true){
											msg('Informação','Operação executada com sucesso!');
											novo();
											codPessoaSelecionada = 0;
											nomeSelecionado      = '';
											usuarioGrid.getForm().reset();
										}else{
											msg('Informação','Problema ao cadastrar o pessoa!');
											return false;
										}
									}
								})								
							}//else da validação do email
						}
					})
				}	
			}
		}else{
			msg('Informação','Existem campos obrigatórios em Branco!');
		}	
}
	
	
	

	var janelaGerenciarConta = new Ext.Window({
		title: 'Contas Bancarias',
		id: 'janelaListarDadosBancario',
		border: false,
		draggable: true,
		resizable: false,
		shadow: false,
		//autoHeight: true,
		width: 1010,
		anchor: 50,
		height: 390,
		closeAction:'close',
		iconCls: 'manterConta',
		modal: true,
		autoScroll: true,
		items:[contaGrid],
		bbar: [
			botaoNovo = new Ext.Button({
				text: 'Novo',
				tooltip: 'Novo',
				handler: novo,   
				iconCls:'botaoNovo'
			}),'-',
			
			botaoSalvar= new Ext.Button({
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
	})
	janelaGerenciarConta.show()
	
	/*Ext.getCmp('fieldPessoaFisica').hide(true)	
	Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
	Ext.getCmp('fieldPessoaFisica').hide(true)	
	Ext.getCmp('fieldPessoaJuridica').hide(true)
	Ext.getCmp('fieldRepresentanteLegal').hide(true)
	Ext.getCmp('fieldPessoaFisica').hide(true)	
	Ext.getCmp('fieldConjuge').hide(true)	
	//Ext.getCmp("botaoConjuge").hide(true)
	Ext.getCmp("botaoDadoBancario").hide(true)*/
}