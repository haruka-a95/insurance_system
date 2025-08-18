<?php
namespace App\Repositories\Contracts;

//ビジネス層で必要なメソッドの定義
interface InsuranceProductInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}