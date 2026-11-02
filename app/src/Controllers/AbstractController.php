<?php

namespace App\Controllers;

use App\Http\Response;
use App\Http\Request;

abstract class AbstractController {
    public abstract function process(Request $request): Response;
}

?>

