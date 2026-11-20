<?php

namespace App\Controllers\Contact;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Services\ContactFileManager;

class DeleteContactController extends AbstractContactController {

    public function process(Request $request): Response
    {
        $fileName = $request->getQueryParam('file');
        $fileManager = new ContactFileManager();

        if ($fileManager->findOne($fileName) === null) {
            return $this->errorResponse(404, 'Contact introuvable');
        }

        $fileManager->delete($fileName);

        $response = new Response();
        $response->statusCode = 204;
        $response->content = '';
        return $response;
    }
}
