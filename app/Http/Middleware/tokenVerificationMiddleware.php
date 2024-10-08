<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class tokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next):Response
    {
        $token = $request->cookie('token');
        $result = JwtToken::verifyToken($token);
        if($result=="unauthorized"){
            return redirect('/userLogin');
        }
        else {
            $request->headers->set('email',$result->userEmail);
            $request->headers->set('id',$result->userId);
            return $next($request);
        }
    }
}
