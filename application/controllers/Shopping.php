<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping extends Shopping_Controller
{
    public function __construct()
    {
        parent::__construct();

        // carrega models
        $this->load->model('energy_model');
        $this->load->model('water_model');
        $this->load->model('shopping_model');

        // carrega Datatables library
        $this->load->library('Datatables');
    }

    public function index()
    {
        $this->setHistory('Acesso à página inicial', 'acesso');

        if ($this->ion_auth->in_group('entity_shopping')) {

            $data['entity_id'] = $this->shopping_model->get_entity_by_user($this->user->id);
            $data['groups'] = $this->shopping_model->get_groups_by_entity($data['entity_id']);
            $data['overall_c'] = $this->energy_model->GetOverallConsumption(1);
            $data['overall_l'] = $this->energy_model->GetOverallConsumption(2);
            $data['area_comum'] = $this->user->config->area_comum;
            $this->render("index", $data);
        } else if ($this->ion_auth->in_group('group_shopping')) {

            $group_id = $this->shopping_model->get_group_by_user($this->user->id);
            redirect('shopping/energy/' . $group_id, 'refresh');
        } else if ($this->ion_auth->in_group('unity_shopping')) {

            $unidade_id = $this->shopping_model->get_unidade_id_by_user($this->user->id);
            $group_id = $this->shopping_model->get_group_id_by_unity($unidade_id);
            redirect('shopping/unidade/' . $group_id . '/' . $unidade_id, 'refresh');
        }
    }

    public function api($group_id)
    {
        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);

        $this->render("api", $data);
    }

    public function energy($group_id)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso ao dashboard de energia do shopping $group_id", 'acesso');

        $data['permission'] = $this->get_user_permission($this->user->id);

        $unidades = $this->shopping_model->get_unidades($group_id);
        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);

        $data['user']    = $this->user;

        $data['unidades'] = $this->shopping_model->get_units($group_id, "energia");
        $data['device_groups'] = $this->shopping_model->get_device_groups(72);

        $data['area_comum'] = $this->user->config->area_comum;

        $this->render('energy', $data);
    }

    public function water($group_id)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso ao dashboard de água do shopping $group_id", 'acesso');

        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);

        $data['user']    = $this->user;

        $data['unidades'] = $this->shopping_model->get_units($group_id, "agua");
        $data['device_groups'] = $this->shopping_model->get_device_groups(73);

        $data['area_comum'] = $this->user->config->area_comum;

        $this->render('water', $data);
    }

    public function unidade($group_id, $unidade_id, $alerta = null)
    {
        if (!$this->checkPermission($group_id, $unidade_id)) {
            return;
        }

        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);

        $data['user']    = $this->user;

        $data['unidade'] = $this->shopping_model->get_unidade($unidade_id);
        $data['device_groups'] = $this->shopping_model->get_device_groups(72);

        $data['alerta'] = false;
        $data['faturamento'] = false;
        $data['unidade_id'] = $unidade_id;

        $data['permission'] = $this->get_user_permission($this->user->id);

        if (!is_null($alerta)) {
            if ($alerta === 'faturamentos') {
                $data['faturamento'] = true;
                $data['group_id']   = $group_id;
                $data['group']      = $this->shopping_model->get_group_info($group_id);
                $data['unidades']   = $this->shopping_model->get_unidades($group_id);
                $data['area_comum'] = $this->user->config->area_comum;

                $this->setHistory("Acesso aos faturamentos da unidade $unidade_id do shopping $group_id", 'acesso');

                $this->render('faturamentos_unidade', $data);
                return;
            }

            $data['alerta'] = true;

            $this->setHistory("Acesso aos alertas da unidade $unidade_id do shopping $group_id", 'acesso');

            $this->render('alertas_unidade', $data);
            return;
        }

        $this->setHistory("Acesso ao consumo da unidade $unidade_id do shopping $group_id", 'acesso');

        $this->render('energy', $data);
    }

    public function configuracoes($group_id)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso à página de configurações do shopping $group_id", 'acesso');

        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);
        $data['unidades'] = $this->shopping_model->get_units($group_id);
        $data['client_config'] = $this->shopping_model->get_client_config($group_id);
        $data['alerts_config'] = $this->shopping_model->get_alert_config($group_id, true);
        $data['token'] = $this->shopping_model->getToken($group_id);

        foreach ($data['alerts_config'] as $c) {
            $data['alerts']['devices']['type-'.$c->type] = $this->shopping_model->get_devices($group_id, $c->type);
            $data['alerts']['config-type-'.$c->type] = $c;
        }

        //echo "<pre>"; print_r($data); echo "</pre>"; return;

        $this->render('configs', $data);
    }

    public function alertas($group_id)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso aos alertas do shopping $group_id", 'acesso');

        $data['group_id'] = $group_id;
        $data['group']    = $this->shopping_model->get_group_info($group_id);
        $data['unidades'] = $this->shopping_model->get_units($group_id);

        $this->render('alertas', $data);
    }

    public function profile()
    {
        $this->setHistory("Acesso ao perfil", 'acesso');

        $data['set'] = false;

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<label id="fullname-error" class="error" for="fullname">', '</label>');

        if ($this->input->method() == 'post') {
            $image = $this->input->post('crop-image');

            if ($image) {
                // valida se é imagem...

                // salva avatar
                list($type, $image) = explode(';', $image);
                list(, $image) = explode(',', $image);
                $image = base64_decode($image);
                $filename = time() . $this->user->id . '.png';
                if (file_put_contents('uploads/avatars/' . $filename, $image)) {
                    // atualiza avatar em auth_users
                    $data['avatar'] = $filename;
                    if ($this->ion_auth->update($this->user->id, $data)) {
                        // apaga avatar anterior
                        if (file_exists('uploads/avatars/' . $this->user->avatar)) {
                            unlink('uploads/avatars/' . $this->user->avatar);
                        }
                        $data['error'] = false;
                        // mensagem
                        $this->user->avatar = $filename;
                    } else {
                        //erro e mensagem
                    }
                } else {
                    //erro e mensagem
                }
            } else {

                $this->load->library('form_validation');
                $this->form_validation->set_rules('password', 'Senha', 'required|min_length[6]');
                $this->form_validation->set_rules('confirm', 'Confirmação da Senha', 'required|matches[password]');

                if ($this->form_validation->run() == TRUE) {
                    // coleta os dados do post
                    $password = $this->input->post('password');
                    $user     = $this->input->post('user');

                    // atualiza dados
                    if (!$this->shopping_model->update_user($user, $password)) {
                        $data['error'] = true;
                    } else {
                        $data['error'] = false;
                        //mensagem
                    }
                }
            }
        }
        $this->render('profile', $data);
    }

    public function relatorio($type, $group_id, $fechamento_id, $relatorio_id)
    {
//        if (!$this->checkPermission($group_id, $fechamento_id, $relatorio_id)) {
//            return;
//        }

        $this->setHistory("Acesso aos relatório $relatorio_id de $type do fechamento $fechamento_id do shopping $group_id", 'acesso');

        $data['group_id']   = $group_id;
        $data['shopping']   = $this->shopping_model->GetGroup($group_id);

        if ($type == "energia") {

            $data['unidade']    = $this->shopping_model->GetFechamentoUnidade("energia", $relatorio_id);
            $data['fechamento'] = $this->shopping_model->GetFechamento("energia", $fechamento_id);
            $data['historico']  = $this->shopping_model->GetFechamentoHistoricoUnidade("energia", $data['unidade']->device, $data['fechamento']->cadastro);

            $this->render('relatorio_energy', $data);
        
        } else if ($type == "agua") {

            $data['unidade']    = $this->shopping_model->GetFechamentoUnidade("agua", $relatorio_id);
            $data['fechamento'] = $this->shopping_model->GetFechamento("agua", $fechamento_id);
            $data['historico']  = $this->shopping_model->GetFechamentoHistoricoUnidade("agua", $data['unidade']->device, $data['fechamento']->cadastro);
            

            $data['equivalencia'][0] = floor($data['unidade']->consumo / 10000);
            $resto = $data['equivalencia'][0] * 10000;
            $data['equivalencia'][1] = floor(($data['unidade']->consumo - $resto) / 1000);
            $resto += $data['equivalencia'][1] * 1000;
            $data['equivalencia'][2] = floor(($data['unidade']->consumo - $resto) / 100);
            $resto += $data['equivalencia'][2] * 100;
            $data['equivalencia'][3] = floor(($data['unidade']->consumo - $resto) / 10);
    
            $this->render('relatorio_water', $data);
        }
    }

    public function faturamentos($group_id)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso aos lançamentos do shopping $group_id", 'acesso');

        $data['group_id']   = $group_id;
        $data['group']      = $this->shopping_model->get_group_info($group_id);
        $data['unidades']   = $this->shopping_model->get_unidades($group_id);
        $data['area_comum'] = $this->user->config->area_comum;

        $this->render('faturamentos', $data);
    }

    public function lancamento($type, $group_id, $id)
    {
        if (!$this->checkPermission($group_id, $id)) {
            return;
        }

        $this->setHistory("Acesso ao lançamento $id de $type do shopping $group_id", 'acesso');

        if ($type == "energia") {

            $data['group_id']   = $group_id;
            $data['group']      = $this->shopping_model->get_group_info($group_id);
            $data['fechamento'] = $this->energy_model->GetLancamento($id);
            $data['area_comum'] = $this->user->config->area_comum;

            $this->render('fechamento', $data);
        
        } else if ($type == "agua") {

            $data['group_id']   = $group_id;
            $data['group']      = $this->shopping_model->get_group_info($group_id);
            $data['fechamento'] = $this->water_model->GetLancamento($id);
            $data['area_comum'] = $this->user->config->area_comum;

            $this->render('lancamento_water', $data);
        }
    }

    public function insights($group_id)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso aos insights do shopping $group_id", 'acesso');

        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);
        // Receber a função específica do model
        $data['lojas_poluentes'] = $this->shopping_model->get_poluicao();

        $this->render('insights', $data);
    }

    public function users($group_id, $op = null, $user_id = null)
    {
        if (!$this->checkPermission($group_id)) {
            return;
        }

        $this->setHistory("Acesso à página com operação $op do usuário $user_id do shopping $group_id", 'acesso');

        $data['group_id'] = $group_id;
        $data['group'] = $this->shopping_model->get_group_info($group_id);

        $data['shoppings'] = $this->shopping_model->get_shoppings_by_user($this->user->id);

        $data['readonly'] = false;

        if (!is_null($user_id)) {
            $data['user_info'] = $this->shopping_model->get_user_info($user_id);
        }

        // echo "<pre>"; print_r($data); echo "</pre>"; return;

        if ($op === 'edit') {
            $this->render('user_edit', $data);
        } elseif ($op === 'create') {
            $this->render('user_add', $data);
        } elseif ($op === 'view') {
            $data['readonly'] = true;
            $this->render('user_edit', $data);
        } else {
            redirect('shopping/configuracoes/' . $group_id, 'refresh');
        }
    }

    public function historico($group_id, $sub = '', $id = '')
    {
        $this->setHistory("Acesso ao histórico", 'acesso');
        $data['group_id'] = $group_id;
        if ($sub == 'boletim') {
            $this->render('boletim', $data);
        } else {
            $this->render('historico', $data);
        }
    }

    /////////////////////////////////
    ///// FUNÇÕES PARA SHOPPING /////
    /////////////////////////////////

    public function get_groups()
    {
        $entity_id = $this->input->post('entity');

        //$this->setHistory("Requisição para buscar shoppings da rede $entity_id", 'requisição');

        // realiza a query via dt
        $dt = $this->datatables->query("
            SELECT
				esm_shoppings.status AS status,
				esm_blocos.id AS id,
				esm_blocos.nome AS nome,
				CONCAT(
					esm_shoppings.logradouro,
					', ',
					esm_shoppings.numero,
					' - ',
					esm_shoppings.bairro,
					', ',
					esm_shoppings.cidade,
					' - ',
					esm_shoppings.uf 
				) AS endereco 
			FROM
				esm_blocos 
			JOIN esm_shoppings ON esm_blocos.id = esm_shoppings.bloco_id
			WHERE
				esm_blocos.condo_id = $entity_id 
			ORDER BY
			status
		");

        $dt->edit('status', function ($data) {
            $status = "";
            if ($data['status'] == "ativo") {
                $status = "<span class='badge badge-success'>Monitorando</span>";
            } else if ($data['status'] == "inativo") {
                $status = "<span class='badge badge-danger'>Em implantação</span>";
            }
            return $status;
        });

        // gera resultados
        echo $dt->generate();
    }

    public function get_unidades()
    {
        $group_id = $this->input->post("group");
        $type = $this->input->post("tipo");

        //$this->setHistory("Requisição para buscar lojas de $type do shopping $group_id", 'requisição');

        $query = "SELECT 
                un.id as id,
                un.nome as unidade,
                IF(unc.type <= 1,(SELECT esm_client_config.area_comum FROM esm_client_config WHERE esm_client_config.group_id = ".$this->input->post("group")."),'Unidades') as subtipo,
                unc.tipo as tipo,
                unc.identificador as identificador,
                unc.localizador as localizador,
                unc.disjuntor as disjuntor,
                unc.faturamento as faturamento
            FROM esm_medidores me
            JOIN esm_unidades un ON un.id = me.unidade_id
            JOIN esm_unidades_config unc ON unc.unidade_id = un.id
            WHERE un.bloco_id = $group_id AND me.tipo = '$type'
            GROUP BY id" ;

        $dt = $this->datatables->query($query);

        $dt->add('disjuntor', function ($data) {
            if (is_null($data['disjuntor']))
                return "";
            else
                return $data['disjuntor']." A";
        });

        $dt->add('subtipo', function ($data) {
            return $data['subtipo'];
        });

        $dt->add('tipo', function ($data) {
            if ($data['tipo'] === 'iluminacao') {
                return 'Iluminação';
            } elseif ($data['tipo'] === 'ar_condicionado') {
                return 'Ar Condicionado';
            } elseif ($data['tipo'] === 'loja') {
                return 'Loja';
            } elseif ($data['tipo'] === 'quiosque') {
                return 'Quiosque';
            }
        });

        $dt->add('faturamento', function ($data) {
            if ($data['faturamento'] === 'incluir') {
                return 'Incluir';
            } elseif ($data['faturamento'] === 'nao_incluir') {
                return 'Não Incluir';
            }
        });

        $dt->add('actions', function ($data) {
            if ($this->ion_auth->in_group("entity_shopping")){
                return '
                    <a href="#" class="hidden on-editing btn-save save-row text-success"><i class="fas fa-save"></i></a>
                                        <a href="#" class="hidden on-editing btn-save cancel-row text-danger"><i
                                                    class="fas fa-times"></i></a>
                                        <a href="#" class="on-default edit-row text-primary"><i class="fas fa-pen"></i></a>
                ';
            } else {
                return '<a href="' . site_url('/shopping/unidades/' . $this->input->post('group') . '/view/' . $data['id']) . '" class="action-visualiza text-center text-success"><i class="fas fa-eye me-2"></i></a>';
            }
        });

        // gera resultados
        echo $dt->generate();
    }

    public function get_alertas_conf()
    {
        $group_id = $this->input->post("group");
        $type = $this->input->post("tipo");

        //$this->setHistory("Requisição para buscar configuração dos alertas de $type do shopping $group_id", 'requisição');

        $query = "SELECT 
                id,
                group_id,
                active as status,
                description as alerta,
                null as medidores,
                when_type as quando,
                notify_shopping as shopping,
                notify_unity as unidade,
                type as actions
            FROM esm_alertas_cfg
            WHERE group_id = $group_id AND subtipo = '$type'" ;

        $dt = $this->datatables->query($query);

        $dt->add('status', function ($data) {
            if ($data['status']) {
                return '
                    <div class="switch switch-sm switch-primary disabled">
                        <input type="checkbox" checked class="switch-input" name="active" data-plugin-ios-switch>
                    </div>
                ';
            } else {
                return '
                    <div class="switch switch-sm switch-primary disabled">
                        <input type="checkbox" class="switch-input" name="active" data-plugin-ios-switch>
                    </div>
                ';
            }
        });

        $dt->add('medidores', function ($data) {
            $medidores = $this->shopping_model->get_devices_alert($this->input->post("group"), $data['id']);
            $unidades = $this->shopping_model->get_units($this->input->post("group"));

            $return = '<select class="form-control select-medidores" multiple="multiple" id="medidores-type" name="medidores_type[]" data-plugin-multiselect data-plugin-options=\'{ "buttonClass": "multiselect dropdown-toggle form-select text-center form-control", "maxHeight": 200, "buttonWidth": "100%", "numberDisplayed": 1, "includeSelectAllOption": true}\' disabled>';

            foreach ($unidades as $u) {
                if ($medidores) {
                    foreach ($medidores as $j => $m) {
                        if ($u['medidor_id'] === $m->dvc) {
                            $return .= '<option selected value="' . $u['medidor_id'] . '">' . $u['unidade_nome'] . '</option>';
                            continue 2;
                        } elseif ($j == array_key_last($medidores)) {
                            $return .= '<option value="' . $u['medidor_id'] . '">' . $u['unidade_nome'] . '</option>';
                        }
                    }
                } else {
                    $return .= '<option value="' . $u['medidor_id'] . '">' . $u['unidade_nome'] . '</option>';
                }
            }

            $return .= '</select>';

            return $return;
        });

        $dt->add('quando', function ($data) {
            $return = '<select class="form-control period" id="when-type" name="when_type" disabled>';

            if ($data['quando'] === 'day') {
                if ($data['actions'] != 3) {
                    $return .= '<option selected value="day">No Dia</option>';
                } else {
                    $return .= '
                        <option value="">Selecione</option>
                        <option selected value="day">No Dia</option>
                        <option value="hour">Na Hora</option>
                        <option value="instant">No Instante</option>
                    ';
                }
            } elseif ($data['quando'] === 'hour') {
                if ($data['actions'] != 3) {
                    $return .= '<option selected value="hour">Na Hora</option>';
                } else {
                    $return .= '
                        <option value="">Selecione</option>
                        <option value="day">No Dia</option>
                        <option selected value="hour">Na Hora</option>
                        <option value="instant">No Instante</option>
                    ';
                }
            } elseif ($data['quando'] === 'instant') {
                if ($data['actions'] != 3) {
                    $return .= '<option selected value="instant">No Instante</option>';
                } else {
                    $return .= '
                        <option value="">Selecione</option>
                        <option value="day">No Dia</option>
                        <option value="hour">Na Hora</option>
                        <option selected value="instant">No Instante</option>
                    ';
                }
            } else {
                $return .= '
                    <option value="">Selecione</option>
                    <option value="day">No Dia</option>
                    <option value="hour">Na Hora</option>
                    <option value="instant">No Instante</option>
                ';
            }


            $return .= '</select>';

            return $return;
        });

        $dt->add('unidade', function ($data) {
            if ($data['unidade']) {
                return '
                    <div class="switch switch-sm switch-primary disabled">
                        <input type="checkbox" class="switch-input" checked name="notify_unity" data-plugin-ios-switch>
                    </div>
                ';
            } else {
                return '
                    <div class="switch switch-sm switch-primary disabled">
                        <input type="checkbox" class="switch-input" name="notify_unity" data-plugin-ios-switch>
                    </div>
                ';
            }
        });

        $dt->add('shopping', function ($data) {
            if ($data['shopping']) {
                return '
                    <div class="switch switch-sm switch-primary disabled">
                        <input type="checkbox" class="switch-input" checked name="notify_shopping" data-plugin-ios-switch>
                    </div>
                ';
            } else {
                return '
                    <div class="switch switch-sm switch-primary disabled">
                        <input type="checkbox" class="switch-input" name="notify_shopping" data-plugin-ios-switch>
                    </div>
                ';
            }
        });

        $dt->add('actions', function ($data) {
            if ($this->ion_auth->in_group("entity_shopping")){
                return '
                    <a href="#" class="hidden on-editing btn-save save-row text-success"><i class="fas fa-save"></i></a>
                                        <a href="#" class="hidden on-editing btn-save cancel-row text-danger"><i
                                                    class="fas fa-times"></i></a>
                                        <a href="#" class="on-default edit-row text-primary"><i class="fas fa-pen"></i></a>
                ';
            } else {
                return '<a href="' . site_url('/shopping/unidades/' . $this->input->post('group') . '/view/' . $data['id']) . '" class="action-visualiza text-center text-success"><i class="fas fa-eye me-2"></i></a>';
            }
        });

        // gera resultados
        echo $dt->generate();
    }

    public function get_faturamentos_energia()
    {
        //$this->setHistory("Requisição para buscar faturamentos de energia", 'requisição');

        $query = "SELECT
                    id,
                    competencia,
                    FROM_UNIXTIME( data_inicio, '%d/%m/%Y' ) AS data_inicio,
                    FROM_UNIXTIME( data_fim, '%d/%m/%Y' ) AS data_fim,
                    FROM_UNIXTIME( vencimento, '%d/%m/%Y' ) as vencimento,
                    consumo,
                    valor_conta,
                    DATE_FORMAT( cadastro, '%d/%m/%Y' ) AS emissao
                FROM
                    ene_fechamentos 
                ORDER BY
                    cadastro DESC";

        // realiza a query via dt
        $dt = $this->datatables->query($query);

        $dt->edit('consumo', function ($data) {
            return number_format($data['consumo'], 0, ",", ".") . '<span class="float-right">kWh</span>';
        });

        $dt->edit('valor_conta', function ($data) {
            return '<span class="float-left">R$</span>' . number_format($data['valor_conta'], 2, ",", ".");
        });

        // gera resultados
        echo $dt->generate();
    }

    public function fechamento_unidades()
    {
        $fechamento_id = $this->input->get('fechamento');

        //$this->setHistory("Requisição para buscar unidades do fechamento $fechamento_id", 'requisição');

        // realiza a query via dt
        $dt = $this->datatables->query("
            SELECT
                ene_fechamentos_unidades.id as id,
                ene_unidades.nome as nome,
                COALESCE(DATE_FORMAT(ene_fechamentos_unidades.visualizado,'%d/%m/%Y %H:%i'), 0) AS visualizado,
            ene_fechamentos_unidades.consumo, 
            ene_fechamentos_unidades.v_consumo, 
            ene_fechamentos_unidades.v_basico, 
            ene_fechamentos_unidades.v_acomum, 
            ene_fechamentos_unidades.v_taxas, 
            ene_fechamentos_unidades.v_gestao, 
			ene_fechamentos_unidades.v_total, ene_fechamentos_unidades.fechamento_id, ene_fechamentos_unidades.unidade_id
            FROM ene_fechamentos_unidades 
            JOIN ene_fechamentos ON ene_fechamentos.id = ene_fechamentos_unidades.fechamento_id
            JOIN ene_unidades ON ene_unidades.id = ene_fechamentos_unidades.unidade_id 
            WHERE ene_fechamentos_unidades.fechamento_id = $fechamento_id
            ORDER BY ene_unidades.nome
		");

        $dt->edit('consumo', function ($data) {
            return number_format($data['consumo'], 0, '', '.') . ' <span class="float-right">kWh</span>';
        });


        $dt->edit('v_consumo', function ($data) {
            return '<span class="float-left">R$</span>' . number_format($data['v_consumo'], 2, ',', '.');
        });

        $dt->edit('v_basico', function ($data) {
            return '<span class="float-left">R$</span>' . number_format($data['v_basico'], 2, ',', '.');
        });

        $dt->edit('v_acomum', function ($data) {
            return '<span class="float-left">R$</span>' . number_format($data['v_acomum'], 2, ',', '.');
        });

        $dt->edit('v_taxas', function ($data) {
            return '<span class="float-left">R$</span>' . number_format($data['v_taxas'], 2, ',', '.');
        });

        $dt->edit('v_gestao', function ($data) {
            return '<span class="float-left">R$</span>' . number_format($data['v_gestao'], 2, ',', '.');
        });

        $dt->edit('v_total', function ($data) {
            return '<span class="float-left">R$</span>' . $data['v_total'];
        });

        $dt->edit('visualizado', function ($data) {

            if ($data['visualizado'] == 0)
                return '<i class="far fa-eye-slash"></i>';
            else
                return '<i class="fas fa-eye" title="Visualizado em ' . $data['visualizado'] . '"></i>';
        });

        $dt->add('action', function ($data) {
            return '<a class="action-download-agua" data-id="' . $data['id'] . '" title="Visualizar relatório" href="' . site_url('/shopping/relatorio/' . $this->input->get('group_id') . '/' . $data['fechamento_id'] . '/' . $data['id']) . '" ><i class="fas fa-eye mr-2"></i></a>';
        });

        // gera resultados
        echo $dt->generate();
    }

    public function get_users()
    {
        //$this->setHistory("Requisição para buscar usuários shopping ".$this->input->post('group'), 'requisição');

        if ($this->ion_auth->in_group("entity_shopping")) {
            $groups = "(34, 35, 36)";
        } else {
            $groups = "(35, 36)";
        }
        $query = "
            SELECT 
                auth_users.id as id,
                auth_groups.id as group_id,
                auth_users.nome as name,
                auth_users.email as email,
                auth_groups.description as grupo
            FROM 
                auth_users
            JOIN auth_users_groups ON auth_users_groups.user_id = auth_users.id
            JOIN auth_groups ON auth_users_groups.group_id = auth_groups.id
            WHERE auth_users_groups.group_id IN $groups AND auth_users.id NOT IN (585, 586, 591)
        ";

        $dt = $this->datatables->query($query);

        $dt->add('actions', function ($data) {
            if ($this->ion_auth->in_group("entity_shopping")){
                return '
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Ver" href="' . site_url('/shopping/users/' . $this->input->post('group') . '/view/' . $data['id']) . '" class="action-visualiza text-success"><i class="fas fa-eye me-2"></i></a>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" href="' . site_url('/shopping/users/' . $this->input->post('group') . '/edit/' . $data['id']) . '" class="action-access text-primary"><i class="fas fa-pen me-2"></i></a>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir" href="#" class="action-delete-user text-danger" data-id="' . $data['id'] . '"><i class="fas fa-trash me-2"></i></a>
                ';
            } else {
                return '<a href="' . site_url('/shopping/users/' . $this->input->post('group') . '/view/' . $data['id']) . '" class="action-visualiza text-center text-success"><i class="fas fa-eye me-2"></i></a>';
            }
        });

        // gera resultados
        echo $dt->generate();
    }

    public function get_agrupamentos()
    {
        $group_id = $this->input->post('group');
        $type = $this->input->post('tipo');

        //$this->setHistory("Requisição para buscar agrupamentos de $type do shopping $group_id", 'requisição');

        $query = "
            SELECT
                    esm_device_groups.id as id,
                    esm_unidades.bloco_id,
                    esm_device_groups.name AS name
            FROM 
                    esm_device_groups
            JOIN esm_medidores ON esm_medidores.entrada_id = esm_device_groups.entrada_id
            JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE esm_unidades.bloco_id = $group_id AND esm_medidores.tipo = '$type'
            GROUP BY esm_device_groups.name";

        $dt = $this->datatables->query($query);

        $dt->add('unidades', function ($data) {
            $unidades = $this->shopping_model->get_units($this->input->post("group"), $this->input->post("tipo"));
            $medidores = $this->shopping_model->get_devices_agrupamento($data['id']);

            $return = '<select class="form-control select-medidores" multiple="multiple" id="medidores-agrupamento" name="medidores_agrupamento[]" data-plugin-multiselect data-plugin-options=\'{ "buttonClass": "multiselect dropdown-toggle form-select text-center form-control", "maxHeight": 200, "buttonWidth": "100%", "numberDisplayed": 1, "includeSelectAllOption": true}\' disabled>';

            foreach ($unidades as $u) {
                if ($medidores) {
                    foreach ($medidores as $j => $m) {
                        if ($u['medidor_id'] === $m->dvc) {
                            $return .= '<option selected value="' . $u['medidor_id'] . '">' . $u['unidade_nome'] . '</option>';
                            continue 2;
                        } elseif ($j == array_key_last($medidores)) {
                            $return .= '<option value="' . $u['medidor_id'] . '">' . $u['unidade_nome'] . '</option>';
                        }
                    }
                } else {
                    $return .= '<option value="' . $u['medidor_id'] . '">' . $u['unidade_nome'] . '</option>';
                }
            }

            return $return;
        });

        $dt->add('actions', function ($data) {
            if ($this->ion_auth->in_group("entity_shopping")){
                return '
                    <a href="#" class="hidden on-editing btn-save save-row text-success"><i class="fas fa-save"></i></a>
                                        <a href="#" class="hidden on-editing btn-save cancel-row text-danger"><i
                                                    class="fas fa-times"></i></a>
                                        <a href="#" class="on-default edit-row text-primary"><i class="fas fa-pen"></i></a>
                                        <a href="#" class="on-default delete-row text-danger"><i class="fas fa-trash"></i></a>
                ';
            } else {
                return '<a href="' . site_url('/shopping/unidades/' . $this->input->post('group') . '/view/' . $data['id']) . '" class="action-visualiza text-center text-success"><i class="fas fa-eye me-2"></i></a>';
            }
        });

        // gera resultados
        echo $dt->generate();
    }

    public function get_unidades_select()
    {
        $group_id = $this->input->post('group');
        $type = $this->input->post('tipo');

        //$this->setHistory("Requisição para buscar unidades de $type do shopping $group_id", 'requisição');

        $unidades = $this->shopping_model->get_units($group_id, $type);

        $data = array();

        foreach ($unidades as $unidade) {
            $data['options'][] = $unidade['medidor_id'];
            $data['_options'][] = $unidade['unidade_nome'];
        }

        echo json_encode($data);
    }

    public function remove_device_group()
    {
        $this->setHistory("Removido medidores agrupamento de medidor ".$this->input->post('medidor')." do shopping ".$this->input->post('grupo'), 'ação');

        if ($this->shopping_model->remove_device_group($this->input->post('medidor'), $this->input->post('grupo'))) {
            echo json_encode(array('status' => 'success', 'message' => 'Removido com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function remove_group()
    {
        $this->setHistory("Removido agrupamento do shopping ".$this->input->post('grupo'), 'ação');

        if ($this->shopping_model->delete_group($this->input->post('grupo'))) {
            echo json_encode(array('status' => 'success', 'message' => 'Removido com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function add_group()
    {
        $name = $this->input->post('name');
        $entry = $this->input->post('entry');

        $this->setHistory("Agrupamento $name criado com as entradas ".is_array($entry) ? implode($entry) : $entry, 'ação');

        if ($this->shopping_model->add_group($name, $entry)) {
            echo json_encode(array('status' => 'success', 'message' => 'Grupo criado com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function delete_user()
    {
        $user = $this->input->post('user');
        $this->setHistory("Usuario $user excluído", 'ação');

        if ($this->shopping_model->delete_user($user)) {
            echo json_encode(array('status' => 'success', 'message' => 'Usuário removido com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function get_lojas()
    {
        $group_id = $this->input->post('shopping_id');

        //$this->setHistory("Requisição que busca lojas do shopping $group_id", 'requisição');

        $dados = $this->shopping_model->get_lojas_by_shopping($group_id);

        echo json_encode($dados);
    }

    public function add_user()
    {
        $group_id = $this->input->post('shopping_id');

        $this->setHistory("Cria usuário ".$this->input->post('email-user'), 'ação');

        $dados['user'] = array(
            'ip_address' => '1.1.1.1',
            'active' => 1,
        );

        if ($this->input->post('nome-user')) {
            $dados['user']['nome'] = $this->input->post('nome-user');
        }
        if ($this->input->post('email-user')) {
            $dados['user']['email'] = $this->input->post('email-user');
        }
        if ($this->input->post('telefone-user')) {
            $dados['user']['telefone'] = $this->input->post('telefone-user');
        }
        if ($this->input->post('celular-user')) {
            $dados['user']['celular'] = $this->input->post('celular-user');
        }
        if ($this->input->post('username-user')) {
            $dados['user']['username'] = $this->input->post('username-user');
        }
        if ($this->input->post('password_user')) {
            $dados['user']['password'] = $this->input->post('password_user');
        }
        if ($this->input->post('acessar_lancamentos')) {
            $dados['user']['acessar_lancamentos'] = 1;
        } else {
            $dados['user']['acessar_lancamentos'] = 0;
        }
        if ($this->input->post('acessar_engenharia')) {
            $dados['user']['acessar_engenharia'] = 1;
        } else {
            $dados['user']['acessar_engenharia'] = 0;
        }
        if ($this->input->post('baixar_planilhas')) {
            $dados['user']['baixar_planilhas'] = 1;
        } else {
            $dados['user']['baixar_planilhas'] = 0;
        }

        $dados['shopping'] = $this->input->post('select-shopping');
        $dados['loja'] = $this->input->post('select-loja');

        if ($this->shopping_model->add_user($dados)) {
            echo json_encode(array('status' => 'success', 'message' => 'Usuário criado com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function edit_user()
    {
        $dados['user_id'] = $this->input->post("user_id");

        $dados['user'] = array(
            'ip_address' => '1.1.1.1',
            'active' => 1,
        );

        if ($this->input->post('nome-user')) {
            $dados['user']['nome'] = $this->input->post('nome-user');
        }
        if ($this->input->post('email-user')) {
            $dados['user']['email'] = $this->input->post('email-user');
        }
        if ($this->input->post('telefone-user')) {
            $dados['user']['telefone'] = $this->input->post('telefone-user');
        }
        if ($this->input->post('celular-user')) {
            $dados['user']['celular'] = $this->input->post('celular-user');
        }
        if ($this->input->post('username-user')) {
            $dados['user']['username'] = $this->input->post('username-user');
        }
        if ($this->input->post('password_user')) {
            $dados['user']['password'] = $this->input->post('password_user');
        }

        $dados['shopping'] = $this->input->post('select-shopping');
        $dados['loja'] = $this->input->post('select-loja');

        $this->setHistory("Usuario ".$dados['user']." editado", 'ação');

        if ($this->shopping_model->edit_user($dados)) {
            echo json_encode(array('status' => 'success', 'message' => 'Usuário alterado com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function edit_unidade()
    {
        for ($i = 0; $i < count($this->input->post()); $i++) {
            $el = $this->input->post($i);
            if ($this->input->post($i)) {
                if ($i == 0) {
                    $dados['unidade_id'] = $el;
                } elseif ($i == 1) {
                    $dados['tabela']['esm_unidades']['nome'] = $el;
                } elseif ($i == 2) {
                    $dados['tabela']['esm_unidades_config']['tipo'] = $el;
                } elseif ($i == 3) {
                    $dados['tabela']['esm_unidades_config']['identificador'] = $el;
                } elseif ($i == 4) {
                    $dados['tabela']['esm_unidades_config']['localizador'] = $el;
                } elseif ($i == 5) {
                    $dados['tabela']['esm_unidades_config']['disjuntor'] = $el;
                } elseif ($i == 6) {
                    $dados['tabela']['esm_unidades_config']['faturamento'] = $el;
                }
            }
        }

        $this->setHistory("Usuario ".$dados['unidade_id']." editada", 'ação');

        if ($this->shopping_model->edit_unidade($dados)) {
            echo json_encode(array('status' => 'success', 'message' => 'Unidade alterada com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function edit_client_conf()
    {
        foreach ($this->input->post() as $i => $post) {
            if ($post) {
                if ($i === 'group_id') {
                    $dados[$i] = $post;
                    $this->setHistory("Configuração do shopping $post alterada", 'ação');
                } elseif ($i === 'area_comum') {
                    $dados['tabela']['esm_client_config'][$i] = $post;
                } elseif ($i === 'split_report') {
                    $dados['tabela']['esm_client_config'][$i] = 1;
                } else {
                    $dados['tabela']['esm_client_config'][$i] = strtotime('01-01-1970 ' . $post);
                }
            }
        }

        if (!$this->input->post('split_report')) {
            $dados['tabela']['esm_client_config']['split_report'] = 0;
        }

        if ($this->shopping_model->edit_client_conf($dados)) {
            echo json_encode(array('status' => 'success', 'message' => 'Configurações gerais alteradas com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function edit_alert_conf()
    {
        $dados = array();

        if ($this->input->post('id')) {
            $dados['config_id'] = $this->input->post('id');
        }
        if ($this->input->post('group_id')) {
            $dados['group_id'] = $this->input->post('group_id');
        }
        if ($this->input->post('when_type')) {
            $dados['esm_alertas_cfg']['when_type'] = $this->input->post('when_type');
        }
        if ($this->input->post('active')) {
            $dados['esm_alertas_cfg']['active'] = $this->input->post('active');
        } else {
            $dados['esm_alertas_cfg']['active'] = 0;
        }
        if ($this->input->post('notify_shopping')) {
            $dados['esm_alertas_cfg']['notify_shopping'] = $this->input->post('notify_shopping');
        } else {
            $dados['esm_alertas_cfg']['notify_shopping'] = 0;
        }
        if ($this->input->post('notify_unity')) {
            $dados['esm_alertas_cfg']['notify_unity'] = $this->input->post('notify_unity');
        } else {
            $dados['esm_alertas_cfg']['notify_unity'] = 0;
        }
        $dados['esm_alertas_cfg_devices'] = false;
        if ($this->input->post('medidores_type')) {
            foreach ($this->input->post('medidores_type') as $m) {
                $dados['esm_alertas_cfg_devices'][] = $m;
            }
        }

        $this->setHistory("Configuração do alerta ".$this->input->post('id')." do shopping ".$this->input->post('group_id')." alterada", 'ação');

        if ($this->shopping_model->edit_alert_conf($dados)) {
            echo json_encode(array('status' => 'success', 'message' => 'Configurações dos alertas alteradas com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function get_subtipo_cliente_config()
    {
        //$this->setHistory("Requisição das configurações do shopping ".$this->input->post("group"), 'requisição');

        echo $this->db->query("
            SELECT
                IF(unc.type <= 1,(
                    SELECT esm_client_config.area_comum 
                    FROM esm_client_config 
                    WHERE esm_client_config.group_id = ".$this->input->post("group")."
                ),'Unidades') as subtipo
            FROM esm_medidores me
            JOIN esm_unidades un ON un.id = me.unidade_id
            JOIN esm_unidades_config unc ON unc.unidade_id = un.id
            WHERE un.bloco_id = ".$this->input->post("group")." AND me.tipo = 'energia'
        ")->row()->subtipo;
    }

    public function get_user_permission($uid)
    {
        //$this->setHistory("Requisição das permissões do usuário $uid", 'requisição');

        return $this->db->query("
            SELECT
                acessar_lancamentos,
                acessar_engenharia,
                baixar_planilhas
            FROM
                auth_users
            WHERE
                id = $uid")->row();
    }

    public function edit_agrupamentos()
    {
        $dados = array();

        if ($this->input->post("undefined")) {
            $dados['name'] = $this->input->post("undefined");
        }
        foreach ($this->input->post("select_unidades") as $m) {
            $dados['devices'][] = $m;
        }

        if ($this->input->post("entrada_id")) {
            $dados['entrada_id'] = $this->input->post("entrada_id") === 'energia' ? 72 : 73;
        }

        if ($this->input->post("id")) {
            $dados['id'] = $this->input->post("id");

            $this->setHistory("Agrupamento ".$dados['id']." editado", 'ação');

            if ($this->shopping_model->edit_agrupamento($dados)) {
                echo json_encode(array('status' => 'success', 'message' => 'Agrupamento alterado com sucesso'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
            }
        } else {
            $this->setHistory("Agrupamento criado", 'ação');

            if ($this->shopping_model->add_agrupamento($dados)) {
                echo json_encode(array('status' => 'success', 'message' => 'Agrupamento criado com sucesso'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
            }
        }
    }

    public function delete_agrupamento()
    {
        $this->setHistory("Agrupamento ".$this->input->post("id")." excluído", 'ação');

        if ($this->shopping_model->delete_agrupamento($this->input->post("id"))) {
            echo json_encode(array('status' => 'success', 'message' => 'Agrupamento excluído com sucesso'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }

    public function count_log($status = -1)
    {
        // aplica filtro pelo status
        //if ($status != -1)
            //$this->db->where('lido', $status);

        // realiza a consulta
        $query = $this->db->get('esm_user_logs');

        // verifica se retornou algo
        return $query->num_rows();
    }

    public function get_log()
    {
        $tipo = $this->input->get('tipo');
        $aux = '';
        if ($tipo > -1) {
            $aux = "WHERE esm_user_logs.tipo = $tipo";
        }

        // realiza a query via dt
        $dt = $this->datatables->query("
            SELECT esm_user_logs.id, esm_user_logs.mensagem, esm_user_logs.tipo, 
            DATE_FORMAT(esm_user_logs.data,'%d/%m/%Y %H:%i:%s') AS cadastro, 
            esm_user_logs.lido, auth_users.nome, auth_users.avatar
            FROM esm_user_logs 
            JOIN auth_users ON auth_users.id = esm_user_logs.user_id
            $aux
            ORDER BY esm_user_logs.data DESC
        ");

        // icone do remetente
        $dt->add('enviado_por', function ($data) {
            return '<img src="' . avatar($data['avatar']) . '" title="' . $data['nome'] . '" style="width: 32px" class="rounded-circle" />';
        });

        // mensagem
        $dt->edit('mensagem', function ($data) {
            return $data['mensagem'];
        });

        // tipo do log
        $dt->edit('tipo', function ($data) {
            if ($data['tipo'] === 'alerta')
                return '<span class="badge badge-warning">Alerta</span>';
            if ($data['tipo'] === 'acesso')
                return '<span class="badge badge-info">Acesso</span>';
            if ($data['tipo'] === 'erro')
                return '<span class="badge badge-danger">Erro</span>';
            if ($data['tipo'] === 'requisição')
                return '<span class="badge badge-success">Requisição</span>';
            if ($data['tipo'] === 'ação')
                return '<span class="badge badge-dark">Ação</span>';
            else
                return '<span class="badge badge-primary">Indefinido</span>';
        });


        $dt->add('DT_RowClass', function ($data) {
            if ($data['lido'])
                return '';
            else
                return 'unread';
        });

        // actions
        $dt->add('actions', function ($data) {
            if ($data['lido'])
                return '<a href="#" class="action-readed" data-id="' . $data['id'] . '"><i class="far fa-eye-slash" title="Marcar como não lido"></i></a>';
            else
                return '<a href="#" class="action-readed" data-id="' . $data['id'] . '"><i class="fas fa-eye" title="Marcar como lido"></i></a>';
        });

        // gera resultados
        echo $dt->generate(true, array('total' => $this->count_log(0)));
    }

    public function set_log_state()
    {
        // pega id do post
        $id = $this->input->post('id');

        // altera status do log
        $return = $this->shopping_model->change_log_state($id);

        // retorna json
        echo json_encode($return);
    }

    public function generateToken()
    {
        $group_id = $this->input->post("group_id");

        $this->setHistory("Token para API gerado por $group_id", 'ação');

        $token = md5(strtotime(date("Y-m-d H:i:s")) . $group_id);

        if ($this->shopping_model->insertToken($token, $group_id)) {
            echo json_encode(array('status' => 'success', 'message' => 'Agrupamento criado com sucesso', 'token' => $token));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Falha na operação, tente novamente em instantes'));
        }
    }
}
