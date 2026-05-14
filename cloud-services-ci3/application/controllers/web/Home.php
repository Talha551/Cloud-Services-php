<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function index()
    {
        $data = array('title' => 'Cloud Services - CodeIgniter Monolith');
        $this->load->view('layouts/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('layouts/footer');
    }
}
