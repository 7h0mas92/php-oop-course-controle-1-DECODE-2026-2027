<?php

namespace App\Controllers\Contact;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Services\ContactFileManager;

class ListContactController extends AbstractContactController {

    public function process(Request $request): Response
    {
        $fileManager = new ContactFileManager();
        $contacts = $fileManager->findAll();

        return $this->jsonResponse(200, $contacts);
    }
}
