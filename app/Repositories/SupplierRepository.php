<?php
namespace App\Repositories;
use App\Models\Supplier;
class SupplierRepository
{
    public function store(array $data)
    {
        return Supplier::create($data);
    }
    public function update(int $id, array $data)
    {
        return Supplier::where('id', $id)->update($data);
    }
    public function delete(int $id)
    {
        return Supplier::where('id', $id)->delete();
    }
    public function find(int $id)
    {
        return Supplier::find($id);
    }
    public function getAll()
    {
        return Supplier::all();
    }
}
