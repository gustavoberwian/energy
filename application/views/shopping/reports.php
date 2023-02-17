<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Relatórios</h2>
        <div class="right-wrapper text-right">
            <ol class="breadcrumbs">
                <li><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
                <li><span>Relatórios</span></li>
            </ol>
        </div>
    </header>
    <!-- start: page -->

    <div class="form-group text-lg-right row">
        <div class="col-lg-12">
            <label class="control-label pt-2 pr-2">Competência:</label>
            <div class="btn-group">
                <select class="form-control competencia">
                    <?php if ($competencias) : ?>
                        <?php foreach ($competencias as $c) { ?>
                            <option value="<?php echo $c->competencia; ?>" data-comp="<?php echo $c->id; ?>"><?= competencia_nice($c->competencia); ?></option>
                        <?php } ?>
                    <?php endif; ?>
                </select>
                <div class="btn-group-append">
                    <button class="btn btn-primary ml-2 btn-download" data-loading-overlay><i class="fas fa-file-download mr-3"></i> Baixar Planilha</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row pt-0">
        <div class="col-6">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Energia</h2>
                    <p class="card-subtitle">Unidades com consumo de energia</p>
                </header>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover table-clickable" id="dt-relatorios" data-url="<?php echo site_url('hortolandia/get_relatorios_energia'); ?>">
                        <thead>
                        <tr role="row">
                            <th width="25%">Unidade</th>
                            <th width="25%">Energia</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <!-- end: page -->
</section>