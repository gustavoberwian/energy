<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section role="main" class="content-body">
    <!-- start: page -->
    <header class="page-header" data-group="<?= $group_id ?>">
        <h2><?= $unidade->nome; ?></h2>
    </header>

    <section class="card card-easymeter mb-4">
        <header class="card-header">
            <div class="card-actions buttons">
            </div>
            <h2 class="card-title">Alertas</h2>
        </header>
        <div class="card-body">
            <table class="table table-bordered table-hover table-click" id="dt-alerts" data-url="/energia/GetAlerts">
                <thead>
                <tr role="row">
                    <th width="10%"></th>
                    <th width="60%">Mensagem</th>
                    <th width="20%">Enviada Em</th>
                    <th width="10%">Ações</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>
</section>