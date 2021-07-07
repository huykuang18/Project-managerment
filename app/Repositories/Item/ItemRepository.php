<?php
namespace App\Repositories\Item;

use App\Models\Item;
use App\Repositories\BaseRepository;

class ItemRepository extends BaseRepository implements InterfaceItemRepository
{
    protected $item;
 
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
 
    public function all()
    {
        return $this->item->get();
    }

    public function create($data = [])
    {
        return $this->item->create($data);
    }

    public function update($id, $data = [])
    {
        $record = $this->item->findOrFail($id);
 
        return $record->update($data);
    }
 
    public function delete($id)
    {
        return $this->item->destroy($id);
    }
 
    public function find($id)
    {
        return $this->item->find($id);
    }
}