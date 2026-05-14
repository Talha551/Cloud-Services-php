<?php
/**
 * ============================================================================
 * Health Check Endpoint
 * ============================================================================
 * 
 * PURPOSE: System status monitoring, liveness probe for load balancers/docker
 * STATUS: 100% Complete
 * ENDPOINT: GET /api/health
 * AUTHENTICATION: None required (public endpoint)
 * 
 * RESPONSE FORMAT:
 *   {
 *     "success": true,
 *     "message": "CodeIgniter monolith is running",
 *     "php_version": "8.2.12",
 *     "timestamp": "2026-05-12T10:42:34+02:00"
 *   }
 * 
 * USE CASES:
 *   - Docker container health checks
 *   - Kubernetes liveness probes
 *   - Load balancer status verification
 *   - Monitoring/alerting systems
 *   - Manual system status checks
 * 
 * FUTURE ENHANCEMENTS:
 *   - Check database connectivity
 *   - Verify JWT service availability
 *   - Check disk space
 *   - Return API version info
 *   - Include build commit hash
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Health extends MY_Controller
{
    public function index()
    {
        $this->json(array(
            'success' => true,
            'message' => 'CodeIgniter monolith is running',
            'php_version' => PHP_VERSION,
            'timestamp' => date('c')
        ), 200);
    }
}
