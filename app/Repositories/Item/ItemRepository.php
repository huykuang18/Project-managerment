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

  public function getItems($params)
  {
    $projectId = $params["project_id"];
    $parentId = isset($params["parent_id"]) ?? null;
    if ($parentId) {
      $items = $this->item::where([['project_id', $projectId], ['parent_id', $parentId]]);
    } else {
      $items = $this->item::where([['project_id', $projectId], ['parent_id', 0]]);
    }

    return $items->orderBy('order', 'asc')->get();
  }

  public function create($data = [])
  {
    return $this->item->create($data);
  }

  public function update($params, $data = [])
  {
    $id = $params["id"];
    $record = $this->item->findOrFail($id);

    return $record->update($data);
  }

  public function delete($id)
  {
    $item = $this->item->findOrFail($id);
    if ($item["parent_id"] !== 0 ) {
      return $this->item->destroy($id);
    } else {
      $this->item::where('parent_id',$id)->delete();
      return $this->item->destroy($id);
    }
  }

  public function find($id)
  {
    return $this->item->find($id);
  }
}
