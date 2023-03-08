<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

    <section role="main" class="content-body">
        <!-- start: page -->

        <header class="page-header" data-group="<?= $group_id ?>">
            <h2><?= $group->group_name; ?></h2>
        </header>

        <img src="<?php echo base_url('assets/img/logo-north.png'); ?>" alt="<?= "North"; ?>" class="mb-4" height="80"/>
        
        <div class="row pt-0">
            <div class="col-md-12 mb-4">
                <section class="card card-easymeter h-100">
                    <form class="form-config-geral">
                        <input type="hidden" value="<?= $group_id; ?>" id="group-id" name="group_id">
                        <header class="card-header">
                            <div class="card-actions"></div>
                            <h2 class="card-title">Geral</h2>
                        </header>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="ponta-start" class="form-label">Horário Início Ponta</label>
                                    <input id="ponta-start" value="<?= date('H:i', $client_config->ponta_start); ?>" name="ponta_start" type="time" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ponta-end" class="form-label">Horário Fim Ponta</label>
                                    <input id="ponta-end" value="<?= date('H:i', $client_config->ponta_end); ?>" name="ponta_end" type="time" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="area-comum" class="form-label">Identificador da Área Comum</label>
                                    <input id="area-comum" value="<?= $client_config->area_comum; ?>" name="area_comum" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="open" class="form-label">Horário Abertura Shopping</label>
                                    <input id="open" value="<?= date('H:i', $client_config->open); ?>" name="open" type="time" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="close" class="form-label">Horário Fechamento Shopping</label>
                                    <input id="close" value="<?= date('H:i', $client_config->close); ?>" name="close" type="time" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="split-report" class="form-label">Separar Área Comum e Unidades nos relatórios</label></br>
                                    <div class="switch switch-sm switch-primary">
                                        <input type="checkbox" <?= $client_config->split_report ? 'checked' : ''; ?> class="switch-input" id="split-report" name="split_report" data-plugin-ios-switch>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-primary btn-save btn-save-geral">Salvar</button>
                            <button type="reset" class="btn btn-default btn-reset">Descartar</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <div class="row pt-0" id="usuarios">
            <div class="col-md-12 mb-4">
                <section class="card card-users card-easymeter h-100">
                    <header class="card-header">
                        <div class="card-actions buttons">
                            <?php if ($this->ion_auth->in_group('entity_shopping')): ?>
                                <button class="btn btn-primary btn-new-user">Criar Usuário</button>
                            <?php endif; ?>
                        </div>
                        <h2 class="card-title">Usuários</h2>
                    </header>
                    <div class="card-body">
                        <div class="tab-form agrupamentos">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped dataTable table-hover no-footer"
                                       id="dt-usuarios" data-url="/shopping/get_users">
                                    <thead>
                                    <tr role="row">
                                        <th class="text-center">Nome</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a class="nav-link" data-bs-target="#energia" href="#energia" data-bs-toggle="tab">Energia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-target="#agua" href="#agua" data-bs-toggle="tab">Água</a>
                </li>
            </ul>

            <div class="tab-content configs">
                <div id="energia" class="tab-pane active">
                    <div class="row pt-0">
                        <div class="col-md-12 mb-4">
                            <section class="card card-agrupamentos card-easymeter h-100">
                                <header class="card-header">
                                    <div class="card-actions buttons">
                                    </div>
                                    <h2 class="card-title">Unidades</h2>
                                </header>
                                <div class="card-body bordered">
                                    <div class="tab-form agrupamentos h-100">
                                        <div class="table-responsive h-100">
                                            <table class="table table-bordered table-striped dataTable table-hover no-footer" id="dt-unidades-energia" data-url="/shopping/get_unidades" data-tipo="energia">
                                                <thead>
                                                <tr role="row">
                                                    <th class="d-none">id</th>
                                                    <th class="text-center">Medidor</th>
                                                    <th class="text-center">LUC</th>
                                                    <th class="text-center">Subtipo</th>
                                                    <th class="text-center">Tipo</th>
                                                    <th class="text-center">Identificador</th>
                                                    <th class="text-center">Localizador</th>
                                                    <th class="text-center">Capacidade</th>
                                                    <th class="text-center">Lançamentos</th>
                                                    <th class="text-center">Ações</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="row pt-0">
                        <div class="col-md-12 mb-4">
                            <section class="card card-agrupamentos card-easymeter h-100">
                                <header class="card-header">
                                    <div class="card-actions buttons">
                                        <button class="btn btn-primary btn-new-agrupamento-energia">Criar Agrupamento</button>
                                    </div>
                                    <h2 class="card-title">Agrupamentos</h2>
                                </header>
                                <div class="card-body bordered">
                                    <div class="tab-form agrupamentos h-100">
                                        <div class="table-responsive h-100" style="min-height: 230px;">
                                            <table class="table table-bordered table-striped dataTable table-hover table-click no-footer"
                                                   id="dt-agrupamentos-energia" data-url="/shopping/get_agrupamentos" data-tipo="energia">
                                                <thead>
                                                <tr role="row">
                                                    <th class="d-none"></th>
                                                    <th class="d-none"></th>
                                                    <th class="text-center">Grupo</th>
                                                    <th class="text-center">Unidades</th>
                                                    <th class="text-center">Ações</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <?php if ($this->ion_auth->in_group("entity_shopping")): ?>
                        <div class="row pt-0" id="alertas-energia">
                            <div class="col-md-12 mb-4">
                                <section class="card card-unidades card-easymeter h-100">
                                    <header class="card-header">
                                        <div class="card-actions buttons">
                                        </div>
                                        <h2 class="card-title">Configurações dos Alertas</h2>
                                    </header>
                                    <div class="card-body bordered">
                                        <div class="tab-form agrupamentos">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped dataTable table-hover no-footer"
                                                       id="dt-alertas-conf-energia" data-url="/shopping/get_alertas_conf" data-tipo="energia">
                                                    <thead>
                                                    <tr role="row">
                                                        <th rowspan="2" class="d-none"></th>
                                                        <th rowspan="2" class="d-none"></th>
                                                        <th rowspan="2" class="text-center">Status</th>
                                                        <th rowspan="2" class="text-center">Alerta</th>
                                                        <th rowspan="2" class="text-center">Medidores</th>
                                                        <th rowspan="2" class="text-center">Quando</th>
                                                        <th colspan="2" class="text-center">Notificar</th>
                                                        <th rowspan="2" class="text-center">Ações</th>
                                                    </tr>
                                                    <tr role="row">
                                                        <th>Shopping</th>
                                                        <th>Unidade</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div id="agua" class="tab-pane">
                    <div class="row pt-0">
                        <div class="col-md-12 mb-4">
                            <section class="card card-agrupamentos card-easymeter h-100">
                                <header class="card-header">
                                    <div class="card-actions buttons">
                                    </div>
                                    <h2 class="card-title">Unidades</h2>
                                </header>
                                <div class="card-body bordered">
                                    <div class="tab-form agrupamentos h-100">
                                        <div class="table-responsive h-100">
                                            <table class="table table-bordered table-striped dataTable table-hover table-click no-footer"
                                                   id="dt-unidades-agua" data-url="/shopping/get_unidades" data-tipo="agua">
                                                <thead>
                                                <tr role="row">
                                                    <th class="d-none">id</th>
                                                    <th class="text-center">Medidor</th>
                                                    <th class="text-center">LUC</th>
                                                    <th class="text-center">Subtipo</th>
                                                    <th class="text-center">Tipo</th>
                                                    <th class="text-center">Identificador</th>
                                                    <th class="text-center">Localizador</th>
                                                    <th class="text-center d-none">Capacidade</th>
                                                    <th class="text-center">Lançamentos</th>
                                                    <th class="text-center">Ações</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="row pt-0">
                        <div class="col-md-12 mb-4">
                            <section class="card card-agrupamentos card-easymeter h-100">
                                <header class="card-header">
                                    <div class="card-actions buttons">
                                        <button class="btn btn-primary btn-new-agrupamento-agua">Criar Agrupamento</button>
                                    </div>
                                    <h2 class="card-title">Agrupamentos</h2>
                                </header>
                                <div class="card-body bordered">
                                    <div class="tab-form agrupamentos h-100">
                                        <div class="table-responsive h-100">
                                            <table class="table table-bordered table-striped dataTable table-hover table-click no-footer"
                                                   id="dt-agrupamentos-agua" data-url="/shopping/get_agrupamentos" data-tipo="agua">
                                                <thead>
                                                <tr role="row">
                                                    <th class="d-none"></th>
                                                    <th class="d-none"></th>
                                                    <th class="text-center">Grupo</th>
                                                    <th class="text-center">Unidades</th>
                                                    <th class="text-center">Ações</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <?php if ($this->ion_auth->in_group("entity_shopping")): ?>
                        <div class="row pt-0" id="alertas-agua">
                            <div class="col-md-12 mb-4">
                                <section class="card card-unidades card-easymeter h-100">
                                    <header class="card-header">
                                        <div class="card-actions buttons">
                                        </div>
                                        <h2 class="card-title">Configurações dos Alertas</h2>
                                    </header>
                                    <div class="card-body bordered">
                                        <div class="tab-form agrupamentos">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped dataTable table-hover no-footer"
                                                       id="dt-alertas-conf-agua" data-url="/shopping/get_alertas_conf" data-tipo="agua">
                                                    <thead>
                                                    <tr role="row">
                                                        <th rowspan="2" class="d-none"></th>
                                                        <th rowspan="2" class="d-none"></th>
                                                        <th rowspan="2" class="text-center">Status</th>
                                                        <th rowspan="2" class="text-center">Alerta</th>
                                                        <th rowspan="2" class="text-center">Medidores</th>
                                                        <th rowspan="2" class="text-center">Quando</th>
                                                        <th colspan="2" class="text-center">Notificar</th>
                                                        <th rowspan="2" class="text-center">Ações</th>
                                                    </tr>
                                                    <tr role="row">
                                                        <th>Shopping</th>
                                                        <th>Unidade</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <div class="row pt-0">
            <div class="col-md-12 mb-4">
                <section class="card card-easymeter h-100">
                    <form class="form-config-api" data-grupo="<?= $group_id; ?>">
                        <input type="hidden" value="<?= $group_id; ?>" id="group-id" name="group_id">
                        <header class="card-header">
                            <div class="card-actions"></div>
                            <h2 class="card-title">API</h2>
                        </header>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="token" class="form-label">Chave</label>
                                    <div class="row">
                                        <div class="col-md-8 input-group">
                                            <input onClick="this.select();"  id="token" value="<?= $token ?>" name="token" type="text" class="form-control" placeholder="" aria-describedby="button-addon2">
                                            <button class="btn btn-primary btn-generate-token <?= $token ? 'renew' : '' ?>" type="button-addon2"><?= $token ? 'Renovar Chave' : 'Gerar Chave' ?></button>
                                        </div>
                                        <a href="/api/doc" class="text-right" target="_blank">Documentação <i class="fas fa-arrow-up-right-from-square"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <!-- end: page -->

        <div class="mt-3">
            <table class="text-dark w-100">
                <tbody><tr>
                    <td>
                        <img src="<?php echo base_url('assets/img/logo-ancar.png'); ?>" alt="<?= "Ancar"; ?>" class="mb-4" height="35"/>
                    </td>
                    <td class="text-end">
                        <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="<?= "Easymeter"; ?>" class="mb-4" height="35"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </section>

