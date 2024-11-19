<?php

namespace App\Repositories;

use App\Models\Resource;

class ResourceRepository
{
    public function find($id)
    {
        return Resource::find($id);
    }

    public function all()
    {
        return Resource::all();
    }

    public function create(array $data)
    {
        return Resource::create($data);
    }
}


