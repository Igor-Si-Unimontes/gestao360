<?php
namespace App\Services;

use App\Repositories\SupplierRepository;
use Exception;
class SupplierService
{
    protected $repository;
    public function __construct(SupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }
    public function update(int $id, array $data)
    {
        $supplier = $this->repository->find($id);
        if(!$supplier)
        {
            throw new Exception("Fornecedor nao encontrado !");
        }
        return $this->repository->update($id,$data);
    }
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
    public function find(int $id)
    {
        return $this->repository->find($id);
    }
    public function getAll()
    {
        return $this->repository->getAll();
    }
}