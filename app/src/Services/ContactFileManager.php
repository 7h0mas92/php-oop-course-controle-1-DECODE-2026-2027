<?php

namespace App\Services;

// s'occupe de lire et d'écrire les contacts dans des fichiers JSON
class ContactFileManager {

    private const string CONTACTS_FOLDER = __DIR__ . '/../../var/contacts/';

    public function __construct()
    {
        if (is_dir(self::CONTACTS_FOLDER) === false) {
            mkdir(self::CONTACTS_FOLDER, 0777, true);
        }
    }

    // enregistre un nouveau contact et renvoie le nom du fichier créé
    public function create(array $data): string
    {
        $now = time();

        $contact = [
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'dateOfCreation' => $now,
            'dateOfLastUpdate' => $now,
        ];

        $fileName = $now . '_' . $data['email'] . '.json';

        file_put_contents(
            self::CONTACTS_FOLDER . $fileName,
            json_encode($contact, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        return $fileName;
    }
}
