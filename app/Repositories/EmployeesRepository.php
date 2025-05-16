<?php

namespace App\Repositories;
use App\Models\Employee;

class EmployeesRepository
{
    public function store(array $data)
    {
        return Employee::create($data);
    }

    public function update(int $id, array $data)
    {
        return Employee::where('id', $id)->update($data);
    }

    public function delete(int $id)
    {
        return Employee::where('id', $id)->delete();
    }

    public function find(int $id)
    {
        return Employee::find($id);
    }

    public function getAll()
    {
        return Employee::all();
    }
}