<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Painel extends Painel_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('painel_model');
    }

	public function index()
	{
        $cid = 26;
        $data['medidores'] = $this->painel_model->get_medidores_geral($cid);

        $data['pocos'] = $this->painel_model->get_medidores_geral($cid, "nivel", true);

        for($i = 0; $i < count($data['pocos']); $i++) {
            $m = $this->painel_model->get_last_nivel($data['pocos'][$i]['id']);

            $data['pocos'][$i]['dinamico'] = number_format($m->profundidade_total - round(($m->leitura - 1162) * $m->mca / 4649), 0, ",", ".");
            $data['pocos'][$i]['minimo']   = number_format($m->profundidade_total - round(($m->minimo - 1162) * $m->mca / 4649), 0, ",", ".");
            $data['pocos'][$i]['estatico'] = number_format($m->profundidade_total - round(($m->estatico - 1162) * $m->mca / 4649), 0, ",", ".");
            $data['pocos'][$i]['tank']     = ($m->leitura - 1162) / 4649 * 100;
        }

        $consumo = $this->painel_model->get_consumo_medidores_geral($cid, date("Y-m-01"), date("Y-m-t"));

        $data['mes']      = number_format(round($consumo->value), 0, ",", ".")." <span style='font-size:10px;'>m³</span>";
        $data['previsao'] = number_format(round($consumo->value / ceil(abs(time() - strtotime(date("Y-m-01"))) / 86400) * date("t")), 0, ",", ".")." <span style='font-size:10px;'>m³</span>";
		
        $this->render('home', $data);
	}

	public function reports($rid = 0)
	{
        if ($rid) {

            $data["id"]     = $rid;
            $data["report"] = $this->painel_model->get_report_by_id($rid);
            $data["data"]   = $this->painel_model->get_report_data_by_id($rid);

            if ($data["report"]->tipo == 1) {
                $data["competencia"] = competencia_nice($data['report']->competencia);
            } else if ($data["report"]->tipo == 2) {
                $data["competencia"] = $data['report']->competencia;
            }

            $this->render('report', $data);

        } else {

		    $this->render('reports');
        }
	}

	public function alerts()
	{
		$this->render('alerts');
	}
}
