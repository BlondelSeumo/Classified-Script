<?php

namespace Packages\Authenticate;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @author MendelManGroup <ezzaroual@mail.com>
 * @category Authenticating
 * @copyright 2019 MendelManGroup
 */
class Login extends Controller
{
    /**
     * This trait has all the login throttling functionality.
     */
    use ThrottlesLogins;
	
	/**
	 * Login by email address
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	protected function loginByEmail(Request $request)
	{
		// Get credentials
		$email    = $request->credentials;
		$password = $request->password;

		try {
			// Failed login
            if (! $token = JWTAuth::attempt(['email' => $email, 'password' => $password, 'isActive' => true])) {
                //keep track of login attempts from the user.
                $this->incrementLoginAttempts($request);
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{

                // Login success get user
                $user = auth()->user();

                // Check if 2FA required
                if ($user->passwordSecurity()->exists() && $user->passwordSecurity->google2fa_enable) {

                    // Get verification code
                    $verify_code = $request->verifyCode;

                    // Validate verify code
                    if (!$verify_code || strlen($verify_code) !== 6) {
                        // 2FA required
                        return response()->json([
                            'state' => 'two_factor_required'
                        ], 422);
                    }

                    $google2fa   = app('pragmarx.google2fa');

                    $valid       = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $verify_code);

                    // Check if 2FA verification code is correct
                    if ($valid) {
                        
                        // Code verified
                        return $this->respondWithToken($token);

                    }else{

                        // Logout
                        auth()->logout();

                        // 2FA Wrong
                        return response()->json([
                            'state' => 'two_factor_wrong'
                        ], 422);

                    }

                }else{

                    // 2FA is not required
                    return $this->respondWithToken($token);

                }

            }

        } catch (JWTException $e) {
            // Failed
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	}


	/**
	 * Login by username
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	protected function loginByUsername(Request $request)
	{
		// Get credentials
		$username = $request->credentials;
		$password = $request->password;

		try {
			// Failed login
            if (! $token = JWTAuth::attempt(['username' => $username, 'password' => $password, 'isActive' => true])) {
                //keep track of login attempts from the user.
                $this->incrementLoginAttempts($request);
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{

                // Login success get user
                $user = auth()->user();

                // Check if 2FA required
                if ($user->passwordSecurity()->exists() && $user->passwordSecurity->google2fa_enable) {

                    // Get verification code
                    $verify_code = $request->verifyCode;

                    // Validate verify code
                    if (!$verify_code || strlen($verify_code) !== 6) {
                        // 2FA required
                        return response()->json([
                            'state' => 'two_factor_required'
                        ], 422);
                    }

                    $google2fa   = app('pragmarx.google2fa');

                    $valid       = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $verify_code);

                    // Check if 2FA verification code is correct
                    if ($valid) {
                        
                        // Code verified
                        return $this->respondWithToken($token);

                    }else{

                        // Logout
                        auth()->logout();

                        // 2FA Wrong
                        return response()->json([
                            'state' => 'two_factor_wrong'
                        ], 422);

                    }

                }else{

                    // 2FA is not required
                    return $this->respondWithToken($token);

                }

            }

        } catch (JWTException $e) {
            // Failed
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	}


	/**
	 * Login by phone number
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	protected function loginByPhone(Request $request)
	{
		
		// Get credentials
		$phone    = $request->credentials;
		$password = $request->password;

		try {
			// Failed login
            if (! $token = JWTAuth::attempt(['phone' => $phone, 'password' => $password, 'isActive' => true])) {
                //keep track of login attempts from the user.
                $this->incrementLoginAttempts($request);
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{

                // Login success get user
                $user = auth()->user();

                // Check if 2FA required
                if ($user->passwordSecurity()->exists() && $user->passwordSecurity->google2fa_enable) {

                    // Get verification code
                    $verify_code = $request->verifyCode;

                    // Validate verify code
                    if (!$verify_code || strlen($verify_code) !== 6) {
                        // 2FA required
                        return response()->json([
                            'state' => 'two_factor_required'
                        ], 422);
                    }

                    $google2fa   = app('pragmarx.google2fa');

                    $valid       = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $verify_code);

                    // Check if 2FA verification code is correct
                    if ($valid) {
                        
                        // Code verified
                        return $this->respondWithToken($token);

                    }else{

                        // Logout
                        auth()->logout();

                        // 2FA Wrong
                        return response()->json([
                            'state' => 'two_factor_wrong'
                        ], 422);

                    }

                }else{

                    // 2FA is not required
                    return $this->respondWithToken($token);

                }

            }

        } catch (JWTException $e) {
            // Failed
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	}


	/**
	 * Login by username or by email
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	protected function loginByUsernameEmail(Request $request)
	{
		// Get credentials
		$type        = filter_var($request->credentials, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$credentials = $request->credentials;
		$password    = $request->password;

		try {
			// Failed login
            if (! $token = JWTAuth::attempt([$type => $credentials, 'password' => $password, 'isActive' => true])) {
                //keep track of login attempts from the user.
                $this->incrementLoginAttempts($request);
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{

                // Login success get user
                $user = auth()->user();

                // Check if 2FA required
                if ($user->passwordSecurity()->exists() && $user->passwordSecurity->google2fa_enable) {

                    // Get verification code
                    $verify_code = $request->verifyCode;

                    // Validate verify code
                    if (!$verify_code || strlen($verify_code) !== 6) {
                        // 2FA required
                        return response()->json([
                            'state' => 'two_factor_required'
                        ], 422);
                    }

                    $google2fa   = app('pragmarx.google2fa');

                    $valid       = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $verify_code);

                    // Check if 2FA verification code is correct
                    if ($valid) {
                        
                        // Code verified
                        return $this->respondWithToken($token);

                    }else{

                        // Logout
                        auth()->logout();

                        // 2FA Wrong
                        return response()->json([
                            'state' => 'two_factor_wrong'
                        ], 422);

                    }

                }else{

                    // 2FA is not required
                    return $this->respondWithToken($token);

                }

            }

        } catch (JWTException $e) {
            // Failed
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	}
	

	/**
	 * Login by username or email or phone
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	protected function loginByUsernameEmailPhone(Request $request)
	{
		// Login by email
		if (filter_var($request->credentials, FILTER_VALIDATE_EMAIL)) {
			return $this->loginByEmail($request);
		}

		// Or by username or phone number
		// Get credentials
		$credentials = $request->credentials;
		$password    = $request->password;

		try {
			if ($token = JWTAuth::attempt(['username' => $credentials, 'password' => $password, 'isActive' => true])) {
                // Login success get user
                $user = auth()->user();

                // Check if 2FA required
                if ($user->passwordSecurity()->exists() && $user->passwordSecurity->google2fa_enable) {

                    // Get verification code
                    $verify_code = $request->verifyCode;

                    // Validate verify code
                    if (!$verify_code || strlen($verify_code) !== 6) {
                        // 2FA required
                        return response()->json([
                            'state' => 'two_factor_required'
                        ], 422);
                    }

                    $google2fa   = app('pragmarx.google2fa');

                    $valid       = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $verify_code);

                    // Check if 2FA verification code is correct
                    if ($valid) {
                        
                        // Code verified
                        return $this->respondWithToken($token);

                    }else{

                        // Logout
                        auth()->logout();

                        // 2FA Wrong
                        return response()->json([
                            'state' => 'two_factor_wrong'
                        ], 422);

                    }

                }else{

                    // 2FA is not required
                    return $this->respondWithToken($token);

                }
			}else if ($token = JWTAuth::attempt(['phone' => $credentials, 'password' => $password, 'isActive' => true])) {
				// Login success get user
                $user = auth()->user();

                // Check if 2FA required
                if ($user->passwordSecurity()->exists() && $user->passwordSecurity->google2fa_enable) {

                    // Get verification code
                    $verify_code = $request->verifyCode;

                    // Validate verify code
                    if (!$verify_code || strlen($verify_code) !== 6) {
                        // 2FA required
                        return response()->json([
                            'state' => 'two_factor_required'
                        ], 422);
                    }

                    $google2fa   = app('pragmarx.google2fa');

                    $valid       = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $verify_code);

                    // Check if 2FA verification code is correct
                    if ($valid) {
                        
                        // Code verified
                        return $this->respondWithToken($token);

                    }else{

                        // Logout
                        auth()->logout();

                        // 2FA Wrong
                        return response()->json([
                            'state' => 'two_factor_wrong'
                        ], 422);

                    }

                }else{

                    // 2FA is not required
                    return $this->respondWithToken($token);

                }
			}else{
                //keep track of login attempts from the user.
                $this->incrementLoginAttempts($request);
				// Failed login
				return response()->json(['error' => 'Unauthorized'], 401);
			}
        } catch (JWTException $e) {
            // Failed
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	}



    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 3600
        ]);
    }
	
}