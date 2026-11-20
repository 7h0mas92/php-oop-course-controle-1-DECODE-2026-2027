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

    // renvoie tous les contacts enregistrés, du plus ancien au plus récent
    public function findAll(): array
    {
        $contacts = [];
        $files = glob(self::CONTACTS_FOLDER . '*.json');
        sort($files);

        foreach ($files as $file) {
            $contact = $this->readFile($file);

            if ($contact !== null) {
                $contacts[] = $contact;
            }
        }

        return $contacts;
    }

    // renvoie un contact à partir du nom de son fichier, ou null s'il n'existe pas
    public function findOne(string $fileName): ?array
    {
        if ($this->isValidFileName($fileName) === false) {
            return null;
        }

        $path = self::CONTACTS_FOLDER . $fileName;

        if (file_exists($path) === false) {
            return null;
        }

        return $this->readFile($path);
    }

    // modifie un contact existant et renvoie le contact mis à jour
    public function update(string $fileName, array $data): array
    {
        $contact = $this->findOne($fileName);

        foreach ($data as $key => $value) {
            $contact[$key] = $value;
        }

        $contact['dateOfLastUpdate'] = time();

        file_put_contents(self::CONTACTS_FOLDER . $fileName, json_encode($contact, JSON_PRETTY_PRINT));

        return $contact;
    }

    public function delete(string $fileName): void
    {
        unlink(self::CONTACTS_FOLDER . $fileName);
    }

    // empêche de lire un fichier en dehors du dossier (ex: ../../config/routes.json)
    private function isValidFileName(string $fileName): bool
    {
        if ($fileName !== basename($fileName)) {
            return false;
        }

        return preg_match('/^[0-9]+_.+\.json$/', $fileName) === 1;
    }

    // lit un fichier et vérifie qu'il contient bien un contact, sinon renvoie null
    private function readFile(string $path): ?array
    {
        $content = file_get_contents($path);
        $contact = json_decode($content, true);

        if (is_array($contact) === false) {
            return null;
        }

        $expectedKeys = ['email', 'subject', 'message', 'dateOfCreation', 'dateOfLastUpdate'];

        foreach ($expectedKeys as $key) {
            if (array_key_exists($key, $contact) === false) {
                return null;
            }
        }

        return [
            'email' => $contact['email'],
            'subject' => $contact['subject'],
            'message' => $contact['message'],
            'dateOfCreation' => $contact['dateOfCreation'],
            'dateOfLastUpdate' => $contact['dateOfLastUpdate'],
        ];
    }
}
