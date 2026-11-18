<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

abstract class AbstractContactController extends AbstractController {

    protected const array ALLOWED_FIELDS = ['email', 'subject', 'message'];

    // crée une réponse au format JSON
    protected function jsonResponse(int $statusCode, mixed $data): Response
    {
        $response = new Response();
        $response->statusCode = $statusCode;
        $response->headers['Content-Type'] = 'application/json';
        $response->content = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return $response;
    }

    protected function errorResponse(int $statusCode, string $message): Response
    {
        return $this->jsonResponse($statusCode, ['error' => $message]);
    }

    // vérifie que le client envoie bien du JSON
    protected function isJsonRequest(Request $request): bool
    {
        $contentType = $request->getHeader('Content-Type');

        if ($contentType === null) {
            return false;
        }

        return str_starts_with(strtolower($contentType), 'application/json');
    }

    // transforme le body en tableau, renvoie null si le JSON est invalide
    protected function decodeBody(Request $request): ?array
    {
        $data = json_decode($request->getBody(), true);

        if (is_array($data) === false) {
            return null;
        }

        // un tableau comme [1, 2] n'est pas un objet JSON
        if (count($data) > 0 && array_is_list($data) === true) {
            return null;
        }

        return $data;
    }

    // renvoie un message d'erreur si les données sont invalides, sinon null
    protected function validateFields(array $data, bool $allFieldsRequired): ?string
    {
        if (count($data) === 0) {
            return 'Le body ne doit pas être vide';
        }

        foreach ($data as $key => $value) {
            if (in_array($key, self::ALLOWED_FIELDS, true) === false) {
                return 'Le champ "' . $key . '" n\'est pas autorisé';
            }
        }

        if ($allFieldsRequired === true) {
            foreach (self::ALLOWED_FIELDS as $field) {
                if (array_key_exists($field, $data) === false) {
                    return 'Le champ "' . $field . '" est obligatoire';
                }
            }
        }

        foreach ($data as $key => $value) {
            if (is_string($value) === false || trim($value) === '') {
                return 'Le champ "' . $key . '" doit être une chaîne non vide';
            }
        }

        if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            return 'L\'email n\'est pas valide';
        }

        if (isset($data['subject']) && strlen($data['subject']) > 255) {
            return 'Le sujet ne doit pas dépasser 255 caractères';
        }

        if (isset($data['message']) && strlen($data['message']) > 5000) {
            return 'Le message ne doit pas dépasser 5000 caractères';
        }

        return null;
    }
}
