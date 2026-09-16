<?php

namespace Config;

use CodeIgniter\Config\Routes as BaseRoutes;

class Routes extends BaseRoutes
{
    public static function init() {
        parent::init();

        // Home and dashboard routes
        $routes->get('/', 'Dashboard::index');
        $routes->get('dashboard', 'Dashboard::index');

        // Contacts routes
        $routes->group('contacts', static function ($routes) {
            $routes->get('', 'Contacts::index');
            $routes->get('create', 'Contacts::create');
            $routes->post('create', 'Contacts::create');
            $routes->get('view/(:num)', 'Contacts::view/$1');
            $routes->get('edit/(:num)', 'Contacts::edit/$1');
            $routes->post('edit/(:num)', 'Contacts::edit/$1');
            $routes->post('delete/(:num)', 'Contacts::delete/$1');
        });
    }
}
