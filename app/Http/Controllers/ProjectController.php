<?php

namespace App\Http\Controllers;


use App\Repositories\Project\ProjectRepository;
use App\Constants\ResponseCode;
use App\Http\Requests\Project\ProjectPostRequest;
use App\Http\Requests\Project\ProjectPutRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends BaseController
{
    protected $projectRepository;
    protected $userRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get data all projects.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getProject(Request $request)
    {
        $projects = $this->projectRepository->filter($request->all());
        return $this->sendSuccess(__('message.LIST'), $projects, ResponseCode::OK);
    }

    /**
     * Get data all members of a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getMember(Request $request)
    {
        $members = $this->projectRepository->detail($request->all());
        return $this->sendSuccess(__('message.LIST'), $members, ResponseCode::OK);
    }

    /**
     * Post data to create new a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function createProject(ProjectPostRequest $request)
    {
        $validated = $request->validated();
        $project_name = $validated["project_name"];
        $manager = Auth::id();
        $customer = $validated["customer"];
        $description = $validated["description"];
        $date_start = $validated["date_start"];
        $date_end = $validated["date_end"];
        $intend_time = round((strtotime($date_end) - strtotime($date_start)) / (30 * 60 * 60 * 24), 0);
        $project = $this->projectRepository->create([
            'project_name' => $project_name,
            'manager_id' => $manager,
            'customer' => $customer,
            'description' => $description,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'intend_time' => $intend_time,
            'dev_quantity' => 0,
            'test_quantity' => 0
        ]);
        $new_project = $this->projectRepository->new();
        $new_project->users()->attach($manager);
        return $this->sendSuccess(__('message.CREATED'), $project, ResponseCode::OK);
    }

    /**
     * Put data to update a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update(ProjectPutRequest $request)
    {
        $validated = $request->validated();
        $project_name = $validated["project_name"];
        $description = $validated["description"];
        $date_start = $validated["date_start"];
        $date_end = $validated["date_end"];
        $intend_time = round((strtotime($date_end) - strtotime($date_start)) / (30 * 60 * 60 * 24), 0);
        $project = $this->projectRepository->update($request->all(), [
            'project_name' => $project_name,
            'description' => $description,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'intend_time' => $intend_time
        ]);
        return $this->sendSuccess(__('message.UPDATED'), $project, ResponseCode::OK);
    }

    /**
     * Delete a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function delete($id)
    {
        $user = $this->userRepository->find(Auth::id());
        $user->projects()->detach($id);
        $project = $this->projectRepository->delete($id);
        return $this->sendSuccess(__('message.DELETED'), $project, ResponseCode::OK);
    }

    /**
     * Add a new member to a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function addMember(Request $request)
    {
        $project = $this->projectRepository->find($request->all());
        $user = $this->userRepository->find($request->user_id);
        $dev = $user['role'] === __('message.ROLE_DEVELOPER') ? 1 : 0;
        $test = $user['role'] === __('message.ROLE_TESTER') ? 1 : 0;
        $project->users()->attach($request->user_id);
        $this->projectRepository->update($request->all(), [
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
    public function removeMember($member_id, Request $request)
    {
        $project = $this->projectRepository->find($request->all());
        $user = $this->userRepository->find($member_id);
        $dev = $user['role'] === __('message.ROLE_DEVELOPER') ? 1 : 0;
        $test = $user['role'] === __('message.ROLE_TESTER') ? 1 : 0;
        $project->users()->detach($member_id);
        $this->projectRepository->update($request->all(), [
            'dev_quantity' => $project->dev_quantity - $dev,
            'test_quantity' => $project->test_quantity - $test
        ]);
        return $this->sendSuccess(__('message.DELETED'), $project, ResponseCode::OK);
    }
}
