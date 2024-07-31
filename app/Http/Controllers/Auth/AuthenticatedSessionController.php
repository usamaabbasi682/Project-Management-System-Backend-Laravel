<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Admin\Auth\LoginResource;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): LoginResource
    {
        try {
            $request->authenticate();
            $user = $request->user();

            $token = $user->createToken('SPA_TOKEN_API')->plainTextToken;
            return LoginResource::make(auth()->user())
                ->additional([
                    'success' => true,
                    'message' => __('messages.login_success'),
                    'token' => $token,
                ]);
        } catch (ValidationException $e) {

            return LoginResource::make(null)
                ->additional([
                    'success' => false,
                    'message' => __('messages.email_or_password'),
                ]);
        } catch (\Exception $e) {

            return LoginResource::make(null)
                ->additional([
                    'success' => false,
                    'message' => __('messages.something_went_wrong'),
                ]);
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): LoginResource
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return LoginResource::make(null)
            ->additional([
                'success' => true,
                'message' => 'Logout successfully',
            ]);
    }
}
