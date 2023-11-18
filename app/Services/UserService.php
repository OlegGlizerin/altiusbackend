<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExampleObject
{
   private $shortUrl;
   private $fullUrl;

   public function __construct($shortUrl, $fullUrl)
   {
      $this->shortUrl = $shortUrl;
      $this->fullUrl = $fullUrl;
   }

   public function getShortUrl()
   {
      return $this->shortUrl;
   }
   public function getFullUrl()
   {
      return $this->fullUrl;
   }
}

class LoginData
{
   private $urls;

   public function __construct()
   {
      $web1 = new ExampleObject("fo1.altius.finance", "https://fo1.api.altius.finance/api/v0.0.2/login");
      $web2 = new ExampleObject("fo2.altius.finance", "https://fo2.api.altius.finance/api/v0.0.2/login");

      $this->urls = [$web1, $web2];
   }

   public function getUrl(string $url)
   {
      for ($i = 0; $i < count($this->urls); $i++) {
         if ($this->urls[$i]->getShortUrl() == $url) {
            return $this->urls[$i]->getFullUrl();
         }
      }
      error_log("Incorect url: ${url}");
      return null;
   }
}

class UserService
{
   protected $loginData;

   public function __construct()
   {
      $this->loginData = new LoginData();
   }

   public function login(Request $req)
   {

      $bodyContent = $req->getContent();
      $requestData = json_decode($bodyContent);

      $website = $requestData->website;
      $userName = $requestData->userName;
      $password = $requestData->password;

      $url = $this->loginData->getUrl($website);

      if ($url == null) {
         throw new \Exception("Bad website given.");
      }

      $response = Http::post($url, ["email" => $userName, "password" => $password]);

      error_log("Response from api: ${response}");
      $responseData = json_decode($response);

      // if (isset($responseData->status) == "error") {
      //    return $responseData;
      // }

      return $responseData;
   }
}