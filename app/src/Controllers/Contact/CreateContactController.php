<?php

namespace App\Controllers\Contact;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Services\ContactFileManager;

class CreateContactController extends AbstractContactController {

    public function process(Request $request): Response
    {
        if ($this->isJsonRequest($request) === false) {
            return $this->errorResponse(400, 'Le header Content-Type doit être application/json');
        }

        $data = $this->decodeBody($request);

        if ($data === null) {
            return $this->errorResponse(400, 'Le body doit être un objet JSON valide');
        }

        $error = $this->validateFields($data, true);

        if ($error !== null) {
            return $this->errorResponse(400, $error);
        }

        $fileManager = new ContactFileManager();
        $fileName = $fileManager->create($data);

        return $this->jsonResponse(201, ['file' => $fileName]);
    }
}
