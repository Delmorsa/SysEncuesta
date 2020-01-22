<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Login_model");
		$this->load->library("session");
		/*if ($this->session->userdata("logged") == 1) {
			redirect(base_url() . 'index.php/Demo', 'refresh');
		}*/
	}

	public function index()
	{
		$this->load->view('header/header_login');
		$this->load->view('Login');
	}

	public function Acreditar()
    {
        $this->form_validation->set_rules('username', 'nombre', 'required');
        $this->form_validation->set_rules('pwd', 'pass', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('?error=1');
        }
        else {
            $name = $this->input->get_post('username');
            $pass = md5($this->input->get_post('pwd'));
            $data['user'] = $this->Login_model->login($name, $pass);

            if ($data['user'] == 0) {
               redirect('?error=2');
            }
            else {
                $sessiondata = array(
                    'id' => $data['user'][0]['IDUSUARIO'],
										'usuario' => $data['user'][0]['USUARIO'],
										'nombre' => $data['user'][0]['NOMBRE'],
										'apellido' => $data['user'][0]['APELLIDO'],
                    'sexo' => $data['user'][0]['SEXO'],
										'estado' => $data["user"][0]["ESTADO"],
										'telefono' => $data["user"][0]["TELEFONO"],
										'correo' => $data["user"][0]["CORREO"],
                    'logged' => 1
                );
                $this->session->set_userdata($sessiondata);

                if ($this->session->userdata) {
                   redirect('tusencuestas'); //por el momento
                }
            }
        }
	}

	public function Salir()
    {
        $this->session->sess_destroy();
        $sessiondata = array('logged' => 0);
        redirect(base_url() . 'index.php', 'refresh');
	}
}
