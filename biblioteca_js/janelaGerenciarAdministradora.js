function gerenciarAdministradora() {
    var nomeSelecionado = '';
    var codAdministradoraSelecionada = 0;

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
    });
    ufStore.load();

    administradoraStore = new Ext.data.Store({
        id: 'administradoraStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/administradora/gerenciar_administradora.php',
            method: 'POST'
        }),
        baseParams: {acao: "administradoraVwListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codAdministradora', 'email', 'nome']
        }, [
            {name: 'codAdministradora', type: 'int', mapping: 'codAdministradora'},
            {name: 'email', type: 'string', mapping: 'email'},
            {name: 'nome', type: 'string', mapping: 'nome'},
            {name: 'telefone', type: 'string', mapping: 'telefone'},
            {name: 'telefone2', type: 'string', mapping: 'telefone2'},
            {name: 'endereco', type: 'string', mapping: 'endereco'},
            {name: 'bairro', type: 'string', mapping: 'bairro'},
            {name: 'cep', type: 'string', mapping: 'cep'},
            {name: 'cidade', type: 'string', mapping: 'cidade'},
            {name: 'uf', type: 'string', mapping: 'uf'},
            {name: 'observacao', type: 'string', mapping: 'observacao'},
            {name: 'status', type: 'boolean', mapping: 'status'}
        ])
    })

    administradoraColuna = new Ext.grid.ColumnModel(
            [{
                    header: 'codAdministradora',
                    dataIndex: 'codAdministradora',
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
    administradoraColuna.defaultSortable = true

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

    tfNome = new Ext.form.TextField({
        fieldLabel: '<b>Nome</b>',
        name: 'nome',
        id: 'tfNome',
        allowBlank: false,
        blankText: "Por favor entre com o nome.",
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

    tfEndereco = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        name: 'endereco',
        id: 'tfEndereco',
        allowBlank: false,
        blankText: "Por favor insira o endereço.",
        autoCreate: {tag: 'input', type: 'text', maxlength: '200'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '98%'
    })

    tfTelefone = {
        xtype: 'masktextfield',
        mask: '(99)9999-9999',
        money: false,
        fieldLabel: '<b>Telefone</b>',
        name: 'telefone',
        id: 'telefone',
        allowBlank: true,
        anchor: '95%'
    }

    tfTelefone2 = {
        xtype: 'masktextfield',
        mask: '(99)9999-9999',
        money: false,
        fieldLabel: '<b>Celular</b>',
        name: 'telefone2',
        id: 'telefone2',
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

    txObservacoes = new Ext.form.TextArea({
        fieldLabel: '<b>Observações</b>',
        name: 'observacao',
        id: 'txObservacoes',
        allowBlank: true,
        anchor: '98%',
        height: 70
    })

    function manterAdministradora(coluna, statusUsuario, codAdministradora) {
        if (coluna == 13) {
            if (statusUsuario == 1) {
                if (confirm('Tem certeza que deseja desativar essa Administradora?')) {
                    Ext.Ajax.request({
                        url: 'modulos/administradora/gerenciar_administradora.php',
                        params: {
                            acao: 'administradoraDesativar',
                            codAdministradora: codAdministradora
                        },
                        callback: function (options, success, response) {

                            var retorno = Ext.decode(response.responseText);

                            if (retorno.success == false) {
                                Ext.MessageBox.alert('ok')
                            } else {
                                administradoraStore.reload()
                                administradoraGrid.getForm().reset()
                            }
                        }
                    })
                }
            } else {
                if (confirm('Tem certeza que deseja desativar essa Administradora?')) {
                    Ext.Ajax.request({
                        url: 'modulos/administradora/gerenciar_administradora.php',
                        params: {
                            acao: 'administradoraAtivar',
                            codAdministradora: codAdministradora
                        },
                        callback: function (options, success, response) {

                            var retorno = Ext.decode(response.responseText);

                            if (retorno.success == false) {
                                msg('Erro', 'Erro ao tentar executar a operação!')
                            } else {
                                administradoraStore.reload()
                            }
                        }
                    })
                }
            }
        }

    }

    var gridListaAdministradora = new Ext.grid.GridPanel({
        id: 'gridListaAdministradora',
        ds: administradoraStore,
        cm: administradoraColuna,
        listeners: {
            cellclick: function (grid, linha, coluna) {
                //Verifica se é a coluna de exclusão
                var dados = grid.store.getAt(linha);
                var codAdministradora = dados.get('codImovel')
                var statusUsuario = dados.get('status')
                manterAdministradora(coluna, statusUsuario, codAdministradora);

            },
            rowcontextmenu: function (grid, rowIndex, e) {
                e.stopEvent()
                var acao;
                var dados = grid.store.getAt(rowIndex);
                var codAdministradora = dados.get('codAdministradora')
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
                        manterAdministradora('13', dados.get('status'), codAdministradora)
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
                    codAdministradoraSelecionada = rec.data.codAdministradora
                    nomeSelecionado = rec.data.nome

                    Ext.getCmp("administradoraGrid").getForm().loadRecord(rec)

                }
            }
        }),
        autoExpandColumn: 'codImovel',
        height: 316,
        border: true
    })

    var administradoraGrid = new Ext.FormPanel({
        id: 'administradoraGrid',
        frame: true,
        autoHeight: true,
        labelAlign: 'left',
        layout: 'column',
        items: [{
                columnWidth: 0.5,
                layout: 'fit',
                items: [gridListaAdministradora]
            }, {
                columnWidth: 0.50,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Cadastro de Administradora',
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
                                items: [tfTelefone]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 65,
                                layout: 'form',
                                border: false,
                                items: [tfTelefone2]
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
                            }]
                    }]

            }]
    })

    function novo() {
        codAdministradoraSelecionada = 0
        nomeSelecionado = ''
        administradoraStore.load()
        administradoraGrid.getForm().reset()
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

    function salvar() {

        if (document.getElementById("tfNome").value != '' &&
                tfEmail.getValue() != '' &&
                tfEndereco.getValue() != '' &&
                tfBairro.getValue() != ''
                ) {

            Ext.Ajax.request({
                url: 'modulos/administradora/gerenciar_administradora.php',
                params: {
                    acao: 'administradoraGerenciar',
                    codAdministradora: codAdministradoraSelecionada,
                    nome: document.getElementById("tfNome").value,
                    email: tfEmail.getValue(),
                    endereco: tfEndereco.getValue(),
                    telefone: document.getElementById("telefone").value,
                    telefone2: document.getElementById("telefone2").value,
                    bairro: tfBairro.getValue(),
                    cep: document.getElementById('tfCep').value,
                    cidade: cbCidade.getRawValue(),
                    uf: cbUf.getRawValue(),
                    observacoes: txObservacoes.getValue(),

                },
                callback: function (options, success, response) {
                    var retorno = Ext.decode(response.responseText);
                    console.log(retorno);

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
                        codAdministradoraSelecionada = 0;
                        nomeSelecionado = '';
                        administradoraGrid.getForm().reset();
                    } else {
                        msg('Informação', 'Problema ao cadastrar o pessoa!');
                        return false;
                    }
                }
            })
        } else {
            msg('Informação', 'Existem campos obrigatórios em Branco!');
        }
    }

    administradoraStore.load({params: {start: 0, limit: 15}})

    var janelaGerenciarUsuario = new Ext.Window({
        title: 'Gerenciar Administradora',
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
        items: [administradoraGrid],
        bbar: [
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
    });

    janelaGerenciarUsuario.show();
}