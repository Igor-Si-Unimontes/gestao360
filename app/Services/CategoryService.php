<?php

namespace App\Services;
use App\Repositories\CategoryRepository;
use Exception;

class CategoryService
{
    protected $repository;
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
    public function store(array $data)
    {
        return $this->repository->store($data);
    }
    public function update(int $id, array $data)
    {
        $category = $this->repository->find($id);
        if(!$category)
        {
            throw new Exception("Categoria nâo encontrada !");
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