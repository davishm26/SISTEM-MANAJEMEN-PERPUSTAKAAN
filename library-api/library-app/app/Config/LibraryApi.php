<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class LibraryApi extends BaseConfig
{
    public string $baseUrl = 'http://localhost:8000/api';
    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = env('LIBRARY_API_URL', $this->baseUrl);
    }
}
