<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Classe base para todos os controllers da aplicação.
 * Fornece os traits de autorização e validação do Laravel.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}