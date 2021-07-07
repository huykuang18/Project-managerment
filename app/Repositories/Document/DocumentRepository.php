<?php
namespace App\Repositories\Document;

use App\Models\Document;
use App\Repositories\BaseRepository;

class DocumentRepository extends BaseRepository implements InterfaceDocumentRepository
{
    protected $document;
 
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
 
    public function all()
    {
        return $this->document->get();
    }

    public function create($data = [])
    {
        return $this->document->create($data);
    }

    public function update($id, $data = [])
    {
        $record = $this->document->findOrFail($id);
 
        return $record->update($data);
    }
 
    public function delete($id)
    {
        return $this->document->destroy($id);
    }
 
    public function find($id)
    {
        return $this->document->find($id);
    }
}