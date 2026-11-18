<?php

namespace App\Lib\Http;

class Request {
    private readonly string $uri;
    private readonly string $method;
    private readonly array $headers;
    private readonly string $body;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    // renvoie l'uri sans les paramètres (ex: /contact?file=abc donne /contact)
    public function getPath(): string
    {
        return parse_url($this->uri, PHP_URL_PATH);
    }

    // renvoie la valeur d'un paramètre de l'url (ex: ?file=abc), ou null s'il n'existe pas
    public function getQueryParam(string $name): ?string
    {
        if (isset($_GET[$name]) === false || is_string($_GET[$name]) === false) {
            return null;
        }

        return $_GET[$name];
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    // renvoie la valeur d'un header sans tenir compte des majuscules/minuscules
    public function getHeader(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === strtolower($name)) {
                return $value;
            }
        }

        return null;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
