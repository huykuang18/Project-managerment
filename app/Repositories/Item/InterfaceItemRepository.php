<?php
namespace App\Repositories\Item;

use App\Repositories\InterfaceBaseRepository;

interface InterfaceItemRepository extends InterfaceBaseRepository
{
    public function all();

    public function create($data = []);

    public function update($id, $data = []);
 
    public function delete($id);
    
    public function find($id);
}