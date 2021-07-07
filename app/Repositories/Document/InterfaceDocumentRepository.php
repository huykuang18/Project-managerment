<?php
namespace App\Repositories\Document;

use App\Repositories\InterfaceBaseRepository;

interface InterfaceDocumentRepository extends InterfaceBaseRepository
{
    public function all();

    public function create($data = []);

    public function update($id, $data = []);
 
    public function delete($id);
    
    public function find($id);
}