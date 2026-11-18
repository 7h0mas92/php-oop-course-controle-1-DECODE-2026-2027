<?php

namespace App\Controllers\Contact;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Services\ContactFileManager;

class GetContactController extends AbstractContactController {

    public function process(Request $request): Response
    {
        $fileName = $request->getQueryParam('file');

        $fileManager = new ContactFileManager();
        $contact = $fileManager->findOne($fileName);

        if ($contact === null) {
            return $this->errorResponse(404, 'Le formulaire de contact n\'a pas été trouvé');
        }

        return $this->jsonResponse(200, $contact);
    }
}
