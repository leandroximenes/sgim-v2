function gerenciarPagamentoCondominio(codContrato) {


    var gridObservacao = new Ext.ux.grid.RowExpander({
        tpl: new Ext.Template(
                '<p><b>Observação:</b> {observacao}</p><br>'
                )
    });

    //Proprietario
    var storeProprietario = new Ext.data.Store({
        id: 'storeProprietario',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/usuario/gerenciar_usuario.php',
            method: 'POST'
        }),
        baseParams: {acao: "proprietarioListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codPessoa', 'nome']
        }, [
            {name: 'codPessoa', type: 'int'},
            {name: 'nome', type: 'string'}

        ]),
        sortInfo: {field: 'codPessoa', direction: "ASC"}
    })
    storeProprietario.load()




    //Imóvel
    var storeImovel = new Ext.data.Store({
        id: 'storeImovel',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/imovel/gerenciar_imovel.php',
            method: 'POST'
        }),
        baseParams: {
            acao: "imovelProprietarioListar",
            codProprietario: 0
        },
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codImovel', 'endereco']
        }, [
            {name: 'codImovel', type: 'int'},
            {name: 'endereco', type: 'string'}

        ]),
        sortInfo: {field: 'codImovel', direction: "ASC"}
    })
    storeImovel.load()


    var ufStore = new Ext.data.Store({
        id: 'ufStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/diversos/gerenciar_uf.php',
            method: 'POST'
        }),
        baseParams: {acao: "ufListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['uf']
        }, [
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

    var pagamentoColuna = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true
        },
        columns: [
            {
                header: '<b>Pagamento</b>',
                dataIndex: 'codPagamento',
                width: 100,
                readOnly: false,
                hidden: true
            }, {
                header: '<b>Contrato</b>',
                dataIndex: 'codContrato',
                width: 70,
                hidden: true,
                readOnly: false
            }, {
                header: '<b>Parc.</b>',
                dataIndex: 'parcela',
                width: 30,
                align: 'center',
                editor: new Ext.form.NumberField({
                    readOnly: true
                })
            }, {
                header: '<b>Valor</b>',
                dataIndex: 'valor',
                width: 70,
                align: 'center',
                renderer: float2moeda,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000
                })
            }, {
                header: '<b>Data Vencimento</b>',
                dataIndex: 'dataVencimento',
                width: 80,
                align: 'center',
                renderer: formatoDia,
                editor: new Ext.form.DateField({
                    format: 'd/m/y',
                    readOnly: false,
                    disabledDaysText: 'Selecione um dia útil.'
                })
            }, {
                header: '<b>Valor Pagamento</b>',
                dataIndex: 'valorPagamento',
                width: 70,
                hidden: true,
                align: 'center',
                renderer: float2moeda,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000
                })
            }, {
                header: '<b>Data Pagamento</b>',
                dataIndex: 'dataPagamento',
                width: 70,
                align: 'center',
                renderer: formatoDia,
                editor: new Ext.form.DateField({
                    format: 'd/m/y',
                    disabledDaysText: 'Selecione um dia útil.'
                })
            }]
    });

    pessoaStore = new Ext.data.Store({
        id: 'pessoaStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/pagamento/gerenciar_pagamento_condominio.php',
            method: 'POST'
        }),
        baseParams: {
            acao: "pagamentoCondominioListar",
            codContrato: codContrato
        },
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codPagamento']
        }, [
            {name: 'codPagamentoCondominio', type: 'int', mapping: 'codPagamentoCondominio'},
            {name: 'codContrato', type: 'int', mapping: 'codContrato'},
            {name: 'parcela', type: 'int', mapping: 'parcela'},
            {name: 'dataPagamento', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataPagamento'},
            {name: 'dataVencimento', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataVencimento'},
            {name: 'valor', type: 'float', mapping: 'valor'},
            {name: 'observacao', type: 'string', mapping: 'observacao'}
        ]),
        sortInfo: {field: 'codPagamentoCondominio', direction: 'ASC'}
    })

    var mask = new Ext.LoadMask(Ext.getBody(), {msg: "Aguarde", store: pessoaStore})


    cbProprietario = new Ext.form.ComboBox({
        id: 'cbProprietario',
        typeAhead: false,
        fieldLabel: '<b>Proprietário</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '98%',
        store: storeProprietario,
        emptyText: "Selecione um proprietário.",
        displayField: 'nome',
        valueField: 'codPessoa',
        forceSelection: true,
        triggerAction: 'all'

    })



    tfContratante = new Ext.form.TextField({
        fieldLabel: '<b>Contratante</b>',
        id: 'tfContratante',
        maxLength: 100,
        anchor: '98%',
        disabled: true
    })

    cbImovel = new Ext.form.ComboBox({
        id: 'cbImovel',
        typeAhead: false,
        fieldLabel: '<b>Imóvel</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '98%',
        store: storeImovel,
        emptyText: "Selecione um imóvel.",
        displayField: 'endereco',
        valueField: 'codImovel',
        forceSelection: true,
        triggerAction: 'all'

    })

    tfUf = new Ext.form.TextField({
        fieldLabel: '<b>UF</b>',
        id: 'tfUf',
        maxLength: 100,
        anchor: '98%',
        disabled: true
    })

    tfNome = new Ext.form.TextField({
        fieldLabel: '<b>Nome</b>',
        //name: 'nome',
        id: 'tfNome',
        allowBlank: false,
        blankText: "Por favor entre com o endereço do imóvel.",
        maxLength: 100,
        anchor: '98%'
    })

    tfEndereco = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        //name: 'endereco',
        id: 'tfEndereco',
        allowBlank: false,
        disabled: true,
        blankText: "Por favor insira o endereço.",
        maxLength: 100,
        anchor: '98%'
    })

    tfBairro = new Ext.form.TextField({
        fieldLabel: '<b>Bairro</b>',
        //name: 'bairro',
        id: 'tfBairro',
        disabled: true,
        allowBlank: false,
        blankText: "Por favor insira o bairro.",
        maxLength: 100,
        anchor: '98%'
    })

    tfCidade = new Ext.form.TextField({
        fieldLabel: '<b>Cidade</b>',
        //name: 'cidade',
        id: 'tfCidade',
        disabled: true,
        allowBlank: false,
        blankText: "Por favor insira a cidade.",
        maxLength: 100,
        anchor: '95%'
    })

    tfCep = new Ext.form.TextField({
        fieldLabel: '<b>CEP</b>',
        //name: 'cep',
        id: 'tfCep',
        disabled: true,
        allowBlank: false,
        blankText: "Por favor insira a cidade.",
        maxLength: 100,
        anchor: '95%'
    })

    tfCnpj = new Ext.form.TextField({
        fieldLabel: '<b>CNPJ</b>',
        //name: 'cnpj',
        id: 'tfCnpj',
        allowBlank: false,
        blankText: "Por favor insira o <b>CNPJ</b>.",
        maxLength: 100,
        anchor: '80%'
    })

    tfInscricaoEstadual = new Ext.form.TextField({
        fieldLabel: '<b>Inscrição Estadual</b>',
        //name: 'inscricaoEstadual',
        id: 'tfInscricaoEstadual',
        allowBlank: false,
        blankText: "Por favor insira a <b>Inscrição Estadual</b>.",
        maxLength: 100,
        anchor: '80%'
    })

    txObservacoes = new Ext.form.TextArea({
        fieldLabel: '<b>Observações</b>',
        //name: 'observacao',
        id: 'txObservacoes',
        allowBlank: true,
        maxLength: 100,
        anchor: '98%',
        height: 50
    })

    tfEmpresa = new Ext.form.TextField({
        fieldLabel: '<b>Empresa</b>',
        //name: 'empresa',
        id: 'tfEmpresa',
        allowBlank: false,
        blankText: "Por favor insira o nome da <b>EMPRESA</b>.",
        maxLength: 100,
        anchor: '98%'
    })

    tfEnderecoDadosProfissionais = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        name: 'enderecoDadosProfissionais',
        id: 'tfEnderecoDadosProfissionais',
        allowBlank: false,
        blankText: "Por favor insira o <b>ENDEREÇO</b>.",
        maxLength: 100,
        anchor: '98%'
    })

    tfBairroDadosProfissionais = new Ext.form.TextField({
        fieldLabel: '<b>Bairro</b>',
        name: 'bairroDadosProfissionais',
        id: 'tfBairroDadosProfissionais',
        allowBlank: false,
        blankText: "Por favor insira o <b>BAIRRO</b>.",
        maxLength: 100,
        anchor: '98%'
    })

    tfCidadeDadosProfissionais = new Ext.form.TextField({
        fieldLabel: '<b>Cidade</b>',
        name: 'cidadeDadosProfissionais',
        id: 'tfCidadeDadosProfissionais',
        allowBlank: false,
        blankText: "Por favor insira o <b>BAIRRO</b>.",
        maxLength: 100,
        anchor: '98%'
    })



    tfCepDadosProfissionais = new Ext.form.TextField({
        fieldLabel: '<b>CEP</b>',
        name: 'CEP',
        id: 'tfCepDadosProfissionais',
        allowBlank: false,
        blankText: "Por favor insira o <b>CEP</b>.",
        maxLength: 100,
        anchor: '98%'
    })


    tfValor = new Ext.form.TextField({
        fieldLabel: '<b>Valor</b>',
        name: 'valorBase',
        id: 'tfValor',
        allowBlank: false,
        anchor: '95%',
        disabled: true
    })

    tfDescPontualidade = new Ext.form.TextField({
        fieldLabel: '<b>Desc. Pontualidade</b>',
        name: 'tfDescPontualidade',
        id: 'tfDescPontualidade',
        allowBlank: false,
        anchor: '95%',
        disabled: true
    })

    tfCpf = new Ext.form.TextField({
        fieldLabel: '<b>CPF</b>',
        name: 'cpf',
        id: 'tfCpf',
        anchor: '98%',
        disabled: true
    })

    dtInicioLocacao = new Ext.form.DateField({
        id: 'dtInicioLocacao',
        name: 'dtInicioLocacao',
        fieldLabel: '<b>Início Locação</b>',
        allowBlank: false,
        format: 'd/m/Y',
        disabled: true,
        anchor: '98%'
    })

    dtFimLocacao = new Ext.form.DateField({
        id: 'dtFimLocacao',
        name: 'dtFimLocacao',
        fieldLabel: '<b>Fim Locação</b>',
        allowBlank: false,
        disabled: true,
        format: 'd/m/Y',
        anchor: '98%'
    })


    tfValorCondominio = {
        xtype: 'masktextfield',
        mask: 'R$ #9.999.990,00',
        money: true,
        fieldLabel: '<b>Valor do Codominio</b>',
        name: 'tfValorCondominio',
        id: 'tfValorCondominio',
        autoCreate: {tag: 'input', type: 'text', maxlength: '5'}, //seta o tamanho máximo q o input vai aceitar
        anchor: '95%'
    }

    tfDataVencimentoCondominio = new Ext.form.DateField({
        name: 'tfDataVencimentoCondominio',
        id: 'tfDataVencimentoCondominio',
        fieldLabel: '<b>Primeiro Vencimento</b>',
        allowBlank: false,
        format: 'd/m/Y',
        anchor: '95%',
    });
    
    function manterImovel(coluna, statusUsuario, codImovel) {
        if (coluna == 13) {
            if (statusUsuario == 1) {
                if (confirm('Tem certeza que deseja desativar esse imóvel?')) {
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
                if (confirm('Tem certeza que deseja ativar esse imóvel?')) {
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

    var gridListaPagamento = new Ext.grid.EditorGridPanel({
        id: 'gridListaPagamento',
        plugins: gridObservacao,
        ds: pessoaStore,
        cm: pagamentoColuna,
        clicksToEdit: 1,
        frame: true,
        listeners: {
            cellclick: function (grid, linha, coluna) {
                //Verifica se é a coluna de exclusão
                var dados = grid.store.getAt(linha);
                var codImovel = dados.get('codImovel')
                var statusUsuario = dados.get('status')
                manterImovel(coluna, statusUsuario, codImovel)
            }, afteredit: function (e) {
                salvar()
            },
            rowcontextmenu: function (grid, rowIndex, e) {
                e.stopEvent()
                var acao;
                var dados = grid.store.getAt(rowIndex);
                var codPagamento = dados.get('codPagamentoCondominio');

                var contextMenu = new Ext.menu.Menu();
                contextMenu.add({
                    text: 'Adicionar Observação',
                    iconCls: 'gerenciarObservacao',
                    handler: function () {
                        gerenciarObservacao(codPagamento);
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

                    if (rec.data.codContrato != 0) {
                        Ext.getCmp("usuarioGrid").getForm().loadRecord(rec)
                    }
                }
            }
        }),
        autoExpandColumn: 'codImovel',
        height: 150,
        border: true
    })

    var usuarioGrid = new Ext.FormPanel({
        id: 'usuarioGrid',
        frame: true,
        autoHeight: true,
        labelAlign: 'left',
        layout: 'column',
        items: [{
                columnWidth: 1,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Dados do Contrato',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding: 10px 0px 5px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: .27,
                                labelWidth: 95,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [dtInicioLocacao]
                            }, {
                                columnWidth: .25,
                                labelWidth: 80,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [dtFimLocacao]
                            }, {
                                columnWidth: .18,
                                labelWidth: 40,
                                layout: 'form',
                                items: [tfValor]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 125,
                                layout: 'form',
                                items: [tfDescPontualidade]
                            }]
                    }]

            }, {
                columnWidth: 1,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Dados do Imóvel',
                bodyStyle: Ext.isIE ? 'padding:0 0 0px 10px' : 'padding: 10px 10px 5px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: 0.55,
                                labelWidth: 85,
                                layout: 'form',
                                border: false,
                                items: [tfEndereco]
                            }, {
                                columnWidth: 0.45,
                                labelWidth: 70,
                                layout: 'form',
                                items: [tfBairro]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 85,
                                layout: 'form',
                                items: [tfCidade]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 40,
                                layout: 'form',
                                items: [tfCep]
                            }, {
                                columnWidth: 0.2,
                                labelWidth: 30,
                                layout: 'form',
                                items: [tfUf]
                            }]
                    }]

            }, {
                columnWidth: 1,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Dados do Contratante',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding: 10px 10px 5px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: .6,
                                labelWidth: 85,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfContratante]
                            }, {
                                columnWidth: .4,
                                labelWidth: 50,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfCpf]
                            }]
                    }]

            }, {
                columnWidth: 1,
                xtype: 'fieldset',
                style: 'margin: 0px 5px 5px 5px;',
                title: 'Dados do Condominio',
                bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding: 10px 10px 5px 10px',
                items: [{
                        layout: 'column',
                        width: '100%',
                        border: false,
                        items: [{
                                columnWidth: .35,
                                labelWidth: 150,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfValorCondominio]
                            }, {
                                columnWidth: .37,
                                labelWidth: 130,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfDataVencimentoCondominio]
                            }, GerarCondominio = new Ext.Button({
                                text: 'Gerar/Atualizar Condomínio',
                                tooltip: 'Salvar',
                                handler: gerarCondominio,
                                iconCls: 'ic_atualizar'
                            })]
                    }]

            }, {
                columnWidth: 1,
                layout: 'fit',
                items: [gridListaPagamento]
            }],
    })

    function AtualizarVencimento() {

        Ext.Ajax.request({
            url: 'modulos/contrato/gerenciar_contrato.php',
            params: {
                acao: 'atualizarVencimento',
                codContrato: codContrato
            }
        })
        pessoaStore.load({params: {start: 0, limit: 0}});
    }



    cbProprietario.on('select', function (node, checked, e) {

        document.getElementById('cbImovel').enable = true;

        document.getElementById('cbImovel').value = ''
        storeImovel.load({
            params: {
                acao: 'imovelProprietarioListar',
                codProprietario: node.value
            }
        })
    })


    cbImovel.on('select', function (node, checked, e) {
        verificarProprietario(node.value)
    })

    Ext.Ajax.request({
        url: 'modulos/contrato/gerenciar_contrato.php',
        params: {
            acao: 'contratoUnicoListar',
            codContrato: codContrato
        },
        callback: function (options, success, response) {

            var retorno = Ext.decode(response.responseText);

            document.getElementById('tfEndereco').value = retorno.resultado[0].endereco;
            document.getElementById('tfBairro').value = retorno.resultado[0].bairro;
            document.getElementById('tfCep').value = retorno.resultado[0].cep;
            document.getElementById('tfCidade').value = retorno.resultado[0].cidade;
            document.getElementById('tfUf').value = retorno.resultado[0].uf;
            document.getElementById('dtInicioLocacao').value = retorno.resultado[0].dataInicio;
            document.getElementById('dtFimLocacao').value = retorno.resultado[0].dataFim;
            document.getElementById('tfValor').value = float2moeda(retorno.resultado[0].valor);
            document.getElementById('tfValorCondominio').value = float2moeda(retorno.resultado[0].valorCondominio);
            document.getElementById('tfDataVencimentoCondominio').value = retorno.resultado[0].vencimentoCondominio;
            document.getElementById('tfDescPontualidade').value = formatoMoeda(retorno.resultado[0].descontoPontualidade);
            document.getElementById('tfContratante').value = retorno.resultado[0].inquilino;
            document.getElementById('tfCpf').value = retorno.resultado[0].cpf;
        }
    })

    function novo() {
        Ext.getCmp('fieldPessoaFisica').hide(true)
        Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
        Ext.getCmp('fieldPessoaJuridica').hide(true)

        codPessoaSelecionada = 0
        nomeSelecionado = ''
        pessoaStore.load()
        usuarioGrid.getForm().reset()
    }

    function gerarCondominio() {
        var aux = 0;
        var valorCondominio = document.getElementById('tfValorCondominio').value;
        var DataVencimentoCondominio = document.getElementById('tfDataVencimentoCondominio').value;; 

        if (valorCondominio == "0,00") {
            Ext.MessageBox.alert('Não foi passado o valor do condomínio');
            aux++;
        }
        if (DataVencimentoCondominio == "" ) {
            Ext.MessageBox.alert('Não foi passado o vencimento do condomínio');
            aux++;
        }
        
        if (aux == 0) {
            Ext.Ajax.request({
                url: 'modulos/pagamento/gerenciar_pagamento_condominio.php',
                params: {
                    acao: 'gerarCondominio',
                    codContrato: codContrato,
                    valorCondominio: valorCondominio,
                    DataVencimentoCondominio: DataVencimentoCondominio,
                }, callback: function (options, success, response) {
                    var retorno = Ext.decode(response.responseText);

                    if (retorno.success) {
                        pessoaStore.reload()
                        Ext.MessageBox.alert('Valor do condomínio atualizado com sucesso');
                    } else {
                        Ext.MessageBox.alert('Não foi possivel atualizar o valor do condomínio');
                    }
                }
            });
            pessoaStore.load({params: {start: 0, limit: 0}});
        }
    }

    function salvar() {
        Ext.each(gridListaPagamento.getStore().getModifiedRecords(), function (record) {
            Ext.Ajax.request({
                url: 'modulos/pagamento/gerenciar_pagamento_condominio.php',
                params: {
                    acao: 'pagamentoCondominioGerenciar',
                    codPagamentoCondominio: record.get('codPagamentoCondominio'),
                    codContrato: record.get('codContrato'),
                    parcela: record.get('parcela'),
                    dataVencimento: record.get('dataVencimento'),
                    dataPagamento: record.get('dataPagamento'),
                    valor: record.get('valor'),
                }, callback: function (options, success, response) {
                    var retorno = Ext.decode(response.responseText);
                }
            });
        });
        pessoaStore.load({params: {start: 0, limit: 0}})
    }


    pessoaStore.load({params: {start: 0, limit: 0}})

    var janelaGerenciarPagamentoCondominio = new Ext.Window({
        title: 'Gerenciar Pagamento do Condomínio',
        id: 'janelaGerenciarPagamentoCondominio',
        border: false,
        draggable: true,
        resizable: false,
        shadow: false,
        autoHeight: false,
        width: 900,
        closeAction: 'close',
        iconCls: 'manterUsuario',
        modal: true,
        items: [usuarioGrid]
    })
    janelaGerenciarPagamentoCondominio.show()
}