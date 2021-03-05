Ext.onReady(function () {
    //window.setInterval(function(){atualizarSomMonitor()}, 10000*3)
    Ext.QuickTips.init()

    var nomeUsuarioLogado;
    var id_perfil;

    Ext.Ajax.request({
        async: true,
        url: 'modulos/diversos/sessao.php',
        params: {
        },
        callback: function (options, success, response) {

            var retorno = Ext.decode(response.responseText);

            nomeUsuarioLogado = retorno.resultado[0].sessao;
            id_perfil = retorno.resultado[0].id_perfil;


            var itensContrato = new Ext.menu.Menu({
                items: [{
                        text: 'Gerenciar Contrato',
                        cls: '?',
                        handler: function () {
                            if (!Ext.getCmp('janelaGerenciarContrato')) { //Verifica se a janela está aberta
                                gerenciarContrato();
                            }
                        }
                    }]
            })

            var itensMenuImovel = new Ext.menu.Menu({
                items: [{
                        text: 'Gerenciar Imóvel',
                        cls: '?',
                        handler: function () {
                            if (!Ext.getCmp('janelaCadastrarImovel')) { //Verifica se a janela está aberta
                                gerenciarImovel()
                            }
                        }
                    }]
            })

            var itensMenuPagamento = new Ext.menu.Menu({
                items: [{
                        text: 'Pagamentos',
                        cls: '?',
                        handler: function () {
                            //if(!Ext.getCmp('janelaCadastrarImovel')){ //Verifica se a janela está aberta
                            gerenciarPagamento(26)
                            //}
                        }
                    }]
            })

            var menuPagamento = {
                menu: itensMenuPagamento,
                iconCls: '',
                enableToggle: true,
                split: true,
                text: '<b>Pagamentos</b>'
            }

            var menuImovel = {
                menu: itensMenuImovel,
                iconCls: 'botaoImovel',
                enableToggle: true,
                split: true,
                text: '<b>Imóvel</b>'
            }

            var itensMenuUsuario = new Ext.menu.Menu({
                items: [{
                        text: 'Gerenciar Usuário',
                        iconCls: 'menuUsuario',
                        handler: function () {
                            if (!Ext.getCmp('janelaCadastrarImovel')) { //Verifica se a janela está aberta
                                gerenciarUsuario()
                            }
                        }
                    }, {
                        text: 'Relacionar Grupo',
                        iconCls: 'manterUsuario',
                        handler: function () {
                            if (!Ext.getCmp('perfisRelacionar')) { //Verifica se a janela está aberta
                                perfisRelacionar(0, '')
                            }
                        }
                    }]
            })

            var arrayitensMenuRelatorios = [];



            arrayitensMenuRelatorios.push({
                text: 'Aniversariantes',
                iconCls: '',
                handler: function () {
                    window.open('modulos/relatorios/aniversario.php');
                }
            });


            arrayitensMenuRelatorios.push({
                text: 'Vencimento Contrato 60 dias',
                iconCls: '',
                handler: function () {
                    window.open('modulos/relatorios/contratoVencimento60dias.php');
                }
            });

            if (id_perfil == 1)
                arrayitensMenuRelatorios.push({
                    text: 'Clientes em débito',
                    iconCls: '',
                    handler: function () {
                        window.open('modulos/relatorios/pagamentoAtrasoParcela.php');
                    }
                });

            if (id_perfil == 1)
                arrayitensMenuRelatorios.push({
                    text: 'Tabakal em débito',
                    iconCls: '',
                    handler: function () {
                        window.open('modulos/relatorios/repasseAtraso.php');
                    }
                });

            if (id_perfil == 1)
                arrayitensMenuRelatorios.push({
                    text: 'Pessoas',
                    iconCls: '',
                    handler: function () {
                        window.open('modulos/relatorios/pessoas.php');
                    }
                });

            if (id_perfil == 1)
                arrayitensMenuRelatorios.push({
                    text: 'Previsão de Arrecadação',
                    iconCls: '',
                    handler: function () {
                        window.open('modulos/relatorios/previsaoArrecadacao.php');
                    }
                });

            if (id_perfil == 1)
                arrayitensMenuRelatorios.push({
                    text: 'Relatório de imposto de renda',
                    iconCls: '',
                    handler: function () {
                        window.open('modulos/relatorios/ImpostoRenda.php');
                    }
                });

            var itensMenuRelatorios = new Ext.menu.Menu({
                items: arrayitensMenuRelatorios
            });

            var itensMenuGraficos = new Ext.menu.Menu({
                items: [{
                        text: 'Contratos',
                        iconCls: '',
                        handler: function () {
                            window.open('modulos/graficos/contratos_pie.html');
                        }
                    }, {
                        text: 'Cidades/Imóveis',
                        iconCls: '',
                        handler: function () {
                            window.open('modulos/graficos/cidades_barra.html');
                        }
                    }]
            })

            var menuRelatorios = {
                menu: itensMenuRelatorios,
                iconCls: 'menuRelatorios',
                enableToggle: true,
                split: true,
                text: '<b>Relatórios</b>'
            }

            var menuGraficos = {
                menu: itensMenuGraficos,
                iconCls: 'menuGraficos',
                enableToggle: true,
                split: true,
                text: '<b>Gráficos</b>'
            }

            var itensMenuRelatorios = new Ext.menu.Menu({
                items: [{
                        text: 'Relatório',
                        iconCls: 'manterUsuario',
                        handler: function () {
                            if (!Ext.getCmp('janelaCadastrarImovel')) { //Verifica se a janela está aberta
                                gerenciarImovel()
                            }
                        }
                    }]
            })

            var menuUsuario = {
                menu: itensMenuUsuario,
                iconCls: 'menuUsuario',
                enableToggle: true,
                split: true,
                text: '<b>Usuário</b>'
            }

            var itensMenuConfiguracoes = new Ext.menu.Menu({
                items: [{
                        text: 'Gerenciar Banco',
                        iconCls: 'manterUsuario',
                        handler: function () {
                            if (!Ext.getCmp('janelaGerenciarBanco')) {
                                gerenciarBanco();
                            }
                        }
                    }, {
                        text: 'Gerenciar Cidade',
                        iconCls: 'manterUsuario',
                        handler: function () {
                            if (!Ext.getCmp('janelaGerenciarCidade')) {
                                gerenciarCidade();
                            }
                        }
                    }, {
                        text: 'Gerenciar Profissão',
                        iconCls: 'manterUsuario',
                        handler: function () {
                            if (!Ext.getCmp('janelaGerenciarProfissao')) {
                                gerenciarProfissao()
                            }
                        }
                    }, {
                        text: 'Gerenciar Grupo',
                        iconCls: 'manterUsuario',
                        handler: function () {
                            if (!Ext.getCmp('janelaGerenciarGrupo')) {
                                gerenciarGrupo()
                            }
                        }
                    }, {
                        text: 'Gerenciar Administradora',
                        iconCls: 'ic_grupo',
                        handler: function () {
                            if (!Ext.getCmp('janelaGerenciarAdministradora')) {
                                gerenciarAdministradora();
                            }
                        }
                    }]
            })

            infoVencimentoAluguel()

            infoContratoVencimento()

            infoAniversario()

            var menuContrato = {
                menu: itensContrato,
                iconCls: 'botaoContrato',
                enableToggle: true,
                split: true,
                text: '<b>Contrato</b>'
            }

            var menuConfiguracoes = {
                menu: itensMenuConfiguracoes,
                iconCls: 'menuConfiguracoes',
                enableToggle: true,
                split: true,
                text: '<b>Configurações</b>'
            }

            var itensMenuAcessoUsuario = {
                type: 'button',
                iconCls: 'botaoLogoff',
                text: 'Logoff',
                iconCls: 'botaoLogoff',
                handler: function () {
                    window.location = 'login.php';
                }
            }

            var menuAcessoUsuario = {
                menu: itensMenuAcessoUsuario,
                iconCls: 'ic_ajuda',
                enableToggle: true,
                split: true,
                text: 'Olá <b>' + nomeUsuarioLogado + '</b>'
            }

            var barraMenus = new Ext.Toolbar({
                resizable: false,
                items: [menuContrato, '-', menuImovel, '-', menuUsuario, '-'/*, menuPagamento, '-'*/, menuConfiguracoes, '-', menuGraficos, '-', menuRelatorios, '->',
                    itensMenuAcessoUsuario]
            })

            var painelCabecalho = {
                xtype: 'panel',
                region: 'north',
                border: false,
                height: 93,
                html: '<div id="topo" class="clearfix"><div id="logoSistema"><img src="img/titulo_sistema.jpg" class="displayBlock" /></div><div id="logoMirante"></div></div>'
            }

            var painelTopo = {
                xtype: 'panel',
                height: 93,
                border: false,
                region: 'north',
                items: [painelCabecalho]
            }

            //painel Principal
            var painelPrincipal = {
                xtype: 'panel',
                region: 'center',
                id: 'painelPrincipal',
                autoScroll: true,
                activeTab: 0,
                frame: false,
                layout: 'fit',
                closeAction: 'hide',
                maximizable: true,
                minimizable: true,
                width: 1024,
                height: 786,
                x: 40,
                y: 60,
                tbar: barraMenus,
                items: [/*{
                 xtype: 'gmappanel',
                 zoomLevel: 14,
                 gmapType: 'map',
                 mapConfOpts: ['enableScrollWheelZoom','enableDoubleClickZoom','enableDragging'],
                 mapControls: ['GSmallMapControl','GMapTypeControl','NonExistantControl'],
                 setCenter: {
                 geoCodeAddr: '252 Church St, Richmond, Victoria, 3121',
                 marker: {title: 'Sitesnstores Pty Ltd.'}
                 },
                 markers: [{
                 lat: -37.81748164010962,
                 lng: 144.99946296215057,
                 marker: {title: 'Richmond Police Station'},
                 listeners: {
                 click: function(e){
                 Ext.Msg.alert('Richmond', 'Richmond Police Station');
                 }
                 }
                 },{
                 lat: -37.82184477198719,
                 lng: 144.99804139137268,
                 marker: {title: 'Richmond Church'},
                 listeners: {
                 click: function(e){
                 Ext.Msg.alert('Richmond', 'Richmond Church');
                 }
                 }
                 }]
                 }*/]
            }

            //Viewport Principal, todas as abas estão anexadas a ela
            var painelGeral = new Ext.Viewport({
                layout: 'border',
                items: [painelTopo, painelPrincipal]
            })
        }
    });
});

