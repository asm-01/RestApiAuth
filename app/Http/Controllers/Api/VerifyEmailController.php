<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VerifyEmailRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }
    
    public function index()
    {
        /*
         * do something when the user open
         * the verification link from email
         */
        return 'Hello';
    }
    
    public function verify(VerifyEmailRequest $request)
    {
        $user = Auth::user();
        $id = $request->id == $user->id ? true : false ;
        $hash = $request->hash == sha1($user->getEmailForVerification()) ? true : false ;
        
        $hashverified = $user->hasVerifiedEmail();
        
        if($id && $hash){
          if($hashverified){
            return $request->wantsJson()
                      ? new JsonResponse([
                            'message' => 'Email is already verified.',
                            'user' => $user
                        ], 200)
                      : null;
          }else{
              $user->markEmailAsVerified();
              event(new Verified($user));
              //doevent
              return $request->wantsJson()
                    ? new JsonResponse([
                          'message' => 'Email have been successfully verified.',
                          'user' => $user
                      ], 200)
                    : null;
          }
        }else {
          return $request->wantsJson()
                    ? new JsonResponse([
                          'message' => 'Something went wrong. The requested uuid and email doesn\'t match.'
                      ], 400)
                    : null;
        }
    }
    
    public function notification(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                  ? new JsonResponse([
                        'message' => 'The requested email have already been verified.',
                        'user' => $user
                    ],200)
                  : null;
        } else {
            $user->sendEmailVerificationNotification();
            return $request->wantsJson()
                  ? new JsonResponse([
                        'message' => 'We have emailed the verification link. Go check your mailbox.',
                        'user' => $user
                    ],200)
                  : null;
        }
    }
    
}
