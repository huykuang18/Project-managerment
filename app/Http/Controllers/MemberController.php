<?php

namespace App\Http\Controllers;


use App\Repositories\Project\ProjectRepository;
use App\Constants\ResponseCode;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class MemberController extends BaseController
{
    protected $projectRepository;
    protected $userRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get data all members of a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function index($projectId)
    {
        $members = $this->projectRepository->detail($projectId);
        return $this->sendSuccess(__('message.LIST'), $members, ResponseCode::OK);
    }

    /**
     * Add a new member to a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store($projectId,Request $request)
    {
        $project = $this->projectRepository->find($projectId);
        $user = $this->userRepository->find($request->user_id);
        $dev = $user['role'] === __('message.ROLE_DEVELOPER') ? 1 : 0;
        $test = $user['role'] === __('message.ROLE_TESTER') ? 1 : 0;
        $project->users()->attach($request->user_id);
        $this->projectRepository->update($projectId, [
            'dev_quantity' => $project->dev_quantity + $dev,
            'test_quantity' => $project->test_quantity + $test
        ]);
        return $this->sendSuccess(__('message.CREATED'), $user, ResponseCode::OK);
    }

    /**
     * Remove a member from a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function remove($projectId, $memberId)
    {
        $project = $this->projectRepository->find($projectId);
        $user = $this->userRepository->find($memberId);
        $dev = $user['role'] === __('message.ROLE_DEVELOPER') ? 1 : 0;
        $test = $user['role'] === __('message.ROLE_TESTER') ? 1 : 0;
        $project->users()->detach($memberId);
        $this->projectRepository->update($projectId, [
            'dev_quantity' => $project->dev_quantity - $dev,
            'test_quantity' => $project->test_quantity - $test
        ]);
        return $this->sendSuccess(__('message.DELETED'), $project, ResponseCode::OK);
    }
}
