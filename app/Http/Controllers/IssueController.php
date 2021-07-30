<?php

namespace App\Http\Controllers;

use App\Constants\ResponseCode;
use App\Http\Requests\Issue\IssuePostRequest;
use App\Http\Requests\Issue\IssuePutRequest;
use App\Repositories\Issue\IssueRepository;
use Illuminate\Http\Request;

class IssueController extends BaseController
{
    protected $issueRepository;

    public function __construct(IssueRepository $issueRepository)
    {
        $this->issueRepository = $issueRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $issues = $this->issueRepository->index($request->all());

        return $this->sendSuccess(__('message.LIST'), $issues, ResponseCode::OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Issue\IssuePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IssuePostRequest $request)
    {
        $issue = $this->issueRepository->create([
            'issue_name' => $request->issue_name,
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'deadline' => $request->deadline,
            'priority' => $request->priority
        ]);

        return $this->sendSuccess(__('message.CREATED'), $issue, ResponseCode::OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Issue\IssuePutRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IssuePutRequest $request, $id)
    {
        $issue = $this->issueRepository->update($id, [
            'issue_name' => $request->issue_name,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'deadline' => $request->deadline,
            'priority' => $request->priority
        ]);

        return $this->sendSuccess(__('message.UPDATED'), $issue, ResponseCode::OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->issueRepository->delete($id);

        return $this->sendSuccess(__('message.DELETED'), '', ResponseCode::OK);
    }
}
