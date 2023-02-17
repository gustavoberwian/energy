<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section role="main" class="content-body">

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item me-2" role="energia">
            <button class="nav-link active pill-energy" id="pills-energia" data-bs-toggle="pill" data-bs-target="#energia" type="button" role="tab" aria-controls="energia" aria-selected="true">Energia</button>
        </li>
        <li class="nav-item" role="agua">
            <button class="nav-link pill-water" id="pills-agua" data-bs-toggle="pill" data-bs-target="#agua" type="button" role="tab" aria-controls="agua" aria-selected="false">Água</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="energia" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="row">
                <section class="card card-easymeter mb-4 col-6">
                    <div class="card-body documentation">
                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://www.easymeter.io/api/get/1.0/energy?q=<span class="text-primary">list</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td><code>q</code></td>
                                <td><span class="sub">fixo</span></td>
                                <td>Lista todos dispositivos disponíveis</td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    status: "success",
    data: [
        {
            "device": "03D24E7A",
            "name": "Local 01",
            "type": "1" <span class="text-success">// 1: Área Comum 2: Unidade</span>
        },
        {
            "device": "03D244AC",
            "name": "Local 02",
            "type": "1"
        }
    ]
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">
                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://www.easymeter.io/api/get/1.0/energy?q=<span class="text-primary">resume</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td><code>q</code></td>
                                <td><span class="sub">fixo</span></td>
                                <td>Obtém os dados de consumo dos medidores no mês atual</td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    status: "success",
    "data": [
        {
            "device": "03D24E7A",
            "name": "Local 01",
            "type": "1",                <span class="text-success">// 1: Área Comum 2: Unidade</span>
            "current": "028941",        <span class="text-success">// Leitura atual do medidor - kWh</span>
            "month":"5341.834",         <span class="text-success">// Consumo no mês - kWh</span>
            "month_opened":"4147.885",  <span class="text-success">// Consumo com o shopping aberto - kWh</span>
            "month_closed":"1193.949",  <span class="text-success">// Consumo com o shopping fechado - kWh</span>
            "ponta":"1023.023",         <span class="text-success">// Consumo em horário de ponta - kWh</span>
            "fora":"4318.811",          <span class="text-success">// Consumo em horário fora de ponta - kWh</span>
            "last": 501.680,            <span class="text-success">// Consumo nas últimas 24 horas - kWh</span>
            "prevision":"8798.315"      <span class="text-success">// Previsão de consumo no mês - kWh</span>
        }
        ...
    ]
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </section>


                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">
                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">consumption</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td><code>q</code></td>
                                <td><span class="sub">fixo</span></td>
                                <td>Obtém o consumo do medidor no período, separado por posto tarifárico</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fora Ponta",           <span class="text-success">// Posto tarifárico</span>
            "data": [
                ["2023-02-01",385.528],     <span class="text-success">// Data/Hora e Consumo no dia/hora</span>
                ["2023-02-02",352.965]
            ]
        },
        {
            "name": "Ponta",
            "data": [
                ["2023-02-01",115.692],
                ["2023-02-02",121.365]
            ]
        }
    ],
    "unity":"kWh"                           <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>


                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">
                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">active_demand</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td><code>q</code></td>
                                <td><span class="sub">fixo</span></td>
                                <td>Obtém a demanda de energia ativa do medidor no período</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Demanda Máxima",
            "data": [
                ["2023-02-01",38.133],      <span class="text-success">// Data/Hora e Demanda Máxima no dia/hora</span>
                ["2023-02-02",37.687]
            ]
        },
        {
            "name":"Demanda Média",         <span class="text-success">// Data/Hora e Demanda Média no dia/hora</span>
            "data":[
                ["2023-02-01",20.862],
                ["2023-02-02",19.739]
            ]
        }
    ],
    "unity":"kW"                            <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">reactive</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Reativa Capacitiva",
            "data": [
                ["2023-02-01",17.799],  <span class="text-success">// Data/Hora e Reativa Capacitiva no dia/hora</span>
                ["2023-02-02",18.202]
            ]
        },
        {
            "name":"Reativa Indutiva",  <span class="text-success">// Data/Hora e Reativa Indutiva no dia/hora</span>
            "data":[
                ["2023-02-01",180.722],
                ["2023-02-02",161.989]
            ]
        }
    ],
    "unity":"kVArh"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">load</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fator de Carga",
            "data": [
                ["2023-02-01",0.547],  <span class="text-success">// Data/Hora e Fator de Carga no dia/hora</span>
                ["2023-02-02",0.520]
            ]
        },
    ],
    "unity":"kVArh"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_active</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",1242.342],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",1196.354]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",807.835],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",742.053]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",947.628],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",904.828]
            ]
        },
    ],
    "unity":"kW"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_current</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",41.323],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",39.556]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",28.792],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",26.388]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",32.973],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",31.419]
            ]
        },
    ],
    "unity":"A"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_voltage</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",219.898],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",220.707]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",221.417],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",222.142]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",218.945],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",219.528]
            ]
        },
    ],
    "unity":"V"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_power</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",14.642],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",13.908]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",12.228],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",11.537]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",13.271],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",13.157]
            ]
        },
    ],
    "unity":"kW"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_load</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",0.589],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",0.597]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",0.459],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",0.447]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",0.496],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",0.478]
            ]
        },
    ],
    "unity":""                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_reactive</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",1242.342],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",1196.354]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",807.835],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",742.053]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",947.628],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",904.828]
            ]
        },
    ],
    "unity":"kVAr"                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">

                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://easymeter/api/get/1.0/energy?q=<span class="text-primary">instant_factor</span>&d=<span class="text-warning">device</span>&s=<span class="text-warning">data inicio</span>&e=<span class="text-warning">data fim</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <h4>Obtém a demanda de energia ativa do medidor no período</h4>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>d</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Código identificador do medidor</td>
                            </tr>
                            <tr>
                                <td><code>s</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data inicial do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>e</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Data final do período desejado no formato YYYY-MM-DD</td>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td colspan="3">Se a data inicial e final forem iguais, os resultados serão retornados por hora.</td>
                            </tr>

                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    "status": "success",
    "data": [
        {
            "name": "Fase R",
            "data": [
                ["2023-02-01",0.952],  <span class="text-success">// Data/Hora e Fase R no dia/hora</span>
                ["2023-02-02",0.957]
            ]
        },
        {
            "name": "Fase S",
            "data": [
                ["2023-02-01",0.885],  <span class="text-success">// Data/Hora e Fase S no dia/hora</span>
                ["2023-02-02",0.888]
            ]
        },
        {
            "name": "Fase T",
            "data": [
                ["2023-02-01",0.933],  <span class="text-success">// Data/Hora e Fase T no dia/hora</span>
                ["2023-02-02",0.938]
            ]
        },
    ],
    "unity":""                     <span class="text-success">// Unidade de medida dos valores</span>
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

        <div class="tab-pane fade" id="agua" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="row">
                <section class="card card-easymeter mb-4 col-6">
                    <div class="card-body documentation">
                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://www.easymeter.io/api/get/1.0/water?q=<span class="text-primary">list</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td><code>q</code></td>
                                <td><span class="sub">fixo</span></td>
                                <td>Lista todos dispositivos disponíveis</td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    status: "success",
    data: [
        {
            "device": "03D24E7A",
            "name": "Local 01",
            "type": "1" <span class="text-success">// 1: Área Comum 2: Unidade</span>
        },
        {
            "device": "03D244AC",
            "name": "Local 02",
            "type": "1"
        }
    ]
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card card-easymeter mt-0 mb-4 col-6">
                    <div class="card-body documentation">
                        <blockquote class="mt-3">
                            <p class="m-0 text-3"><b>http://www.easymeter.io/api/get/1.0/energy?q=<span class="text-primary">resume</span>&appid=<span class="text-warning">{API key}</span></b></p>
                        </blockquote>
                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Parâmetros</th>
                            </tr>
                            <tr>
                                <td><code>appid</code></td>
                                <td><span class="sub">obrigatório</span></td>
                                <td>Sua API key única (ela encontra-se no seu painel, no item configurações)</td>
                            </tr>
                            <tr>
                                <td><code>q</code></td>
                                <td><span class="sub">fixo</span></td>
                                <td>Obtém os dados de consumo dos medidores no mês atual</td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="api-table">
                            <tbody>
                            <tr>
                                <th colspan="3">Resposta</th>
                            </tr>
                            <tr>
                                <td>
                                        <pre class="mb-0"><code class="answer">{
    status: "success",
    "data": [
        {
            "device": "03D24E7A",
            "name": "Local 01",
            "type": "1",                <span class="text-success">// 1: Área Comum 2: Unidade</span>
            "current": "028941",        <span class="text-success">// Leitura atual do medidor - L</span>
            "month":"5341.834",         <span class="text-success">// Consumo no mês - L</span>
            "month_opened":"4147.885",  <span class="text-success">// Consumo com o shopping aberto - L</span>
            "month_closed":"1193.949",  <span class="text-success">// Consumo com o shopping fechado - L</span>
            "ponta":"1023.023",         <span class="text-success">// Consumo em horário de ponta - L</span>
            "fora":"4318.811",          <span class="text-success">// Consumo em horário fora de ponta - L</span>
            "last": 501.680,            <span class="text-success">// Consumo nas últimas 24 horas - L</span>
            "prevision":"8798.315"      <span class="text-success">// Previsão de consumo no mês - L</span>
        }
        ...
    ]
}</code></pre>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<!--


http://www.easymeter.io/api/get/1.0/energy?q=list&appid=1

http://www.easymeter.io/api/get/1.0/energy?q=resume&appid=1

http://easymeter/api/get/1.0/energy?q=consumption&d=03D267C0&s=2023-02-10&e=2023-02-16&appid=1

http://easymeter/api/get/1.0/energy?q=active_demand&d=03D267C0&s=2023-02-10&e=2023-02-16&appid=1

http://easymeter/api/get/1.0/energy?q=reactive&d=03D25718&s=2023-02-11&e=2023-02-16&appid=1

-->