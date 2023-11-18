<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;


class MyController extends Controller
{
   protected $userService;
   // Constructor injection
   public function __construct(UserService $userService)
   {
      $this->userService = $userService;
   }

   function login(Request $req)
   {
      error_log('Start login.');
      $result = $this->userService->login($req);
      error_log('Finish login.');

      if (isset($result->success->token)) {
         return ["token" => $result->success->token];
      } else {
         return ["error" => $result];
      }
   }
}