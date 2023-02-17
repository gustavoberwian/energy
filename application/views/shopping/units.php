<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section role="main" class="content-body">
    <!-- start: page -->
    <header class="page-header">
        <h2><?= $group->group_name; ?> - Unidades</h2>
    </header>

    <div class="row pt-0">
        <section class="col-md-12 card card-easymeter h-auto mt-0 mb-3">
            <header class="card-header">
                <div class="card-actions buttons">
                    <button class="btn btn-primary btn-incluir-unidade"><i class="fa fa-plus"></i> Incluir Unidade</button>
                </div>
                <h2 class="card-title">Unidades</h2>
            </header>

            <div class="card-body">
                <div class="tab-form unidades">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable table-hover table-click no-footer" id="dt-unidades" data-url="/shopping/get_unidades" data-group="<?=$group_id ?>">
                            <thead>
                                <tr role="row">
                                    <th>Nome</th>
                                    <th>Monitora</th>
                                    <th>Tipo</th>
                                    <th>Localização</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modais Unidades -->
            <div class="modal" id="excluirUnidade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja excluir esse registro?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-danger">Excluir</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="editarUnidade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="max-width: 620px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#">
                            <div class="modal-body m-3">

                                <div class="form-group row">
                                    <label class="col-lg-3 control-label text-lg-right pt-2">Nome <span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control vcompetencia" name="nome_estabelecimento" value="" placeholder="Nome do estabelecimento" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control vcompetencia" name="complemento" value="" placeholder="complemento" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 control-label text-lg-right pt-2">Endereço <span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control " name="endereco" placeholder="Endereço" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success">Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>

    </div>

    <!-- end: page -->
</section>