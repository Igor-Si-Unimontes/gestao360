<?php

namespace App\Services;
use App\Repositories\EmployeesRepository;
use Exception;

class EmployeesService
{
    protected $repository;
    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function update(int $id, array $data)
    {
        $employees = $this->repository->find($id);
        if(!$employees)
        {
            throw new Exception("Funcionário nâo encontrado !");
        }
        $this->repository->update($id,$data);
        return $this->find($id);
    }

    public function delete(int $id)
    {
        $employees = $this->repository->find($id);
        if(!$employees)
        {
            throw new Exception("Funcionário nâo encontrado !");
        }
        return $this->repository->delete($id);
    }

    public function find(int $id)
    {
        $employees = $this->repository->find($id);
        if(!$employees)
        {
            throw new Exception("Funcionário nâo encontrado !");
        }
        return $employees;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }
}