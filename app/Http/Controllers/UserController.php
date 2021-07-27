<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Constants\ResponseCode;
use App\Http\Requests\User\UserPostRequest;
use App\Http\Requests\User\UserPutRequest;
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

    /**
     * Get all user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filterUser(Request $request)
    {
        $users = $this->userRepository->filter($request->all());
        return $this->sendSuccess(__('message.LIST'), $users, $this->resCode::OK);
    }

    public function allUser(Request $request)
    {
        $users = $this->userRepository->allNotIn($request->all());
        return $this->sendSuccess(__('message.LIST'), $users, $this->resCode::OK);
    }

    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function register(UserPostRequest $request)
    {
        $name = $request->name;
        $username = $request->username;
        $password = $request->password;
        $role = $request->role;
        $user = $this->userRepository->create([
            'name' => $name,
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role
        ]);
        return $this->sendSuccess(__('message.CREATED'), $user, $this->resCode::OK);

    }

    /**
     * Put data to update a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update($id, UserPutRequest $request)
    {
        $name = $request->name;
        $role = $request->role;
        $user = $this->userRepository->update($id, [
            'name' => $name,
            'role' => $role,
        ]);
        return $this->sendSuccess(__('message.UPDATED'), $user, $this->resCode::OK);

    }

    /**
     * Post and check data to login to home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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

    /**
     * Get the user infomation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getUserInfo(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        return $this->sendSuccess('Information', $user, $this->resCode::OK);
    }

    /**
     * Delete a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function delete($id)
    {
        $user = $this->userRepository->delete($id);
        return $this->sendSuccess(__('message.DELETED'), '', $this->resCode::OK);
    }

    /**
     * Remove token to logout user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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
