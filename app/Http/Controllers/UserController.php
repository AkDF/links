<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function __construct() {
        //
    }

    function register(Request $request) {

        $rules = [
            'login' => 'required',
            'email' => 'required',
            'password' => 'required',
        ];

        $customMessages = [
            'required' => 'Please fill attribute :attribute'
        ];
        $this->validate($request, $rules, $customMessages);

        try {
            $hasher = app()->make('hash');
            $username = $request->input('login');
            $email = $request->input('email');
            $password = $hasher->make($request->input('password'));

            $save = User::create([
                'login' => $username,
                'email' => $email,
                'password' => $password,
                'api_token' => ''
            ]);
            $res['status'] = true;
            $res['message'] = 'Registration success!';
            return response($res, 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['status'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }

    }

    function login(Request $request) {

        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $customMessages = [
            'required' => ':attribute is required'
        ];
        $this->validate($request, $rules, $customMessages);
        $email = $request->input('email');
        try {
            $login = User::where('email', $email)->first();
            if ($login) {
                if ($login->count() > 0) {
                    if (Hash::check($request->input('password'), $login->password)) {
                        try {
                            $api_token = sha1($login->id . time());

                            $create_token = User::where('id', $login->id)->update(['api_token' => $api_token]);
                            $res['status'] = true;
                            $res['message'] = 'Success login';
                            $res['data'] = $login;
                            $res['api_token'] = $api_token;

                            return response($res, 200);


                        } catch (\Illuminate\Database\QueryException $ex) {
                            $res['status'] = false;
                            $res['message'] = $ex->getMessage();
                            return response($res, 500);
                        }
                    } else {
                        $res['success'] = false;
                        $res['message'] = 'Username / email / password not found';
                        return response($res, 401);
                    }
                } else {
                    $res['success'] = false;
                    $res['message'] = 'Username / email / password  not found';
                    return response($res, 401);
                }
            } else {
                $res['success'] = false;
                $res['message'] = 'Username / email / password not found';
                return response($res, 401);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['success'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }

    }

    function logout(Request $request) {

        if (User::logout($request->header('x-api-key'))) {
            $res['success'] = true;
            $res['message'] = 'Logout has been successful';
            return response($res, 200);
        } else {
            $res['success'] = false;
            $res['message'] = 'Logout failed';
            return response($res, 500);
        }
    }

    function users() {
        $user = User::all();
        if ($user) {
            $res['status'] = true;
            $res['message'] = $user;

            return response($res);
        } else {
            $res['status'] = false;
            $res['message'] = 'Cannot find user!';

            return response($res);
        }

    }

    function update(Request $request) {

    }


}
