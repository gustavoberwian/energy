<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sse extends SSE_Controller
{
    public int $ultimaLeitura = 0;

    public function __construct()
    {
        parent::__construct();

        $this->load->model("sse_model");
        $this->load->model("water_model");
        $this->load->library('session');
    }

    public function index()
    {
        $data['alertas'] = $this->sse_model->get_alertas(113, 'agua');
        $data['unidades'] = $this->sse_model->get_unidades(113, 'agua');
        $ultimo_envio = 0;
        foreach ($data['unidades'] as $un) {
            if ($un->ultimo_envio > $ultimo_envio) {
                $ultimo_envio = $un->ultimo_envio;
            }
        }

        $data['ultimo_envio'] = $ultimo_envio;
        $data['unidades'] = $this->sse_model->get_unidades(113, 'agua');

        $data['max'] = 30;

        //$chart = $this->chart('ALL', strtotime('-1 day', $ultimo_envio), $ultimo_envio, 'mainActivePositive', 'h');
        //echo "<pre>"; print_r($alertas); echo "</pre>"; return;
        //echo "<pre>"; print_r($chart); echo "</pre>"; return;
        $this->render('index', $data);
    }

    public function event()
    {
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Connection: keep-alive");

        while(true) {
            $leituras = $this->sse_model->verifica_nova_leitura($this->input->get('atual'));

            $alertas = $this->sse_model->verifica_novo_alerta($this->input->get('last'), 113, 'agua', 6);

            if ($leituras) {
                $data['unidades'] = $this->sse_model->get_unidades(113, 'agua');
                $ultimo_envio = 0;
                foreach ($data['unidades'] as $un) {
                    if ($un->ultimo_envio > $ultimo_envio) {
                        $ultimo_envio = $un->ultimo_envio;
                    }
                }

                $chart = $this->chart('ALL', strtotime('-1 day', $ultimo_envio), $ultimo_envio, 'mainActivePositive', 'h');

                echo "event: chart\n", "data: " . json_encode($chart) . "\n\n";
            }

            if ($alertas) {

                $data = array();
                
                foreach ($alertas as $i => $alerta) {

                    $status = "primary";
                    $icon = "fa-info";
                    if ($alerta->status === 'aviso') {
                        $status = "warning";
                        $icon = "fa-exclamation";
                    } elseif ($alerta->status === 'vazamento') {
                        $status = "danger";
                        $icon = "fa-exclamation-triangle";
                    }

                    $data[$i]['data'] = "<div class=\"row\" data-id=\"$alerta->id\">";
                    $data[$i]['data'] .= "<div class=\"col-12\">";
                    $data[$i]['data'] .= "<section class=\"card card-featured-left card-featured-$status  mb-3\">";
                    $data[$i]['data'] .= "<div class=\"card-body bg-quaternary\">";
                    $data[$i]['data'] .= "<div class=\"widget-summary\">";
                    $data[$i]['data'] .= "<div class=\"widget-summary-col widget-summary-col-icon align-middle\">";
                    $data[$i]['data'] .= "<div class=\"summary-icon sse status\"><i class=\"fas $icon text-$status\"></i></div>";
                    $data[$i]['data'] .= "</div>";
                    $data[$i]['data'] .= "<div class=\"widget-summary-col\">";
                    $data[$i]['data'] .= "<div class=\"summary d-flex flex-column justify-content-center\">";
                    $data[$i]['data'] .= "<span class=\"text-uppercase enviada\"> " . date("d/m/Y H:i:s", strtotime($alerta->enviada)) . "</span>";
                    $data[$i]['data'] .= "<h4 class=\"title title-alert\"><strong class=\"amount\" style=\"font-size: 1.1rem\">$alerta->titulo</strong></h4>";
                    $data[$i]['data'] .= "<div class=\"info text-alert\">$alerta->texto</div>";
                    $data[$i]['data'] .= "</div></div></div></div></section></div></div>";

                    if ($i == array_key_first($alertas)) {
                        $data[$i]['last'] = strtotime($alerta->enviada);
                    }

                    $data[$i]['prepend'] = "append";
                    if ($this->input->get('last')) {
                        $data[$i]['prepend'] = "prepend";
                    }
                }

                echo "event: alertas\n", "data: " . json_encode($data) . "\n\n";
            }

            echo "event: last\n", "data: " . $this->sse_model->get_ultimo_alerta(113) . "\n\n";
            echo "event: timestamp\n", "data: " . $this->sse_model->get_ultima_leitura() . "\n", "retry: " . 20000 . "\n", "\n\n";

            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            flush();

            if (connection_aborted()) {
                echo "data: connection aborted\n\n";
            }
        }
    }

    private function chartConfig($type, $stacked, $series, $titles, $labels, $unit, $decimals, $extra = array(), $footer = "", $dates = array(), $units = array())
    {
        $config = array(
            "chart" => array(
                "type" => $type,
                "height" => '280',
                "foreColor" => "#ccc",
                "stacked"   => $stacked,
                "toolbar"  => array(
                    "autoSelected" => "pan",
                    "show" => false
                )
            ),
            "colors" => ["#00BAEC"],
            "stroke" => array(
                "width" => 3
            ),
            "grid" => array(
                "borderColor" => "#555",
                "yaxis" => array(
                    "lines" => array(
                        "show" => false
                    )
                )
            ),
            "dataLabels" => array(
                "enabled" => false
            ),
            "fill" => array(
                "gradient" => array(
                    "enabled" => true,
                    "opacityFrom" => 0.55,
                    "opacityTo" => 0
                )
            ),
            "markers" => array(
                "size" => 4,
                "colors" => ["#000524"],
                "strokeColor" => "#00BAEC",
                "strokeWidth" => 2
            ),
            "series" => $series,
            "tooltip" => array(
                "theme" => "dark",
                "x" => array(
                    "formatter" => "function"
                ),
                "y" => array(
                    "formatter" => "function"
                )
            ),
            "xaxis" => array(
                "categories" => $labels,
            ),
            "yaxis" => array(
                "min" => 0,
                "tickAmount" => 4,
                "labels" => array(
                    "formatter" => "function"
                )
            ),
            "extra" => array(
                "tooltip" => array(
                    "title" => $titles,
                    "decimals" => 0,
                ),
                "unit"     => $unit,
                "decimals" => $decimals,
                "custom"   => $extra,
                "footer"   => $footer,
                "dates"    => $dates,
                "units"    => $units
            ),
        );

        return $config;
    }

    private function chartFooter($data, $colorize = false)
    {
        $html = '<div class="card-footer total text-right d-none d-sm-block" data-loading-overlay="" data-loading-overlay-options="{ \"css\": { \"backgroundColor\": \"#00000080\" } }" style="">
                    <div class="row">';

        foreach ($data as $d) {

            $html .= '<div class="col text-center">
                        <div class="row">
                            <div class="col-6 col-lg-12">
                                <p class="text-3 mb-0" style="color: '.$d[2].';">'.$d[0].'</p>
                            </div>
                            <div class="col-6 col-lg-12">
                                <p class="text-3 mb-0" style="color: '.$d[2].';">'.$d[1].'</p>
                            </div>
                        </div>
                    </div>';
        }

        $html .= '</div></div>';

        return $html;
    }

    public function chart($device, $start, $end, $field, $interval = null)
    {
        $divisor  = 1;
        $decimals = 0;
        $unidade  = "";
        $type     = "line";

        $period   = $this->water_model->GetConsumption($device, $start, $end, array(), true, 'h');

        $period_o = $this->water_model->GetConsumption($device, $start, $end, array("opened", $this->user->config->open, $this->user->config->close), false, 'h')[0]->value;
        $period_c = $this->water_model->GetConsumption($device, $start, $end, array("closed", $this->user->config->open, $this->user->config->close), false, 'h')[0]->value;
        $main     = $this->water_model->GetDeviceLastRead($device);
        $month_o  = $this->water_model->GetConsumption($device, date("Y-m-01"), date("Y-m-d"), array("opened", $this->user->config->open, $this->user->config->close), false)[0]->value;
        $month_c  = $this->water_model->GetConsumption($device, date("Y-m-01"), date("Y-m-d"), array("closed", $this->user->config->open, $this->user->config->close), false)[0]->value;

        $day_o  = $this->water_model->GetConsumption($device, date("Y-m-d", strtotime("-1 months")), date("Y-m-d"), array("opened", $this->user->config->open, $this->user->config->close), false)[0]->value;
        $day_c  = $this->water_model->GetConsumption($device, date("Y-m-d", strtotime("-1 months")), date("Y-m-d"), array("closed", $this->user->config->open, $this->user->config->close), false)[0]->value;

        $values  = array();
        $labels  = array();
        $titles  = array();
        $dates   = array();

        $max = -1;
        $min = 999999999;

        $series = array();

        if ($period) {
            foreach ($period as $v) {
                $values[] = $v->value;
                $labels[] = $v->label;

                if ($start == $end || $interval === 'h') {
                    $titles[] = $v->label." - ".$v->next;
                } else {
                    $titles[] = $v->label." - ".weekDayName($v->dw);
                    $dates[]  = $v->date;
                }
                if ($max < floatval($v->value) && !is_null($v->value)) $max = floatval($v->value);
                if ($min > floatval($v->value) && !is_null($v->value)) $min = floatval($v->value);
            }

            $series[] = array(
                "name" => "Consumo",
                "data" => $values,
                "color" => "#007AB8",
            );
        }

        $dias   = ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-01"))) / 86400) + 1;
        $dias_t = date('d', mktime(0, 0, 0, date("m") + 1, 0, date("Y")));
        $dias_m = (strtotime(date("Y-m-d")) - strtotime("-1 months")) / 86400;

        $extra = array(
            "main"        => ($main ? str_pad(round($main), 6 , '0' , STR_PAD_LEFT) : "- - - - - -"). " <span style='font-size:12px;'>L</span>",
            "period"      => number_format(round($period_o + $period_c, $decimals), $decimals, ",", ".") . " <small>L</small>",
            "period_o"    => number_format(round($period_o, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "period_c"    => number_format(round($period_c, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "month"       => number_format(round($month_o + $month_c, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "month_o"     => number_format(round($month_o, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "month_c"     => number_format(round($month_c, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "prevision"   => number_format(round(($month_o + $month_c) / $dias * $dias_t, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "prevision_o" => number_format(round($month_o / $dias * $dias_t, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "prevision_c" => number_format(round($month_c / $dias * $dias_t, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "day"         => number_format(round(($day_o + $day_c) / $dias_m, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "day_o"       => number_format(round($day_o / $dias_m, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
            "day_c"       => number_format(round($day_c / $dias_m, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>",
        );

        $data = array(
            array("Total", ($period == -1) ? "-" : number_format(round($period_o + $period_c, $decimals), $decimals, ",", ".") . " <span style='font-size:12px;'>L</span>", "#268ec3"),
            array("Máximo", ($max == -1) ? "-" : number_format(round($max), 0, ",", ".") . " <span style='font-size:12px;'>L</span>", "#268ec3"),
            array("Mínimo", ($min == 999999999) ? "-" : number_format(round($min), 0, ",", ".") . " <span style='font-size:12px;'>L</span>", "#268ec3"),
            array("Médio",  ($min == 999999999) ? "-" : number_format(round(($period_o + $period_c) / count($period)), 0, ",", ".") . " <span style='font-size:12px;'>L</span>", "#268ec3"),
        );

        $units = $this->consumptionUnits($start, $end);

        $footer = $this->chartFooter($data);

        $config = $this->chartConfig("area", true, $series, $titles, $labels, "L", 0, $extra, $footer, $dates, $units);

        return $config;
    }

    public function consumptionUnits($start, $end)
    {
        $unidades = $this->sse_model->get_unidades(113, 'agua');
        $response = array();

        foreach ($unidades as $unidade) {
            $units[$unidade->unidade_id] = $this->sse_model->getTotal($unidade->medidor_id, $start, $end);

            $response['total'][$unidade->unidade_id] = 0;
            $response['last'][$unidade->unidade_id] = array();
            $response['status'][$unidade->unidade_id] = array();

            if ($units[$unidade->unidade_id]) {
                foreach ($units[$unidade->unidade_id] as $i => $hour) {
                    if (array_key_last($units[$unidade->unidade_id]) == $i) {
                        $response['last'][$unidade->unidade_id] = intval($hour->consumo);
                    }

                    $response['total'][$unidade->unidade_id] += $hour->consumo;

                    $response['status'][$unidade->unidade_id] = format_online_status($unidade->ultimo_envio);
                }
            }
        }

        return $response;
    }

}