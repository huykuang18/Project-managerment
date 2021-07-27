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
    public function index(Request $request)
    {
        $projects = $this->projectRepository->filter($request->all());
        return $this->sendSuccess(__('message.LIST'), $projects, ResponseCode::OK);
    }

    /**
     * Post data to create new a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(ProjectPostRequest $request)
    {
        $manager = Auth::id();
        $dateStart = $request->date_start;
        $dateEnd = $request->date_end;
        $itendTime = round((strtotime($dateEnd) - strtotime($dateStart)) / (30 * 60 * 60 * 24), 0);
        $project = $this->projectRepository->create([
            'project_name' => $request->project_name,
            'manager_id' => $manager,
            'customer' => $request->customer,
            'description' => $request->description,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'intend_time' => $itendTime,
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
    public function update($id, ProjectPutRequest $request)
    {
        $dateStart = $request->date_start;
        $dateEnd = $request->date_end;
        $itendTime = round((strtotime($dateEnd) - strtotime($dateStart)) / (30 * 60 * 60 * 24), 0);
        $project = $this->projectRepository->update($id, [
            'project_name' => $request->project_name,
            'description' => $request->description,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'intend_time' => $itendTime
        ]);
        return $this->sendSuccess(__('message.UPDATED'), $project, ResponseCode::OK);
    }

    /**
     * Delete a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find(Auth::id());
        $user->projects()->detach($id);
        $this->projectRepository->delete($id);
        return $this->sendSuccess(__('message.DELETED'), '', ResponseCode::OK);
    }
}
