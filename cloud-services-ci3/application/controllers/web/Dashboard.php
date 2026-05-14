<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        if (!$this->require_login_web()) {
            return;
        }

        $user = $this->current_user();
        $services = $this->Service_model->list_for_user($user);

        $data = array(
            'title' => 'Dashboard',
            'user' => $user,
            'services' => $services
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layouts/footer');
    }

    public function services()
    {
        if (!$this->require_login_web()) {
            return;
        }

        $user = $this->current_user();
        $services = $this->Service_model->list_for_user($user);

        $data = array(
            'title' => 'My Services',
            'user' => $user,
            'services' => $services
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/services', $data);
        $this->load->view('layouts/footer');
    }
}
