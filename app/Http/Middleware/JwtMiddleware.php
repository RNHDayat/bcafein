<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ApiResponser;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!request()->hasHeader('login-type')) {
                return $this->showMessage('Authorization Token not found', 401);
            }
            $loginType = $this->checkLoginType(request()->header('login-type'));
            if ($loginType == -1) {
                return $this->showMessage('Authorization Token not found', 401);
            }
            $splitToken = explode(' ', $request->header('Authorization'));
            $token = User::where('id', $user->id)->where('remember_token', $splitToken[1])->get();
            if (count($token) == 0) {
                return response()->json(['error' => 'Authorization Token not found', 'code' => 401], 401);
            }
            if ($user->status == 0) {
                return response()->json(['error' => 'User is Inactive !', 'code' => 401], 401);
            }
            // return $this->showMessage($loginType);
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->showMessage('Token is Invalid', 401);
            } elseif ($e instanceof TokenExpiredException) {
                return $this->showMessage('Token is Expired', 401);
            } else {
                return $this->showMessage('Authorization Token not found', 401);
            }
        }
        return $next($request);
        // return $next($request);
    }
}
