<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
    body {
        background: #000524;
    }
    #wrapper {
        padding-top: 20px;
        background: #000524;
        border: 1px solid #000;
        box-shadow: 0 22px 35px -16px rgba(0, 0, 0, 0.71);
        max-width: 650px;
        margin: 35px auto;
    }
    #chart-bar {
        position: relative;
        margin-top: -38px;
    }
    ::-webkit-scrollbar {
        display: none;
    }
</style>
<section role="main" class="content-body p-4">

    <div class="row h-100 align-content-between">
        <div class="col-md-9 d-flex h-100 flex-column justify-content-between">
            <div class="row pb-0">
                <?php foreach ($unidades as $unidade) : ?>
                    <section class="card card-easymeter mt-0 pb-4 card-unidade col-md-2" data-unidade="<?= $unidade->unidade_id ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="font-weight-semibold text-dark text-uppercase mb-0 mt-0"><?= $unidade->unidade_nome ?></h5>
                                <div class="h6 font-weight-bold mb-0 mt-0 status"></div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <h5 class="font-weight-semibold text-dark text-uppercase mb-0 mt-0 last-unidade"></h5>
                                    <h6 class="font-weight-normal text-dark mb-0 mt-0">Último envio</h6>
                                </div>
                                <div class="col-md">
                                    <h5 class="font-weight-semibold text-dark text-uppercase mb-0 mt-0 total-unidade"></h5>
                                    <h6 class="font-weight-normal text-dark mb-0 mt-0">Total Desde às 0h</h6>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
            <section class="card card-easymeter mb-0 mt-0">
                <div class="card-body chart_activePositive-body" data-loading-overlay="" data-loading-overlay-options="{ &quot;css&quot;: { &quot;backgroundColor&quot;: &quot;#00000080&quot; } }" style="">
                    <div class="chart-container h-auto">
                        <div class="chart-main" data-field="mainActivePositive"></div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3">
            <section class="card card-easymeter mt-0 h-100">
                <div class="card-body">
                    <h5 class="font-weight-semibold text-dark text-uppercase mb-3 mt-0">Alertas</h5>
                    <!--<div class="row demo">
                        <div class="col-12">
                            <section class="card card-featured-left card-featured-primary mb-3">
                                <div class="card-body bg-quaternary">
                                    <div class="widget-summary">
                                        <div class="widget-summary-col widget-summary-col-icon align-middle">
                                            <div class="summary-icon">
                                                <i class="fas fa-info text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="widget-summary-col">
                                            <div class="summary d-flex flex-column justify-content-center">
                                                <h4 class="title">
                                                    <strong class="amount">Teste</strong>
                                                </h4>
                                                <div class="info">
                                                    testetsetestse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="summary-footer">
                                        <span class="text-uppercase">3</span>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>-->
                    <?php foreach ($alertas as $alerta) : ?>
                        <?php
                        $status = "primary";
                        $icon = "fa-info";
                        if ($alerta->status === 'aviso') {
                            $status = "warning";
                            $icon = "fa-exclamation";
                        } elseif ($alerta->status === 'vazamento') {
                            $status = "danger";
                            $icon = "fa-exclamation-triangle";
                        }
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <section class="card card-featured-left card-featured-<?= $status ?> mb-3">
                                    <div class="card-body bg-quaternary">
                                        <div class="widget-summary">
                                            <div class="widget-summary-col widget-summary-col-icon align-middle">
                                                <div class="summary-icon">
                                                    <i class="fas <?= $icon ?> text-<?= $status ?>"></i>
                                                </div>
                                            </div>
                                            <div class="widget-summary-col">
                                                <div class="summary d-flex flex-column justify-content-center">
                                                    <h4 class="title">
                                                        <strong class="amount"><?= $alerta->titulo ?></strong>
                                                    </h4>
                                                    <div class="info">
                                                        <?= $alerta->texto ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="summary-footer">
                                            <span class="text-uppercase"><?= $alerta->enviada ?></span>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
</section>