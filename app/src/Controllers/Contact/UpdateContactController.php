<?php

namespace App\Controllers\Contact;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Services\ContactFileManager;

class UpdateContactController extends AbstractContactController {

    public function process(Request $request): Response
    {
        $fileName = $request->getQueryParam('file');
        $fileManager = new ContactFileManager();

        if ($fileManager->findOne($fileName) === null) {
            return $this->errorResponse(404, 'Contact introuvable');
        }

        if ($this->isJsonRequest($request) === false) {
            return $this->errorResponse(400, 'Le header Content-Type doit être application/json');
        }

        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->errorResponse(400, 'JSON invalide');
        }

        // ici les champs ne sont pas tous obligatoires
        $error = $this->validateFields($data, false);
        if ($error !== null) {
            return $this->errorResponse(400, $error);
        }

        $contact = $fileManager->update($fileName, $data);

        return $this->jsonResponse(200, $contact);
    }
}
