function gerenciarRepasse(codContrato) {


    function usuarioExcluir(value) {
        if (value == 1) {
            return '<center><img src="img/ic_desativar.png" /></center>'
        } else {
            return '<center><img src="img/ic_ativar.png" /></center>'
        }
    }


    var gridObservacaoRepasse = new Ext.ux.grid.RowExpander({
        tpl: new Ext.Template(
                '<p><b>Resumo:</b> {observacao}</p><br>'
                )
    });

    //Proprietario
    var storeProprietarioRepasse = new Ext.data.Store({
        id: 'storeProprietarioRepasse',
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
    storeProprietarioRepasse.load()




    //Imóvel
    var storeImovelRepasse = new Ext.data.Store({
        id: 'storeImovelRepasse',
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
    storeImovelRepasse.load()


    var ufStoreRepasse = new Ext.data.Store({
        id: 'ufStoreRepasse',
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

    ufStoreRepasse.load()


    //Tipo de Imóvel
    var storeTipoImovelRepasse = new Ext.data.Store({
        id: 'storeTipoImovelRepasse',
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

    storeTipoImovelRepasse.load()

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
                header: '<b>Valor Pagamento</b>',
                dataIndex: 'valorPagamento',
                width: 70,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    readOnly: true,
                    maxValue: 100000,
                    decimalPrecision: 2,
                    decimalSeparator: ',',
                }),
                align: 'center',
                renderer: float2moeda,
                hidden: false,
                readOnly: true
            }, {
                header: '<b>Data Pagamento</b>',
                dataIndex: 'dataPagamento',
                width: 80,
                align: 'center',
                renderer: formatoDia,
                editor: new Ext.form.DateField({
                    format: 'd/m/y',
                    readOnly: true,
                    disabledDaysText: 'Selecione um dia útil.'
                })
            }, {
                header: '<b>Comissão %</b>',
                dataIndex: 'comissao',
                width: 70,
                hidden: false,
                align: 'center',
                renderer: formatoPorcento,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000,
                    decimalPrecision: 2,
                    decimalSeparator: ',',
                })
            }, {
                header: '<b>Comissão R$</b>',
                dataIndex: 'comissao2',
                width: 70,
                align: 'center',
                renderer: float2moeda,
                hidden: false,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000,
                    decimalPrecision: 2,
                    decimalSeparator: ',',
                })
            }, {
                header: '<b>Gasto Inquilino R$</b>',
                dataIndex: 'valorGastoInquilino',
                width: 70,
                align: 'center',
                renderer: float2moeda,
                hidden: false,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000,
                    decimalPrecision: 2,
                    decimalSeparator: ',',
                })
            },{
                header: '<b>Gasto Serviço R$</b>',
                dataIndex: 'valorGastoServico',
                width: 70,
                align: 'center',
                renderer: float2moeda,
                hidden: false,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000,
                    decimalPrecision: 2,
                    decimalSeparator: ',',
                })
            }, {
                header: '<b>Valor Repasse</b>',
                dataIndex: 'valorRepasse',
                width: 60,
                align: 'center',
                renderer: float2moeda,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    readOnly: true,
                    maxValue: 100000,
                    decimalPrecision: 2,
                    decimalSeparator: ',',
                })
            }, {
                header: '<b>Data Repasse</b>',
                dataIndex: 'dataRepasse',
                width: 70,
                align: 'center',
                renderer: formatoDia,
                editor: new Ext.form.DateField({
                    format: 'd/m/y',
                    disabledDaysText: 'Selecione um dia útil.'
                })
            }]
    });

    repasseStore = new Ext.data.Store({
        id: 'repasseStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/pagamento/gerenciar_pagamento.php',
            method: 'POST'
        }),
        baseParams: {
            acao: "repasseListar",
            codContrato: codContrato
        },
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codPagamento']
        }, [
            {name: 'codPagamento', type: 'int', mapping: 'codPagamento'},
            {name: 'codContrato', type: 'int', mapping: 'codContrato'},
            {name: 'parcela', type: 'int', mapping: 'parcela'},
            {name: 'valorPagamento', type: 'float', mapping: 'valorPagamento'},
            {name: 'valorGastoInquilino', type: 'float', mapping: 'valorGastoInquilino'},
            {name: 'valorGastoServico', type: 'float', mapping: 'valorGastoServico'},
            {name: 'dataPagamento', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataPagamento'},
            {name: 'comissao', type: 'float', mapping: 'comissao'},
            {name: 'comissao2', type: 'float', mapping: 'comissao2'},
            {name: 'dataRepasse', type: 'date', dateFormat: 'Y-m-d H:i:s', mapping: 'dataRepasse'},
            {name: 'valorRepasse', type: 'float', mapping: 'valorRepasse'}
        ]),
        sortInfo: {field: 'codPagamento', direction: 'ASC'}
    })

    var mask = new Ext.LoadMask(Ext.getBody(), {msg: "Aguarde", store: repasseStore})

    cbProprietarioRepasse = new Ext.form.ComboBox({
        id: 'cbProprietarioRepasse',
        typeAhead: false,
        fieldLabel: '<b>Proprietário</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '98%',
        store: storeProprietarioRepasse,
        emptyText: "Selecione um proprietário.",
        displayField: 'nome',
        valueField: 'codPessoa',
        forceSelection: true,
        triggerAction: 'all'

    })

    tfContratanteRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Contratante</b>',
        id: 'tfContratanteRepasse',
        maxLength: 100,
        anchor: '98%',
        disabled: true
    })

    cbImovelRepasse = new Ext.form.ComboBox({
        id: 'cbImovelRepasse',
        typeAhead: false,
        fieldLabel: '<b>Imóvel</b>',
        value: '',
        mode: 'local',
        editable: false,
        anchor: '98%',
        store: storeImovelRepasse,
        emptyText: "Selecione um imóvel.",
        displayField: 'endereco',
        valueField: 'codImovel',
        forceSelection: true,
        triggerAction: 'all'

    })

    tfUfRepasse = new Ext.form.TextField({
        fieldLabel: '<b>UF</b>',
        id: 'tfUfRepasse',
        maxLength: 100,
        anchor: '98%',
        disabled: true
    })

    tfNomeRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Nome</b>',
        //name: 'nome',
        id: 'tfNomeRepasse',
        allowBlank: false,
        blankText: "Por favor entre com o endereço do imóvel.",
        maxLength: 100,
        anchor: '98%'
    })

    tfEnderecoRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        //name: 'endereco',
        id: 'tfEnderecoRepasse',
        allowBlank: false,
        disabled: true,
        blankText: "Por favor insira o endereço.",
        maxLength: 100,
        anchor: '98%'
    })

    tfBairroRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Bairro</b>',
        //name: 'bairro',
        id: 'tfBairroRepasse',
        disabled: true,
        allowBlank: false,
        blankText: "Por favor insira o bairro.",
        maxLength: 100,
        anchor: '98%'
    })

    tfCidadeRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Cidade</b>',
        //name: 'cidade',
        id: 'tfCidadeRepasse',
        disabled: true,
        allowBlank: false,
        blankText: "Por favor insira a cidade.",
        maxLength: 100,
        anchor: '95%'
    })

    tfCepRepasse = new Ext.form.TextField({
        fieldLabel: '<b>CEP</b>',
        //name: 'cep',
        id: 'tfCepRepasse',
        disabled: true,
        allowBlank: false,
        blankText: "Por favor insira a cidade.",
        maxLength: 100,
        anchor: '95%'
    })

    tfCnpjRepasse = new Ext.form.TextField({
        fieldLabel: '<b>CNPJ</b>',
        //name: 'cnpj',
        id: 'tfCnpjRepasse',
        allowBlank: false,
        blankText: "Por favor insira o <b>CNPJ</b>.",
        maxLength: 100,
        anchor: '80%'
    })

    tfInscricaoEstadualRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Inscrição Estadual</b>',
        //name: 'inscricaoEstadual',
        id: 'tfInscricaoEstadualRepasse',
        allowBlank: false,
        blankText: "Por favor insira a <b>Inscrição Estadual</b>.",
        maxLength: 100,
        anchor: '80%'
    })

    txObservacoesRepasse = new Ext.form.TextArea({
        fieldLabel: '<b>Observações</b>',
        //name: 'observacao',
        id: 'txObservacoesRepasse',
        allowBlank: true,
        maxLength: 100,
        anchor: '98%',
        height: 50
    })

    tfEmpresaRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Empresa</b>',
        //name: 'empresa',
        id: 'tfEmpresaRepasse',
        allowBlank: false,
        blankText: "Por favor insira o nome da <b>EMPRESA</b>.",
        maxLength: 100,
        anchor: '98%'
    })

    tfEnderecoDadosProfissionaisRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Endereço</b>',
        name: 'tfEnderecoDadosProfissionaisRepasse',
        id: 'tfEnderecoDadosProfissionaisRepasse',
        allowBlank: false,
        blankText: "Por favor insira o <b>ENDEREÇO</b>.",
        maxLength: 100,
        anchor: '98%'
    })

    tfBairroDadosProfissionaisRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Bairro</b>',
        name: 'tfBairroDadosProfissionaisRepasse',
        id: 'tfBairroDadosProfissionaisRepasse',
        allowBlank: false,
        blankText: "Por favor insira o <b>BAIRRO</b>.",
        maxLength: 100,
        anchor: '98%'
    })

    tfCidadeDadosProfissionaisRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Cidade</b>',
        name: 'tfCidadeDadosProfissionaisRepasse',
        id: 'tfCidadeDadosProfissionaisRepasse',
        allowBlank: false,
        blankText: "Por favor insira o <b>BAIRRO</b>.",
        maxLength: 100,
        anchor: '98%'
    })



    tfCepDadosProfissionaisRepasse = new Ext.form.TextField({
        fieldLabel: '<b>CEP</b>',
        name: 'tfCepDadosProfissionaisRepasse',
        id: 'tfCepDadosProfissionaisRepasse',
        allowBlank: false,
        blankText: "Por favor insira o <b>CEP</b>.",
        maxLength: 100,
        anchor: '98%'
    })


    tfValorRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Valor</b>',
        name: 'tfValorRepasse',
        id: 'tfValorRepasse',
        allowBlank: false,
        anchor: '95%',
        disabled: true
    })

    tfDescPontualidadeRepasse = new Ext.form.TextField({
        fieldLabel: '<b>Desc. Pontualidade</b>',
        name: 'tfDescPontualidadeRepasse',
        id: 'tfDescPontualidadeRepasse',
        allowBlank: false,
        anchor: '95%',
        disabled: true
    })

    tfCpfRepasse = new Ext.form.TextField({
        fieldLabel: '<b>CPF</b>',
        name: 'tfCpfRepasse',
        id: 'tfCpfRepasse',
        anchor: '98%',
        disabled: true
    })

    dtInicioLocacaoRepasse = new Ext.form.DateField({
        id: 'dtInicioLocacaoRepasse',
        name: 'dtInicioLocacaoRepasse',
        fieldLabel: '<b>Início Locação</b>',
        allowBlank: false,
        format: 'd/m/Y',
        disabled: true,
        anchor: '98%'
    })

    dtFimLocacaoRepasse = new Ext.form.DateField({
        id: 'dtFimLocacaoRepasse',
        name: 'dtFimLocacaoRepasse',
        fieldLabel: '<b>Fim Locação</b>',
        allowBlank: false,
        disabled: true,
        format: 'd/m/Y',
        anchor: '98%'
    })

    var gridListaRepasse = new Ext.grid.EditorGridPanel({
        id: 'gridListaRepasse',
        plugins: gridObservacaoRepasse,
        ds: repasseStore,
        cm: pagamentoColuna,
        clicksToEdit: 1,
        frame: true,
        listeners: {
            cellclick: function(grid, linha, coluna) {
                //Verifica se é a coluna de exclusão
                var dados = grid.store.getAt(linha);
                var codImovel = dados.get('codImovel')
                var statusUsuario = dados.get('status')
            }, afteredit: function(e) {
                salvar()
            },
            rowcontextmenu: function(grid, rowIndex, e) {
                e.stopEvent()
                var acao;
                var dados = grid.store.getAt(rowIndex);
                var codPessoa = dados.get('codPessoa')
                var codPagamento = dados.get('codPagamento')
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
                    handler: function() {
                        manterPessoa('13', dados.get('status'), codPessoa)
                    }
                }, {
                    text: 'Relacionar Perfis',
                    iconCls: 'manterUsuario',
                    handler: function() {
                        perfisRelacionar(codPessoa, nome)
                    }
                }, {
                    text: 'Adicionar Observação',
                    iconCls: 'gerenciarObservacao',
                    handler: function() {
                        gerenciarObservacaoRepasse(codPagamento)
                    }
                });

                contextMenu.showAt(e.xy);
            }
        },
        viewConfig: {
            forceFit: true,
            getRowClass: function(record, rowIndex, rp, ds) {
                if (record.data.status == '0') {
                    return 'linhaDesativada'
                }
            }
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                rowselect: function(sm, row, rec) {
                    codPessoaSelecionada = rec.data.codPessoa

                    if (rec.data.codContrato != 0) {
                        Ext.getCmp("usuarioGridRepasse").getForm().loadRecord(rec)
                    }
                }
            }
        }),
        autoExpandColumn: 'codImovel',
        height: 150,
        border: true
    })

    var usuarioGridRepasse = new Ext.FormPanel({
        id: 'usuarioGridRepasse',
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
                                items: [dtInicioLocacaoRepasse]
                            }, {
                                columnWidth: .25,
                                labelWidth: 80,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [dtFimLocacaoRepasse]
                            }, {
                                columnWidth: .18,
                                labelWidth: 40,
                                layout: 'form',
                                items: [tfValorRepasse]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 125,
                                layout: 'form',
                                items: [tfDescPontualidadeRepasse]
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
                                items: [tfEnderecoRepasse]
                            }, {
                                columnWidth: 0.45,
                                labelWidth: 70,
                                layout: 'form',
                                items: [tfBairroRepasse]
                            }, {
                                columnWidth: 0.5,
                                labelWidth: 85,
                                layout: 'form',
                                items: [tfCidadeRepasse]
                            }, {
                                columnWidth: 0.3,
                                labelWidth: 40,
                                layout: 'form',
                                items: [tfCepRepasse]
                            }, {
                                columnWidth: 0.2,
                                labelWidth: 30,
                                layout: 'form',
                                items: [tfUfRepasse]
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
                                items: [tfContratanteRepasse]
                            }, {
                                columnWidth: .4,
                                labelWidth: 50,
                                layout: 'form',
                                autoHeight: true,
                                border: false,
                                items: [tfCpfRepasse]
                            }]
                    }]

            }, {
                columnWidth: 1,
                layout: 'fit',
                items: [gridListaRepasse]
            }],
        bbar: [
            '->',
            botaoSalvar = new Ext.Button({
                text: 'Salvar',
                tooltip: 'Salvar',
                handler: salvar,
                iconCls: 'botaoSalvar'
            })
        ]
    })


    cbProprietarioRepasse.on('select', function(node, checked, e) {

        document.getElementById('cbImovelRepasse').enable = true;

        document.getElementById('cbImovelRepasse').value = ''
        storeImovelRepasse.load({
            params: {
                acao: 'imovelProprietarioListar',
                codProprietario: node.value
            }
        })
    })


    cbImovelRepasse.on('select', function(node, checked, e) {
        verificarProprietario(node.value)
    })


    Ext.Ajax.request({
        url: 'modulos/contrato/gerenciar_contrato.php',
        params: {
            acao: 'contratoUnicoListar',
            codContrato: codContrato
        },
        callback: function(options, success, response) {

            var retorno = Ext.decode(response.responseText);

            document.getElementById('tfEnderecoRepasse').value = retorno.resultado[0].endereco;
            document.getElementById('tfBairroRepasse').value = retorno.resultado[0].bairro;
            document.getElementById('tfCepRepasse').value = retorno.resultado[0].cep;
            document.getElementById('tfCidadeRepasse').value = retorno.resultado[0].cidade;
            document.getElementById('tfUfRepasse').value = retorno.resultado[0].uf;
            document.getElementById('dtInicioLocacaoRepasse').value = retorno.resultado[0].dataInicio;
            document.getElementById('dtFimLocacaoRepasse').value = retorno.resultado[0].dataFim;
            document.getElementById('tfValorRepasse').value = formatoMoeda(retorno.resultado[0].valor);
            document.getElementById('tfDescPontualidadeRepasse').value = formatoMoeda(retorno.resultado[0].descontoPontualidade);
            document.getElementById('tfContratanteRepasse').value = retorno.resultado[0].inquilino;
            document.getElementById('tfCpfRepasse').value = retorno.resultado[0].cpf;
        }
    })

    function novo() {
        Ext.getCmp('fieldPessoaFisica').hide(true)
        Ext.getCmp('fieldPessoaFisicaDadosProfissionais').hide(true)
        Ext.getCmp('fieldPessoaJuridica').hide(true)

        codPessoaSelecionada = 0
        nomeSelecionado = ''
        repasseStore.load()
        usuarioGridRepasse.getForm().reset()
    }

    function salvar() {

        Ext.each(gridListaRepasse.getStore().getModifiedRecords(), function(record) {
            Ext.Ajax.request({
                url: 'modulos/pagamento/gerenciar_pagamento.php',
                params: {
                    acao: 'repasseGerenciar',
                    codPagamento: record.get('codPagamento'),
                    codContrato: record.get('codContrato'),
                    parcela: record.get('parcela'),
                    comissao: record.get('comissao'),
                    dataRepasse: record.get('dataRepasse')
                }, callback: function(options, success, response) {
                    var retorno = Ext.decode(response.responseText);
                }
            });
        });
        repasseStore.load({params: {start: 0, limit: 0}})
    }


    repasseStore.load({params: {start: 0, limit: 0}})

    var janelaGerenciarRepasse = new Ext.Window({
        title: 'Gerenciar Repasse',
        id: 'janelaGerenciarRepasse',
        border: false,
        draggable: true,
        resizable: false,
        shadow: false,
        autoHeight: false,
        width: 900,
        closeAction: 'close',
        iconCls: 'manterUsuario',
        modal: true,
        items: [usuarioGridRepasse]
    })
    janelaGerenciarRepasse.show()
}