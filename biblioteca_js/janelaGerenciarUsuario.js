function gerenciarUsuario() {
    var nomeSelecionado = ''
    var codPessoaSelecionada = 0

    function manterPessoa(coluna, statusUsuario, codPessoa) {
        //if(coluna == 13){
        if (statusUsuario == 1) {
            if (confirm('Tem certeza que deseja desativar esse Usuário?')) {
                Ext.Ajax.request({
                    url: 'modulos/usuario/gerenciar_usuario.php',
                    params: {
                        acao: 'pessoaGerenciar',
                        codPessoa: codPessoa,
                        statusUsuario: 0
                    },
                    callback: function (options, success, response) {

                        var retorno = Ext.decode(response.responseText);

                        if (retorno.success == false) {
                            Ext.MessageBox.alert('Mensagem', 'Usuário Desativado com sucesso!')
                        } else {
                            pessoaStore.reload()
                        }
                    }
                })
            }
        } else {
            if (confirm('Tem certeza que deseja ativar esse Usuário?')) {
                Ext.Ajax.request({
                    url: 'modulos/usuario/gerenciar_usuario.php',
                    params: {
                        acao: 'pessoaGerenciar',
                        codPessoa: codPessoa,
                        statusUsuario: 1
                    },
                    callback: function (options, success, response) {

                        var retorno = Ext.decode(response.responseText);

                        if (retorno.success == false) {
                            msg('Erro', 'Erro ao tentar executar a operação!')
                        } else {
                            pessoaStore.reload()
                        }
                    }
                })
            }
        }
        //}
    }

    verificarTipoPessoa = function () {

        if (cbTipoPessoa.getValue() == '2') {
            Ext.getCmp('fieldPessoaFisica').hide(true)
            Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
            Ext.getCmp('fieldPessoaJuridica').show(true)
            Ext.getCmp('fieldRepresentanteLegal').show(true)
            //Ext.getCmp('montarFieldSet').show(true)
        } else {
            if (cbTipoPessoa.getValue() == '1') {
                Ext.getCmp('fieldPessoaFisica').show(true)
                Ext.getCmp('fieldPessoaFisicaDadosProfissionais').show(true)
                Ext.getCmp('fieldPessoaJuridica').hide(true)
                Ext.getCmp('fieldRepresentanteLegal').hide(true)
                //Ext.getCmp('montarFieldSet').hide(true)

            } else {
                Ext.getCmp('fieldPessoaFisica').hide(true)
                Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
                Ext.getCmp('fieldPessoaFisica').hide(true)
                Ext.getCmp('fieldPessoaJuridica').hide(true)
                Ext.getCmp('fieldRepresentanteLegal').hide(true)
            }
        }
    }

    var storeProfissao = new Ext.data.Store({
        id: 'storeProfissao',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/usuario/gerenciar_usuario.php',
            method: 'POST'
        }),
        baseParams: {acao: "profissaoListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codProfissao', 'nome']
        }, [
            {name: 'codProfissao', type: 'int'},
            {name: 'nome', type: 'string'}

        ]),
        sortInfo: {field: 'nome', direction: "ASC"}
    })

    storeProfissao.load()

    var ufStore = new Ext.data.Store({
        id: 'ufStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/diversos/gerenciar_cidade.php',
            method: 'POST'
        }),
        baseParams: {acao: "UFListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codUf', 'uf']
        }, [
            {name: 'codUf', type: 'int'},
            {name: 'uf', type: 'string'}

        ]),
        sortInfo: {field: 'uf', direction: "ASC"}
    })

    ufStore.load()

    //Tipo de Imóvel
    var storeTipoImovel = new Ext.data.Store({
        id: 'storeTipoImovel',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/imovel/gerenciar_imovel.php',
            method: 'POST'
        }),
        baseParams: {acao: "listarTipoImovel"},
        reader: new Ext.data.JsonReader({
            root: 'results',
            totalProperty: 'total',
            id: ['codTipoImovel', 'nome']
        }, [
            {name: 'codTipoImovel', type: 'int'},
            {name: 'nome', type: 'string'}

        ]),
        sortInfo: {field: 'nome', direction: "ASC"}
    })

    storeTipoImovel.load()

    //Estado Civil
    var estadoCivilStore = new Ext.data.Store({
        id: 'estadoCivilStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/usuario/gerenciar_usuario.php',
            method: 'POST'
        }),
        baseParams: {acao: "estadoCivilListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codEstadoCivil', 'nome']
        }, [
            {name: 'codEstadoCivil', type: 'int'},
            {name: 'nome', type: 'string'}

        ]),
        sortInfo: {field: 'nome', direction: "ASC"}
    })

    estadoCivilStore.load()

    //Estado Civil Representante
    var estadoCivilRepresentanteStore = new Ext.data.Store({
        id: 'estadoCivilRepresentanteStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/usuario/gerenciar_usuario.php',
            method: 'POST'
        }),
        baseParams: {acao: "estadoCivilListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codEstadoCivil', 'nome']
        }, [
            {name: 'codEstadoCivil', type: 'int'},
            {name: 'nome', type: 'string'}

        ]),
        sortInfo: {field: 'nome', direction: "ASC"}
    })

    estadoCivilRepresentanteStore.load()

    function imovelEditar(value) {
        return '<center><img src="img/ic_editar.png" /></center>'
    }

    function imovelExcluir(value) {
        if (value == 1) {
            return '<center><img src="img/ic_desativar.png" /></center>'
        } else {
            return '<center><img src="img/ic_ativar.png" /></center>'
        }
    }

    function verificarAtivo(value) {
        if (value == true) {
            return 'sim'
        } else {
            return 'não'
        }
    }

    pessoaStore = new Ext.data.Store({
        id: 'pessoaStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/usuario/gerenciar_usuario.php',
            method: 'POST'
        }),
        baseParams: {acao: "pessoaVwListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codPessoa', 'email', 'nome']
        }, [
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
            {name: 'codSexo', type: 'string', mapping: 'codSexo'},
            {name: 'tipoSexo', type: 'string', mapping: 'tipoSexo'},
            {name: 'tituloEleitor', type: 'string', mapping: 'tituloEleitor'},
            {name: 'enderecoTrabalho', type: 'string', mapping: 'enderecoTrabalho'},
            {name: 'cidadeTrabalho', type: 'string', mapping: 'cidadeTrabalho'},
            {name: 'bairroTrabalho', type: 'string', mapping: 'bairroTrabalho'},
            {name: 'cepTrabalho', type: 'string', mapping: 'cepTrabalho'},
            {name: 'ufTrabalho', type: 'string', mapping: 'ufTrabalho'},
            {name: 'codProfissao', type: 'int', mapping: 'codProfissao'},
            {name: 'profissao', type: 'string', mapping: 'profissao'},
            {name: 'codEstadoCivil', type: 'int', mapping: 'codEstadoCivil'},
            {name: 'estadoCivil', type: 'string', mapping: 'estadoCivil'},
            {name: 'dataNascimento', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataNascimento'},
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
            {name: 'dataNascimentoRepresentante', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataNascimentoRepresentante'},
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
            {name: 'dataNascimentoConjuge', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataNascimentoConjuge'},
            {name: 'identidadeConjuge', type: 'string', mapping: 'identidadeConjuge'},
            {name: 'orgaoExpedidorConjuge', type: 'string', mapping: 'orgaoExpedidorConjuge'},
            {name: 'rendaConjuge', type: 'float', mapping: 'rendaConjuge'},
            {name: 'outroRendimentoConjuge', type: 'float', mapping: 'outroRendimentoConjuge'},
            {name: 'nomeConjuge', type: 'string', mapping: 'nomeConjuge'},
            {name: 'codProfissaoConjuge', type: 'string', mapping: 'codProfissaoConjuge'},
            {name: 'profissaoConjuge', type: 'string', mapping: 'profissaoConjuge'}
        ])
    })

    pessoaColuna = new Ext.grid.ColumnModel(
            [{
                    header: 'codPessoa',
                    dataIndex: 'codPessoa',
                    width: 100,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Nome',
                    dataIndex: 'nome',
                    width: 200,
                    readOnly: false
                }, {
                    header: 'E-mail',
                    dataIndex: 'email',
                    width: 150,
                    readOnly: false,
                    renderer: txtMinusculo
                }, {
                    header: 'Endereço',
                    dataIndex: 'endereco',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Bairro',
                    dataIndex: 'bairro',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Cidade',
                    dataIndex: 'cidade',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'UF',
                    dataIndex: 'uf',
                    width: 50,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'cep',
                    dataIndex: 'cep',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Empresa',
                    dataIndex: 'empresaTrabalho',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'endereco Trabalho',
                    dataIndex: 'enderecoTrabalho',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Bairro Trabalho',
                    dataIndex: 'bairroTrabalho',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Cidade Trabalho',
                    dataIndex: 'cidadeTrabalho',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'UF Trabalho',
                    dataIndex: 'ufTrabalho',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Tipo Pessoa',
                    dataIndex: 'tipoPessoa',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'codTipoPessoa',
                    dataIndex: 'codTipoPessoa',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Nacionalidade',
                    dataIndex: 'nacionalidade',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'codProfissao',
                    dataIndex: 'codProfissao',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'profissao',
                    dataIndex: 'profissao',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'codEstadoCivil',
                    dataIndex: 'codEstadoCivil',
                    width: 80,
                    hidden: true
                }, {
                    header: 'EstadoCivil',
                    dataIndex: 'estadoCivil',
                    width: 80,
                    hidden: true
                }, {
                    header: 'dataNascimento',
                    dataIndex: 'dataNascimento',
                    width: 110,
                    renderer: formatoData,
                    hidden: true
                }, {
                    header: 'Cpf',
                    dataIndex: 'cpf',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Rg',
                    dataIndex: 'rg',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'CPF Representante',
                    dataIndex: 'cpfRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Nome Representante',
                    dataIndex: 'nomeRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Data Nascimento Representante',
                    dataIndex: 'dataNascimentoRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Cod Estado Civil Representante',
                    dataIndex: 'codEstadoCivilRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Estado Civil Representante',
                    dataIndex: 'estadoCivilRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Cod Profissão Representante',
                    dataIndex: 'codProfissaoRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Renda Representante',
                    dataIndex: 'rendaRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Outro rendimento Representante',
                    dataIndex: 'outroRendimentoRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Orgão expedidor Representante',
                    dataIndex: 'orgaoExpedidorRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'RG Representante',
                    dataIndex: 'rgRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Profissão Representante',
                    dataIndex: 'profissaoRepresentante',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'Cnpj',
                    dataIndex: 'cnpj',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'ie',
                    dataIndex: 'ie',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'orgaoExpedidor',
                    dataIndex: 'orgaoExpedidor',
                    width: 80,
                    readOnly: false,
                    hidden: true
                }, {
                    header: 'renda',
                    dataIndex: 'renda',
                    width: 80,
                    renderer: alterarFloat,
                    hidden: true
                }, {
                    header: 'outroRendimento',
                    dataIndex: 'outroRendimento',
                    width: 80,
                    renderer: alterarFloat,
                    hidden: true
                }, {
                    header: 'Status',
                    dataIndex: 'status',
                    width: 50,
                    hidden: true,
                    readOnly: true
                }, {
                    header: 'Excluir',
                    dataIndex: 'status',
                    width: 50,
                    renderer: usuarioExcluir,
                    readOnly: true
                }
            ])
    pessoaColuna.defaultSortable = true

    cbProfissao = new Ext.form.ComboBox({
        id: 'cbProfissao',
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
        allowBlank: false,
        blankText: "Campo obrigatório.",
        triggerAction: 'all',
        emptyText: "selecione.."
    })

    cbTipoPessoa = new Ext.form.ComboBox({
        id: 'cbTipoPessoa',
        typeAhead: false,
        fieldLabel: '<b>Pessoa</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '60%',
        store: new Ext.data.SimpleStore({
            fields: ['codTipoPessoa', 'tipoPessoa'],
            data: [['1', 'Física'], ['2', 'Jurídica']]
        }),
        displayField: 'tipoPessoa',
        valueField: 'codTipoPessoa',
        forceSelection: true,
        allowBlank: false,
        blankText: 'Campo obrigatório',
        triggerAction: 'all'

    });

    cbSexo = new Ext.form.ComboBox({
        id: 'cbSexo',
        typeAhead: false,
        fieldLabel: '<b>Sexo</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '95%',
        store: new Ext.data.SimpleStore({
            fields: ['codSexo', 'tipoSexo'],
            data: [['f', 'Feminino'], ['m', 'Masculino']]
        }),
        displayField: 'tipoSexo',
        valueField: 'codSexo',
        forceSelection: true,
        allowBlank: false,
        blankText: 'Campo obrigatório',
        triggerAction: 'all'

    });

    cbEstadoCivil = new Ext.form.ComboBox({
        id: 'cbEstadoCivil',
        typeAhead: false,
        fieldLabel: '<b>Estado Civil</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '98%',
        store: estadoCivilStore,
        displayField: 'nome',
        valueField: 'codEstadoCivil',
        forceSelection: true,
        triggerAction: 'all',
        allowBlank: false,
        blankText: "Campo obrigatório."
    })

    cbEstadoCivilRepresentante = new Ext.form.ComboBox({
        id: 'cbEstadoCivilRepresentante',
        typeAhead: false,
        fieldLabel: '<b>Estado Civil</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '98%',
        store: estadoCivilRepresentanteStore,
        displayField: 'nome',
        valueField: 'codEstadoCivil',
        forceSelection: true,
        triggerAction: 'all',
        allowBlank: false,
        blankText: "Campo obrigatório."
    })

    dtDataNascimento = new Ext.form.DateField({
        id: 'dtDataNascimento',
        name: 'dataNascimento',
        fieldLabel: '<b>Data Nascimento</b>',
        allowBlank: false,
        blankText: "Por favor insira a <b>DATA DE NASCIMENTO</b>!",
        format: 'd/m/Y',
        anchor: '98%'
    })

    dtDataNascimentoRepresentante = new Ext.form.DateField({
        id: 'dtDataNascimentoRepresentante',
        name: 'dataNascimentoRepresentante',
        fieldLabel: '<b>Data Nascimento</b>',
        allowBlank: false,
        blankText: "Por favor insira a <b>DATA DE NASCIMENTO</b> do Representante!",
        format: 'd/m/Y',
        anchor: '98%'
    })

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
        allowBlank: false,
        blankText: "Campo obrigatório."
    })

    cbUfDadosProfissionais = new Ext.form.ComboBox({
        xtype: 'combo',
        id: 'cbUfDadosProfissionais',
        typeAhead: false,
        name: 'ufDadosProfissionais',
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
        allowBlank: false,
        blankText: "Campo obrigatório."
    })

    tfCpf = {
        xtype: 'masktextfield',
        fieldLabel: '<b>CPF</b>',
        name: 'cpf',
        id: 'tfCpf',
        enableKeyEvents: true,
        allowBlank: false,
        blankText: "Por favor entre com o CPF do usuário.",
        mask: '999.999.999-99',
        money: false,
        anchor: '98%'
    }

    tfCpfRepresentante = {
        xtype: 'masktextfield',
        fieldLabel: '<b>CPF</b>',
        name: 'cpfRepresentante',
        id: 'tfCpfRepresentante',
        allowBlank: false,
        blankText: "Por favor entre com o cpf do representante.",
        mask: '999.999.999-99',
        money: false,
        anchor: '98%'
    }

    tfNacionalidade = new Ext.form.TextField({
        fieldLabel: '<b>Nacionalidade</b>',
        name: 'nacionalidade',
        id: 'tfNacionalidade',
        value: 'Brasileiro',
        allowBlank: false,
        blankText: "Por favor entre com a nacionalidade.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    cbProfissaoRepresentante = new Ext.form.ComboBox({
        id: 'cbProfissaoRepresentante',
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
        triggerAction: 'all',
        allowBlank: false
    })

    tfIdentidade = new Ext.form.TextField({
        fieldLabel: '<b>Identidade</b>',
        name: 'rg',
        id: 'tfIdentidade',
        allowBlank: false,
        blankText: "Por favor entre com o RG do usuário.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfIdentidadeRepresentante = new Ext.form.TextField({
        fieldLabel: '<b>Identidade</b>',
        name: 'rgRepresentante',
        id: 'tfIdentidadeRepresentante',
        allowBlank: false,
        blankText: "Por favor entre com o RG do usuário.",
        maxLength: 100,
        anchor: '98%'
    })

    tfOrgaoExpedidor = new Ext.form.TextField({
        fieldLabel: '<b>Orgão Exp.</b>',
        name: 'orgaoExpedidor',
        id: 'tfOrgaoExpedidor',
        allowBlank: false,
        blankText: "Por favor entre com o orgão expedidor.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '10'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfOrgaoExpedidorRepresentante = new Ext.form.TextField({
        fieldLabel: '<b>Orgão Exp.</b>',
        name: 'orgaoExpedidorRepresentante',
        id: 'tfOrgaoExpedidorRepresentante',
        allowBlank: false,
        blankText: "Por favor entre com o orgão expedidor do representante.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '10'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfRendaRepresentante = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Renda</b>',
        name: 'rendaRepresentante',
        id: 'tfRendaRepresentante',
        allowBlank: false,
        blankText: "Por favor entre com a renda do representante.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '9'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    }

    tfRenda = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Renda</b>',
        name: 'renda',
        id: 'tfRenda',
        allowBlank: false,
        blankText: "Por favor entre com a renda do usuário.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '9'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    }

    tfOutroRendimento = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Outro Rendimento</b>',
        name: 'outroRendimento',
        id: 'tfOutroRendimento',
        allowBlank: false,
        blankText: "Por favor entre com a outra renda do Representante.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '9'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    }

    tfOutroRendimentoRepresentante = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Outro Rendimento</b>',
        name: 'outroRendimentoRepresentante',
        id: 'tfOutroRendimentoRepresentante',
        allowBlank: false,
        blankText: "Por favor entre com a outra renda do usuário.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '9'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    }

    tfNome = new Ext.form.TextField({
        fieldLabel: '<b>Nome</b>',
        name: 'nome',
        id: 'tfNome',
        allowBlank: false,
        blankText: "Por favor entre com o nome.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfNomeRepresentante = new Ext.form.TextField({
        fieldLabel: '<b>Nome</b>',
        name: 'nomeRepresentante',
        id: 'tfNomeRepresentante',
        allowBlank: false,
        blankText: "Por favor entre com o nome do Representante.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfEmail = new Ext.form.TextField({
        fieldLabel: '<b>E-mail</b>',
        name: 'email',
        id: 'tfEmail',
        allowBlank: true,
        anchor: '85%',
        msgTarget: 'side',
        vtype: 'email',
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        vtypeText: 'Digite um e-mail válido (exemplo@exe.com.br)!'
    })

    psSenha = new Ext.form.TextField({
        fieldLabel: '<b>Senha</b>',
        name: 'senha',
        id: 'tfSenha',
        inputType: 'password',
        allowBlank: true,
        autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    psConfirmarSenha = new Ext.form.TextField({
        fieldLabel: '<b>Confirmar</b>',
        name: 'confirmarSenha',
        id: 'tfConfirmarSenha',
        inputType: 'password',
        allowBlank: true,
        autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfEndereco = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        name: 'endereco',
        id: 'tfEndereco',
        allowBlank: false,
        blankText: "Por favor insira o endereço.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '200'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfTelefoneResidencial = {
        xtype: 'masktextfield',
        mask: '(99)9999-9999',
        money: false,
        fieldLabel: '<b>Telefone</b>',
        name: 'telefoneResidencial',
        id: 'telefoneResidencial',
        allowBlank: true,
        anchor: '95%'
    }

    tfTelefoneCelular = {
        xtype: 'masktextfield',
        mask: '(99)9999-9999',
        money: false,
        fieldLabel: '<b>Celular</b>',
        name: 'telefoneCelular',
        id: 'telefoneCelular',
        allowBlank: true,
        anchor: '95%'
    }

    tfTelefoneComercial = {
        xtype: 'masktextfield',
        mask: '(99)9999-9999',
        money: false,
        fieldLabel: '<b>Comercial</b>',
        name: 'telefoneComercial',
        id: 'telefoneComercial',
        allowBlank: true,
        anchor: '95%'
    }

    tfBairro = new Ext.form.TextField({
        fieldLabel: '<b>Bairro</b>',
        name: 'bairro',
        id: 'tfBairro',
        allowBlank: false,
        blankText: "Por favor insira o bairro.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfTituloEleitor = new Ext.form.TextField({
        fieldLabel: '<b>Título de Eleitor</b>',
        name: 'tituloEleitor',
        id: 'tftituloEleitor',
        allowBlank: true,
        anchor: '95%',
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        blankText: 'Digite o título de eleitor'
    })

    cidadeStore = new Ext.data.Store({
        id: 'cidadeStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/diversos/gerenciar_cidade.php',
            method: 'POST'
        }),
        baseParams: {
            acao: "cidadeListar",
            codUf: cbUf.getValue()
        },
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codCidade,cidade']
        }, [
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
        store: cidadeStore,
        displayField: 'cidade',
        valueField: 'codCidade',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '95%',
        emptyText: "selecione..",
        allowBlank: false,
        blankText: "Campo obrigatório."
    })
    //carregar as cidades do df como default
    cidadeStore.load({params: {acao: "cidadeListar", codUf: 6}});

    tfCep = {
        xtype: 'masktextfield',
        mask: '99999-999',
        money: false,
        fieldLabel: '<b>CEP</b>',
        name: 'cep',
        id: 'tfCep',
        allowBlank: false,
        blankText: "Por favor insira a <b>CEP</b>.",
        anchor: '95%'
    }

    tfCnpj = {
        xtype: 'masktextfield',
        mask: '99.999.999/9999-99',
        money: false,
        fieldLabel: '<b>CNPJ</b>',
        name: 'cnpj',
        id: 'tfCnpj',
        allowBlank: false,
        blankText: "Por favor insira o <b>CNPJ</b>.",
        //autoCreate: {tag: 'input', type: 'text', maxlength: '12'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '80%'
    }

    tfInscricaoEstadual = new Ext.form.TextField({
        fieldLabel: '<b>Inscrição Estadual</b>',
        name: 'ie',
        id: 'tfInscricaoEstadual',
        allowBlank: false,
        blankText: "Por favor insira a <b>Inscrição Estadual</b>.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '45'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '80%'
    })

    txObservacoes = new Ext.form.TextArea({
        fieldLabel: '<b>Observações</b>',
        name: 'observacao',
        id: 'txObservacoes',
        allowBlank: true,
        anchor: '98%',
        height: 70
    })

    tfEmpresa = new Ext.form.TextField({
        fieldLabel: '<b>Empresa</b>',
        name: 'empresa',
        id: 'tfEmpresa',
        allowBlank: true,
        //blankText:"Por favor insira o nome da <b>EMPRESA</b>.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfEnderecoDadosProfissionais = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        name: 'enderecoDadosProfissionais',
        id: 'tfEnderecoDadosProfissionais',
        allowBlank: true,
        //blankText:"Por favor insira o <b>ENDEREÇO</b>.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '200'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfBairroDadosProfissionais = new Ext.form.TextField({
        fieldLabel: '<b>Bairro</b>',
        name: 'bairroDadosProfissionais',
        id: 'tfBairroDadosProfissionais',
        allowBlank: true,
        //blankText:"Por favor insira o <b>BAIRRO</b>.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    cbCidadeDadosProfissionais = new Ext.form.ComboBox({
        xtype: 'combo',
        id: 'cbCidadeDadosProfissionais',
        typeAhead: false,
        name: 'cidadeDadosProfissionais',
        fieldLabel: '<b>Cidade</b>',
        value: '',
        mode: 'local',
        editable: false,
        store: cidadeStore,
        displayField: 'cidade',
        valueField: 'codCidade',
        forceSelection: false,
        triggerAction: 'all',
        anchor: '95%',
        emptyText: "selecione..",
        allowBlank: true
                //blankText : "Campo obrigatório."
    })

    tfCepDadosProfissionais = {
        xtype: 'masktextfield',
        mask: '99999-999',
        money: false,
        fieldLabel: '<b>CEP</b>',
        name: 'CEP',
        id: 'tfCepDadosProfissionais',
        allowBlank: true,
        //blankText:"Por favor insira o <b>CEP</b>.",
        //autoCreate: {tag: 'input', type: 'text', maxlength: '9'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    }

    //conjuge------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    tfNomeConjuge = new Ext.form.TextField({
        fieldLabel: '<b>Nome Conjuge</b>',
        name: 'nomeConjuge',
        id: 'tfNomeConjuge',
        allowBlank: false,
        blankText: "Por favor entre com o nome do Conjuge.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '200'},
        anchor: '98%'
    })

    tfNacionalidadeConjuge = new Ext.form.TextField({
        fieldLabel: '<b>Nacionalidade</b>',
        name: 'nacionalidadeConjuge',
        id: 'tfNacionalidadeConjuge',
        allowBlank: false,
        blankText: "Por favor entre com a nacionalidade.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '100'},
        anchor: '98%'
    })

    tfCpfConjuge = {
        xtype: 'masktextfield',
        fieldLabel: '<b>CPF</b>',
        name: 'cpfConjuge',
        id: 'tfCpfConjuge',
        enableKeyEvents: true,
        allowBlank: false,
        blankText: "Por favor entre com o CPF do conjuge.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '14'},
        mask: '999.999.999-99',
        money: false,
        anchor: '98%'
    }

    dtDataNascimentoConjuge = new Ext.form.DateField({
        id: 'dtDataNascimentoConjuge',
        name: 'dataNascimentoConjuge',
        fieldLabel: '<b>Data Nascimento</b>',
        allowBlank: false,
        blankText: "Por favor insira a <b>DATA DE NASCIMENTO</b> do conjuge!",
        format: 'd/m/Y',
        anchor: '98%'
    })

    tfIdentidadeConjuge = new Ext.form.TextField({
        fieldLabel: '<b>Identidade</b>',
        name: 'identidadeConjuge',
        id: 'tfIdentidadeConjuge',
        allowBlank: false,
        blankText: "Por favor entre com o RG do usuário.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '45'},
        anchor: '98%'
    })

    tfOrgaoExpedidorConjuge = new Ext.form.TextField({
        fieldLabel: '<b>Orgão Exp.</b>',
        name: 'orgaoExpedidorConjuge',
        id: 'tfOrgaoExpedidorConjuge',
        allowBlank: false,
        blankText: "Por favor entre com o orgão expedidor.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '10'},
        anchor: '98%'
    })

    tfRendaConjuge = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Renda</b>',
        name: 'rendaConjuge',
        id: 'tfRendaConjuge',
        allowBlank: false,
        blankText: "Por favor entre com a renda do usuário.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '9'},
        anchor: '98%'
    }

    tfOutroRendimentoConjuge = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Outro Rendimento</b>',
        name: 'outroRendimentoConjuge',
        id: 'tfOutroRendimentoConjuge',
        allowBlank: false,
        blankText: "Por favor entre com a outra renda do Representante.",
        autoCreate: {tag: 'input', type: 'text', maxLength: '9'},
        anchor: '98%'
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
    //conjuge------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    function manterImovel(coluna, statusUsuario, codImovel) {
        if (coluna == 13) {
            if (statusUsuario == 1) {
                if (confirm('Tem certeza que deseja desativar esse usuário?')) {
                    Ext.Ajax.request({
                        url: 'modulos/imovel/gerenciar_imovel.php',
                        params: {
                            acao: 'imovelDesativar',
                            codImovel: codImovel
                        },
                        callback: function (options, success, response) {

                            var retorno = Ext.decode(response.responseText);

                            if (retorno.success == false) {
                                Ext.MessageBox.alert('ok')
                            } else {
                                pessoaStore.reload()
                                usuarioGrid.getForm().reset()
                            }
                        }
                    })
                }
            } else {
                if (confirm('Tem certeza que deseja desativar esse usuário?')) {
                    Ext.Ajax.request({
                        url: 'modulos/imovel/gerenciar_imovel.php',
                        params: {
                            acao: 'imovelAtivar',
                            codImovel: codImovel
                        },
                        callback: function (options, success, response) {

                            var retorno = Ext.decode(response.responseText);

                            if (retorno.success == false) {
                                msg('Erro', 'Erro ao tentar executar a operação!')
                            } else {
                                pessoaStore.reload()
                            }
                        }
                    })
                }
            }
        }

    }

    var gridListaPessoa = new Ext.grid.GridPanel({
        id: 'gridListaPessoa',
        ds: pessoaStore,
        cm: pessoaColuna,
        listeners: {
            cellclick: function (grid, linha, coluna) {
                //Verifica se é a coluna de exclusão
                var dados = grid.store.getAt(linha);
                var codImovel = dados.get('codImovel')
                var statusUsuario = dados.get('status')
                manterImovel(coluna, statusUsuario, codImovel)

            },
            rowcontextmenu: function (grid, rowIndex, e) {
                e.stopEvent()
                var acao;
                var dados = grid.store.getAt(rowIndex);
                var codPessoa = dados.get('codPessoa')
                var nome = dados.get('nome');

                if (dados.get('status') == 1) {
                    acao = 'Desativar';
                    status = 'botaoDesativar';
                } else {
                    acao = 'Ativar';
                    status = 'botaoAtivar';
                }

                var contextMenu = new Ext.menu.Menu();
                contextMenu.add({
                    text: acao,
                    iconCls: status,
                    handler: function () {
                        manterPessoa('13', dados.get('status'), codPessoa)
                    }
                }, {
                    text: 'Relacionar Perfis',
                    iconCls: 'manterUsuario',
                    handler: function () {
                        perfisRelacionar(codPessoa, nome)
                    }
                });

                contextMenu.showAt(e.xy);
            }
        },
        viewConfig: {
            forceFit: true,
            getRowClass: function (record, rowIndex, rp, ds) {
                if (record.data.status == '0') {
                    return 'linhaDesativada'
                }
            }
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                rowselect: function (sm, row, rec) {
                    codPessoaSelecionada = rec.data.codPessoa
                    nomeSelecionado = rec.data.nome
                    codEstadoCivil = rec.data.codEstadoCivil

                    /*if(codEstadoCivil == '2'){
                     Ext.getCmp("botaoConjuge").show(true)
                     }else{
                     Ext.getCmp("botaoConjuge").hide(true)
                     }*/

                    Ext.getCmp("botaoDadoBancario").show(true)

                    Ext.getCmp("usuarioGrid").getForm().loadRecord(rec)
                    Ext.getCmp("cbSexo").setRawValue(rec.data.tipoSexo)
                    Ext.getCmp("cbSexo").setValue(rec.data.codSexo)
                    Ext.getCmp("cbTipoPessoa").setRawValue(rec.data.tipoPessoa)
                    Ext.getCmp("cbTipoPessoa").setValue(rec.data.codTipoPessoa)

                    Ext.getCmp("tfEnderecoDadosProfissionais").setValue(rec.data.enderecoTrabalho)
                    Ext.getCmp("tfEmpresa").setValue(rec.data.empresaTrabalho)
                    Ext.getCmp("tfBairroDadosProfissionais").setValue(rec.data.bairroTrabalho)
                    Ext.getCmp("cbCidadeDadosProfissionais").setValue(rec.data.cidadeTrabalho)
                    Ext.getCmp("tfCepDadosProfissionais").setValue(rec.data.cepTrabalho)
                    Ext.getCmp("cbUfDadosProfissionais").setValue(rec.data.ufTrabalho)

                    Ext.getCmp("cbProfissao").setValue(rec.data.codProfissao)
                    Ext.getCmp("cbProfissao").setRawValue(rec.data.profissao)
                    Ext.getCmp("cbProfissaoRepresentante").setValue(rec.data.codProfissaoRepresentante)
                    Ext.getCmp("cbProfissaoRepresentante").setRawValue(rec.data.profissaoRepresentante)

                    Ext.getCmp("cbEstadoCivil").setValue(rec.data.codEstadoCivil)
                    Ext.getCmp("cbEstadoCivil").setRawValue(rec.data.estadoCivil)

                    if (document.getElementById("cbEstadoCivil").value == "Casado") {
                        Ext.getCmp('fieldConjuge').show(true);
                    } else {
                        Ext.getCmp('fieldConjuge').hide(true);
                    }

                    Ext.getCmp("cbProfissaoConjuge").setValue(rec.data.codProfissaoConjuge);
                    Ext.getCmp("cbProfissaoConjuge").setRawValue(rec.data.profissaoConjuge);

                    Ext.getCmp("cbEstadoCivilRepresentante").setValue(rec.data.codEstadoCivilRepresentante)
                    Ext.getCmp("cbEstadoCivilRepresentante").setRawValue(rec.data.estadoCivilRepresentante)
                    verificarTipoPessoa()
                }
            }
        }),
        autoExpandColumn: 'codImovel',
        height: 316,
        border: true
    })

    var usuarioGrid = new Ext.FormPanel({
        id: 'usuarioGrid',
        frame: true,
        autoHeight: true,
        labelAlign: 'left',
        layout: 'column',
        items: [{
                columnWidth: 0.5,
                layout: 'fit',
                items: [gridListaPessoa]
            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Cadastro de Usuário',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 1,
                                labelWidth: 85,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfNome, tfEmail]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 85,
                                layout: 'form',
                                border: false,
                                items: [psSenha]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 75,
                                layout: 'form',
                                items: [psConfirmarSenha]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 85,
                                layout: 'form',
                                border: false,
                                items: [tfTelefoneResidencial]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 65,
                                layout: 'form',
                                border: false,
                                items: [tfTelefoneCelular]
                            }, {
                                columnWidth: 1,
                                labelWidth: 85,
                                layout: 'form',
                                border: false,
                                items: [tfEndereco]
                            }, {
                                columnWidth: 0.7,
                                labelWidth: 85,
                                layout: 'form',
                                items: [tfBairro]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 40,
                                layout: 'form',
                                items: [tfCep]
                            }, {
                                columnWidth: 0.35,
                                labelWidth: 85,
                                layout: 'form',
                                items: [cbUf]
                            }, {
                                columnWidth: 0.6,
                                labelWidth: 50,
                                layout: 'form',
                                items: [cbCidade]
                            }, {
                                columnWidth: 1,
                                labelWidth: 85,
                                layout: 'form',
                                items: [txObservacoes]
                            }, {
                                columnWidth: 1,
                                labelWidth: 85,
                                layout: 'form',
                                items: [cbTipoPessoa]
                            }]
                    }]

            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 0px;',
                title: 'Dados Profissionais',
                id: 'fieldPessoaFisicaDadosProfissionais',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 0.5,
                                labelWidth: 70,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [cbProfissao]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 60,
                                layout: 'form',
                                border: false,
                                items: [tfEmpresa]
                            }, {
                                columnWidth: 1,
                                labelWidth: 70,
                                layout: 'form',
                                items: [tfEnderecoDadosProfissionais]
                            }, {
                                columnWidth: 1,
                                labelWidth: 70,
                                layout: 'form',
                                items: [tfTelefoneComercial]
                            }, {
                                columnWidth: 0.6,
                                labelWidth: 70,
                                layout: 'form',
                                border: false,
                                items: [tfBairroDadosProfissionais]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 30,
                                layout: 'form',
                                items: [tfCepDadosProfissionais]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 70,
                                layout: 'form',
                                items: [cbUfDadosProfissionais]
                            }, {
                                columnWidth: 0.7,
                                labelWidth: 55,
                                layout: 'form',
                                items: [cbCidadeDadosProfissionais]
                            }]
                    }]

            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Pessoa Física',
                id: 'fieldPessoaFisica',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [cbEstadoCivil]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 90,
                                layout: 'form',
                                border: false,
                                items: [tfNacionalidade]
                            }, {
                                columnWidth: 0.6,
                                labelWidth: 115,
                                layout: 'form',
                                border: false,
                                items: [dtDataNascimento]
                            }, {
                                columnWidth: 0.4,
                                labelWidth: 45,
                                layout: 'form',
                                items: [tfCpf]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                border: false,
                                items: [tfIdentidade]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 80,
                                layout: 'form',
                                items: [tfOrgaoExpedidor]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                items: [tfRenda]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 120,
                                layout: 'form',
                                items: [tfOutroRendimento]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                items: [cbSexo]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 110,
                                layout: 'form',
                                items: [tfTituloEleitor]
                            }]
                    }]

            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                title: 'Representante Legal',
                id: 'fieldRepresentanteLegal',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 1,
                                labelWidth: 115,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfNomeRepresentante]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [cbEstadoCivilRepresentante]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 60,
                                layout: 'form',
                                border: false,
                                items: [cbProfissaoRepresentante]
                            }, {
                                columnWidth: 0.6,
                                labelWidth: 115,
                                layout: 'form',
                                border: false,
                                items: [dtDataNascimentoRepresentante]
                            }, {
                                columnWidth: 0.4,
                                labelWidth: 45,
                                layout: 'form',
                                items: [tfCpfRepresentante]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                border: false,
                                items: [tfIdentidadeRepresentante]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 80,
                                layout: 'form',
                                items: [tfOrgaoExpedidorRepresentante]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 115,
                                layout: 'form',
                                items: [tfRendaRepresentante]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 120,
                                layout: 'form',
                                items: [tfOutroRendimentoRepresentante]
                            }]
                    }]

            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Pessoa Jurídica',
                id: 'fieldPessoaJuridica',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 1,
                                labelWidth: 115,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfCnpj]
                            }, {
                                columnWidth: 1,
                                labelWidth: 115,
                                layout: 'form',
                                border: false,
                                items: [tfInscricaoEstadual]
                            }]
                    }]

            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Conjuge',
                id: 'fieldConjuge',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 1,
                                labelWidth: 100,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfNomeConjuge]
                            }, {
                                columnWidth: 1,
                                labelWidth: 100,
                                layout: 'form',
                                border: false,
                                items: [tfNacionalidadeConjuge]
                            }, {
                                columnWidth: 0.55,
                                labelWidth: 100,
                                layout: 'form',
                                border: false,
                                items: [dtDataNascimentoConjuge]
                            }, {
                                columnWidth: 0.45,
                                labelWidth: 50,
                                layout: 'form',
                                border: false,
                                items: [tfCpfConjuge]
                            }, {
                                columnWidth: 0.6,
                                labelWidth: 100,
                                layout: 'form',
                                border: false,
                                items: [tfIdentidadeConjuge]
                            }, {
                                columnWidth: 0.4,
                                labelWidth: 100,
                                layout: 'form',
                                border: false,
                                items: [tfOrgaoExpedidorConjuge]
                            }, {
                                columnWidth: 0.45,
                                labelWidth: 100,
                                layout: 'form',
                                border: false,
                                items: [tfRendaConjuge]
                            }, {
                                columnWidth: 0.45,
                                labelWidth: 120,
                                layout: 'form',
                                border: false,
                                items: [tfOutroRendimentoConjuge]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 100,
                                layout: 'form',
                                border: false,
                                items: [cbProfissaoConjuge]
                            }]
                    }]

            }]
    })


    cbTipoPessoa.on('select', function (node, checked, e) {
        verificarTipoPessoa()
    })

    cbEstadoCivil.on('select', function (node, checked, e) {
        if (document.getElementById("cbEstadoCivil").value == "Casado") {
            Ext.getCmp('fieldConjuge').show(true);
        } else {
            Ext.getCmp('fieldConjuge').hide(true);
        }
    })

    function novo() {
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

    //habilitar combo da cidade quando um uf for selecionado e listar as cidades
    cbUf.on('select', function () {
        if (cbUf.getValue() != 0) {
            cbCidade.enable();
            cbCidade.reset();
            cidadeStore.load({params: {acao: "cidadeListar", codUf: cbUf.getValue()}});
        } else {
            tfCidade.disable();
        }
    });

    cbUfDadosProfissionais.on('select', function () {
        if (cbUfDadosProfissionais.getValue() != 0) {
            cbCidadeDadosProfissionais.enable();
            cbCidadeDadosProfissionais.reset();
            cidadeStore.load({params: {acao: "cidadeListar", codUf: cbUfDadosProfissionais.getValue()}});
        } else {
            tfCidade.disable();
        }
    });

    cbProfissao.on('focus', function () {
        storeProfissao.reload();
    });

    function validarEstadoCivil() {
        if (document.getElementById("cbEstadoCivil").value == "Casado") {
            if (
                    document.getElementById("tfNomeConjuge").value == "" ||
                    document.getElementById("tfNacionalidadeConjuge").value == "" ||
                    document.getElementById("tfCpfConjuge").value == "" ||
                    document.getElementById("dtDataNascimentoConjuge").value == "" ||
                    document.getElementById("tfIdentidadeConjuge").value == "" ||
                    document.getElementById("tfOrgaoExpedidorConjuge").value == "" ||
                    document.getElementById("tfRendaConjuge").value == "" ||
                    document.getElementById("tfOutroRendimentoConjuge").value == "" ||
                    document.getElementById("cbProfissaoConjuge").value == ""
                    ) {
                return false;
            } else {
                return true;
            }
        }
    }

    function salvar() {

        if ((cbTipoPessoa.getValue() == '1' &&
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
                cbSexo.getValue() != '' &&
                tfTituloEleitor.getValue() != '' &&
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

                        )) {
            if (psSenha.getValue() != '' && psConfirmarSenha.getValue() != psSenha.getValue()) {

                msg('Informação', 'As senhas são diferentes!')

            } else {
                if (tfEmail.getValue() != '' && tfEmail.isValid()) {

                    Ext.Ajax.request({
                        url: 'modulos/usuario/gerenciar_usuario.php',
                        params: {
                            acao: 'emailVerificar',
                            codPessoa: codPessoaSelecionada,
                            email: tfEmail.getValue()
                        },
                        callback: function (options, success, response) {
                            retorno = Ext.decode(response.responseText)

                            if (retorno.success == true) {
                                msg('Atenção', 'E-mail já cadastrado, informe outro e-mail.');
                                tfEmail.focus();
                            } else {

                                //valida o conjuge
                                if (validarEstadoCivil() == false) {
                                    msg('Informação', 'Dados do conjuge incompletos');
                                    return false;
                                }

                                Ext.Ajax.request({
                                    url: 'modulos/usuario/gerenciar_usuario.php',
                                    params: {
                                        acao: 'pessoaCadastrar',
                                        codPessoa: codPessoaSelecionada,
                                        nome: document.getElementById("tfNome").value,
                                        email: tfEmail.getValue(),
                                        senha: psSenha.getValue(),
                                        confirmarSenha: psConfirmarSenha.getValue(),
                                        endereco: tfEndereco.getValue(),
                                        telefoneResidencial: document.getElementById("telefoneResidencial").value,
                                        telefoneCelular: document.getElementById("telefoneCelular").value,
                                        telefoneComercial: document.getElementById("telefoneComercial").value,
                                        bairro: tfBairro.getValue(),
                                        cep: document.getElementById('tfCep').value,
                                        cidade: cbCidade.getRawValue(),
                                        uf: cbUf.getRawValue(),
                                        observacoes: txObservacoes.getValue(),
                                        codTipoPessoa: cbTipoPessoa.getValue(),
                                        codSexo: cbSexo.getValue(),
                                        tituloEleitor: tfTituloEleitor.getValue(),
                                        codProfissao: cbProfissao.getValue(),
                                        empresaTrabalho: tfEmpresa.getValue(),
                                        enderecoTrabalho: tfEnderecoDadosProfissionais.getValue(),
                                        bairroTrabalho: tfBairroDadosProfissionais.getValue(),
                                        cepTrabalho: document.getElementById('tfCepDadosProfissionais').value,
                                        cidadeTrabalho: cbCidadeDadosProfissionais.getRawValue(),
                                        ufTrabalho: cbUfDadosProfissionais.getRawValue(),
                                        estadoCivil: cbEstadoCivil.getValue(),
                                        dataNascimento: dtDataNascimento.getValue(),
                                        cpf: document.getElementById('tfCpf').value,
                                        nacionalidade: tfNacionalidade.getValue(),
                                        rg: tfIdentidade.getValue(),
                                        orgaoExpedidor: tfOrgaoExpedidor.getValue(),
                                        renda: document.getElementById('tfRenda').value,
                                        outroRendimento: document.getElementById('tfOutroRendimento').value,
                                        cnpj: document.getElementById('tfCnpj').value,
                                        ie: tfInscricaoEstadual.getValue(),
                                        nomeRepresentante: tfNomeRepresentante.getValue(),
                                        estadoCivilRepresentante: cbEstadoCivilRepresentante.getValue(),
                                        profissaoRepresentante: cbProfissaoRepresentante.getValue(),
                                        dataNascimentoRepresentante: dtDataNascimentoRepresentante.getValue(),
                                        cpfRepresentante: document.getElementById('tfCpfRepresentante').value,
                                        identidadeRepresentante: tfIdentidadeRepresentante.getValue(),
                                        orgaoExpedidorRepresentante: tfOrgaoExpedidorRepresentante.getValue(),
                                        rendaRepresentante: document.getElementById('tfRendaRepresentante').value,
                                        outroRendimentorepresentante: document.getElementById('tfOutroRendimentoRepresentante').value,
                                        //dados do conjuge
                                        nomeConjuge: document.getElementById("tfNomeConjuge").value,
                                        nacionalidadeConjuge: document.getElementById("tfNacionalidadeConjuge").value,
                                        cpfConjuge: document.getElementById("tfCpfConjuge").value,
                                        dataNascimentoConjuge: dtDataNascimentoConjuge.getValue(),
                                        identidadeConjuge: document.getElementById("tfIdentidadeConjuge").value,
                                        orgaoExpedidorConjuge: document.getElementById("tfOrgaoExpedidorConjuge").value,
                                        rendaConjuge: document.getElementById("tfRendaConjuge").value,
                                        outroRendimentoConjuge: document.getElementById("tfOutroRendimentoConjuge").value,
                                        profissaoConjuge: cbProfissaoConjuge.getValue()
                                                //dados do conjuge

                                    },
                                    callback: function (options, success, response) {
                                        var retorno = Ext.decode(response.responseText);

                                        if (retorno.failure == true) {
                                            msg('Informação', 'Problema ao cadastrar o pessoa!');
                                            return false;
                                        } else if (retorno.cpf == false) {
                                            msg('Informação', 'CPF inválido');
                                            return false;
                                        } else if (retorno.cpfRepresentante == false) {
                                            msg('Informação', 'CPF do representante inválido');
                                            return false;
                                        } else if (retorno.cpfConjuge == false) {
                                            msg('Informação', 'CPF do conjuge inválido');
                                            return false;
                                        } else if (retorno.success == true) {
                                            msg('Informação', 'Operação executada com sucesso!');
                                            novo();
                                            codPessoaSelecionada = 0;
                                            nomeSelecionado = '';
                                            usuarioGrid.getForm().reset();
                                        } else {
                                            msg('Informação', 'Problema ao cadastrar o pessoa!');
                                            return false;
                                        }
                                    }
                                })
                            }//else da validação do email
                        }
                    })
                }
            }
        } else {
            msg('Informação', 'Existem campos obrigatórios em Branco!');
        }
    }

    pessoaStore.load({params: {start: 0, limit: 15}})



    var janelaGerenciarUsuario = new Ext.Window({
        title: 'Gerenciar Usuário',
        id: 'janelaGerenciarUsuario',
        border: false,
        draggable: true,
        resizable: false,
        shadow: false,
        //autoHeight: true,
        width: 1010,
        anchor: 50,
        height: 390,
        closeAction: 'close',
        iconCls: 'manterUsuario',
        modal: true,
        autoScroll: true,
        items: [usuarioGrid],
        bbar: [
            botaoDadoBancario = new Ext.Button({
                id: 'botaoDadoBancario',
                text: '<b>Gerenciar Dados Bancário</b>',
                tooltip: '<b>Gerenciar Dados Bancário</b>',
                handler: function () {
                    dadosBancariosRelacionar(codPessoaSelecionada, nomeSelecionado);
                },
                iconCls: 'icConjuge'
            }),
            '|',
            botaoRelacionar = new Ext.Button({
                text: '<b>Gerenciar Perfis</b>',
                tooltip: '<b>Gerenciar Perfis</b>',
                handler: function () {
                    perfisRelacionar(codPessoaSelecionada, nomeSelecionado)
                },
                iconCls: 'manterUsuario'
            }), '|',
            botaoProfissao = new Ext.Button({
                text: '<b>Gerenciar Profissão</b>',
                tooltip: '<b>Gerenciar Profissão</b>',
                handler: function () {
                    gerenciarProfissao();
                },
                iconCls: 'manterUsuario'
            }), /*
             '|',
             botaoConjuge = new Ext.Button({
             id: 'botaoConjuge',
             text: '<b>Gerenciar Conjuge</b>',
             tooltip: '<b>Gerenciar Conjuge</b>',
             handler: function(){
             conjugeRelacionar(codPessoaSelecionada,nomeSelecionado)
             },   
             iconCls:'icConjuge'
             }),*/
            '->',
            botaoNovo = new Ext.Button({
                text: 'Novo',
                tooltip: 'Novo',
                handler: novo,
                iconCls: 'botaoNovo'
            }), '-',
            botaoSalvar = new Ext.Button({
                text: 'Salvar',
                tooltip: 'Salvar',
                handler: salvar,
                iconCls: 'botaoSalvar'
            })
        ]
    })
    janelaGerenciarUsuario.show()

    Ext.getCmp('fieldPessoaFisica').hide(true)
    Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
    Ext.getCmp('fieldPessoaFisica').hide(true)
    Ext.getCmp('fieldPessoaJuridica').hide(true)
    Ext.getCmp('fieldRepresentanteLegal').hide(true)
    Ext.getCmp('fieldPessoaFisica').hide(true)
    Ext.getCmp('fieldConjuge').hide(true)
    //Ext.getCmp("botaoConjuge").hide(true)
    Ext.getCmp("botaoDadoBancario").hide(true)
}