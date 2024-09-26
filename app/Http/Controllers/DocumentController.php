<?php

namespace App\Http\Controllers;

use App\Constants\ResponseCode;
use App\Http\Requests\Document\DocumentPostRequest;
use App\Repositories\Document\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends BaseController
{
    protected $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = $this->documentRepository->getDocs();

        return $this->sendSuccess(__('message.LIST'), $documents, ResponseCode::OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentPostRequest $request)
    {
        $file = $request->file('file') === null? null : Storage::putFile('public/document', $request->file('file'));
        $document = $this->documentRepository->create([
            'project_id' => $request->project_id,
            'filename' => $request->filename,
            'link' => $request->link,
            'file' => $file,
            'user_id' => Auth::id()
        ]);

        return $this->sendSuccess(__('message.CREATED'), $document, ResponseCode::OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $doc = $this->documentRepository->find($id);
        $oldFile = $doc->file;
        Storage::delete($oldFile);
        $file = $request->file('file') === null? null : Storage::putFile('public/document', $request->file('file'));
        $document = $this->documentRepository->update($id, [
            'filename' => $request->filename,
            'link' => $request->link,
            'file' => $file
        ]);

        return $this->sendSuccess(__('message.UPDATED'), $document, ResponseCode::OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = $this->documentRepository->find($id);
        $oldFile = $doc->file;
        Storage::delete($oldFile);
        $this->documentRepository->delete($id);
        
        return $this->sendSuccess(__('message.DELETED'), '', ResponseCode::OK);
    }
}
