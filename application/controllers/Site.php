<?php
defined('BASEPATH') or exit('No direct script access allowed');

use mult1mate\crontab\TaskRunner;

class Site extends UNO_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('site_model');
    }

    public function index()
    {
        $this->render('home2');
    }

    public function novo()
    {
        $this->render('home2');
    }

    public function solucoes()
    {
        $this->render('solucoes');
    }

    public function tecnologia()
    {
        $this->render('tecnologia');
    }

    public function sobre()
    {
        $this->render('sobre');
    }

    public function suporte()
    {
        $this->render('suporte');
    }

    public function downloads()
    {
        $this->render('downloads');
    }

    public function faq()
    {
        $this->render('faq');
    }

    public function chamados()
    {
        $this->render('chamados');
    }

    public function equipe()
    {
        $this->render('equipe');
    }

    public function trabalhe()
    {
        $this->render('trabalhe');
    }

    public function imprensa()
    {
        $this->render('imprensa');
    }

    public function privacidade()
    {
        $this->render('privacidade');
    }

    public function login($action = NULL)
    {
        if ($action == 'process') {

            redirect('admin', 'refresh');
        } else if ($action == 'recover') {

            $this->load->view('site/recover');
        } else if ($action == 'logout') {

            redirect('/', 'refresh');
        } else {

            $this->load->view('site/login');
        }
    }

    public function _remap($method, $params = array())
    {
        // Check if the requested route exists
        if (method_exists($this, $method)) {
            // Method exists - so just continue as normal
            return call_user_func_array(array($this, $method), $params);
        }

        // Set status header to a 404 for SEO
        $this->output->set_status_header('404');

        $this->render('404');
    }

    public function email()
    {
        $nome =  $this->input->post('nome');
        $email =  $this->input->post('email');
        $assunto =  $this->input->post('assunto');
        $mensagem =  $this->input->post('mensagem');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'email-ssl.com.br';
        $config['smtp_port'] = '587';
        $config['smtp_timeout'] = '60';
        $config['smtp_user'] = 'contato@easymeter.com.br';
        $config['smtp_pass'] = 'index#1996';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = "html";
        $config['smtp_crypto'] = 'tls';

        $this->load->library('email');
        $this->email->initialize($config);

        $this->email->from('contato@easymeter.com.br');
        $this->email->to('contato@easymeter.com.br');
        $this->email->reply_to('contato@easymeter.com.br'); //email de resposta
        $this->email->subject('Easymeter Website Contato: ' . $nome . ', ' . $email . ' - ' . $assunto);
        $this->email->message($mensagem);

        echo $this->email->send();

        echo $this->email->print_debugger();
    }

    public function cron()
    {
        $this->load->model('DbBaseModel');
        $this->load->model('Task', 'task');

        TaskRunner::checkAndRunTasks($this->task->getAll());
    }
}
