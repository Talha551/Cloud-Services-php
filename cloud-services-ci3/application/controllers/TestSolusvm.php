<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestSolusvm extends CI_Controller {

    public function index() {
        $this->load->library('Solusvm_client');

        $results = [
            'list_servers' => $this->solusvm_client->list_servers(),
            'list_applications' => $this->solusvm_client->list_applications(),
            'list_os_images' => $this->solusvm_client->list_os_images(),
            'list_plans' => $this->solusvm_client->list_plans(),
            'list_locations' => $this->solusvm_client->list_locations(),
        ];

        file_put_contents(FCPATH . 'test_solusvm_results.json', json_encode($results, JSON_PRETTY_PRINT));

        echo "Endpoint tests completed. Results saved to test_solusvm_results.json\n";
    }

    public function server_debug($server_id = 0)
    {
        $this->load->library('Solusvm_client');
        $sid = (int) $server_id;
        if ($sid <= 0) { $sid = (int) $this->input->get('server_id', true); }
        if ($sid <= 0) { $sid = 18; }
        $result = $this->solusvm_client->get_server($sid);
        $this->output->set_content_type('application/json')->set_output(json_encode($result, JSON_PRETTY_PRINT));
    }

    public function vnc_test($server_id = 0)
    {
        $this->load->library('Solusvm_client');
        $sid = (int) $server_id;
        if ($sid <= 0) {
            $sid = (int) $this->input->get('server_id', true);
        }
        if ($sid <= 0) {
            // Auto-pick first server
            $servers = $this->solusvm_client->list_servers();
            if (!empty($servers['data']['data'][0]['id'])) {
                $sid = (int) $servers['data']['data'][0]['id'];
            }
        }
        $result = $this->solusvm_client->vnc_up($sid);
        $payload = array('server_id' => $sid, 'tested_at' => date('c'), 'result' => $result);
        file_put_contents(FCPATH . 'test_solusvm_vnc_result.json', json_encode($payload, JSON_PRETTY_PRINT));
        $this->output
            ->set_status_header($result['ok'] ? 200 : 500)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }

    public function reset_password($server_id = 0)
    {
        $this->load->library('Solusvm_client');

        $sid = (int) $server_id;
        if ($sid <= 0) {
            $sid = (int) $this->input->get('server_id', true);
        }

        $password = trim((string) $this->input->post('password', true));
        if ($password === '') {
            $password = trim((string) $this->input->get('password', true));
        }

        if ($sid <= 0 || $password === '') {
            $this->output
                ->set_status_header(422)
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'ok' => false,
                    'message' => 'Provide server_id and password. Example: /index.php/TestSolusvm/reset_password?server_id=18&password=YourPass123'
                )));
            return;
        }

        $result = $this->solusvm_client->change_root_password($sid, $password);
        $payload = array(
            'server_id' => $sid,
            'tested_at' => date('c'),
            'result' => $result,
        );

        file_put_contents(FCPATH . 'test_solusvm_reset_password_result.json', json_encode($payload, JSON_PRETTY_PRINT));

        $status = !empty($result['ok']) ? 200 : 500;
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
}