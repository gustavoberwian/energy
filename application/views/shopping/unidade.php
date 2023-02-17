<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section role="main" class="content-body">

    <header class="page-header">
        <h2><?= $unidade->nome; ?></h2>
    </header>

    <ul class="nav nav-pills nav-pills-primary mb-3">
        <li class="nav-item me-2" role="presentation">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#charts" type="button">Medição</button>
        </li>
        <li class="nav-item me-2" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#analysis" type="button">Analises</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#engineering" type="button">Engenharia</button>
        </li>
        <li class="nav-item me-2" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#data" type="button">Dados</button>
        </li>

    </ul>

    <div class="tab-content" style="background-color: transparent; box-shadow: none; padding: 0;">

        <div class="row pt-0 selector" style="display: none;">

            <div class="col-md-4 mb-4">
                <section class="card card-comparativo h-100">
                    <div class="card-body">
                        <h6 class="card-body-title mb-3 mt-0 text-primary">Leitura Atual</h6>
                        <div class="row">
                            <div class="h5 mb-0 mt-1"><span class="main">-</span></div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-4 mb-4">
                <section class="card card-comparativo h-100 h-100">
                    <div class="card-body" style="background-color: #03aeef;">
                        <h6 class="card-body-title mb-3 mt-0 text-light">Período <i class="float-end fas fa-calendar"></i></h6>
                        <div id="daterange-main" class="btn btn-light w-100 overflow-hidden" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <span></span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-4 mb-4">
                <section class="card card-comparativo h-100">
                    <div class="card-body">
                        <h6 class="card-body-title mb-3 mt-0 text-primary">Média Diária <i class="float-end fas fa-info-circle" data-bs-toggle="tooltip" title="Nos últimos 30 dias"></i></h6>
                        <div class="row">
                            <div class="col-lg-4 pr-1">
                                <div class="h5 mb-0 mt-1"><span class="day">-</span></div>
                                <p class="text-3 text-muted mb-0">Consumo</p>
                            </div>
                            <div class="col-lg-4 pr-1">
                                <div class="h5 mb-0 mt-1"><span class="day-f" style="color: #268ec3;">-</span></div>
                                <p class="text-3 text-muted mb-0">Fora Ponta</p>
                            </div>
                            <div class="col-lg-4 pr-1">
                                <div class="h5 mb-0 mt-1"><span class="day-p" style="color: #ff6178;">-</span></div>
                                <p class="text-3 text-muted mb-0">Ponta</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>

        <div class="row pt-0 consumption" style="display: none;">

            <div class="col-md-4 mb-4">
                <section class="card card-comparativo h-100">
                    <div class="card-body">
                        <h6 class="card-body-title mb-3 mt-0 text-primary">Consumo Total</h6>
                        <div class="row">
                            <div class="col-lg-4 pr-1">
                                <div class="h5 mb-0 mt-1"><span class="period">-</span></div>
                                <p class="text-3 text-muted mb-0">Período selecionado</p>
                            </div>
                            <div class="col-lg-4 pl-1">
                                <div class="h5 mb-0 mt-1"><span class="month">-</span></div>
                                <p class="text-3 text-muted mb-0">No mês atual</p>
                            </div>
                            <div class="col-lg-4 pl-1">
                                <div class="h5 mb-0 mt-1"><span class="prevision">-</span></div>
                                <p class="text-3 text-muted mb-0">Previsão no mês</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-4 mb-4">
                <section class="card card-comparativo h-100">
                    <div class="card-body">
                        <h6 class="card-body-title mb-3 mt-0 text-primary">Consumo Fora da Ponta</h6>
                        <div class="row">
                            <div class="col-lg-4 pr-1">
                                <div class="h5 mb-0 mt-1"><span class="period-f">-</span></div>
                                <p class="text-3 text-muted mb-0">Período selecionado</p>
                            </div>
                            <div class="col-lg-4 pl-1">
                                <div class="h5 mb-0 mt-1"><span class="month-f">-</span></div>
                                <p class="text-3 text-muted mb-0">No mês atual</p>
                            </div>
                            <div class="col-lg-4 pl-1">
                                <div class="h5 mb-0 mt-1"><span class="prevision-f">-</span></div>
                                <p class="text-3 text-muted mb-0">Previsão no mês</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-4 mb-4">
                <section class="card card-comparativo h-100">
                    <div class="card-body">
                        <h6 class="card-body-title mb-3 mt-0 text-primary">Consumo Ponta</h6>
                        <div class="row">
                            <div class="col-lg-4 pr-1">
                                <div class="h5 mb-0 mt-1"><span class="period-p">-</span></div>
                                <p class="text-3 text-muted mb-0">Período selecionado</p>
                            </div>
                            <div class="col-lg-4 pl-1">
                                <div class="h5 mb-0 mt-1"><span class="month-p">-</span></div>
                                <p class="text-3 text-muted mb-0">No mês atual</p>
                            </div>
                            <div class="col-lg-4 pl-1">
                                <div class="h5 mb-0 mt-1"><span class="prevision-p">-</span></div>
                                <p class="text-3 text-muted mb-0">Previsão no mês</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>

        <div class="tab-pane fade show active" id="charts">

            <div class="row pt-0">

                <div class="col-md-8 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Consumo Energia Ativa" data-bs-content="Gráfico de consumo de energia ativa do período selecionado."><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Consumo</h2>
                        </header>
                        <div class="card-body chart_activePositive-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainActivePositive"></div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-4 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Consumo Por Posto Tarifário" data-bs-content="Gráfico por posto tarifário de consumo de energia ativa do período selecionado."><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Consumo por Posto</h2>
                        </header>
                        <div class="card-body chart_station-body d-flex align-items-center" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainStation"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Demandas Máximas e Médias" data-bs-content="Gráfico de demandas máxima e média do período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Demandas</h2>
                        </header>
                        <div class="card-body chart_demand-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainActiveDemand"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Energia Reativa" data-bs-content="Gráfico de energia reativa no período selecionado, divido entre Reativa Capacitiva e Reativa Indutiva"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Energia Reativa</h2>
                        </header>
                        <div class="card-body chart_reactiv-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainReactive"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Fator de Potência" data-bs-content="Gráfico de fator de potência do período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Fator de Potência</h2>
                        </header>
                        <div class="card-body chart_factor1-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainFactor"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!--<div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Popover title" data-bs-content="And here's some amazing content. It's very engaging. Right?"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Mapa Calor Potência</h2>
                        </header>
                        <div class="card-body chart_heat_map_power-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div id="chart_heat_map_power" data-field="chart_heat_map_power" style="width: 100% !important; max-height: 100%; min-height: unset"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>-->

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Fator de Carga" data-bs-content="Gráfico de fator de carga do período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Fator de Carga</h2>
                        </header>
                        <div class="card-body chart_profile-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainLoad"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Emissão de Gás Carbônico" data-bs-content="Gráfico de emissão de Gás Carbônico (CO²) com base no consumo de energia ativa"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Pegada de Carbono </h2>
                        </header>
                        <div class="card-body chart_carbon1-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="mainCarbon"></div>
                            </div>
                        </div>
                    </section>
                    <h3></h3>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="analysis">

            <section class="card card-easymeter mb-4">
                <header class="card-header">
                    <div class="card-actions buttons">
                        <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Lista de Anomalias" data-bs-content="Lista de anomalias nas medidas com base nos valores passados no filtro"><i class="float-end fas fa-info-circle"></i></button>
                    </div>
                    <h2 class="card-title">Anomalias</h2>
                </header>
                <div class="card-body chart_abnormal-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>

                    <form class="row gx-3 gy-2 align-items-center">
                        <div class="col-sm-3">
                            <label class="control-label">Grandeza:</label>
                            <select class="form-control mb-3 type">
                                <option value="voltage">Tensão</option>
                                <option value="current">Corrente</option>
                                <option value="power">Potência</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Valores Válidos:</label>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <input type="text" placeholder="Mínimo" class="form-control" name="min" id="min" value="">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" placeholder="Máximo" class="form-control" name="max"  id="max" value="">
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-primary btn-view">Visualizar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped table-hover" id="dt-abnormal">
                        <thead>
                        <tr role="row">
                            <th rowspan="2">Data</th>
                            <th colspan="3">Tensão (V)</th>
                            <th colspan="3">Corrente (A)</th>
                            <th colspan="3">Potência Ativa Instantânea (kW)</th>
                            <th colspan="3">Potência Reativa Instantânea (kVAr)</th>
                            <th rowspan="2">Consumo (Wh)</th>
                        </tr>
                        <tr role="row">
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        <div class="tab-pane fade" id="engineering">

            <section class="card card-easymeter mb-4">
                <header class="card-header">
                    <div class="card-actions buttons">
                        <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Potência Instantânea Por Fase" data-bs-content="Gráfico de potência instantânea de energia ativa por fase do período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                    </div>
                    <h2 class="card-title">Potência Instantânea por Fase</h2>
                </header>
                <div class="card-body chart_active-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                    <div class="chart-container">
                        <div class="chart-main" data-field="active"></div>
                    </div>
                </div>
            </section>

            <div class="row pt-0">
                <div class="col-md-6 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Corrente Média" data-bs-content="Gráfico de corrente média dividido por fases no período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Corrente Média</h2>
                        </header>
                        <div class="card-body chart_current-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="current"></div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Tensão Média" data-bs-content="Gráfico de tensão média dividido por fases no período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Tensão Média</h2>
                        </header>
                        <div class="card-body chart_voltage-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="voltage"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Potência Máxima Instantânea" data-bs-content="Gráfico de potência máxima instantânea dividido por fases no período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Potência Máxima Instantânea</h2>
                        </header>
                        <div class="card-body chart_activedemand-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="power"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Fator de Carga Instantânea" data-bs-content="Gráfico de fator de carga instantânea dividido por fases no período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Fator de Carga Instantânea</h2>
                        </header>
                        <div class="card-body chart-factor-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="load"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Energia Reativa Instantânea" data-bs-content="Gráfico de energia reativa instantânea dividido por fases no período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Energia Reativa Instantânea</h2>
                        </header>
                        <div class="card-body chart-reactive-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="reactive"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Fator de Potência Instantâneo" data-bs-content="Gráfico de fator de potência instantâneo dividido por fases no período selecionado"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Fator de Potência Instantâneo</h2>
                        </header>
                        <div class="card-body chart-factor-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container">
                                <div class="chart-main" data-field="factor"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="data">

            <div class="row pt-0">
                <div class="col-md-12 mb-4">
                    <section class="card card-easymeter h-100 mb-4">
                        <header class="card-header">
                            <div class="card-actions buttons">
                                <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Consumo Últimas 24h" data-bs-content="Consumo de energia ativa das últimas 24 horas a cada 10 minutos"><i class="float-end fas fa-info-circle"></i></button>
                            </div>
                            <h2 class="card-title">Consumo Últimas 24h</h2>
                        </header>
                        <div class="card-body chart_consumption-body" data-loading-overlay data-loading-overlay-options='{ "css": { "backgroundColor": "#00000080" } }'>
                            <div class="chart-container" style="height: 200px;">
                                <div class="chart-main" data-field="consumption"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <section class="card card-easymeter mb-4">
                <header class="card-header">
                    <div class="card-actions buttons">
                        <button type="button" class="btn btn-lg btn-primary" data-bs-trigger="focus" data-bs-toggle="popover" title="Lista Leituras Detalhadas" data-bs-content="Lista de dados das leituras a cada 10 minutos."><i class="float-end fas fa-info-circle"></i></button>
                    </div>
                    <h2 class="card-title">Dados</h2>
                </header>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover" id="dt-data">
                        <thead>
                        <tr role="row">
                            <th rowspan="2">Data</th>
                            <th rowspan="2">Leitura (kWh)</th>
                            <th colspan="3">Tensão (V)</th>
                            <th colspan="3">Corrente (A)</th>
                            <th colspan="3">Potência Ativa Instantânea (kW)</th>
                            <th colspan="3">Potência Reativa Instantânea (kVAr)</th>
                            <th rowspan="2">Consumo (Wh)</th>
                        </tr>
                        <tr role="row">
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                            <th>Fase R</th>
                            <th>Fase S</th>
                            <th>Fase T</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</section>