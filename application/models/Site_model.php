<?php  if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Site_model extends CI_Model {


	public function insert_contact($data)
	{
		if (!$this->db->insert('esm_contatos', $data)) {

            echo json_encode(array("status"  => "error", "message" => $this->db->error()['message']));

		} else {
			echo json_encode(array("status"  => "success"));
		}
	}

	public function insert_ticket($data)
	{
		if (!$this->db->insert('esm_tickets', $data)) {

            echo json_encode(array("status"  => "error", "message" => $this->db->error()['message']));

		} else {
			echo json_encode(array("status"  => "success"));
		}
	}

    public function inclui_post($data)
    {
        $this->db->insert('post', array('text' => $data));
    }

    public function get_last_post()
    {
        $query = $this->db->select("*")->limit(1)->order_by('id','DESC')->get('post');
        
        return $query->row();
    }

    public function inclui_teste()
    {
        $this->db->insert('esm_log', array('mensagem' => 'teste via cron'));
    }

}