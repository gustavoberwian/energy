<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends Api_Controller {

    public $central;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('api_model');
    }

    public function doc()
    {
        $this->render('doc');
    }

    public function get($version, $param)
    {
        if ($version != 1) {
            echo json_encode(array("status" => "error", "message" => "Invalid API version"));
            return;
        }

        $auth = $this->input->get('appid');
        if (is_null($auth)) {
            echo json_encode(array("status" => "error", "message" => "Invalid API key"));
            return;
        }

        $client = $this->api_model->api_get_token($auth);

        if (!$client) {
            echo json_encode(array("status" => "error", "message" => "Invalid API key"));
            return;
        }

        if ($param == "energy" && !is_null($client->energia_id)) {

            $cfg = $this->api_model->GetClientConfig($client->group_id);

            $cmd = $this->input->get('q');

            if ($cmd == "list") {

                echo json_encode(array("status" => "success", "data" => $this->api_model->api_device_list($client->energia_id)));

            } else if ($cmd == "resume") {

                echo json_encode(array("status" => "success", "data" => $this->api_model->api_get_resume($cfg)));

            } else if (in_array($cmd, array("consumption", "active_demand", "reactive", "load", "instant_active", "instant_current",
                "instant_voltage", "instant_power", "instant_load", "instant_reactive", "instant_factor",
                "instant_consumption"))) {

                $device = $this->input->get('d');
                $start  = $this->input->get('s');
                $end    = $this->input->get('e');

                if (is_null($device)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid d parameter"));
                    return;
                }
                if (is_null($start) || !checkDateFormat($start)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid s parameter"));
                    return;
                }
                if (is_null($end) || !checkDateFormat($end)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid e parameter"));
                    return;
                }

                if (!$this->api_model->api_get_device($device, $client->energia_id, 'energia')) {
                    echo json_encode(array("status" => "error", "message" => "Invalid device"));
                    return;
                }

                echo json_encode($this->chart_engineering($cmd, $device, $start, $end, $cfg));

            } else {

                echo json_encode(array("status" => "error", "message" => "Invalid q parameter"));
            }

        } else if ($param == "water" && !is_null($client->agua_id)) {

            $cfg = $this->api_model->GetClientConfig($client->group_id);

            $cmd = $this->input->get('q');

            if ($cmd == "list") {

                echo json_encode(array("status" => "success", "data" => $this->api_model->api_device_list($client->agua_id)));

            } else if ($cmd == "resume") {

                echo json_encode(array("status" => "success", "data" => $this->api_model->water_resume($cfg), "unity" => "L"));

            } else if ($cmd == "consumption") {

                $device = $this->input->get('d');
                $start  = $this->input->get('s');
                $end    = $this->input->get('e');

                if (is_null($device)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid d parameter"));
                    return;
                }
                if (is_null($start) || !checkDateFormat($start)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid s parameter"));
                    return;
                }
                if (is_null($end) || !checkDateFormat($end)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid e parameter"));
                    return;
                }

                if (!$this->api_model->api_get_device($device, $client->agua_id, 'agua')) {
                    echo json_encode(array("status" => "error", "message" => "Invalid device"));
                    return;
                }

                echo json_encode($this->water_data($device, $start, $end));

            } else if ($cmd == "accountings") {

                $pag = $this->input->get('p');

                if (is_null($pag)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid p parameter"));
                    return;
                }

                echo json_encode(array("status" => "success", "data" => $this->api_model->api_get_lancamentos($client->group_id, $pag * 10), "unity" => "L"));

            } else if ($cmd == "accounting") {

                $fid = $this->input->get('i');

                if (is_null($fid)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid i parameter"));
                    return;
                }

                $data    = $this->api_model->api_get_lancamento_details($fid);
                $message = $this->api_model->api_get_lancamento_message($fid);

                if ($data)
                    echo json_encode(array("status" => "success", "data" => $data, "message" => $message->mensagem, "unity" => "L"));
                else
                    echo json_encode(array("status" => "error", "message" => "Invalid accounting"));

            } else if ($cmd == "add_accounting") {

                $competencia = $this->input->get('c');
                $start       = $this->input->get('s');
                $end         = $this->input->get('e');
                $message     = $this->input->get('m');

                if (is_null($competencia)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid c parameter"));
                    return;
                } else {
                    $c = explode('-', $competencia);

                    if (intval($c[0]) > 0 && intval($c[0]) < 13) {
                        $competencia =  intval($c[0])."/".$c[1];
                    } else {
                        echo json_encode(array("status" => "error", "message" => "Invalid c parameter"));
                        return;
                    }
                }
                if (is_null($start) || !checkDateFormat($start)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid s parameter"));
                    return;
                }
                if (is_null($end) || !checkDateFormat($end)) {
                    echo json_encode(array("status" => "error", "message" => "Invalid e parameter"));
                    return;
                }

                if ($this->api_model->VerifyCompetencia($client->agua_id, $competencia)) {
                    echo json_encode(array("status" => "error", "message" => "Competence already have a accounting"));
                    return;
                }

                // validade dates

                $data = array(
                    "group_id"    => $client->group_id,
                    "entrada_id"  => $client->agua_id,
                    "competencia" => $competencia,
                    "inicio"      => $start,
                    "fim"         => $end,
                    "mensagem"    => $message,
                );

                echo $this->api_model->Calculate($data, $cfg);

            } else {

                echo json_encode(array("status" => "error", "message" => "Invalid q parameter"));
            }

        } else {

            echo json_encode(array("status" => "error", "message" => "Invalid URL"));
        }
    }

    private function chartMainActivePositive($device, $start, $end, $cfg)
    {
        if ($start == $end && date("N", strtotime($start)) >= 6) {

            $period_p = false;
            $period_f = $this->api_model->api_get_active_positive($device, $start, $end);
            $period_i = false;

        } else {
            $period_p = $this->api_model->api_get_active_positive($device, $start, $end, array("ponta", $cfg->ponta_start, $cfg->ponta_end));
            $period_f = $this->api_model->api_get_active_positive($device, $start, $end, array("fora", $cfg->ponta_start, $cfg->ponta_end));
            $period_i = false;
        }

        $values_p  = array();
        $values_f  = array();
        $values_i  = array();
        $labels    = array();
        $titles    = array();
        $dates     = array();
        $total_p   = 0;
        $total_f   = 0;
        $total_i   = 0;

        $max_p = -1;
        $max_f = -1;
        $min_p = 999999999;
        $min_f = 999999999;

        if ($period_f) {
            foreach ($period_f as $v) {
                $values_f[] = array($v->label, round($v->value, 3));
            }
        }

        if ($period_p) {
            foreach ($period_p as $v) {
                $values_p[] = array($v->label, round($v->value, 3));
            }
        }

        $series = array();

        if ($period_f) {
            $series[] = array(
                "name" => "Fora Ponta",
                "data" => $values_f,
            );
        }
        if ($period_p) {
            $series[] = array(
                "name" => "Ponta",
                "data" => $values_p,
            );
        }

        return $series;
    }

    private function chartMainStation($device, $start, $end, $cfg)
    {
        if ($start == $end && date("N", strtotime($start)) >= 6) {

            $period_p = false;
            $period_f = $this->api_model->api_get_active_positive($device, $start, $end);

        } else {
            $period_p = $this->api_model->api_get_active_positive($device, $start, $end, array("ponta", $cfg->ponta_start, $cfg->ponta_end));
            $period_f = $this->api_model->api_get_active_positive($device, $start, $end, array("fora", $cfg->ponta_start, $cfg->ponta_end));
        }

        $series = array();

        $consumption_f = 0;
        if ($period_f) {
            foreach ($period_f as $v) {
                $consumption_f += $v->value;
            }

            $series[] = array(
                "name" => "Fora Ponta",
                "data" => round($consumption_f, 3),
            );
        }

        $consumption_p = 0;
        if ($period_p) {
            foreach ($period_p as $v) {
                $consumption_p += $v->value;
            }

            $series[] = array(
                "name" => "Ponta",
                "data" => round($consumption_p, 3),
            );
        }

        return $series;
    }

    private function chartMainActiveDemand($device, $start, $end, $cfg)
    {

        if ($start == $end) {

            $period = $this->api_model->GetActiveDemand($device, $start, $end);
            $count  = 1;

        } else {

            $period = $this->api_model->GetActiveDemand($device, $start, $end);
            $count  = 24;
        }

        $values_max = array();
        $values_avg = array();

        if ($period) {
            foreach ($period as $v) {
                $values_max[] = array($v->label, round($v->valueMax, 3));
                $values_avg[] = array($v->label, $v->valueSum === null ? null : round($v->valueSum / $count, 3));
            }
        }

        $series = array(
            array(
                "name" => "Demanda Máxima",
                "data" => $values_max,
            ),
            array(
                "name" => "Demanda Média",
                "data" => $values_avg,
            )
        );

        return $series;
    }

    private function chartMainReactive($device, $start, $end, $cfg)
    {
        $period = $this->api_model->GetMainReactive($device, $start, $end);

        $values_c = array();
        $values_i = array();
        $labels   = array();

        if ($period) {
            foreach ($period as $v) {
                $values_c[] = array($v->label, round($v->valueCap, 3));
                $values_i[] = array($v->label, round($v->valueInd, 3));
            }
        }

        $series = array(
            array(
                "name" => "Reativa Capacitiva",
                "data" => $values_c,
            ),
            array(
                "name" => "Reativa Indutiva",
                "data" => $values_i,
            )
        );

        return $series;
    }

    private function chartMainLoad($device, $start, $end, $cfg)
    {
        $value_load = array();

        $values = $this->api_model->getMainLoad($device, $start, $end);

        foreach ($values as $v) {
            $value_load[] = array($v->label, $v->value);
        }

        $series[] = array(
            "name" => "Fator de Carga",
            "data" => $value_load,
        );

        return $series;
    }

    private function chartFactorPhases($device, $start, $end, $cfg)
    {
        $value_a = array();
        $value_b = array();
        $value_c = array();
        $values  = $this->api_model->GetFactorPhases($device, $start, $end);

        foreach ($values as $v) {

            if (is_null($v->value_a)) {
                $value_a[] = array($v->label, null);
            } else {
                $vl = ($v->type_a == "I") ? $v->value_a : $v->value_a * -1;
                $value_a[] = array($v->label, round($vl, 3));
            }

            if (is_null($v->value_b)) {
                $value_b[] = array($v->label, null);
            } else {
                $vl = ($v->type_b == "I") ? $v->value_b : $v->value_b * -1;
                $value_b[] = array($v->label, round($vl, 3));
            }

            if (is_null($v->value_c)) {
                $value_c[] = array($v->label, null);
            } else {
                $vl = ($v->type_c == "I") ? $v->value_c : $v->value_c * -1;
                $value_c[] = array($v->label, round($vl, 3));
            }
        }

        $series = array(
            array(
                "data" => $value_a,
                "name" => "Fase R",
            ),
            array(
                "data" => $value_b,
                "name" => "Fase S",
            ),
            array(
                "data" => $value_c,
                "name" => "Fase T",
            ),
        );

        return $series;
    }

    private function chart_engineering($field, $device, $start, $end, $cfg)
    {
        $divisor  = 1;
        $decimals = 0;
        $unidade  = "";
        $type     = "line";

        if ($field == "consumption") {
            $data = $this->chartMainActivePositive($device, $start, $end, $cfg);
            return array("status" => "success", "data" => $data, "unity" => "kWh");
        } else if ($field == "mainStation") {
            $data = $this->chartMainStation($device, $start, $end, $cfg);
            return array("status" => "success", "data" => $data, "unity" => "kWh");
        } else if ($field == "active_demand") {
            $data = $this->chartMainActiveDemand($device, $start, $end, $cfg);
            return array("status" => "success", "data" => $data, "unity" => "kW");
        } else if ($field == "reactive") {
            $data = $this->chartMainReactive($device, $start, $end, $cfg);
            return array("status" => "success", "data" => $data, "unity" => "kVArh");
        } else if ($field == "load") {
            $data = $this->chartMainLoad($device, $start, $end, $cfg);
            return array("status" => "success", "data" => $data, "unity" => "");


            /*        else if ($field == "mainFactor") {
                        echo $this->chartMainFactor();
                        return;
                    }
                */
        } else if ($field == "instant_active") {
            $divisor  = 1000;
            $unidade  = "kW";
            $decimals = 0;
        } else if ($field == "instant_current") {
            $unidade  = "A";
            $decimals = 1;
        } else if ($field == "instant_voltage") {
            $unidade = "V";
            $decimals = 1;
        } else if ($field == "instant_power") {
            $unidade  = "Kw";
            $divisor  = 1000;
            $decimals = 1;
            $type     = "bar";
        } else if ($field == "instant_load") {
            $unidade  = "";
            $divisor  = 1;
            $decimals = 1;
            $type     = "bar";
        } else if ($field == "instant_reactive") {
            $unidade  = "kVAr";
            $divisor  = 1000;
            $decimals = 0;
        } else if ($field == "instant_factor") {
            $data = $this->chartFactorPhases($device, $start, $end, $cfg);
            return array("status" => "success", "data" => $data, "unity" => "");
        }

        $value_a = array();
        $value_b = array();
        $value_c = array();

        if ($field == "instant_load")
            $values = $this->api_model->GetLoadPhases($device, $start, $end, $field);
        else
            $values = $this->api_model->GetValuesPhases($device, $start, $end, $field);

        foreach ($values as $v) {

            $value_a[] = array($v->label, is_null($v->value_a) ? null : round($v->value_a / $divisor, 3));
            $value_b[] = array($v->label, is_null($v->value_b) ? null : round($v->value_b / $divisor, 3));
            $value_c[] = array($v->label, is_null($v->value_c) ? null : round($v->value_c / $divisor, 3));
        }

        $series = array(
            array(
                "name" => "Fase R",
                "data" => $value_a,
            ),
            array(
                "name" => "Fase S",
                "data" => $value_b,
            ),
            array(
                "name" => "Fase T",
                "data" => $value_c,
            ),
        );

        return array("status" => "success", "data" => $series, "unity" => $unidade);
    }

    public function water_data($device, $start, $end)
    {
        $period   = $this->api_model->GetConsumption($device, $start, $end);
        $values   = array();
        $series   = array();

        if ($period) {
            foreach ($period as $v) {
                $values[] = array($v->label, round($v->value, 0));
                $labels[] = $v->label;
            }

            $series[] = array(
                "name"  => "Consumo",
                "data"  => $values,
            );
        }

        return array("status" => "success", "name" => "Consumo", "data" => $values, "unity" => "L");
    }
}