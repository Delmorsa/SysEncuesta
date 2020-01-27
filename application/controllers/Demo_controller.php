<?php

/**
 * @Author: Cesar Mejía
 * @Date:   2019-11-23 09:36:13
 * @Last Modified by:   Sistemas
 * @Last Modified time: 2019-11-25 08:51:06
 */
class Demo_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library("session");
		/*if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}*/
	}

	public function index()
	{
		$this->load->view("header/header");
		$this->load->view("header/menu");
		$this->load->view("Demo/Demo");
		$this->load->view("footer/footer");
		$this->load->view("jsview/jsdemo");
	}

	public function Demo1()
	{
		$this->load->view("header/header");
		$this->load->view("header/menu");
		$this->load->view("Demo/Demo1");
		$this->load->view("footer/footer");
		$this->load->view("jsview/jsdemo");
	}
}