<?php
$data['modal_id'] = 'modalExcluiUser';
$data['modal_title'] = 'Você tem certeza?';
$data['modal_message'] = 'Deseja realmente excluir este Usuário?';
$data['button'] = array('Excluir', 'Cancelar');
$this->load->view('modals/confirm', $data);
?>

<?php
$data_unidade['modal_id'] = 'modalExcluiUnidade';
$data_unidade['modal_title'] = 'Você tem certeza?';
$data_unidade['modal_message'] = 'Deseja realmente excluir esta Unidade?';
$data_unidade['button'] = array('Excluir', 'Cancelar');
$this->load->view('modals/confirm', $data_unidade);
?>

<?php
$data_unidade['modal_id'] = 'modalExcluiAgrupamentoAgua';
$data_unidade['modal_title'] = 'Você tem certeza?';
$data_unidade['modal_message'] = 'Deseja realmente excluir este agrupamento?';
$data_unidade['button'] = array('Excluir', 'Cancelar');
$this->load->view('modals/confirm', $data_unidade);
?>

<?php
$data_unidade['modal_id'] = 'modalExcluiAgrupamentoEnergia';
$data_unidade['modal_title'] = 'Você tem certeza?';
$data_unidade['modal_message'] = 'Deseja realmente excluir este agrupamento?';
$data_unidade['button'] = array('Excluir', 'Cancelar');
$this->load->view('modals/confirm', $data_unidade);
?>

<?php
$dataKey['modal_id'] = 'modalGenerateKey';
$dataKey['modal_title'] = 'Você tem certeza?';
$dataKey['modal_message'] = 'Ao renovar, sua chave atual será invalidada, continuar?';
$dataKey['button'] = array('Renovar', 'Cancelar');
$this->load->view('modals/confirm_key', $dataKey);
?>
