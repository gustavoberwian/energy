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
        <div class="col-md-8 d-flex h-100 flex-column justify-content-between">
            <div class="row pb-0">
                <?php foreach ($unidades as $i => $unidade) : ?>
                    <?php if ($i == $max): break; endif; ?>
                    <div class="flip" style="width: 20%">
                        <div class="front pe-4">
                            <section class="card card-easymeter mt-0 pb-4 card-unidade" data-unidade="<?= $unidade->unidade_id ?>">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="font-weight-semibold text-dark text-uppercase mb-0 mt-0"><?= $unidade->unidade_nome ?></h5>
                                        <div class="h6 font-weight-bold mb-0 mt-0 status"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <h5 class="font-weight-semibold text-dark text-uppercase mb-0 mt-0 last-unidade"></h5>
                                            <div class="sse font-weight-normal mb-0 mt-0" style="line-height: 18px">Último envio</div>
                                        </div>
                                        <div class="col-md">
                                            <h5 class="font-weight-semibold text-dark text-uppercase mb-0 mt-0 total-unidade"></h5>
                                            <div class="sse font-weight-normal mb-0 mt-0" style="line-height: 18px">Total Desde às 0h</div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="back">
                            <section class="card card-easymeter mt-0 pb-4 card-unidade" data-unidade="<?= $unidades[$i+2]->unidade_id ?? $unidades[$i+2-count($unidades)]->unidade_id ?>">
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
                        </div>
                    </div>
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
        <div class="col-md-4">
            <section class="card card-easymeter mt-0 h-100">
                <div class="card-body">
                    <h5 class="font-weight-semibold text-dark text-uppercase mb-3 mt-0">Alertas</h5>
                    <div class="body-alerts"></div>
                </div>
            </section>
        </div>
    </div>
</section>