<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData=$request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if(!Auth::attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }else {
            $email = $request->all('email');
            $customer=DB::table('customer')->select('nama_customer')->join('users', 'customer.id', '=', 'users.id')->where('customer.email_customer', $email)->whereNotNull('users.created_at')->get();
            $driver=DB::table('driver')->select('nama_driver')->join('users', 'driver.id', '=', 'users.id')->where('driver.email_driver', $email)->whereNotNull('users.created_at')->get();
            $pegawai=DB::table('pegawai')->select('nama_pegawai')->join('users', 'pegawai.id', '=', 'users.id')->where('pegawai.email_pegawai', $email)->where('pegawai.id_jabatan', 1)->whereNotNull('users.created_at')->get();

            if(count($customer)>0) {
                $user = Auth::user();
                $id = $user->id;
                $token = $user->createToken('Authentication Token')->accessToken;

                return response([
                    'message' => 'Authenticated as Customer',
                    'user' => $user,
                    'id' => $id,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ]);

            }else if(count($driver)>0) {
                $user = Auth::user();
                $id = $user->id;
                $token = $user->createToken('Authentication Token')->accessToken;

                return response([
                    'message' => 'Authenticated as Driver',
                    'user' => $user,
                    'id' => $id,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ]);

            }else if(count($pegawai)>0) {
                $user = Auth::user();
                $id = $user->id;
                $token = $user->createToken('Authentication Token')->accessToken;

                return response([
                    'message' => 'Authenticated as Manager',
                    'user' => $user,
                    'id' => $id,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ]);

            }else {
                return response(['message' => 'Authorization Failed'], 401);
            }
        }

    }
}
