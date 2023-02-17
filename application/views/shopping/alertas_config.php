<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section role="main" class="content-body">
    <!-- start: page -->
    <header class="page-header">
        <h2><?= $group->group_name; ?> - Alertas</h2>
    </header>

    <section class="card card-easymeter mb-4">
        <header class="card-header">
            <div class="card-actions buttons">
            </div>
            <h2 class="card-title">Configurações dos Alertas</h2>
        </header>
        <div class="card-body">

            <table class="table table-no-more table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th class=""></th>
                        <th class="">Alerta</th>
                        <th class="">Medidores</th>
                        <th class="">Quando</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-title="Code" class="text-center" style="width: 100px;">
                            <div class="switch switch-sm switch-primary">
                                <input type="checkbox" name="switch" data-plugin-ios-switch checked="checked" />
                            </div>
                        </td>
                        <td data-title="Company" class="" style="padding-top: 14px;">
                            Emitir alerta quando o consumo no dia for maior que a média
                            <span id="m-a-1" style="display: none;"><br/><label id="fullname-error" class="error" for="fullname">This field is required.</label></span>
                        </td>
                        <td data-title="Price" class="text-end" style="width: 270px;">
                            <select class="form-control" multiple="multiple" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200, "buttonWidth": 250, "numberDisplayed": 1, "includeSelectAllOption": true }' id="ms_example5">
                                <?php foreach ($unidades as $u) { ?>
                                    <option value="<?= $u["medidor_id"] ?>"><?= $u["unidade_nome"]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td data-title="Change" class="text-end" style="width: 130px;">
                            <select class="form-control period" id="p-a-1" data-msg="m-a-1" disabled>
                                <option value="1">No Dia</option>
                                <option value="2">Na Hora</option>
                                <option value="3">No Instante</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td data-title="Code" class="text-center" style="width: 100px;">
                            <div class="switch switch-sm switch-primary">
                                <input type="checkbox" name="switch" data-plugin-ios-switch checked="checked" />
                            </div>
                        </td>
                        <td data-title="Company" class="" style="padding-top: 14px;">
                            Emitir alerta quando o consumo previsto for maior do que no mês anterior
                            <span id="m-a-2" style="display: none;"><br/><label id="fullname-error" class="error" for="fullname">This field is required.</label></span>
                        </td>
                        <td data-title="Price" class="text-end" style="width: 270px;">
                            <select class="form-control" multiple="multiple" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200, "buttonWidth": 250, "numberDisplayed": 1, "includeSelectAllOption": true }' id="ms_example5">
                                <?php foreach ($unidades as $u) { ?>
                                    <option value="<?= $u["medidor_id"] ?>"><?= $u["unidade_nome"]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td data-title="Change" class="text-end" style="width: 130px;">
                            <select class="form-control period" id="p-a-2" data-msg="m-a-2" disabled>
                                <option value="1">No Dia</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td data-title="Code" class="text-center" style="width: 100px;">
                            <div class="switch switch-sm switch-primary">
                                <input type="checkbox" name="switch" data-plugin-ios-switch checked="checked" />
                            </div>
                        </td>
                        <td data-title="Company" class="" style="padding-top: 14px;">
                            Emitir alerta quando o fator de potência estiver fora do limites
                            <span id="m-a-3" style="display: none;"><br/><label id="fullname-error" class="error" for="fullname">This field is required.</label></span>
                        </td>
                        <td data-title="Price" class="text-end" style="width: 270px;">
                            <select class="form-control" multiple="multiple" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200, "buttonWidth": 250, "numberDisplayed": 1, "includeSelectAllOption": true }' id="ms_example5">
                                <?php foreach ($unidades as $u) { ?>
                                    <option value="<?= $u["medidor_id"] ?>"><?= $u["unidade_nome"]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td data-title="Change" class="text-end" style="width: 130px;">
                            <select class="form-control period" id="p-a-3" data-msg="m-a-3">
                                <option value="1">No Dia</option>
                                <option value="2">Na Hora</option>
                                <option value="3">No Instante</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary">Salvar</button>
        </div>        
    </section>
</section>