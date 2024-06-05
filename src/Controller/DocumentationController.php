<?php
// src/Controller/DocumentationController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\Model\Client;
use Twetech\Nestogy\Model\Documentation;
use Twetech\Nestogy\View\View;
use Twetech\Nestogy\Auth\Auth;

class DocumentationController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        if (!Auth::check()) {
            // Redirect to login page or handle unauthorized access
            header('Location: login.php');
            exit;
        }
    }

    public function index() {
        //Redirect to /public/?page=home temporarily
        // TODO: Implement the documentation home page
        header('Location: /public/?page=home');
        exit;
    }

    public function show($documentation_type, $client_id = false) {
        $view = new View();
        $documentationModel = new Documentation($this->pdo);
        $client_page = false;
        $data = [];

        if ($client_id) {
            $client_page = true;
            $client = new Client($this->pdo);
            $client_header = $client->getClientHeader($client_id);
            $data['client_header'] = $client_header['client_header'];
        }


        switch ($documentation_type) {
            case 'asset': {
                $assets = $documentationModel->getAssets($client_id ? $client_id : false);
                $data['card']['title'] = 'Assets';
                $data['table']['header_rows'] = [
                    'Name',
                    'Type',
                    'Model',
                    'Serial',
                    'OS',
                    'IP',
                    'Install Date',
                    'Assigned To',
                    'Location',
                    'Status',
                ];
                    
                $data['table']['body_rows'] = [];

                foreach ($assets as $asset) {
                    $data['table']['body_rows'][] = [
                        $asset['asset_name'],
                        $asset['asset_type'],
                        $asset['asset_model'],
                        $asset['asset_serial'],
                        $asset['asset_os'],
                        $asset['asset_ip'],
                        $asset['asset_install_date'],
                        0, // todo get assigned to
                        $asset['asset_location_id'],
                        $asset['asset_status'],
                    ];
                }

                break;
            }
            case 'license': {
                $licenses = $documentationModel->getLicenses($client_id);
                $data['card']['title'] = 'Licenses';
                $data['table']['header_rows'] = [
                    'Software',
                    'Type',
                    'License Type',
                    'Seats'
                ];
                $data['table']['body_rows'] = [];

                foreach ($licenses as $license) {
                    $data['table']['body_rows'][] = [
                        $license['software_name'],
                        $license['software_type'],
                        $license['software_license_type'],
                        $license['software_seats']
                    ];
                }
                break;
            }
            case 'login': {
                $logins = $documentationModel->getLogins($client_id);
                $data['card']['title'] = 'Logins';
                $data['table']['header_rows'] = [
                    'Name',
                    'Username',
                    'Password',
                    'OTP',
                    'URL'
                ];
                $data['table']['body_rows'] = [];

                foreach ($logins as $login) {
                    $data['table']['body_rows'][] = [
                        $login['login_name'],
                        $login['login_username'],
                        $login['login_password'],
                        $login['login_otp_secret'],
                        $login['login_uri']
                    ];
                }
                break;
            }
            case 'network': {
                $networks = $documentationModel->getNetworks($client_id);
                $data['card']['title'] = 'Networks';
                $data['table']['header_rows'] = [
                    'Name',
                    'VLAN',
                    'Subnet',
                    'Gateway',
                    'DCHP Pool',
                    'Location'
                ];
                $data['table']['body_rows'] = [];

                foreach ($networks as $network) {
                    $data['table']['body_rows'][] = [
                        $network['network_name'],
                        $network['network_vlan'],
                        $network['network'],
                        $network['network_gateway'],
                        $network['network_dhcp_range'],
                        $network['network_location_id']
                    ];
                }
                break;
            }
            case 'service': {
                $services = $documentationModel->getServices($client_id);
                $data['card']['title'] = 'Services';
                $data['table']['header_rows'] = [
                    'Name',
                    'Category',
                    'Importance',
                    'Updated'
                ];
                $data['table']['body_rows'] = [];

                foreach ($services as $service) {
                    $data['table']['body_rows'][] = [
                        $service['service_name'],
                        $service['service_category'],
                        $service['service_importance'],
                        $service['service_updated_at']
                    ];
                }
                break;
            }
            case 'vendor': {
                $vendors = $documentationModel->getVendors($client_id);
                $data['card']['title'] = 'Vendors';
                $data['table']['header_rows'] = [
                    'Name',
                    'Contact',
                    'SLA',
                    'Notes'
                ];
                $data['table']['body_rows'] = [];

                foreach ($vendors as $vendor) {
                    $data['table']['body_rows'][] = [
                        $vendor['vendor_name'],
                        $vendor['vendor_contact_name'].' <a href="mailto:'.$vendor['vendor_email'].'">'.$vendor['vendor_email'].'</a> <a href="tel:'.$vendor['vendor_phone'].'">'.$vendor['vendor_phone'].'</a>',
                        $vendor['vendor_sla'],
                        $vendor['vendor_notes']
                    ];
                }

                break;
            }
            case 'file': {
                $message = [
                    'title' => 'Page not found',
                    'message' => 'File documentation not implemented yet.'
                ];
                $view->error($message);
                exit;
            }
            case 'document': {
                $message = [
                    'title' => 'Page not found',
                    'message' => 'Document documentation not implemented yet.'
                ];
                $view->error($message);
                exit;
            }

            default: {
                http_response_code(404);
                echo "Page not found.";
                exit;
            }
        }
        $view->render('simpleTable', $data, $client_page);
    }
}