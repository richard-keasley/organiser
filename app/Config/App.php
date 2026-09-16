<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public string $baseURL = 'http://localhost:8080/';
    public string $indexPage = '';
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'UTC';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;
    public string $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';
    public $sessionCookieName = 'ci_session';
    public int $sessionExpiration = 7200;
    public bool $sessionSavePath = false;
    public bool $sessionMatchIP = false;
    public bool $sessionTimeToUpdate = 300;
    public bool $sessionRegenerateDestroy = false;
    public $cookiePrefix = '';
    public $cookieDomain = '';
    public $cookiePath = '/';
    public bool $cookieSecure = false;
    public bool $cookieSameSite = 'Lax';
    public bool $CSPEnabled = false;
}
