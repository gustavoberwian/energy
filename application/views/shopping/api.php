<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section role="main" class="content-body">
    <!-- start: page -->

    <img src="<?php echo base_url('assets/img/logo-north.png'); ?>" alt="<?= "North"; ?>" class="mb-4" height="80"/>

    <section class="card card-easymeter mb-4">
        <header class="card-header">
            <div class="card-actions"></div>
            <h2 class="card-title">APIs disponíveis - Energia</h2>
        </header>
        <div class="card-body documentation">
            <h4>Lista todos dispositivos disponíveis</h4>
            <blockquote class="mt-3">
                <p class="m-0 text-4"><b>http://www.easymeter.io/api/get/1.0/energy?q=list&appid=<span class="text-warning">{API key}</span></b></p>
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
                </tbody>
            </table>
            <h5>Resposta</h5>
            <div class="response">
                <div class="content">
                    <pre class="mb-0"><code>[
    {
        "device":"03D278DE",
        "name":"Casa de M\u00e1quina A14",
        "type":"1"
    },
    {
        "device":"03D2792A",
        "name":"Corredor Anaconda",
        "type":"1"
    }
]</code></pre>
               </div>            
        </div>
    </section>
</section>

<!--


http://www.easymeter.io/api/get/1.0/energy?q=list&appid=1

http://www.easymeter.io/api/get/1.0/energy?q=resume&appid=1

http://easymeter/api/get/1.0/energy?q=consumption&d=03D267C0&s=2023-02-10&e=2023-02-16&appid=1

http://easymeter/api/get/1.0/energy?q=active_demand&d=03D267C0&s=2023-02-10&e=2023-02-16&appid=1

http://easymeter/api/get/1.0/energy?q=reactive&d=03D25718&s=2023-02-11&e=2023-02-16&appid=1

-->