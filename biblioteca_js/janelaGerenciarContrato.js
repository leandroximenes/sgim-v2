function gerenciarContrato() {

    var codContratoSelecionada = 0;


    function manterPessoa(coluna, statusContrato, codContrato) {
        if (statusContrato == 1) {
            if (confirm('Tem certeza que deseja desativar esse Usuário?')) {
                Ext.Ajax.request({
                    url: 'modulos/contrato/gerenciar_contrato.php',
                    params: {
                        acao: 'contratoGerenciar',
                        codContrato: codContrato,
                        statusContrato: 0
                    },
                    callback: function (options, success, response) {

                        var retorno = Ext.decode(response.responseText);

                        if (retorno.success == false) {
                            Ext.MessageBox.alert('Mensagem', 'Usuário Desativado com sucesso!')
                        } else {
                            contratoStore.reload()
                        }
                    }
                })
            }
        } else {
            if (confirm('Tem certeza que deseja ativar esse Usuário?')) {
                Ext.Ajax.request({
                    url: 'modulos/contrato/gerenciar_contrato.php',
                    params: {
                        acao: 'contratoGerenciar',
                        codContrato: codContrato,
                        statusContrato: 1
                    },
                    callback: function (options, success, response) {

                        var retorno = Ext.decode(response.responseText);

                        if (retorno.success == false) {
                            msg('Erro', 'Erro ao tentar executar a operação!')
                        } else {
                            contratoStore.reload()
                        }
                    }
                })
            }
        }
    }

    contratoStore = new Ext.data.Store({
        id: 'contratoStore',
        proxy: new Ext.data.HttpProxy({
            url: 'modulos/contrato/gerenciar_contrato.php',
            method: 'POST'
        }),
        baseParams: {acao: "contratoListar"},
        reader: new Ext.data.JsonReader({
            root: 'resultado',
            totalProperty: 'total',
            id: ['codContrato']

        }, [
            {name: 'proprietario', type: 'string', mapping: 'proprietario'},
            {name: 'inquilino', type: 'string', mapping: 'inquilino'},
            {name: 'dataInicio', type: 'date', dateFormat: 'Y-m-d', mapping: 'dataInicio'},
            {name: 'qtdMeses', type: 'int', mapping: 'qtdMeses'},
            {name: 'dataFim', type: 'date', dateFormat: 'Y-m-d', mapping: 'dataFim'},
            {name: 'valor', type: 'float', mapping: 'valor'},
            {name: 'nome', type: 'string', mapping: 'nome'},
            {name: 'codContrato', type: 'int', mapping: 'codContrato'},
            {name: 'status', type: 'int', mapping: 'status'},
            {name: 'marcado', type: 'int', mapping: 'marcado'},
            {name: 'encerrado', type: 'int', mapping: 'encerrado'},
             {name: 'dataEncerramento', type: 'date', dateFormat: 'Y-m-d', mapping: 'dataEncerramento'},
        ])
    })

    contratoColuna = new Ext.grid.ColumnModel(
            [{
                    header: '<b>Contrato</b>',
                    dataIndex: 'codContrato',
                    width: 100,
                    align: 'center',
                    readOnly: false
                }, {
                    header: '<b>Proprietario</b>',
                    dataIndex: 'proprietario',
                    width: 300,
                    readOnly: false
                }, {
                    header: '<b>Inquilino</b>',
                    dataIndex: 'inquilino',
                    width: 300,
                    readOnly: false
                }, {
                    header: '<b>Início</b>',
                    dataIndex: 'dataInicio',
                    width: 150,
                    renderer: formatoDia
                }, {
                    header: '<b>Nº de Meses</b>',
                    dataIndex: 'qtdMeses',
                    width: 200,
                    align: 'center',
                    readOnly: false
                }, {
                    header: '<b>Fim</b>',
                    dataIndex: 'dataFim',
                    width: 150,
                    renderer: formatoDia
                }, {
                    header: '<b>Valor</b>',
                    dataIndex: 'valor',
                    width: 200,
                    align: 'center',
                    readOnly: false,
                    renderer: float2moeda
                }, {
                    header: '<b>Imóvel</b>',
                    dataIndex: 'nome',
                    width: 200,
                    readOnly: false
                }, {
                    header: '<b>Excluir</b>',
                    dataIndex: 'status',
                    width: 150,
                    align: 'center',
                    renderer: usuarioExcluir,
                    readOnly: true
                }, {
                    header: '<b>Vencimento</b>',
                    dataIndex: 'marcado',
                    width: 150,
                    align: 'center',
                    hidden: true
                }, {
                    header: '<b>Encerrado</b>',
                    dataIndex: 'encerrado',
                    width: 150,
                    align: 'center',
                    hidden: true
                },{
                    header: '<b>Encerramento</b>',
                    dataIndex: 'dataEncerramento',
                    width: 200,
                    renderer: formatoDia
                }
            ])
    contratoColuna.defaultSortable = true

    var grid = new Ext.grid.GridPanel({
        id: 'gridListaPessoa',
        ds: contratoStore,
        cm: contratoColuna,
        listeners: {
            rowcontextmenu: function (grid, rowIndex, e) {
                e.stopEvent()
                var acao;
                var dados = grid.store.getAt(rowIndex);
                var codContrato = dados.get('codContrato')


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
                        manterPessoa('13', dados.get('status'), codContrato)
                    }
                }, {
                    text: 'Encerrar Contrato',
                    iconCls: 'manterUsuario',
                    handler: function () {
                        if (dados.get('encerrado') == 1) {
                            if(confirm('Este contrato já encontra-se encerrado. Deseja alterar?')){
                                contratoEncerrar(codContrato);
                            }
                        } else {
                            contratoEncerrar(codContrato);
                        }
                    }
                }, {
                    text: 'Relacionar Fiador',
                    iconCls: 'manterUsuario',
                    handler: function () {
                        fiadorRelacionar(codContrato)
                    }
                }, {
                    text: 'Alterar',
                    iconCls: 'botaoEditar',
                    handler: function () {
                        cadastrarContrato(codContrato)
                    }
                }, {
                    text: 'Pagamento',
                    iconCls: 'botaoPagamento',
                    handler: function () {
                        gerenciarPagamento(codContrato)
                    }
                },{
                    text: 'Pagamento Condominio',
                    iconCls: 'botaoPagamento',
                    handler: function () {
                        gerenciarPagamentoCondominio(codContrato)
                    }
                }, {
                    text: 'Repasse',
                    iconCls: 'botaoPagamento',
                    handler: function () {
                        gerenciarRepasse(codContrato)
                    }
                }, {
                    text: 'Súmula',
                    iconCls: 'botaoEditar',
                    handler: function () {
                        gerenciarSumario(codContrato)
                    }
                }, {
                    text: 'Contrato Fiador',
                    iconCls: 'botaoImprimir',
                    handler: function () {
                        window.location = 'modulos/contratos/contrato_locacao_fiador_modelo.php?codContrato=' + codContrato;
                    }
                }, {
                    text: 'Contrato Caução',
                    iconCls: 'botaoImprimir',
                    handler: function () {
                        window.location = 'modulos/contratos/contrato_locacao_caucao_modelo.php?codContrato=' + codContrato;
                    }
                }, {
                    text: 'Contrato CredPago',
                    iconCls: 'botaoImprimir',
                    handler: function () {
                        window.location = 'modulos/contratos/contrato_locacao_credPago.php?codContrato=' + codContrato;
                    }
                },{
                    text: 'Kit de boas vindas',
                    iconCls: 'botaoImprimir',
                    handler: function () {
                        window.open('modulos/relatorios/kit_boas_vindas.php?codContrato=' + codContrato);
                    }
                });

                contextMenu.showAt(e.xy);
            }
        },
        viewConfig: {
            forceFit: true,
            getRowClass: function (record, rowIndex, rp, ds) {
                if (record.data.marcado == '0') {
                    return 'contratoVencido'
                } else {
                    if (record.data.encerrado == '1') {
                        return 'contratoEncerrado'
                    }

                    if (record.data.status == '0') {
                        return 'linhaDesativada'
                    }
                }


            }
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
            }
        }),
        autoExpandColumn: 'codImovel',
        height: 290,
        border: true
    })


    function SelecionarIdCliente(obj, record, index) {
        codContratoSelecionada = record.get('codPessoa')

        contratoStore.baseParams = {
            acao: 'pessoaGrupoListar',
            codPessoa: codContratoSelecionada
        }
        contratoStore.load({params: {start: 0, limit: 30}});
    }

    function verificar() {
        if (codContratoSelecionada == '' || codContratoSelecionada == null) {
            Ext.Msg.alert('Aviso', 'Por favor selecione um Usuário!')
        } else {
            Ext.each(grid.getStore().getModifiedRecords(), function (record) {
                var c = record.get('marcado')
                codGrupo = record.get('codGrupo')

                if (c == true) {
                    Ext.Ajax.request({
                        url: 'modulos/usuario/gerenciar_usuario.php',
                        params: {
                            acao: 'pessoaGrupoRelacionar',
                            codGrupo: codGrupo,
                            codPessoa: codContratoSelecionada,
                            op: 1
                        },
                        callback: function (options, success, response) {
                            ds.reload({params: {start: 0, limit: 10}});
                        }
                    });
                } else {
                    Ext.Ajax.request({
                        url: 'modulos/usuario/gerenciar_usuario.php',
                        params: {
                            acao: 'pessoaGrupoRelacionar',
                            codGrupo: codGrupo,
                            codPessoa: codContratoSelecionada,
                            op: 2
                        },
                        callback: function (options, success, response) {
                            ds.reload({params: {start: 0, limit: 10}});
                        }
                    });
                }

            });
        }
    }

    win = new Ext.Window({
        title: 'Gerenciar Contrato',
        id: 'contratoGerenciar',
        layout: 'fit',
        border: false,
        draggable: true,
        resizable: false,
        width: 850,
        height: 400,
        iconCls: 'botaoContrato',
        items: [grid],
        modal: true,
        bbar: [
            botaoNovo = new Ext.Button({
                text: 'Atualizar',
                tooltip: 'Atualizar',
                handler: function () {
                    contratoStore.load({params: {start: 0, limit: 10}});
                },
                iconCls: 'ic_atualizar'
            }),
            '->',
            botaoNovo = new Ext.Button({
                text: 'Novo',
                tooltip: 'Novo',
                handler: function () {
                    cadastrarContrato(0);
                },
                iconCls: 'botaoNovo'
            })

        ]
    })
    win.show()
    contratoStore.load({params: {start: 0, limit: 10}});
}