function infoVencimentoAluguel() {


    Ext.Ajax.request({
        url: 'modulos/informacoes/verVencimentoAluguel.php',
        callback: function (options, success, response) {
            var r = response.responseText;


            if (r == true) {
                new Ext.ux.window.MessageWindow({
                    title: 'Emitir Boleto',
                    html: '<center><iframe width="230" src="modulos/informacoes/vencimentoAluguel.php" style="border: 0px;"></iframe></center>',
                    origin: {offY: -5, offX: -5},
                    autoHeight: true,
                    width: 250,
                    iconCls: 'botaoImovel',
                    help: false,
                    hideFx: {delay: 12000, mode: 'standard'},
                    listeners: {
                        render: function () {
//                            Ext.ux.Sound.play('sons/mensagem.wav');
                        }
                    }
                }).show(Ext.getDoc());
            }
        }
    });
}

function infoContratoVencimento() {


    Ext.Ajax.request({
        url: 'modulos/informacoes/verVencimentoContrato.php',
        callback: function (options, success, response) {
            var r = response.responseText;


            if (r == true) {
                new Ext.ux.window.MessageWindow({
                    title: 'Contrato com Vencimento',
                    html: '<center><iframe width="230" src="modulos/informacoes/vencimentoContrato.php" style="border: 0px;"></iframe></center>',
                    origin: {offY: -5, offX: -5},
                    autoHeight: true,
                    width: 250,
                    iconCls: 'botaoImovel',
                    help: false,
                    hideFx: {delay: 12000, mode: 'standard'},
                    listeners: {
                        render: function () {
//                            Ext.ux.Sound.play('sons/mensagem.wav');
                        }
                    }
                }).show(Ext.getDoc());
            }
        }
    });
}

function infoAniversario() {


    Ext.Ajax.request({
        url: 'modulos/informacoes/verAniversariante.php',
        callback: function (options, success, response) {
            var r = response.responseText;


            if (r == true) {
                new Ext.ux.window.MessageWindow({
                    title: 'Data de Nascimento',
                    html: '<center><iframe width="230" src="modulos/informacoes/aniversariante.php" style="border: 0px;"></iframe></center>',
                    origin: {offY: -5, offX: -5},
                    autoHeight: true,
                    width: 250,
                    iconCls: 'botaoEditar',
                    help: false,
                    hideFx: {delay: 12000, mode: 'standard'},
                    listeners: {
                        render: function () {
//                            Ext.ux.Sound.play('sons/mensagem.wav');
                        }
                    }
                }).show(Ext.getDoc());
            }
        }
    });
}