<?php

namespace App\Http\Controllers;

use App\Constants\ResponseCode;
use App\Http\Requests\Item\ItemPostRequest;
use App\Repositories\Item\ItemRepository;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    protected $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Get data all items in a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getItem(Request $request)
    {
        $items = $this->itemRepository->getFromProject($request->all());
        return $this->sendSuccess(__('message.LIST'), $items, ResponseCode::OK);
    }

    /**
     * Post data to create new a item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function createItem($project_id, ItemPostRequest $request)
    {
        $name = $request->name;
        $order = $request->order;
        $parent_id = $request->parent_id;
        $item = $this->itemRepository->create([
            'name' => $name,
            'order' => $order,
            'parent_id' => $parent_id,
            'project_id' => $project_id
        ]);
        return $this->sendSuccess(__('message.CREATED'), $item, ResponseCode::OK);
    }

    /**
     * Put data to update a item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update(ItemPostRequest $request)
    {
        $name = $request->name;
        $order = $request->order;
        $parent_id = $request->parent_id;
        $item = $this->itemRepository->update($request->all(), [
            'name' => $name,
            'order' => $order,
            'parent_id' => $parent_id
        ]);
        return $this->sendSuccess(__('message.UPDATED'), $item, ResponseCode::OK);
    }

    /**
     * Delete a item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function delete($id)
    {
        $item = $this->itemRepository->delete($id);
        return $this->sendSuccess(__('message.DELETED'),'', ResponseCode::OK);
    }
}
