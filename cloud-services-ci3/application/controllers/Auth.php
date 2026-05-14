<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    private function dashboard_route_for($user)
    {
        return ($user['role'] === 'admin') ? 'admin' : 'client';
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            $current = $this->current_user();
            redirect($this->dashboard_route_for($current));
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
                redirect($this->dashboard_route_for($user));
                return;
            }
            $error = 'Invalid email or password.';
        }

        $data = array('title' => 'CloudPanel - Login', 'error' => $error);
        $this->load->view('public_site/header', $data);
        $this->load->view('public_site/auth_login', $data);
        $this->load->view('public_site/closing');
    }

    public function signup()
    {
        if ($this->session->userdata('user_id')) {
            $current = $this->current_user();
            redirect($this->dashboard_route_for($current));
            return;
        }

        $error = '';
        $success = false;
        if ($this->input->method(TRUE) === 'POST') {
            $first_name = trim((string) $this->input->post('first_name', true));
            $last_name = trim((string) $this->input->post('last_name', true));
            $name = trim($first_name.' '.$last_name);
            if ($name === '') {
                $name = trim((string) $this->input->post('full_name', true));
            }
            $email = trim((string) $this->input->post('email', true));
            $password = (string) $this->input->post('password', true);
            $confirm_password = (string) $this->input->post('confirm_password', true);

            if ($name === '' || $email === '' || strlen($password) < 8) {
                $error = 'Please provide valid input. Password must be at least 8 characters.';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match.';
            } elseif ($this->User_model->find_by_email($email)) {
                $error = 'Email already exists.';
            } else {
                $this->User_model->create(array(
                    'full_name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'client'
                ));
                $success = true;
            }
        }

        $data = array('title' => 'CloudPanel - Register', 'error' => $error, 'success' => $success);
        $this->load->view('public_site/header', $data);
        $this->load->view('public_site/auth_signup', $data);
        $this->load->view('public_site/closing');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
