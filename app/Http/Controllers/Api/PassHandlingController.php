<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PassHandlingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['forgot','reset']]);
    }
    
    public function forgot()
    {
      
    }
    
    public function reset()
    {
      
    }
    
    public function update()
    {
      
    }
    
    public function confirm()
    {
      
    }
    
}
