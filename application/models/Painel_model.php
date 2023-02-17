<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Painel_model extends CI_Model
{
    public function get_report_by_id($id, $array = false)
    {
        // realiza a query via dt
        $query = $this->db->query("
            SELECT esm_condominios.nome, esm_relatorios.*
            FROM esm_relatorios
            JOIN esm_condominios ON esm_condominios.id = esm_relatorios.condo_id
            WHERE esm_relatorios.id = $id
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        if ($array)
            return $query->row_array();
        else
            return $query->row();
    }

    public function get_report_data_by_id($id, $array = false)
    {
        // realiza a query via dt
        $query = $this->db->query("
                SELECT 
                    esm_medidores.nome, 
                    esm_medidores.tipo, 
                    esm_relatorios_dados.leitura_anterior, 
                    esm_relatorios_dados.leitura_atual, 
                    esm_relatorios_dados.consumo 
                FROM esm_relatorios_dados
                JOIN esm_medidores ON esm_medidores.id = esm_relatorios_dados.medidor_id
                WHERE esm_relatorios_dados.relatorio_id = $id
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        if ($array)
            return $query->result_array();
        else
            return $query->result();
    }

    public function get_medidores_geral($id, $monitoramento = 'agua', $array = false)
    {
        $query = $this->db->query("
            SELECT 
                esm_medidores.*, 
                esm_unidades.nome AS unidade
            FROM esm_condominios_centrais 
            JOIN esm_medidores ON esm_medidores.central = esm_condominios_centrais.nome
            JOIN esm_unidades ON esm_unidades.id = esm_medidores.unidade_id
            WHERE 
                esm_condominios_centrais.condo_id = $id AND 
                esm_medidores.tipo = '$monitoramento' AND
                esm_medidores.sub_tipo = 'geral'
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        if ($array)
            return $query->result_array();
        else
            return $query->result();
    }

    public function get_consumo_medidores_geral($id, $start, $end, $monitoramento = 'agua', $array = false)
    {
        $query = $this->db->query("
            SELECT 
                SUM(consumo) / 1000 AS value, 
                DATEDIFF('$end', '$start') + 1 AS days
            FROM esm_condominios_centrais 
            JOIN esm_medidores ON esm_medidores.central = esm_condominios_centrais.nome
            JOIN esm_leituras_bauducco_agua ON esm_leituras_bauducco_agua.medidor_id = esm_medidores.id
            WHERE 
                esm_condominios_centrais.condo_id = $id AND 
                esm_medidores.tipo = '$monitoramento' AND
                esm_medidores.sub_tipo = 'geral' AND
                esm_leituras_bauducco_agua.timestamp > UNIX_TIMESTAMP('$start 00:00:00') AND 
                esm_leituras_bauducco_agua.timestamp < (UNIX_TIMESTAMP('$end 23:59:59') + 3600 )
                
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        if ($array)
            return $query->row_array();
        else
            return $query->row();
    }

    public function get_leituras($mid, $start, $end, $monitoramento, $array = false)
    {
        if ($monitoramento == "nivel") {
//((esm_leituras_bauducco_nivel.leitura - 1162) * esm_sensores_nivel.mca / 4649 / esm_sensores_nivel.profundidade_total) * 100 as value
            $query = $this->db->query("
                SELECT 
                    IF(MOD(timestamp, 3600) = 0, DATE_FORMAT(FROM_UNIXTIME(timestamp), '%H:%i'), '') AS label,
                    DATE_FORMAT(FROM_UNIXTIME(timestamp), '%H:%i') AS tooltip,
                    esm_sensores_nivel.profundidade_total - ((esm_leituras_bauducco_nivel.leitura - 1162) * esm_sensores_nivel.mca / 4649) as value
                FROM esm_leituras_bauducco_nivel
                JOIN esm_sensores_nivel ON esm_sensores_nivel.medidor_id = esm_leituras_bauducco_nivel.medidor_id
                WHERE
                    esm_leituras_bauducco_nivel.medidor_id = $mid
                    AND esm_leituras_bauducco_nivel.leitura > 0
                    AND timestamp >= UNIX_TIMESTAMP('$end 00:00:00') AND 
                    timestamp <= IF(DATE_FORMAT(NOW(), '%Y-%m-%d') = '$end', UNIX_TIMESTAMP(NOW()), UNIX_TIMESTAMP('$end 23:59:59'))
                ORDER BY timestamp
            ");

        } else {
            
            if ($start == $end) {

                $query = $this->db->query("
                    SELECT 
                        CONCAT(LPAD(esm_hours.num, 2, '0'), ':00') AS label, 
                        CONCAT(LPAD(IF(esm_hours.num + 1 > 23, 0, esm_hours.num + 1), 2, '0'), ':00') AS next,
                        consumo / 1000 AS value
                    FROM esm_hours
                    LEFT JOIN esm_leituras_bauducco_agua d ON 
                        HOUR(FROM_UNIXTIME(d.timestamp - 3600)) = esm_hours.num AND 
                        d.timestamp > UNIX_TIMESTAMP('$start 00:00:00') AND 
                        d.timestamp <= UNIX_TIMESTAMP('$end 23:59:59') + 3600 AND
                        d.medidor_id = $mid
                    GROUP BY esm_hours.num
                    ORDER BY esm_hours.num
                ");

            } else {

                $query = $this->db->query("
                    SELECT 
                        CONCAT(LPAD(esm_calendar.d, 2, '0'), '/', LPAD(esm_calendar.m, 2, '0')) AS label, 
                        esm_calendar.dt AS date,
                        esm_calendar.dw AS dw,
                        SUM(consumo) / 1000 AS value
                    FROM esm_calendar
                    LEFT JOIN esm_leituras_bauducco_agua d ON 
                        d.timestamp > esm_calendar.ts_start AND 
                        d.timestamp <= esm_calendar.ts_end + 3600 AND
                        d.medidor_id = $mid
                    WHERE 
                        esm_calendar.dt >= '$start' AND 
                        esm_calendar.dt <= '$end' 
                    GROUP BY esm_calendar.dt
                    ORDER BY esm_calendar.dt
                ");
            }
        }

        if ($query->num_rows() == 0)
            return false;

        if ($array)
            return $query->result_array();
        else
            return $query->result();
    }

    public function get_last_nivel($id, $array = false)
    {
        $query = $this->db->query("
            SELECT esm_sensores_nivel.*, esm_leituras_bauducco_nivel.leitura, esm_leituras_bauducco_nivel.timestamp, d.*
            FROM esm_leituras_bauducco_nivel
            JOIN esm_sensores_nivel ON esm_sensores_nivel.medidor_id = esm_leituras_bauducco_nivel.medidor_id
            JOIN (
                SELECT $id AS id, MAX(leitura) AS estatico, MIN(leitura) AS minimo FROM esm_leituras_bauducco_nivel WHERE medidor_id = $id AND leitura > 0
                ) d ON d.id = esm_leituras_bauducco_nivel.medidor_id
            WHERE 
                esm_leituras_bauducco_nivel.medidor_id = $id AND
                timestamp <= UNIX_TIMESTAMP(NOW())
            ORDER BY timestamp DESC
            LIMIT 1                
        ");

        // verifica se retornou algo
        if ($query->num_rows() == 0)
            return false;

        if ($array)
            return $query->row_array();
        else
            return $query->row();
    }
}