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
    public function index(Request $request)
    {
        $items = $this->itemRepository->getItems($request->all());
        return $this->sendSuccess(__('message.LIST'), $items, ResponseCode::OK);
    }

    /**
     * Post data to create new a item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(ItemPostRequest $request)
    {
        $item = $this->itemRepository->create([
            'name' => $request->name,
            'order' => $request->order,
            'parent_id' => $request->parent_id,
            'project_id' => $request->project_id
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
        $item = $this->itemRepository->update($request->all(), [
            'name' => $request->name,
            'order' => $request->order,
            'parent_id' => $request->parent_id
        ]);
        return $this->sendSuccess(__('message.UPDATED'), $item, ResponseCode::OK);
    }

    /**
     * Delete a item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function destroy($id)
    {
        $item = $this->itemRepository->delete($id);
        return $this->sendSuccess(__('message.DELETED'),'', ResponseCode::OK);
    }
}
