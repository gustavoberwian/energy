<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UNO_Controller extends CI_Controller
{
    protected $user = false;

    public function __construct()
    {
        parent::__construct();

        if ($this->ion_auth->logged_in() === TRUE) {

            $this->user           = $this->ion_auth->user()->row();
            $this->user->nickname = explode(' ', trim($this->user->nome))[0];
            $this->user->alerts   = 0;

            date_default_timezone_set('America/Sao_Paulo');
        }
    }

    protected function render($view, $data = NULL)
    {
        $data['class']  = $this->router->fetch_class();
        $data['method'] = $this->router->fetch_method();
        $data['user']   = $this->user;

        $this->load->view('site/template/header', $data);
        $this->load->view('site/template/menu', $data);
        $this->load->view('site/' . $view, $data);
        $this->load->view('site/template/footer', $data);
    }
}

class Painel_Controller extends CI_Controller
{
    protected $user = false;

    public function __construct()
    {
        parent::__construct();

        if ($this->ion_auth->logged_in() === TRUE) {

            $this->user           = $this->ion_auth->user()->row();
            $this->user->nickname = explode(' ', trim($this->user->nome))[0];
            $this->user->alerts   = 0;

            date_default_timezone_set('America/Sao_Paulo');
        
        } else {

            redirect("auth/login");
        }
    }

    protected function render($view, $data = NULL)
    {
        $data['class']  = $this->router->fetch_class();
        $data['method'] = $this->router->fetch_method();
        $data['user']   = $this->user;

        $this->load->view('painel/template/header', $data);
        $this->load->view('painel/template/menu', $data);
        $this->load->view('painel/' . $view, $data);
        $this->load->view('painel/template/footer', $data);
    }
}

class Shopping_Controller extends UNO_Controller
{
    protected $user;

    public function __construct()
    {

        parent::__construct();

        // carrega models
        $this->load->model('energy_model');
        $this->load->model('shopping_model');

        // verifica se usuário está logado
        if ($this->ion_auth->logged_in() === TRUE) {

            $this->user = $this->ion_auth->user()->row();
            $this->user->nickname = explode(' ', trim($this->user->nome))[0];
            $this->user->alerts   = $this->energy_model->CountAlerts($this->user->id);
            $this->user->groups   = $this->ion_auth->get_users_groups($this->user->id)->result()[1];
            $this->user->entity   = $this->shopping_model->get_user_entity($this->user->id);
            $this->user->group    = $this->shopping_model->get_user_group($this->user->id);
            // TODO: select client num auto
            $this->user->config   = $this->energy_model->GetClientConfig($this->user->group);

            date_default_timezone_set('America/Sao_Paulo');

        } else {
            redirect("auth/login");
        }
    }

    protected function render($view, $data = NULL)
    {
        $data['class']        = $this->router->fetch_class();
        $data['method']       = $this->router->fetch_method();
        $data['user']         = $this->user;

        $data['logs']   = $this->db->get('esm_user_logs')->num_rows();

        $this->load->view('shopping/template/header', $data);
        $this->load->view('shopping/template/menu', $data);
        $this->load->view('shopping/' . $view, $data);
        $this->load->view('shopping/template/footer', $data);
    }

    protected function checkPermission($groupToCheck, $firstId = null, $secondId = null): bool
    {
        if ($this->user->group != $groupToCheck) {
            $data['group_id'] = $this->user->group;
            $this->render("403", $data);

            return false;
        }

        if (!is_null($firstId)) {
            if ($this->uri->segment(2) == 'fechamento') {
                $q = "SELECT id FROM esm_fechamentos_energia WHERE group_id = $groupToCheck AND id = $firstId";

                if (!$this->db->query($q)->num_rows()) {
                    $data['group_id'] = $this->user->group;
                    $this->render("403", $data);

                    return false;
                }
            }

            if ($this->uri->segment(2) == 'relatorio') {
                $q = "SELECT esm_fechamentos_energia_entradas.id
                    FROM esm_fechamentos_energia
                    JOIN esm_fechamentos_energia_entradas ON esm_fechamentos_energia_entradas.fechamento_id = esm_fechamentos_energia.id
                    WHERE esm_fechamentos_energia.group_id = $groupToCheck 
                        AND esm_fechamentos_energia.id = $firstId 
                        AND esm_fechamentos_energia_entradas.id = $secondId";

                if (!$this->db->query($q)->num_rows()) {
                    $data['group_id'] = $this->user->group;
                    $this->render("403", $data);

                    return false;
                }
            }

            if ($this->uri->segment(2) == 'unidade') {
                $q = "SELECT id FROM esm_unidades WHERE esm_unidades.bloco_id = $groupToCheck AND id = $firstId";

                if (!$this->db->query($q)->num_rows()) {
                    $data['group_id'] = $this->user->group;
                    $this->render("403", $data);

                    return false;
                }
            }
        }

        return true;
    }

    protected function setHistory($msg, $type)
    {
        $data = array(
            'user_id' => $this->user->id,
            'mensagem' => $msg,
            'tipo' => $type,
            'lido' => 0
        );

        $this->db->insert('esm_user_logs', $data);
    }
}

class Api_Controller extends UNO_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function render($view, $data = NULL)
    {
        $data['class']        = $this->router->fetch_class();
        $data['method']       = $this->router->fetch_method();

        $this->load->view('api/template/header', $data);
        $this->load->view('api/doc', $data);
        $this->load->view('api/template/footer', $data);
    }
}

class SSE_Controller extends UNO_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('energy_model');
        $this->load->model('shopping_model');

        if ($this->ion_auth->logged_in() !== TRUE) {
            redirect("auth/login");
        }

        $this->user->group    = $this->shopping_model->get_user_group($this->user->id);
        $this->user->config   = $this->energy_model->GetClientConfig($this->user->group);
    }

    protected function render($view, $data = NULL)
    {
        $data['class']        = $this->router->fetch_class();
        $data['method']       = $this->router->fetch_method();
        $data['user']         = $this->user;

        $data['logs']   = $this->db->get('esm_user_logs')->num_rows();

        $this->load->view('sse/template/header', $data);
        $this->load->view('sse/' . $view, $data);
        $this->load->view('sse/template/footer', $data);
    }
}