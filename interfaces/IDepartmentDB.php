<?php

Interface IDepartmentDB
{
  public function getAll(): array|false;
  public function search(string $searchText): array|false;
  public function getById(int $departmentID): Department|false;
  public function insert(string $name): bool;
  public function update(int $departmentID, string $name): bool;
  public function delete(int $departmentID): bool;
}
