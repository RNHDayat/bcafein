<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Encryption;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\CreateAccount;
use App\Mail\ForgotPass;
use App\Models\Employee;
use App\Models\FollowUser;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_type' => 'required|numeric|in:0,1',
        ], [
            'required' => 'Login Type request is required, please use the provided system or ask the tech support to fix it',
            'in' => 'Please login from the available app or website'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        // === Input Validation and Decription ===
        // Only the authorized interface are granted by grace to pass the authentication successfulsy
        try {
            $login_type = $request->login_type;
            if ($login_type == '1') {
                $validator = Validator::make($request->all(), [
                    'username' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                    'password' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                    'firebase_token' => 'required|string|min:12|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                ]);
                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 400);
                }
                $encrypt = new Encryption();
                $username = $encrypt->decrypt($request->username, config('secretKey.secretKey'));
                $password = $encrypt->decrypt($request->password, config('secretKey.secretKey'));
                $firebase_token = $encrypt->decrypt($request->firebase_token, config('secretKey.secretKey'));
                $requestLoginType = $login_type;
            } else {
                $validator = Validator::make($request->all(), [
                    'username' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                    'password' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                ]);
                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 400);
                }
                $encrypt = new Encryption();
                $username = $encrypt->decrypt($request->username, config('secretKey.secretKey'));
                $password = $encrypt->decrypt($request->password, config('secretKey.secretKey'));
                $requestLoginType = $login_type;
            }
        } catch (JWTException $e) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                'password' => 'required|string|min:12|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/'
            ]);
            $encrypt = new Encryption();
            $username = $encrypt->decrypt($request->username, config('secretKey.secretKey'));
            $password = $encrypt->decrypt($request->password, config('secretKey.secretKey'));
            $requestLoginType = $login_type;
        }
        // If doesn't exist, will be seen here
        $user = User::where('username', '=', $username)->orWhere('email', '=', $username)->get();

        if (count($user) == 0 || gettype($username) == "boolean") {
            $error['user'] = ['username atau password anda salah'];
            return $this->errorResponse($error, 404);
        }

        // Picking the first user data
        $user = $user->first();

        // === Authentication role restriction policy ===
        // Check User Status
        $checkStatus = $this->checkUserStatus($user->status);
        if ($checkStatus[0] != 200) {
            return $this->errorResponse($checkStatus[1], 401);
        }
        // Every role restriction in login action is controlled here
        if ($requestLoginType == 1 && $user->level == User::ADMINS) {
            $error['user'] = ['For Admins please login through website for a proper interface'];
            return $this->errorResponse($error, 401);
        }
        if ($requestLoginType == 1 && $user->level == User::SUPER_ADMIN) {
            $error['user'] = ['For Super Admin please login through website for a proper interface'];
            return $this->errorResponse($error, 401);
        }

        // Check Credentials if token is available to create
        $credentials = [
            'email' => $user->email,
            'password' => $password
        ];
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $error['user'] = ['username atau password anda salah'];
                return $this->errorResponse($error, 400);
            }
        } catch (JWTException $e) {
            return $this->errorResponse('could_not_create_token', 500);
        }

        // YAY! The Token is created, let's store it on database
        $token = JWTAuth::fromUser($user);

        if ($login_type == 1) {
            $firebaseToken = $request->firebase_token;
        } else {
            $firebaseToken = null;
        }
        // // Restriction user. One Device, One User
        // if (count($userToken) == 0) {
        //     UserToken::create([
        //         'user_id' => $user->id,
        //         'token' => $token,
        //         'firebase_token' => $firebaseToken,
        //         'login_type' => $loginType
        //     ]);
        // } else {
        //     UserToken::where('user_id', '=', $user->id)->where('login_type', '=', $loginType)->delete();
        //     UserToken::create([
        //         'user_id' => $user->id,
        //         'token' => $token,
        //         'firebase_token' => $firebaseToken,
        //         'login_type' => $loginType
        //     ]);
        // }

        $today = Carbon::now();
        $user->last_login_at = date('Y-m-d H:i:s', strtotime($today));
        $user->remember_token = $token;
        $user->firebase_token = $firebaseToken;
        $user->save();
        $pegawai = $user->employees; // agar yang foreign key bisa masuk ^_^

        return $this->showData(['user' => $user, 'token' => $user->remember_token, 'level' => $user->level]);
    }

    /**
     * *Generate a Token for URL security pass to resetting the password
     *
     * @param request email
     * @param request username
     * @return mixed return success response if token is generated successfully, or else the error response will show out
     */
    public function logout()
    {
        $loginType = request()->header('login_type');
        $splitToken = explode(' ', request()->header('Authorization'));

        $user = User::where('remember_token', '=', $splitToken[1])->first();
        $user->remember_token = null;
        $user->firebase_token = null;
        $user->save();
        return $this->showMessage('Logout berhasil', 200);
    }

    /**
     * *Creating a new account that needs to fulfill the profile after registrating in
     *
     * @param request mixed request that necessary to create a new user
     * @return response success if token is generated successfully, or else the error response will show out
     */
    public function createAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255|regex:/\w*$/|unique:users,username',
            'email' => 'required|email|unique:users,email|max:50',
            'fullname' => 'required|min:3|max:255',
            'nickname' => 'required|max:40|string',
            'datebirth' => 'required|date_format:Y-m-d',
            'phone' => 'required|string|min:5|max:15',
            'gender' => 'required|in:L,P',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // $password = Hash::make(env('MIX_SECRET_KEY_ENCRYPTION'));
            $password = Hash::make($request->password);
            $user = User::create([
                'username' => $request->username,
                'account_name' => $request->nickname,
                'email' => $request->email,
                'password' => $password,
                'status' => 1
            ]);

            $employee = Employee::create([
                'id_user' => $user->id,
                'fullname' => $request->fullname,
                'nickname' => $request->nickname,
                'gender' => $request->gender,
                'datebirth' => $request->datebirth,
                'phone' => $request->phone,
            ]);

            // $image = QrCode::format('png')->size(500)->errorCorrection('H')->generate(route('api.showUser', ['id' => $employee->id]));
            // $tujuan = 'public/Employees/qrcode/qr-' . strval($employee->id) . '_' . time() . '.png';
            // $output_file = 'storage/Employees/qrcode/qr-' . strval($employee->id) . '_' . time() . '.png';
            // $saveStorage = Storage::disk('local')->put($tujuan, $image);

            $encrypt = new Encryption();
            $token = $encrypt->encrypt($request->email, config('secretKey.secretKey'));
            $verifyEmail = DB::table('verify_email')->insert([
                'id_user' => $user->id,
                'email' => $request->email,
                'token' => $token,
                'email_isVerified' => 1
            ]);
            // Mail::to($request->email)->send(new CreateAccount($request->email, $request->fullname, $request->nickname, $token));

            // if ($saveStorage == true) {
            //     Employee::find($employee->id)->update([
            //         'qrcode' => $output_file,
            //         'qrcode_link' => $tujuan,
            //         'qrcode_link' => route('api.showUser', ['id' => $employee->id])
            //     ]);
            //     return $this->showMessage("We have e-mailed your password reset link! Mr./Mrs. " . $employee->fullname);
            // } else {
            //     return $this->errorResponse('Generate QRCode is failed. You can generate it on Profile later', 409);
            // }
            return $this->showMessage('Created Account successfully', 200);
        }
    }

    /**
     * *Manually Activate user and verify email
     *
     * @param String generated token from encrypted string email. This token would be decrypted into one piece of email address. Make sure the user uses the provided application to avoid any errors.
     * @return response success if the token and email are matched and activate the user.
     */
    public function verifyEmailManual($email)
    {
        $token = $email;
        $encrypt = new Encryption();
        $email = $encrypt->decrypt($email, config('secretKey.secretKey'));

        $user = User::where('username', '=', $email)->orWhere('email', '=', $email)->first();
        $employee = Employee::where('id_user', '=', $user->id)->first();

        // Check User Status
        if ($user->status == 4) {
            $error['user'] = ['We\'ve determined that this account is suspended for some reason'];
            return $this->errorResponse($error, 401);
        }

        $verifyData = DB::table('verify_email')->where('email', '=', $email)->where('id_user', '=', $user->id);
        if ($verifyData->get()->count() == 0) {
            $verifyEmail = DB::table('verify_email')->insert([
                'id_user' => $user->id,
                'email' => $user->email,
                'token' => $token
            ]);
            Mail::to($user->email)->send(new CreateAccount($user->email, $employee->fullname, $employee->nickname, $token));
            return $this->showMessage("We have just e-mailed your password reset link! Mr./Mrs. " . $employee->fullname);
        } else {
            $data = $verifyData->update([
                'email' => $email,
                'token' => $token
            ]);
            Mail::to($user->email)->send(new CreateAccount($user->email, $employee->fullname, $employee->nickname, $token));
            return $this->showMessage("We have e-mailed your password reset link again! Mr./Mrs. " . $employee->fullname);
        }
    }

    /**
     * *Activate user and verify email
     *
     * @param String generated token from encrypted string email. This token would be decrypted into one piece of email address. Make sure the user uses the provided application to avoid any errors.
     * @return response success if the token and email are matched and activate the user.
     */
    public function verifyEmail($token)
    {
        $encrypt = new Encryption();
        $email = $encrypt->decrypt($token, config('secretKey.secretKey'));

        if ($email == false) {
            return $this->errorResponse('Please return the exact generated token', 404);
        } else {
            $data = DB::table('verify_email')->where('email', '=', $email)->where('token', '=', $token);
            if ($data->get()->count() == 0) {
                return $this->errorResponse('Data does not exists', 404);
            } else if ($data->first()->email_isVerified == User::ACTIVE) {
                return $this->showMessage("Your account is already activated");
            }
            $user = $data->get()[0];

            // Activating User and Verifying Email
            $activeUser = User::find($user->id_user);
            // Check User Status
            if ($activeUser->status == 4) {
                $error['user'] = ['We\'ve determined that this account is suspended for some reason'];
                return $this->errorResponse($error, 401);
            }
            $activeUser->email_verified_at = Carbon::now();
            $activeUser->status = User::ACTIVE;
            $activeUser->save();
            $data = $data->update(['email_isVerified' => User::ACTIVE]);
            $employee = Employee::where('id_user', '=', $activeUser->id)->first();
            return $this->showMessage("Thank you and welcome in PowerShare Mr./Mrs. " . $employee->fullname);
        }
    }

    /**
     * * Showing Authenticated User Profile
     *
     * @param
     * @return
     */
    public function showProfile($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        // $user->employees;
        $followme = FollowUser::where('id_user', $id)->where('following_id', $user->id)->first();
        $followuser = FollowUser::where('id_user', $user->id)->where('following_id', $id)->first();
        $userData = User::with('employees')->find($id);
        if ($user->id != $id) {
            if ($followme) {
                $userData->follow_me = $followme->follow_status;
            } else {
                $userData->follow_me = 0;
            }
            if ($followuser) {
                $userData->follow_status = $followuser->follow_status;
            } else {
                $userData->follow_status = 0;
            }
        }


        // $user->followUser;
        // $following = DB::table('follow_users')->where('id_user', '=', $user->id)->get();
        // $user->following=$following;
        // $data = Employee::where('id_user', '=', $user->id)->get();
        return response()->json($userData);
    }

    /**
     * *Generate a Token for URL security pass to resetting the password
     *
     * @param request email
     * @param request username
     * @return response return success response if token is generated successfully, or else the error response will show out
     */
    public function generateKodeVerifikasi(Request $request)
    {
        $setPassword = false;
        // Validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'username' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        $encrypt = new Encryption();
        $username = $encrypt->decrypt($request->username, config('secretKey.secretKey'));
        $email = $encrypt->decrypt($request->email, config('secretKey.secretKey'));

        // Check if email is exist
        $user = User::where('email', '=', $email)->orWhere('username', $email)->get();
        if (count($user) == 0) {
            $error['user'] = ['Email not found'];
            return $this->errorResponse($error, 404);
        } else {
            $user = $user->first();
            if ($user->username != $username) {
                $error['user'] = ['The username is not matched with the given user\'s email'];
                return $this->errorResponse($error, 404);
            } else {
                $setPassword = true;
            }
        }

        // Check User Status
        $checkStatus = $this->checkUserStatus($user->first()->status);
        if ($checkStatus[0] != 200) {
            return $this->errorResponse($checkStatus[1], 401);
        }

        // Generate Token
        $token = Str::random(64);

        $time = Carbon::now()->subHour(24);
        $howMuchInADay = DB::table('password_resets')->where('id_user', '=', $user->id)->where('used', '=', 1);
        $howMuchInADay = $howMuchInADay->where('created_at', '>=', $time)->get();
        if ($howMuchInADay->count() > 3) {
            $setPassword = false;
            return $this->errorResponse('You have resetting your password many times... Please wait for 24 hours or less to reset your password again', 429);
        }
        // Check if there is a new
        // $time = Carbon::now()->subHour(env('PASSWORT_RESET_EXPIRE', 3));
        // $realUser = DB::table('password_resets')->where('token', '=', $token);
        // $dataResetPass = $realUser->where('created_at', '>=', $time)->get();

        if ($setPassword) {
            // Redundant Token Generate
            $hapus = DB::table('password_resets')->where('token', '=', $token)->where('used', '=', 0)->get();
            if (count($hapus)) {
                foreach ($hapus as $data) {
                    $data->delete();
                }
            }
            $employee = Employee::where('id_user', '=', $user->id)->first();

            DB::table('password_resets')->insert([
                'id_user' => $user->id,
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }

        return $this->showMessage("We have e-mailed your password reset link! Mr./Mrs. " . $employee->fullname);
    }

    public function changePassword(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        $encrypt = new Encryption();
        $password = $encrypt->decrypt($request->password, config('secretKey.secretKey'));

        $time = Carbon::now()->subHour(3);
        $realUser = DB::table('password_resets')->where('token', '=', $token)->where('used', '=', 0);
        $dataResetPass = $realUser->where('created_at', '>=', $time)->get();
        if ($dataResetPass->count()) {
            $realUser->update(['used' => 1]);
            $dataUser = User::where('id', '=', $dataResetPass[0]->id_user)->update(['password' => Hash::make($password)]);
            return $this->showMessage('Password reset successfully', 200);
        } else {
            return $this->showMessage('The generated token link is expired (more than 3 hours)', 401);
        }
    }

    public function checkUserStatus($userStatus)
    {
        if ($userStatus == 0) {
            $error['user'] = ['Your account is deactivated, please verify your email first or contact the admin'];
            return [401, $error];
        }
        if ($userStatus == 4) {
            $error['user'] = ['We\'ve determined that this account is suspended for some reason'];
            return [401, $error];
        }
        if ($userStatus == 1) {
            $error['user'] = ['User is activated'];
            return [200, $error];
        }
    }

    public function validatePassword(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        // $id=User::where('id',$user->id)->first();
        $pass = Hash::make($request->password);
        $encrypt = new Encryption();
        $password = $encrypt->decrypt($request->password, config('secretKey.secretKey'));
        // $cek = User::where('password','=',$pass);
        // Mengecek apakah password cocok
        if (!Hash::check($password, $user->password)) {
            return response()->json(['msg' => 'Password tidak cocok.'], 400);
        }

        // Jika password cocok
        return response()->json(['msg' => 'Password valid.'], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $encrypt = new Encryption();
        $password = $encrypt->decrypt($request->password, config('secretKey.secretKey'));

        $data = User::where('id', '=', $user->id)->first();
        if (!$data->count()) {
            return response()->json(['msg' => 'Tidak ada data'], 400);
        }
        $dataUser = User::where('id', '=', $user->id)->update(['password' => Hash::make($password)]);
        return response()->json(['msg' => 'Password berhasil diubah.'], 200);
    }

    protected function cekToken()
    {
        return response()->json([
            'expired_token' => JWTAuth::parseToken()->factory()->getTTL(),
        ]);
    }
}
