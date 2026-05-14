<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $error = '';
        if ($this->input->method(TRUE) === 'POST') {
            $email = (string) $this->input->post('email', true);
            $password = (string) $this->input->post('password', true);

            $user = $this->User_model->find_by_email($email);
            if ($user && $this->User_model->verify_password($user, $password)) {
                $this->session->set_userdata(array(
                    'user_id' => (int) $user['id'],
                    'user_name' => $user['full_name'],
                    'user_role' => $user['role']
                ));
                redirect('dashboard');
                return;
            }
            $error = 'Invalid email or password.';
        }

        $data = array('title' => 'Login', 'error' => $error);
        $this->load->view('layouts/header', $data);
        $this->load->view('auth/login', $data);
        $this->load->view('layouts/footer');
    }

    public function signup()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $error = '';
        if ($this->input->method(TRUE) === 'POST') {
            $name = trim((string) $this->input->post('full_name', true));
            $email = trim((string) $this->input->post('email', true));
            $password = (string) $this->input->post('password', true);

            if ($name === '' || $email === '' || strlen($password) < 6) {
                $error = 'Please provide valid input. Password must be at least 6 characters.';
            } elseif ($this->User_model->find_by_email($email)) {
                $error = 'Email already exists.';
            } else {
                $user = $this->User_model->create(array(
                    'full_name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'client'
                ));

                $this->session->set_userdata(array(
                    'user_id' => (int) $user['id'],
                    'user_name' => $user['full_name'],
                    'user_role' => $user['role']
                ));
                redirect('dashboard');
                return;
            }
        }

        $data = array('title' => 'Signup', 'error' => $error);
        $this->load->view('layouts/header', $data);
        $this->load->view('auth/signup', $data);
        $this->load->view('layouts/footer');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
