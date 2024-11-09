<?php

namespace Caiquebispo\Project\Core\Service;

use Caiquebispo\Project\Core\Service\Contracts\HttpInterface;

class Http implements HttpInterface
{

    protected static ?string $url = null;
    protected static ?string $method = null;
    protected static ?string $type_request = 'GET';
    protected ?string $basic_toke = null;
    protected ?string $user_b64 = null;
    protected ?string $password_b64 = null;
    protected static $instanceClass = null;
    private ?array $headers = null;
    private $paramsURL = null;
    private $data = null;
    private $response = null;
    private $statusCode = null;

    private function __construct()
    {
        self::$instanceClass = $this;
        $this->headers = [
            "Content-Type: application/json; charset=utf-8",
        ];
    }
    public static function get(string $path): ?Http
    {
        self::$url = $path;
        return self::getInstance();
    }
    public static function post(string $url): ?Http
    {
        self::$url = $url;
        self::$type_request = 'POST';
        return self::getInstance();
    }
    public static function put(string $url): ?Http
    {
        self::$url = $url;
        self::$type_request = 'PUT';
        return self::getInstance();
    }
    public static function delete(string $url): ?Http
    {
        self::$url = $url;
        self::$type_request = "DELETE";
        return self::getInstance();
    }
    public function withHeader(array $array): ?Http
    {
        $this->headers = array_merge($this->headers, $array);
        return $this;
    }
    public function withParamsUrl(array $array): ?Http
    {
        $this->paramsURL = http_build_query($array);
        return $this;
    }
    public function withData(array $array): ?Http
    {
        $this->data = $array;
        return $this;
    }
    public function withBasicAuth($user, $password): ?Http
    {
        $this->basic_toke = base64_encode($user . ':' . $passwor);
        $this->setHeaderRequest($this->basic_toke, 'basic');
        return $this;
    }
    public function withBearerAuth($token): ?Http
    {
        $this->basic_toke = $token;
        $this->setHeaderRequest($this->basic_toke, 'bearer');
        return $this;
    }
    public function generateTokenBase64(): string
    {
        return base64_encode($this->user_b64 . ':' . $this->password_b64);
    }
    private function setHeaderRequest($token, $auth_type): array
    {
        $this->headers = $this->getHeaderRequest();

        switch ($auth_type) {
            case 'basic':
                array_push($this->headers, "Authorization: Basic {$token}");
                break;
            case 'bearer':
                array_push($this->headers, "Authorization: Bearer {$token}");
                break;
        }
        return $this->headers;
    }
    private function getHeaderRequest(): array
    {
        return $this->headers;
    }
    public function toJson(): ?array
    {
        $this->execute();
        return json_decode($this->response, true);
    }
    public function toOriginal(): string
    {
        $this->execute();
        return $this->response;
    }
    public function execute(): void
    {
        $url = $this->paramsURL ? self::$url . '?' . $this->paramsURL :  self::$url . self::$method;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaderRequest());

        if (self::$type_request !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::$type_request);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->response = $result;
    }
    public static function getInstance(): ?Http
    {
        if (!self::$instanceClass) {
            self::$instanceClass = new Http();
        }
        return self::$instanceClass;
    }
}
