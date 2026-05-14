<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function index()
    {
        $data = array('title' => 'CloudPanel - Cloud Servers Built for Speed');
        $this->load->view('public_site/header', $data);
        $this->load->view('public_site/home', $data);
        $this->load->view('public_site/closing');
    }

    public function pricing()
    {
        $data = array(
            'title' => 'CloudPanel - Pricing',
            'plans' => array(
                array('name' => 'Starter', 'price' => '$5', 'period' => '/mo', 'desc' => 'Perfect for personal projects and small websites.', 'highlight' => false, 'features' => array('1 vCPU', '1 GB RAM', '25 GB NVMe SSD', '1 TB Bandwidth', '1 IPv4 Address', 'Automated Backups', 'VNC Console', '24/7 Support')),
                array('name' => 'Pro', 'price' => '$12', 'period' => '/mo', 'desc' => 'Ideal for growing applications and small businesses.', 'highlight' => true, 'badge' => 'Most Popular', 'features' => array('2 vCPU', '4 GB RAM', '80 GB NVMe SSD', '3 TB Bandwidth', '1 IPv4 + IPv6', 'Automated Backups', 'VNC Console', 'Priority Support')),
                array('name' => 'Business', 'price' => '$24', 'period' => '/mo', 'desc' => 'For production workloads that demand performance.', 'highlight' => false, 'features' => array('4 vCPU', '8 GB RAM', '160 GB NVMe SSD', '6 TB Bandwidth', '2 IPv4 + IPv6', 'Daily Snapshots', 'VNC Console', 'Priority Support')),
                array('name' => 'Enterprise', 'price' => '$48', 'period' => '/mo', 'desc' => 'Maximum power for resource-intensive applications.', 'highlight' => false, 'features' => array('8 vCPU', '16 GB RAM', '320 GB NVMe SSD', 'Unlimited BW', '4 IPv4 + IPv6', 'Hourly Snapshots', 'Dedicated vCPU', 'Dedicated Support')),
            )
        );

        $this->load->view('public_site/header', $data);
        $this->load->view('public_site/pricing', $data);
        $this->load->view('public_site/closing');
    }

    public function features()
    {
        $data = array(
            'title' => 'CloudPanel - Features',
            'features' => array(
                array('icon' => 'zap', 'color' => 'bg-indigo-500/10 text-indigo-400', 'title' => 'Instant VM Deployment', 'desc' => 'KVM-based virtual machines deploy in under 60 seconds. Choose your OS, plan, and location - server is live instantly.'),
                array('icon' => 'monitor', 'color' => 'bg-blue-500/10 text-blue-400', 'title' => 'VNC Console Access', 'desc' => 'Browser-based VNC console for direct server access even when SSH is unavailable. Emergency access at any time.'),
                array('icon' => 'shield', 'color' => 'bg-green-500/10 text-green-400', 'title' => 'Automated Backups', 'desc' => 'Scheduled daily backups with one-click restore. Keep multiple restore points and recover from any incident instantly.'),
                array('icon' => 'refresh-cw', 'color' => 'bg-purple-500/10 text-purple-400', 'title' => 'Snapshots', 'desc' => 'Take point-in-time snapshots before major changes. Roll back instantly if anything goes wrong.'),
                array('icon' => 'activity', 'color' => 'bg-yellow-500/10 text-yellow-400', 'title' => 'Real-Time Metrics', 'desc' => 'Live CPU, RAM, disk I/O, and network graphs. Historical usage data to plan capacity and optimize performance.'),
                array('icon' => 'network', 'color' => 'bg-pink-500/10 text-pink-400', 'title' => 'VPC & Private Networking', 'desc' => 'Create private VPC networks to connect your servers securely without exposing traffic to the public internet.'),
                array('icon' => 'globe', 'color' => 'bg-sky-500/10 text-sky-400', 'title' => 'IPv4 & IPv6', 'desc' => 'Every server gets a dedicated IPv4 address. Native IPv6 support with /64 block available on all plans.'),
                array('icon' => 'hard-drive', 'color' => 'bg-orange-500/10 text-orange-400', 'title' => 'NVMe SSD Storage', 'desc' => 'All servers run on enterprise NVMe SSDs for maximum read/write performance. Expandable volumes with no downtime.'),
                array('icon' => 'lock', 'color' => 'bg-indigo-500/10 text-indigo-400', 'title' => 'SSH Key Management', 'desc' => 'Manage SSH keys from the dashboard. Inject keys at deployment or add them to running servers.'),
                array('icon' => 'cpu', 'color' => 'bg-blue-500/10 text-blue-400', 'title' => 'Dedicated vCPU Plans', 'desc' => 'Business and Enterprise plans feature dedicated vCPUs with no noisy-neighbor contention. Consistent performance guaranteed.'),
                array('icon' => 'bar-chart-2', 'color' => 'bg-green-500/10 text-green-400', 'title' => 'Bandwidth Monitoring', 'desc' => 'Track inbound and outbound traffic in real time. Get alerts before hitting bandwidth limits.'),
                array('icon' => 'key', 'color' => 'bg-purple-500/10 text-purple-400', 'title' => 'API Access', 'desc' => 'Full REST API access to manage everything programmatically. Automate deployments, scaling, and monitoring.'),
                array('icon' => 'database', 'color' => 'bg-yellow-500/10 text-yellow-400', 'title' => 'Flexible OS Images', 'desc' => 'Choose from dozens of pre-built OS images: Ubuntu, Debian, CentOS, AlmaLinux, Windows, and custom templates.'),
                array('icon' => 'server', 'color' => 'bg-pink-500/10 text-pink-400', 'title' => 'Multi-Region Availability', 'desc' => 'Deploy in multiple data centers across different continents. Low-latency access for your global users.'),
            )
        );

        $this->load->view('public_site/header', $data);
        $this->load->view('public_site/features', $data);
        $this->load->view('public_site/closing');
    }
}
