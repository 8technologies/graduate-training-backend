<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Token;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareAuth extends Authenticate
{

    protected $except = [
        'login',
        'register',
        'min/login',
    ];

    // public function handle($request, Closure $next): Response
    // public function handle($request, Closure $next, ...$guards): Response
    
    // {

    //     // If request starts with api then we will check for token
    //     if (!$request->is('api/*')) {
    //         return $next($request);
    //     }
    //     try {
    //         //$headers = apache_request_headers(); //get header
    //         $headers = getallheaders(); //get header

    //         header('Content-Type: application/json');

    //         Log::info(['head1', $request->headers]);

    //         $Authorization = "";
    //         if (isset($headers['Authorization']) && $headers['Authorization'] != "") {
    //             $Authorization = $headers['Authorization'];
    //         } else if (isset($headers['authorization']) && $headers['authorization'] != "") {
    //             $Authorization = $headers['authorization'];
    //         } else if (isset($headers['Authorizations']) && $headers['Authorizations'] != "") {
    //             $Authorization = $headers['Authorizations'];
    //         } else if (isset($headers['authorizations']) && $headers['authorizations'] != "") {
    //             $Authorization = $headers['authorizations'];
    //         } else if (isset($headers['Tok']) && $headers['Tok'] != "") {
    //             $Authorization = $headers['Tok'];
    //         }


    //         // $request->headers->set('Authorization', $Authorization); // set header in request

    //         $request->headers->set('Authorization', 'Bearer ' . $Authorization);
    //         $request->headers->set('authorization', 'Bearer ' . $Authorization); // set header in request

    //         Log::info(['head2', $request->headers]);
            
    //     $user = $this->authenticate($request, $guards);;

    //     // return $next($request);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ], 401);
    //     }
        
    //     return $next($request);
    // }

    public function handle($request, Closure $next, ...$guards): Response
    {
        if (!$request->is('api/*')) {
            return $next($request);
        }

        try {
            $headers = getallheaders();
            header('Content-Type: application/json');

            Log::info(['head1' => $headers]);

            $Authorization = '';

            foreach (['Authorization', 'authorization', 'Authorizations', 'authorizations', 'Tok'] as $key) {
                if (!empty($headers[$key])) {
                    $Authorization = $headers[$key];
                    break;
                }
            }

            if (!$Authorization) {
                throw new AuthenticationException('Authorization token not found.');
            }

            // Set header correctly for Laravel Passport to work
            $request->headers->set('Authorization', 'Bearer ' . $Authorization);

            Log::info(['guards' => $guards]);
            Log::info(['request' => $request]);

            // THIS is what you wanted to use
            $this->authenticate($request, ['api']);
            // Log::info(['Authenticated user' => $user->id]);

        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthenticated: ' . $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }

        return $next($request);
    }


    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!$request->is('api/*')) {
    //         return $next($request);
    //     }

    //     try {
    //         $headers = getallheaders();

    //         $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    //         if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
    //             throw new AuthenticationException('Token not provided.');
    //         }

    //         $accessToken = str_replace('Bearer ', '', $authorization);
    //         $hashToken= hash('sha256', $accessToken);
    //         Log::info('Raw token: ' . $hashToken);

    //         // Validate token manually
    //         $tokenModel = Token::where('id', hash('sha256', $accessToken))->first();
    //         Log::info('tokenModel', [$tokenModel]);

    //         if (!$tokenModel || $tokenModel->revoked || $tokenModel->expires_at->isPast()) {
    //             throw new AuthenticationException('Invalid or expired token.');
    //         }

    //         $user = $tokenModel->user;

    //         if (!$user) {
    //             throw new AuthenticationException('User not found.');
    //         }

    //         // Log user in for current request (like Auth::guard('api')->user())
    //         Auth::setUser($user);

    //     } catch (\Throwable $e) {
    //         Log::error('Auth Error', ['error' => $e->getMessage()]);
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     return $next($request);
    // }
}

