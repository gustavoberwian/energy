<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sse_model extends CI_Model
{
    public function get_ultima_leitura()
    {
        $result = $this->db->query("SELECT
                MAX(esm_leituras_ancar_agua.TIMESTAMP) as timestamp
            FROM
                esm_leituras_ancar_agua
                JOIN esm_medidores ON esm_medidores.id = esm_leituras_ancar_agua.medidor_id
                JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id 
            WHERE
                esm_unidades.bloco_id = 113");

        if ($result->num_rows())
            return $result->row()->timestamp;

        return false;
    }

    public function verifica_nova_leitura($leitura_atual)
    {
        $limit = "";
        if ($leitura_atual == 0) {
            $limit = " ORDER BY timestamp DESC LIMIT 1 ";
        }
        $result = $this->db->query("SELECT
				esm_leituras_ancar_agua.timestamp
            FROM
                esm_leituras_ancar_agua 
            JOIN esm_medidores ON esm_medidores.id = esm_leituras_ancar_agua.medidor_id
            JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE
                esm_unidades.bloco_id = 113 AND 
				timestamp > $leitura_atual
            GROUP BY timestamp
				$limit ");

        if ($result->num_rows())
            return $result->result();

        return false;
    }

    public function getActiveConsumiption($leitura)
    {
        $q = "
            SELECT
	             SUM(consumo) as consumo
            FROM
                esm_leituras_ancar_agua 
            JOIN esm_medidores ON esm_medidores.id = esm_leituras_ancar_agua.medidor_id
            JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE
                esm_unidades.bloco_id = 113 AND esm_leituras_ancar_agua.timestamp = $leitura
        ";

        if ($this->db->query($q)->num_rows())
            return $this->db->query($q)->row()->consumo;

        return false;
    }

    public function get_unidades($group_id, $monitoramento = null)
    {
        $m = "";

        if (!is_null($monitoramento)) {
            $m = " AND esm_medidores.tipo = '$monitoramento' ";
        }

        $query = "
            SELECT
                esm_unidades.id AS unidade_id,
                esm_medidores.id as medidor_id,
                esm_unidades.nome AS unidade_nome,
                esm_medidores.ultima_leitura AS ultima_leitura,
                esm_medidores.ultimo_consumo AS ultimo_consumo,
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
                ) as endereco,
                esm_condominios_centrais.ultimo_envio as ultimo_envio
            FROM
                esm_unidades
                JOIN esm_blocos ON esm_blocos.id = esm_unidades.bloco_id
                JOIN esm_shoppings ON esm_shoppings.bloco_id = esm_blocos.id
                JOIN esm_medidores ON esm_unidades.id = esm_medidores.unidade_id
                JOIN esm_condominios_centrais ON esm_condominios_centrais.nome = esm_medidores.central
            WHERE
                esm_unidades.bloco_id = $group_id
                $m
        ";
        $result = $this->db->query($query);

        if ($result->num_rows() <= 0) {

            return false;
        }

        return $result->result();
        // return $query;
    }

    public function getTotal($device, $start, $end)
    {
        $query = "
            SELECT
                esm_hours.num,
                SUM(esm_leituras_ancar_agua.consumo) as consumo 	
            FROM
                esm_hours
                JOIN esm_leituras_ancar_agua ON HOUR (
                FROM_UNIXTIME( TIMESTAMP - 3600 )) = esm_hours.num 
                AND TIMESTAMP > UNIX_TIMESTAMP( '$start 00:00:00' ) 
                AND TIMESTAMP <= UNIX_TIMESTAMP( '$end 23:59:59' ) + 600 
                WHERE esm_leituras_ancar_agua.medidor_id = $device
            GROUP BY
                esm_hours.num 
            ORDER BY
                esm_hours.num
        ";

        $result = $this->db->query($query);

        if ($result->num_rows() <= 0) {

            return false;
        }

        return $result->result();
    }

    public function get_alertas($group_id, $monitoramento = null)
    {
        $m = "";

        if (!is_null($monitoramento)) {
            $m = " esm_alertas.monitoramento = '$monitoramento' ";
        }

        $query = "
            SELECT 
                esm_alertas.id as id,
                esm_alertas.tipo as status,
                esm_alertas.titulo as titulo,
                esm_alertas.texto as texto,
                esm_alertas.enviada as enviada
            FROM esm_alertas
            JOIN esm_alertas_envios ON esm_alertas_envios.alerta_id = esm_alertas.id
            JOIN auth_users_group ON auth_users_group.user_id = esm_alertas_envios.user_id
            WHERE auth_users_group.group_id = $group_id AND $m
        ";
        $result = $this->db->query($query);

        if ($result->num_rows() <= 0) {

            return false;
        }

        return $result->result();
    }

    public function verifica_novo_alerta($last, $group_id, $monitoramento = null, $limit = false)
    {
        $l = "";
        $m = "";

        if (!is_null($monitoramento)) {
            $m = " esm_alertas.monitoramento = '$monitoramento' ";
        }
        if ($limit) {
            $l = " LIMIT $limit ";
        }

        $query = "
           SELECT 
                esm_alertas.id as id,
                esm_alertas.tipo as status,
                esm_alertas.titulo as titulo,
                esm_alertas.texto as texto,
                esm_alertas.enviada as enviada
            FROM esm_alertas
            JOIN esm_alertas_envios ON esm_alertas_envios.alerta_id = esm_alertas.id
            JOIN auth_users_group ON auth_users_group.user_id = esm_alertas_envios.user_id
            WHERE auth_users_group.group_id = $group_id AND $m
            AND UNIX_TIMESTAMP(enviada) > $last
	        ORDER BY enviada DESC $l
        ";

        $result = $this->db->query($query);

        if ($result->num_rows() <= 0) {

            return false;
        }

        return $result->result();
    }

    public function get_ultimo_alerta($group_id, $monitoramento = null)
    {
        $m = "";

        if (!is_null($monitoramento)) {
            $m = " AND esm_alertas.monitoramento = '$monitoramento' ";
        }

        $query = "
           SELECT UNIX_TIMESTAMP( esm_alertas.enviada ) AS timestamp 
            FROM esm_alertas
            JOIN esm_alertas_envios ON esm_alertas_envios.alerta_id = esm_alertas.id
            JOIN auth_users_group ON auth_users_group.user_id = esm_alertas_envios.user_id
            WHERE auth_users_group.group_id = $group_id $m
	        ORDER BY enviada DESC LIMIT 1
        ";

        $result = $this->db->query($query);

        if ($result->num_rows())
            return $result->row()->timestamp;

        return false;
    }
}