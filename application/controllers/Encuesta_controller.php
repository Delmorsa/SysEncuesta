<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encuesta_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Login_model");
        $this->load->model("encuesta_model");
		$this->load->library("session");
		/*if ($this->session->userdata("logged") == 1) {
			redirect(base_url() . 'index.php/Demo', 'refresh');
		}*/
	}

	public function index()
	{
		
	}

    public function tusencuestas()
    {
        $data["encuestas"] = $this->encuesta_model->getEncuestas();
        //echo json_encode($data);
        $this->load->view("header/header");
        $this->load->view("header/menu");
        $this->load->view('/encuesta/tusencuestas',$data);
        $this->load->view("footer/footer");
    }
	

	public function resolverencuesta($idencuesta)
    {
        
        $data["encabezado"] = $this->encuesta_model->getEncuestaID($idencuesta);
        $data["areas"] = $this->encuesta_model->getAreas();
        $data["preguntas"] = $this->encuesta_model->getPreguntaAResolver($idencuesta);
        $data["respuestas"] = $this->encuesta_model->getRespuestasAResolver($idencuesta);
        
        //echo json_encode($data["respuestas"]);
        $this->load->view("header/header");
        $this->load->view("header/menu");
        $this->load->view('/encuesta/resolverencuesta',$data);
        $this->load->view("footer/footer");
    }

    public function guardarEncuesta()
    {
        $this->encuesta_model->guardarEncuesta(
            $this->input->post("enc"),
            $this->input->post("datos")
        );
    }

    public function nuevaencuesta()
    {
        $data["areas"] = $this->encuesta_model->getAreas();
        $data["tiposPreg"] = $this->encuesta_model->getTiposPreguntas();
        //echo json_encode($data["tiposPreg"]);
        $this->load->view("header/header");
        $this->load->view("header/menu");
        $this->load->view('/encuesta/crearencuesta',$data);
        $this->load->view("footer/footer");
    }

    public function guardarEncuestaNueva()
    {
        $this->encuesta_model->guardarEncuestaNueva(
            $this->input->post("enc"),
            $this->input->post("datos")
        );
    }
}
