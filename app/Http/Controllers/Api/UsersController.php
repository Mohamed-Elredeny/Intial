<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Mail\ContactEmail;
use App\Models\Admin;
use App\Models\Employee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    use GeneralTrait;

    public function login(Request $request)
    {
        if($request->type == 'admin') {
            $guard = 'admin';
        }elseif( $request->type == 'user') {
            $guard = 'user';
        }elseif($request->type == 'employee'){
            $guard = 'employee';
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $credentials = $request->only('email', 'password');
            if($request->type == 'admin') {
                $user = Admin::where('email', $request->email)->first();
            }elseif( $request->type == 'user') {
                $user = User::where('email', $request->email)->first();
            }elseif($request->type == 'employee'){
                $user = Employee::where('email', $request->email)->first();
            }
//            return $this->returnSuccessMessage('Token',200);
            if (!$user || !Hash::check($request->password, $user->password)) {
                if($request->type == 'admin') {
                    $userPhone = Admin::where('phone', $request->email)->first();
                }elseif( $request->type == 'user') {
                    $userPhone = User::where('phone', $request->email)->first();
                }elseif($request->type == 'employee'){
                    $userPhone = Employee::where('phone', $request->email)->first();
                }

                if (!$userPhone || !Hash::check($request->password, $userPhone->password)) {
                    return $this->returnError(201, 'email or phone or password is not correct ');
                } else {
                    $user = $userPhone;
                    $access = 1;
                }
                //   return redirect('login')->with('error', trans('frontend.email_or_password_is_not_correct'));
            } elseif ($user->status && Auth::guard($guard)->attempt($credentials, $request->remember)) {
                $access = 1;
            } else {
                $token = $user->token;
                return $this->returnError(201, 'Please Active Your Account');
            }
            if ($access == 1) {

                $token = Str::random(60);
                 $user->update([
                     'remember_token'=>$token
                 ]);
                return $this->returnData(['user'], [$user], 'User Details');
            }
        }

    }

    public function phoneCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $rand = mt_rand(10000, 99999);
            $phone = $request->phone;
            $type = $request->type;
            $users = User::where('phone', $phone)->get();
            if ($type == 'register') {
                if (count($users) > 0) {
                    return [
                        'status' => false,
                        'code' => 200,
                        'msg' => 'Phone Already Exist',
                        'verification_code' => ''
                    ];
                } else {
                    return [
                        'status' => true,
                        'code' => 200,
                        'msg' => 'This phone number is valid',
                        'verification_code' => $rand
                    ];
                }
            } elseif ($type == 'reset') {
                if (count($users) > 0) {
                    $rand = mt_rand(10000, 99999);
                    $users[0]->update([
                        'verification_code' => $rand
                    ]);
                    return [
                        'status' => true,
                        'code' => 200,
                        'msg' => 'Check Your Phone And Enter the code',
                        'verification_code' => $rand
                    ];


                } else {
                    return [
                        'status' => false,
                        'code' => 200,
                        'msg' => 'Phone Not Found',
                        'verification_code' => ''
                    ];

                }
            }

        }
    }

    public function logout(Request $request)
    {
        $token123 = Str::random(60);
        $token = $request->header('token') ;
        if($request->header('type') == 'admin') {
            $user = Admin::where('remember_token', $token)->get();
        }elseif($request->header('type')  == 'user') {
            $user = User::where('remember_token', $token)->get();
        }elseif($$request->header('type')  == 'employee'){
            $user = Employee::where('remember_token', $token)->get();
        }
        if (count($user) > 0) {
            Auth::logout();
            $user[0]->update([
                'remember_token' => $token123
            ]);

            return $this->returnSuccessMessage('User logged out successfully', 200);
        } else {
            return $this->returnError(201, 'User Not Found');
        }

    }

    public function register(Request $request)
    {
        $rand = mt_rand(10000, 99999);
        $token = Str::random(60);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:255',
            'phone' => 'required|string|min:9|unique:'.$request->type .'s',
            'email' => 'required|string|email|min:5|max:255|unique:'.$request->type .'s',
            'password' => 'required|string|min:8',
            'image'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $image = $this->uploadImage($request, 'image');
            if($request->type == 'client') {
                $user = User::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'image'=>$image
                ]);
            }elseif($request->type == 'employee'){
                $user = Employee::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'image'=>$image,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
            }elseif($request->type == 'admin'){
                $user = Admin::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'image'=>$image,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
            }

                return $this->returnData(['user'], [$user], 'User Details');
            // return $this->returnSuccessMessage(trans('frontend.your_has_registered_successfully_activation_code_has_been_sent_to_your_email'),200);

        }


    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('verification_code', $request->verification_code)->where('phone', $request->phone)->get();
        if (count($user) > 0) {
            $user[0]->update([
                'status' => 1
            ]);
            return $this->returnSuccessMessage('Your Account has been verified successfully', 200);
        } else {
            return $this->returnError(200, 'Make Sure you have entered a correct code');

        }
    }

    public function profile(Request $request)
    {
        if ($this->validToken($request) == 1) {
            // return $this->returnError(201,'Please Active Your Account');

            return $this->returnData(['user'], [$this->user], 'User Details');
        } elseif ($this->validToken($request) == 0) {
            return $this->returnError(201, 'Unauthorized User');
        } else {
            return $this->returnError(201, 'Please Active Your Account');
        }
    }

    public function uploadVideo(Request $request, $filename)
    {

        $filename = strval($filename);
        if ($request->hasFile($filename)) {
            //  Let's do everything here
            //
            $extension = $request->file($filename)->extension();
            $image = time() . '.' . $request->file($filename)->getClientOriginalExtension();
            $request->file($filename)->move(public_path('/frontend/images'), $image);
            return $image;

        }
    }

    public function updateProfile(Request $request)
    {
        if ($this->validToken($request) == 1) {

            // return $this->returnError(201,'Please Active Your Account');
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:5|max:255',
                'phone' => 'required|string|min:9|unique:users,phone,' . $this->user->id,
                'address' => 'required|string|min:4',
                'id_number' => 'required|string|min:10|unique:users,id_number,' . $this->user->id,
                'birthday' => 'required',
                'email' => 'required|string|email|min:5|max:255|unique:users,email,' . $this->user->id,
                'password' => 'string|min:8',
            ]);
            if ($validator->fails()) {
                return $this->returnValidationError(422, $validator);
            } else {
                $filename = 'avatar';
                $image = $this->uploadVideo($request, $filename);
                $dataUpdated = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'id_number' => $request->id_number,
                    'gender' => $request->gender,
                    'birthday' => date('Y-m-d', $request->birthday),
                    'email' => $request->email,
                    'address' => $request->address,
                    'avatar' => $image

                ];
                if ($request->has('password') && $request->get('password')) {
                    $dataUpdated['password'] = $request->password;
                }
                $user = User::find($this->user->id);
                $user->update($dataUpdated);


                $bank_account = UserBank::where('user_id', $user->id)->get();
                if (count($bank_account) == 0) {
                    $user->bank_account = new empyt();
                } else {
                    $user->bank_account = $bank_account;
                }
                $new = 1000 * strtotime($user->birthday);
                unset($user->birthday);
                $user->birthday_unix = $new;

                $user->monthly_commission = Tax::find(6)->percentage;
                return $this->returnData(['user'], [$user], 'User Details');

            }

        } elseif ($this->validToken($request) == 0) {
            return $this->returnError(201, 'Unauthorized User');
        } else {
            return $this->returnError(201, 'Please Active Your Account');
        }


    }

    public function forgetPass(Request $request)
    {
        $user = User::where('phone', $request->email)->get();
        if (count($user) > 0) {
            /*  $objDemo = new \stdClass();
              $objDemo->urlVerfiy = 'Code : ' . $user[0]->token ;
              //url('api/user/verify/' . $token);
              $objDemo->name = $user[0]->name;
              Mail::to($request->email)->send(new DemoEmailApiForegtPassword($objDemo));*/
            $rand = mt_rand(10000, 99999);
            $user[0]->update([
                'verification_code' => $rand
            ]);
            return $this->returnSuccessMessage(
                [
                    'msg' => 'Check Your Phone And Enter the code',
                    'verification_code' => $rand
                ], 200);
        } else {
            return $this->returnError(200, 'Phone Not Found');

        }
    }

    public function resetPass(Request $request)
    {
        if ($request->action == 'forget') {
            $validator = Validator::make($request->all(), [
                'action' => 'required',
                'phone' => 'required',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->returnValidationError(422, $validator);
            } else {
                $user = User::where('phone', $request->phone)->get();
                if (count($user) > 0) {
                    $rand = mt_rand(10000, 99999);
                    if ($request->resetCode == $user[0]->verification_code) {

                        if ($request->has('password') && $request->get('password')) {
                            $dataUpdated['password'] = $request->password;
                        }
                        User::find($user[0]->id)->update($dataUpdated);
                        return [
                            'status' => true,
                            'code' => 200,
                            'msg' => 'Your activation code is ok',
                            'verification_code' => $request->resetCode
                        ];
                    } else {
                        return [
                            'status' => true,
                            'code' => 200,
                            'msg' => 'Wrong Code check Your Phone And Enter the code again',
                            'verification_code' => $request->resetCode
                        ];
                    }
                } else {
                    return [
                        'status' => false,
                        'code' => 201,
                        'msg' => 'Phone Not Found',
                        'verification_code' => ''
                    ];
                }
            }

        } elseif ($request->action == 'reset') {
            $user = User::where('phone', $request->phone)->get();
            if (count($user) > 0) {

                if (!Hash::check($request->old_password, $user[0]->password)) {
                    return [
                        'status' => false,
                        'code' => 201,
                        'msg' => 'Old Password is not match',
                        'verification_code' => ''
                    ];
                }
                if ($request->has('password') && $request->get('password')) {
                    $dataUpdated['password'] = $request->password;
                }
                User::find($user[0]->id)->update($dataUpdated);

                return [
                    'status' => true,
                    'code' => 200,
                    'msg' => 'Your Account has been verified successfully',
                    'verification_code' => ''
                ];
            } else {
                return [
                    'status' => false,
                    'code' => 201,
                    'msg' => 'Code is not correct please check your phone',
                    'verification_code' => ''
                ];

            }
        } else {
            return [
                'status' => false,
                'code' => 201,
                'msg' => 'Phone Not Found',
                'verification_code' => ''
            ];
        }


    }

    public function contact(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'subject' => 'required',
            'phone' => 'required',
            'message' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $objDemo = 'hi';
            Mail::to('mohamedelredeny1@gmail.com')->send(new ContactEmail($objDemo));
            return $this->returnSuccessMessage('Email Sent Successfully', 200);
        }
    }

    public function bankAccount(Request $request)
    {
        if ($this->validToken($request) == 1) {
            // return $this->returnError(201,'Please Active Your Account');
            $validator = Validator::make($request->all(), [
                'action' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->returnValidationError(422, $validator);
            } else {
                $exist = UserBank::where('user_id', $this->user->id)->get();
                switch ($request->action) {
                    case 'view':
                        if (count($exist) > 0) {
                            return $this->returnData(['User Accounts'], [$exist[0]], 'User Account Details');
                        } else {
                            return $this->returnError(201, 'Add Your Bank Account');
                        }
                    case 'update':
                        $validator = Validator::make($request->all(), [
                            'bank_name' => 'required',
                            'account_name' => 'required',
                            'iban' => 'required',
                            'account_number' => 'required',
                        ]);
                        if ($validator->fails()) {
                            return $this->returnValidationError(422, $validator);
                        } else {
                            if (count($exist) > 0) {
                                $dataUpdated = [
                                    'bank_name' => $request->bank_name,
                                    'account_name' => $request->account_name,
                                    'iban' => $request->iban,
                                    'account_number' => $request->account_number,
                                ];
                                UserBank::find($exist[0]->id)->update($dataUpdated);
                                return $this->returnSuccessMessage('Your Account has been Updated successfully', 200);
                            } else {
                                UserBank::create([
                                    'bank_name' => $request->bank_name,
                                    'account_name' => $request->account_name,
                                    'iban' => $request->iban,
                                    'account_number' => $request->account_number,
                                    'user_id' => $this->user->id
                                ]);
                                return $this->returnSuccessMessage('User Account has been created  Successfully', 200);
                            }
                        }
                }
            }

        } elseif ($this->validToken($request) == 0) {
            return $this->returnError(201, 'Unauthorized User');
        } else {
            return $this->returnError(201, 'Please Active Your Account');
        }
    }

    public function settings()
    {
        $settings = Setting::get();
        foreach ($settings as $set) {
            unset(
                $set->created_at,
                $set->updated_at,
                $set->deleted_at
            );
        }
        return $this->returnData(['settings'], [$settings]);
    }

    public function validToken(Request $request)
    {
        $token = $request->header('token');
        $type = $request->header('type');
        if($type == 'user') {
            $exist = User::where('remember_token', $token)->get();
        }elseif($type == 'employee'){
            $exist = Employee::where('remember_token', $token)->get();
        }elseif($type == 'admin'){
            $exist = Admin::where('remember_token', $token)->get();
        }
        if (count($exist) > 0) {
            foreach ($exist as $ex) {
                if ($ex->status == 0) {
                    return 2;
                    //2 please active your account
                }
            }
            $this->user = $exist[0];
            return 1;
        } else {
            return 0;
        }
    }
}
