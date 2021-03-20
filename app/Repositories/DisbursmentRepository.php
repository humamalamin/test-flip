<?php

namespace App\Repositories;

use App\Models\Disbursment;
use Illuminate\Http\Request;

class DisbursmentRepository
{
    /**
     * Eloquent model instance
     */
    protected $model;

    /**
     * Load default class depedencies.
     *
     * @param Model model Illuminate\Database\Eloquent\Model
     */
    public function __construct(Disbursment $model)
    {
        $this->model = $model;
    }

     /**
      * Get all item collections from database
      *
      * @return Collection of items
      */
    public function getAll()
    {
        return $this->model->get();
    }

     /**
      * Get item collection by id
      *
      * @return single item
      */
    public function getByID($disbursmentID)
    {
        return $this->model->findOrFail($disbursmentID);
    }

     /**
      * Create new record
      *
      * @param Request $request Illuminate\Http\Request
      * @return saved model object with data
      */
    public function store(Request $request)
    {
        $data = $this->setDataPayload($request);
        $item = $this->model;
        $item->fill($data);
        $item->save();

        return $item;
    }


    /**
     * Set data for saving
     *
     * @param  Request $request Illuminate\Http\Request
     * @return array of data.
     */
    protected function setDataPayload(Request $request)
    {
        return $request->all();
    }


}