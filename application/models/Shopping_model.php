<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shopping_model extends CI_Model
{
    /**
     * Busca unidades
     *
     * @return false
     */
    public function get_unidades($group_id)
    {        $query = "
            SELECT
                esm_unidades.id AS unidade_id,
                esm_medidores.id as medidor_id,
                esm_unidades.nome AS unidade_nome,
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
                ) as endereco
            FROM
                esm_unidades
                JOIN esm_blocos ON esm_blocos.id = esm_unidades.bloco_id
                JOIN esm_shoppings ON esm_shoppings.bloco_id = esm_blocos.id
                JOIN esm_medidores ON esm_unidades.id = esm_medidores.unidade_id
            WHERE
                esm_unidades.bloco_id = $group_id
        ";
        $result = $this->db->query($query);

        if ($result->num_rows() <= 0) {

            return false;
        }

        return $result->result();
        // return $query;
    }

    public function GetUnidadeByDevice($device)
    {
        $result = $this->db->query("
            SELECT
                esm_unidades.id AS unidade_id,
                esm_medidores.id as medidor_id,
                esm_unidades.nome AS nome
            FROM
                esm_unidades
                JOIN esm_medidores ON esm_unidades.id = esm_medidores.unidade_id
            WHERE
                esm_medidores.nome = '$device'
        ");

        if ($result->num_rows() <= 0) {
            return false;
        }

        return $result->row();
    }

    /**
     * Busca unidade pelo id
     *
     * @param $unidade_id
     *
     * @return false
     */
    public function get_unidade($unidade_id)
    {
        $query = "
            SELECT
                esm_unidades.id as id,
                esm_unidades.nome,
                esm_medidores.id as medidor_id,
                esm_medidores.nome as device
            FROM
                esm_unidades
                JOIN esm_medidores ON esm_unidades.id = esm_medidores.unidade_id
            WHERE
                esm_unidades.id = $unidade_id LIMIT 1
        ";
        $result = $this->db->query($query);

        if ($result->num_rows() <= 0)
            return false;

        return $result->row();
    }

    /**
     * Busca informações da unidade pelo id
     *
     * @param $unidade_id
     *
     * @return false
     */
    public function get_unidade_info($unidade_id)
    {
        $query = "
            SELECT
                ene_unidades.id AS id,
                ene_unidades.nome as nome,
                esm_medidores_energia.id AS medidor_id,
                ene_estabelecimentos.nome as est_nome,
                ene_estabelecimentos.logradouro as logradouro,
                ene_estabelecimentos.numero as numero,
                ene_estabelecimentos.bairro as bairro,
                ene_estabelecimentos.cidade as cidade,
                ene_estabelecimentos.uf as uf,
                ene_estabelecimentos.cep as cep
            FROM
                ene_unidades
                JOIN esm_medidores_energia ON ene_unidades.id = esm_medidores_energia.unidade_id
            	JOIN ene_estabelecimentos ON ene_unidades.estabelecimento_id = ene_estabelecimentos.id
            WHERE
                ene_unidades.id = $unidade_id LIMIT 1
        ";
        $result = $this->db->query($query);

        if ($result->num_rows() <= 0)
            return false;

        return $result->row();
        /*return $query;*/
    }

    /**
     * Busca fechamento pelo id
     *
     * @param $fechamento_id
     *
     * @return false
     */
    public function get_fechamento($fechamento_id)
    {
        $query = $this->db->query("
            SELECT 
                ene_fechamentos.*,   
                DATEDIFF(FROM_UNIXTIME(data_fim), FROM_UNIXTIME(data_inicio)) + 1 AS dias, 
                data_inicio, 
                data_fim,
                DATE_FORMAT(ene_fechamentos.cadastro,'%d/%m/%Y') AS leitura, 
                DATE_ADD(ene_fechamentos.cadastro, INTERVAL 8 HOUR) AS hora_mostrar
            FROM ene_fechamentos
            WHERE ene_fechamentos.id = $fechamento_id
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }

    /**
     * Busca fechamento da unidade pelo relatório
     *
     * @param $relatorio_id
     *
     * @return false
     */
    public function get_unidades_fechamento($relatorio_id)
    {
        $q = "
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
            WHERE ene_fechamentos_unidades.id = $relatorio_id
            ORDER BY ene_unidades.nome
            ";

        $query = $this->db->query($q);

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }

    /**
     * Busca poluição emitida
     *
     * @return mixed
     */
    public function get_poluicao()
    {
        $result = $this->db->query('SELECT
            esm_medidores_energia.id AS medidor,
            ene_unidades.estabelecimento_id,
            ene_unidades.nome AS nome,
            SUM( esm_leituras_energia_final.active_power_a + esm_leituras_energia_final.active_power_b + esm_leituras_energia_final.active_power_c ) AS consumo,
            MONTH (
            FROM_UNIXTIME( TIMESTAMP )) AS mes,
            YEAR (
            FROM_UNIXTIME( TIMESTAMP )) AS ano 
        FROM
            ene_unidades
            RIGHT JOIN esm_medidores_energia ON esm_medidores_energia.unidade_id = ene_unidades.id
            RIGHT JOIN esm_leituras_energia_final ON esm_leituras_energia_final.medidor_id = esm_medidores_energia.id 
        GROUP BY
            nome,
            mes,
            ano 
        ORDER BY
            mes DESC LIMIT 3
        ');

        return $result->result();
    }

    /**
     * Busca unidades com maior latência no fator de potência
     *
     * @return mixed
     */
    public function get_error_fator()
    {
        //TODO: get_error_fator
        $result = $this->db->query('');

        return $result->result();
    }

    /**
     * Busca unidades com maior consumo
     *
     * @return mixed
     */
    public function get_lojas_consumo()
    {
        //TODO: get_lojas_consumo
        $result = $this->db->query('');

        return $result->result();
    }

    /**
     * Busca unidades com maior consumo fora de ponta
     *
     * @return mixed
     */
    public function get_consumo_foraponta()
    {
        //TODO: get_consumo_foraponta
        $result = $this->db->query('');

        return $result->result();
    }

    /**
     * Busca fechamento energia pelo id
     *
     * @param $fechamento_id
     *
     * @return false
     */
    public function get_fechamento_energia($fechamento_id)
    {
        $query = $this->db->query("
            SELECT 
                ene_fechamentos.*
            FROM ene_fechamentos
            WHERE ene_fechamentos.id = $fechamento_id
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }

    /**
     * Busca relatório de energia pelo fechamento
     *
     * @param $fechamento_id
     *
     * @return mixed
     */
    public function relatorio_energia($fechamento_id)
    {
        $query = $this->db->query("
            SELECT ene_unidades.nome, ene_fechamentos_entradas.leitura_anterior, ene_fechamentos_entradas.leitura_atual, ene_fechamentos_unidades.consumo, ene_fechamentos_unidades.v_consumo, ene_fechamentos_unidades.v_basico, ene_fechamentos_unidades.v_total
            FROM ene_fechamentos_unidades
            JOIN ene_unidades ON ene_unidades.id = ene_fechamentos_unidades.unidade_id
            JOIN ene_fechamentos_entradas ON ene_unidades.id = ene_fechamentos_entradas.medidor_id AND ene_fechamentos_entradas.fechamento_id = ene_fechamentos_unidades.fechamento_id
            WHERE ene_fechamentos_unidades.fechamento_id = $fechamento_id
        ");

        return $query->result_array();
    }

    /**
     * Busca entidade pelo usuário
     *
     * @param $user_id
     *
     * @return false
     */
    public function get_entity_by_user($user_id)
    {
        // Aplica filtro na query
        $this->db->where('user_id', $user_id);

        // realiza a consulta
        $query = $this->db->get('auth_users_entity');

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row()->entity_id;
    }

    /**
     * Busca grupo pelo usuário
     *
     * @param $user_id
     *
     * @return false
     */
    public function get_group_by_user($user_id)
    {
        // Aplica filtro na query
        $this->db->where('user_id', $user_id);

        // realiza a consulta
        $query = $this->db->get('auth_users_group');

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row()->group_id;
    }

    /**
     * Busca informações do grupo
     *
     * @param $group_id
     *
     * @return false|void
     */
    public function get_group_info($group_id)
    {
        // Seleciona tudo
        $this->db->select('esm_condominios.id as entity_id, esm_condominios.nome as entity_name, esm_blocos.id as group_id, esm_blocos.nome as group_name');
        // Seleciona tabela
        $this->db->from('esm_shoppings');
        // Realiza join na query
        $this->db->join('esm_blocos', 'esm_blocos.id = esm_shoppings.bloco_id');
        $this->db->join('esm_condominios', 'esm_condominios.id = esm_blocos.condo_id');
        // Aplica filtro na query
        $this->db->where('esm_blocos.id', $group_id);

        // realiza a consulta
        $query = $this->db->get();

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        // Retorna result
        return $query->row();
    }

    public function GetGroup($gid)
    {
        $result = $this->db->query("
            SELECT 
                esm_blocos.nome, 
                esm_shoppings.*
            FROM 
                esm_shoppings
            JOIN 
                esm_blocos ON esm_blocos.id = esm_shoppings.bloco_id
            WHERE 
                bloco_id = $gid
        ");

        if ($result->num_rows()) {
            return $result->row();
        }

        return false;
    }

    public function GetFechamento($type, $fid)
    {
        $result = $this->db->query("
            SELECT 
                *
            FROM 
                esm_fechamentos_{$type}
            WHERE 
                id = $fid
        ");

        if ($result->num_rows()) {
            return $result->row();
        }

        return false;
    }

    public function GetFechamentoUnidade($type, $rid)
    {
        $result = $this->db->query("
            SELECT 
                esm_unidades.nome, 
                esm_unidades_config.tipo,
                esm_fechamentos_{$type}_entradas.* 
            FROM 
                esm_fechamentos_{$type}_entradas
            JOIN
                esm_medidores ON esm_medidores.nome = esm_fechamentos_{$type}_entradas.device
            JOIN 
                esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            LEFT JOIN 
                esm_unidades_config ON esm_unidades_config.unidade_id = esm_unidades.id
            WHERE 
                esm_fechamentos_{$type}_entradas.id = $rid
        ");

        if ($result->num_rows()) {
            return $result->row();
        }

        return false;
    }

    public function GetFechamentoHistoricoUnidade($type, $device, $date)
    {
        $result = $this->db->query("
            SELECT 
                esm_unidades.nome, 
                esm_fechamentos_{$type}_entradas.*,
                esm_fechamentos_{$type}.competencia
            FROM 
                esm_fechamentos_{$type}_entradas
            JOIN 
                esm_fechamentos_{$type} ON esm_fechamentos_{$type}.id = esm_fechamentos_{$type}_entradas.fechamento_id
            JOIN
                esm_medidores ON esm_medidores.nome = esm_fechamentos_{$type}_entradas.device
            JOIN 
                esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE 
                esm_fechamentos_{$type}_entradas.device = '$device' AND esm_fechamentos_{$type}.cadastro < '$date'
        ");

        if ($result->num_rows()) {
            return $result->result_array();
        }

        return false;
    }

    /**
     * Busca e retorna id da unidade pelo usuário
     *
     * @param $user_id
     * @return false
     */
    public function get_unidade_id_by_user($user_id)
    {
        // Aplica filtro na query
        $this->db->where('user_id', $user_id);

        // realiza a consulta
        $query = $this->db->get('auth_users_unidades');

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row()->unidade_id;
    }

    /**
     * Busca grupo pela unidade
     *
     * @param $user_id
     *
     * @return false
     */
    public function get_group_id_by_unity($unity_id)
    {
        // Aplica filtro na query
        $this->db->where('id', $unity_id);

        // realiza a consulta
        $query = $this->db->get('esm_unidades');

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row()->bloco_id;
    }

    /**
     * Busca entity pelo ususário
     *
     * @param $uid
     *
     * @return false
     */
    public function get_user_entity($uid)
    {
        // realiza a consulta
        $query = $this->db->query(
            "SELECT
                entity_id 
            FROM
                auth_users_entity 
            WHERE
                user_id = $uid");

        // verifica se retornou algo
        if ($query->num_rows() == 0) {
            // realiza a consulta
            $query = $this->db->query(
                "SELECT
                    esm_blocos.condo_id
                FROM
                    auth_users_group
                JOIN esm_blocos ON esm_blocos.id = auth_users_group.group_id
                WHERE
                    auth_users_group.user_id = $uid");

            // verifica se retornou algo
            if ($query->num_rows() == 0){
                // realiza a consulta
                $query = $this->db->query(
                    "SELECT
                        esm_blocos.condo_id
                    FROM
                        auth_users_unidades
                    JOIN esm_unidades ON esm_unidades.id = auth_users_unidades.unidade_id
                    JOIN esm_blocos ON esm_blocos.id = esm_unidades.bloco_id
                    WHERE
                        auth_users_unidades.user_id = $uid");

                // verifica se retornou algo
                if ($query->num_rows() == 0)
                    return false;

                return $query->row()->condo_id;
            }
            return $query->row()->condo_id;
        }

        return $query->row()->entity_id;
    }

    public function get_user_group($user)
    {


        $query = "
            SELECT 
                auth_users_group.group_id AS group_id
            FROM
                auth_users_group 
            JOIN esm_blocos ON esm_blocos.id = auth_users_group.group_id
            WHERE
                auth_users_group.user_id = $user";

        if ($this->db->query($query)->num_rows() == 0) {
            $query = "SELECT
                    esm_unidades.bloco_id
                FROM
                    auth_users_unidades 
                JOIN esm_unidades ON esm_unidades.id = auth_users_unidades.unidade_id
                WHERE 
                    user_id = $user";

            if ($this->db->query($query)->num_rows() == 0)
                return 113;

            return $this->db->query($query)->row()->bloco_id;
        }

        return $this->db->query($query)->row()->group_id;

    }

    public function get_units($eid, $tipo = null)
    {
        $t = "";
        if (!is_null($tipo)) {
            $t = " AND esm_entradas.tipo = '$tipo' ";
        }
        $result = $this->db->query("
            SELECT
                esm_unidades.id AS id,
                esm_medidores.nome as medidor_id,
                esm_unidades.nome AS unidade_nome,
                esm_unidades.tipo AS unidade_tipo,
                esm_unidades.andar AS unidade_localizacao
            FROM
                esm_unidades
            JOIN 
                esm_medidores ON esm_unidades.id = esm_medidores.unidade_id
            JOIN 
                esm_entradas ON esm_entradas.id = esm_medidores.entrada_id
            WHERE 
                esm_unidades.bloco_id = $eid
                $t
            ORDER BY 
                esm_unidades.nome
        ");

        if ($result->num_rows()) {
            return $result->result_array();
        }

        return false;
    }

    public function get_units_water($eid)
    {
        $result = $this->db->query("
            SELECT
                esm_unidades.id AS id,
                esm_medidores.nome as medidor_id,
                esm_unidades.nome AS unidade_nome,
                esm_unidades.tipo AS unidade_tipo,
                esm_unidades.andar AS unidade_localizacao
            FROM
                esm_unidades
            JOIN 
                esm_medidores ON esm_unidades.id = esm_medidores.unidade_id
            JOIN 
                esm_entradas ON esm_entradas.id = esm_medidores.entrada_id
            WHERE 
                esm_unidades.bloco_id = $eid AND esm_entradas.tipo = 'agua'
            ORDER BY 
                esm_unidades.nome
        ");

        if ($result->num_rows()) {
            return $result->result_array();
        }

        return false;
    }

    public function get_device_groups($eid)
    {
        $result = $this->db->query("
            SELECT
                *
            FROM
                esm_device_groups
            WHERE 
                entrada_id = $eid
            ORDER BY 
                name
        ");

        if ($result->num_rows()) {
            return $result->result_array();
        }

        return false;
    }

    public function remove_device_group($dvc, $grp)
    {
        $this->db->where('device', $dvc);
        $this->db->where('group_id', $grp);
        return $this->db->delete('esm_device_groups_entries');
    }

    public function delete_group($grp)
    {
        $status = true;
        if (!$this->db->delete('esm_device_groups', array('id' => $grp)) || !$this->db->delete('esm_device_groups_entries', array('group_id' => $grp))) {
            $status = false;
        }

        return $status;
    }

    public function add_device_group($dvc, $grp)
    {
        $status = true;
        if (!$this->db->insert('esm_device_groups_entries', array('group_id' => $grp, 'device' => $dvc))) {
            $status = false;
        }

        return $status;
    }

    public function add_group($name, $entry)
    {
        $status = true;
        if (!$this->db->insert('esm_device_groups', array('entrada_id' => $entry, 'name' => $name))) {
            $status = false;
        }

        return $status;
    }

    public function delete_user($user)
    {
        $status = true;
        if (!$this->db->delete('auth_users', array('id' => $user))) {
            $status = false;
        }

        return $status;
    }

    public function delete_unidade($unidade)
    {
        $status = true;
        if (!$this->db->delete('esm_medidores', array('unidade_id' => $unidade)) || !$this->db->delete('esm_unidades', array('id' => $unidade)) || !$this->db->delete('esm_unidades_config', array('unidade_id' => $unidade))) {
            $status = false;
        }

        return $status;
    }

    public function get_shoppings_by_user($user_id)
    {
        $query = "
            SELECT esm_shoppings.*, esm_blocos.nome as nome
            FROM esm_shoppings
            JOIN esm_blocos ON esm_blocos.id = esm_shoppings.bloco_id
            JOIN esm_condominios ON esm_condominios.id = esm_blocos.condo_id
            JOIN auth_users_entity ON auth_users_entity.entity_id = esm_condominios.id
            WHERE auth_users_entity.user_id = $user_id";

        $result = $this->db->query($query);

        if ($result->num_rows() <= 0)
            return false;

        return $result->result();
    }

    public function get_lojas_by_shopping($shopping_id)
    {
        $query = "
            SELECT esm_unidades.*
            FROM esm_unidades
            JOIN esm_medidores ON esm_medidores.unidade_id = esm_unidades.id
            WHERE esm_unidades.bloco_id = $shopping_id AND esm_medidores.tipo = 'energia'";

        $result = $this->db->query($query);

        if ($result->num_rows() <= 0)
            return false;

        return $result->result();
    }

    public function add_user($dados)
    {
        $dados['user']['password'] = $this->ion_auth->hash_password($dados['user']['password']);

        $this->db->trans_start();

        $this->db->insert('auth_users', $dados['user']);
        $user_id = $this->db->insert_id();

        $group_tipo = "";
        $group_id = 0;

        if (!is_null($dados['shopping'])) {
            $group_id = 35;
            $group_tipo = "group_shopping";
        }

        if (!is_null($dados['loja'])) {
            $group_id = 36;
            $group_tipo = "unity_shopping";
        }

        $this->db->insert('auth_users_groups', array('user_id' => $user_id, 'group_id' => 33));
        $this->db->insert('auth_users_groups', array('user_id' => $user_id, 'group_id' => $group_id));

        if ($group_tipo === "group_shopping") {
            $this->db->insert('auth_users_group', array('user_id' => $user_id, 'group_id' => $dados['shopping']));
        } elseif ($group_tipo === "unity_shopping") {
            $this->db->insert('auth_users_unidades', array('user_id' => $user_id, 'unidade_id' => $dados['loja']));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function edit_user($dados)
    {
        if (array_key_exists('password', $dados['user'])) {
            $dados['user']['password'] = $this->ion_auth->hash_password($dados['user']['password']);
        }

        $this->db->trans_start();

        $this->db->where('id', $dados['user_id']);
        $this->db->update('auth_users', $dados['user']);

        $group_tipo = "";
        $group_id = 0;

        if (!is_null($dados['shopping'])) {
            $group_id = 35;
            $group_tipo = "group_shopping";
        }

        if (!is_null($dados['loja'])) {
            $group_id = 36;
            $group_tipo = "unity_shopping";
        }

        $this->db->where('user_id', $dados['user_id']);
        $this->db->where('group_id', 33);
        $this->db->update('auth_users_groups', array('user_id' => $dados['user_id'], 'group_id' => 33));

        $this->db->where('user_id', $dados['user_id']);
        $this->db->where('group_id', $group_id);
        $this->db->update('auth_users_groups', array('user_id' => $dados['user_id'], 'group_id' => $group_id));

        if ($group_tipo === "group_shopping") {
            $this->db->where('user_id', $dados['user_id']);
            $this->db->where('group_id', $dados['shopping']);
            $this->db->update('auth_users_group', array('user_id' => $dados['user_id'], 'group_id' => $dados['shopping']));
        } elseif ($group_tipo === "unity_shopping") {
            $this->db->where('user_id', $dados['user_id']);
            $this->db->where('group_id', $dados['loja']);
            $this->db->update('auth_users_unidades', array('user_id' => $dados['user_id'], 'unidade_id' => $dados['loja']));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_info($user_id)
    {
        if ($this->ion_auth->in_group("unity_shopping", $user_id)) {

            $this->db->select("auth_users.*, esm_unidades.id as unidade_id, esm_unidades.nome as unidade_nome, esm_blocos.id as bloco_id, esm_blocos.nome as bloco_nome");

            $this->db->from("auth_users");

            $this->db->join("auth_users_unidades", "auth_users_unidades.user_id = auth_users.id");
            $this->db->join("esm_unidades", "esm_unidades.id = auth_users_unidades.unidade_id");
            $this->db->join("esm_blocos", "esm_blocos.id = esm_unidades.bloco_id");
        } elseif ($this->ion_auth->in_group("group_shopping", $user_id)) {

            $this->db->select("auth_users.*, esm_blocos.id as bloco_id, esm_blocos.nome as bloco_nome");

            $this->db->from("auth_users");

            $this->db->join("auth_users_group", "auth_users_group.user_id = auth_users.id");
            $this->db->join("esm_blocos", "esm_blocos.id = auth_users_group.group_id");
        } else {
            $this->db->select("auth_users.*");

            $this->db->from("auth_users");

            $this->db->join("auth_users_entity", "auth_users_entity.user_id= auth_users.id");
            $this->db->join("esm_condominios", "esm_condominios.id = auth_users_entity.entity_id");
        }

        $this->db->where("auth_users.id", $user_id);

        return $this->db->get()->row();
    }

    public function update_user($user, $password)
    {
        // decode user
        $user_id = $this->decode_user($user);

        // atualiza user

        $data['password'] = $password;

        return $this->ion_auth->update($user_id, $data);
    }

    public function decode_user($str)
    {
        $query = $this->db->query("
            SELECT id
            FROM auth_users
            WHERE MD5(CONCAT('easymeter', id, '123456')) = '$str'
        ");

        if ($query->num_rows() == 0)
            return false;

        return $query->row()->id;
    }

    public function edit_unidade($dados)
    {
        $this->db->trans_start();

        foreach ($dados['tabela'] as $tabela => $campos) {
            if ($tabela === 'esm_unidades') {
                $this->db->where('id', $dados['unidade_id']);
            } elseif ($tabela === 'esm_unidades_config') {
                $this->db->where('unidade_id', $dados['unidade_id']);
            }

            $this->db->update($tabela, $campos);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function edit_client_conf($dados)
    {
        $this->db->trans_start();

        foreach ($dados['tabela'] as $tabela => $campos) {

            $this->db->where('group_id', $dados['group_id']);
            $this->db->update($tabela, $campos);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function get_client_config($condo)
    {
        $this->db->select("*");
        $this->db->from("esm_client_config");
        $this->db->where("group_id", $condo);

        return $this->db->get()->row();
    }

    public function get_groups_by_entity($entity)
    {
        $query = "
            SELECT 
                esm_blocos.nome as nome, esm_shoppings.* 
            FROM 
                esm_shoppings
            JOIN esm_blocos ON esm_blocos.id = esm_shoppings.bloco_id
            WHERE 
                esm_blocos.condo_id = $entity
        ";

        if ($this->db->query($query)->num_rows() <= 0)
            return false;

        return $this->db->query($query)->result();
    }

    public function get_alert_config($group, $grp = false)
    {
        $gr = "";
        if ($grp) {
            $gr = " GROUP BY type ";
        }
        $result = $this->db->query("
            SELECT
                esm_alertas_cfg.*
            FROM
                esm_alertas_cfg
            WHERE
                esm_alertas_cfg.group_id = $group
            $gr"
        );

        if ($result->num_rows())
            return $result->result();

        return false;
    }

    public function get_devices($group, $type)
    {
        $result = $this->db->query("
            SELECT
                esm_alertas_cfg_devices.device
            FROM
                esm_alertas_cfg
	    JOIN esm_alertas_cfg_devices ON esm_alertas_cfg_devices.config_id = esm_alertas_cfg.id
            WHERE
                esm_alertas_cfg.group_id = $group AND esm_alertas_cfg.type = $type"
        );

        if ($result->num_rows()) {
            $list = array();
            foreach($result->result() as $d) {
                $list[] = $d->device;
            }

            return $list;
        }
    
        return false;
    }

    public function get_devices_alert($group, $type)
    {
        $result = $this->db->query("
            SELECT
                esm_alertas_cfg_devices.device as dvc,
                esm_unidades.nome as nome
            FROM
                esm_alertas_cfg
                JOIN esm_alertas_cfg_devices ON esm_alertas_cfg_devices.config_id = esm_alertas_cfg.id
                JOIN esm_medidores ON esm_medidores.nome = esm_alertas_cfg_devices.device
                JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE
                esm_alertas_cfg.group_id = $group AND esm_alertas_cfg_devices.config_id = $type"
        );

        if ($result->num_rows()) {
            return $result->result();
        }

        return false;
    }

    public function get_devices_agrupamento($id)
    {
        $result = $this->db->query("
            SELECT
                esm_device_groups_entries.device as dvc,
                esm_unidades.nome
            FROM 
                esm_device_groups_entries
            JOIN esm_medidores ON esm_medidores.nome = esm_device_groups_entries.device
            JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE esm_device_groups_entries.group_id = $id"
        );

        if ($result->num_rows()) {
            return $result->result();
        }

        return false;
    }

    public function edit_alert_conf($dados)
    {
        $this->db->trans_start();

        $this->db->where("config_id", $dados['config_id']);
        $this->db->delete("esm_alertas_cfg_devices");

        if ($dados['esm_alertas_cfg_devices']) {
            foreach ($dados['esm_alertas_cfg_devices'] as $dvc) {
                $this->db->insert("esm_alertas_cfg_devices", array('config_id' => $dados['config_id'], 'device' => $dvc,));
            }
        }

        $this->db->where("group_id", $dados['group_id']);
        $this->db->where("id", $dados['config_id']);
        $this->db->update('esm_alertas_cfg', array(
            'when_type' => $dados['esm_alertas_cfg']['when_type'],
            'notify_unity' => $dados['esm_alertas_cfg']['notify_unity'],
            'notify_shopping' => $dados['esm_alertas_cfg']['notify_shopping'],
            'active' => $dados['esm_alertas_cfg']['active'],
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }



        $config_id = $this->db->query("
            SELECT id 
            FROM esm_alertas_cfg 
            WHERE group_id = " . $dados['group'] . " AND type = " . $dados['type'])->row()->id;

        $this->db->trans_start();

        $this->db->where("config_id", $config_id);
        $this->db->delete("esm_alertas_cfg_devices");

        foreach ($dados['esm_alertas_cfg_devices']['device'] as $dvc) {
            $this->db->insert("esm_alertas_cfg_devices", array('config_id' => $config_id, 'device' => $dvc,));
        }

        $this->db->where("group_id", $dados['group']);
        $this->db->where("type", $dados['type']);
        $this->db->update('esm_alertas_cfg', array(
            'when_type' => $dados['esm_alertas_cfg']['when_type'],
            'notify_unity' => $dados['esm_alertas_cfg']['notify_unity'],
            'notify_shopping' => $dados['esm_alertas_cfg']['notify_shopping'],
            'active' => $dados['esm_alertas_cfg']['active'],
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function edit_agrupamento($dados)
    {
        $this->db->trans_start();

        $this->db->where("group_id", $dados['id']);
        $this->db->delete("esm_device_groups_entries");

        if ($dados['devices']) {
            foreach ($dados['devices'] as $dvc) {
                $this->db->insert("esm_device_groups_entries", array('group_id' => $dados['id'], 'device' => $dvc,));
            }
        }

        $this->db->where("id", $dados['id']);
        $this->db->update('esm_device_groups', array('name' => $dados['name']));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_agrupamento($id)
    {
        $this->db->trans_start();

        $this->db->where("group_id", $id);
        $this->db->delete("esm_device_groups_entries");

        $this->db->where("id", $id);
        $this->db->delete("esm_device_groups");

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function add_agrupamento($dados)
    {
        $this->db->trans_start();

        $this->db->insert("esm_device_groups", array("entrada_id" => $dados['entrada_id'], "name" => $dados['name']));

        $inserted = $this->db->insert_id();

        if ($dados['devices']) {
            foreach ($dados['devices'] as $dvc) {
                $this->db->insert("esm_device_groups_entries", array('group_id' => $inserted, 'device' => $dvc));
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function get_unidade_by_medidor($mid)
    {
        // seleciona todos os campos
        $query = $this->db->query("
            SELECT unidade_id
            FROM esm_medidores
            WHERE id = $mid
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row()->unidade_id;
    }

    public function change_log_state($id)
    {
        if ($id == 0) {
            if ($this->db->query("UPDATE esm_user_logs SET lido = 1"))
                return array("status"  => "success", "message" => "Entradas marcadas com sucesso");
            else
                return array("status"  => "error", "message" => $this->db->error()['message']);
        } else {
            if ($this->db->query("UPDATE esm_user_logs SET lido = IF(lido = 1, 0, 1) WHERE id = $id"))
                return array("status"  => "success", "message" => "Entrada marcada com sucesso");
            else
                return array("status"  => "error", "message" => $this->db->error()['message']);
        }
    }

    public function insertToken($token, $group_id)
    {
        $this->db->trans_start();

        $this->db->where('group_id', $group_id);
        $q = $this->db->get('esm_api_keys');

        $this->db->where('group_id', $group_id);
        if ( $q->num_rows() > 0 ) {
            $this->db->update('esm_api_keys', array("token" => $token));
        } else {
            $this->db->insert('esm_api_keys', array("group_id" => $group_id, "token" => $token));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function getToken($group_id)
    {
        // seleciona todos os campos
        $query = $this->db->query("
            SELECT token
            FROM esm_api_keys
            WHERE group_id = $group_id
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        return $query->row()->token;
    }
}
