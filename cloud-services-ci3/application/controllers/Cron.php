<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
    private function is_authorized()
    {
        if (is_cli()) {
            return true;
        }

        $token = (string) $this->input->get('token', true);
        $expected = getenv('AUTOMATION_TOKEN');
        if ($expected === false || trim((string) $expected) === '') {
            return false;
        }

        return hash_equals((string) $expected, $token);
    }

    public function billing_daily()
    {
        if (!$this->is_authorized()) {
            $this->output
                ->set_status_header(403)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('ok' => false, 'message' => 'Forbidden')));
            return;
        }

        $cycle_days = (int) $this->input->get('cycle_days', true);
        $grace_days = (int) $this->input->get('grace_days', true);
        if ($cycle_days <= 0) {
            $cycle_days = 30;
        }
        if ($grace_days < 0) {
            $grace_days = 3;
        }

        $this->load->model('Service_model');
        $result = $this->Service_model->run_billing_automation($cycle_days, $grace_days);
        $this->Service_model->add_audit_log(NULL, 'automation.billing_daily', $result, (string) $this->input->ip_address());

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(array('ok' => true, 'result' => $result)));
    }
}
