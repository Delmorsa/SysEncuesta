<?php

/**
 * @Author: Cesar Mejía
 * @Date:   2020-01-16 12:31:13
 * @Last Modified by:   Sistemas
 * @Last Modified time: 2020-01-16 08:51:06
 */
class Informes_Encuestas_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library("session");
    $this->load->model("Informes_Encuestas_model");
		/*if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}*/
	}

	public function index()
	{
    $data["encuestas"] = $this->Informes_Encuestas_model->getEncuestas();
		$data["areas"] = $this->Informes_Encuestas_model->getAreas();
		$this->load->view("header/header");
		$this->load->view("header/menu");
		$this->load->view("encuesta/informes",$data);
		$this->load->view("footer/footer");
		$this->load->view("jsview/jsinformes");
	}

	public function cantUsersEncuesta($idPregunta,$idArea){
		  $this->Informes_Encuestas_model->cantUsersEncuesta($idPregunta,$idArea);
	}

  public function resultadosEncuesta($idPregunta,$idArea){
    $this->Informes_Encuestas_model->resultadosEncuesta($idPregunta,$idArea);
  }

  public function getPregPorEnc($idEncuesta){
    $this->Informes_Encuestas_model->getPregPorEnc($idEncuesta);
  }

	public function respPorAreas($idPregunta){
		$this->Informes_Encuestas_model->respPorAreas($idPregunta);
	}

	public function detalleEncuestasAreas($idPregunta){
		$this->Informes_Encuestas_model->detalleEncuestasAreas($idPregunta);
	}
}
