<?php

namespace App\Repositories;
use App\Models\Category;

class CategoryRepository
{
    public function store(array $data)
    {
        return Category::create($data);
    }
    public function update(int $id, array $data)
    {
        return Category::where('id', $id)->update($data);
    }
    public function delete(int $id)
    {
        return Category::where('id', $id)->delete();
    }
    public function find(int $id)
    {
        return Category::find($id);
    }
    public function getAll()
    {
        return Category::all();
    }
}