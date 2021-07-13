<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Constants\ResponseCode;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends BaseController
{
    protected $userRepository;
    protected $resCode;

    public function __construct(UserRepository $userRepository, ResponseCode $resCode)
    {
        $this->userRepository = $userRepository;
        $this->resCode = $resCode;
    }

    public function allUser()
    {
        $users = $this->userRepository->all();
        return $this->sendSuccess('All user', $users, $this->resCode::OK);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'unique:users|required',
            'name' => 'required',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendSuccess('Error', $validator->errors(), $this->resCode::NOT_FOUND);
        }
        $name = $request->name;
        $username = $request->username;
        $password = $request->password;
        $user = $this->userRepository->create([
            'name' => $name,
            'username' => $username,
            'password' => Hash::make($password)
        ]);
        return $this->sendSuccess(__('message.USER_CREATED'), $user, $this->resCode::OK);

    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required|alpha',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendSuccess('Error', $validator->errors(), $this->resCode::NOT_FOUND);
        }
        $name = $request->name;
        $avatar = $request->avatar;
        $role = $request->role;
        $password = $request->password;
        $user = $this->userRepository->update($id, [
            'name' => $name,
            'role' => $role,
            'avatar' => $avatar,
            'password' => Hash::make($password)
        ]);
        return $this->sendSuccess(__('message.USER_UPDATED'), $user, $this->resCode::OK);

    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->sendSuccess(__('message.INVALID_USER'), [], 500);
            }
        } catch (JWTException $e) {
            return $this->sendSuccess(__('message.TOKEN_FAILED'), [], $this->resCode::INTERNAL_SERVER_ERROR);
        }
        return $this->sendSuccess(__('message.TOKEN_SUCCESS'), compact('token'), $this->resCode::OK);
    }

    public function getUserInfo(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        return $this->sendSuccess('Information', $user, $this->resCode::OK);
    }

    public function delete($id)
    {
        $user = $this->userRepository->delete($id);
        return $this->sendSuccess(__('message.DELETED'), $user, $this->resCode::OK);
    }

    function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return $this->sendSuccess(__('message.LOGOUT_SUCCESS'), true, $this->resCode::OK);
        } catch (JWTException $e) {
            return $this->sendSuccess(__('message.LOGOUT_FAILED'), false, $this->resCode::INTERNAL_SERVER_ERROR);
        }
    }
}
