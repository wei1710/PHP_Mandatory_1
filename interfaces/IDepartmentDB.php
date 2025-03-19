<?php

Interface IDepartmentDB {
  public function getAll(): array|false;
  public function search(string $searchText): array|false;
  public function getByID(int $departmentID): Department|false;
  public function getUnassignedEmployees(): array;
  public function insert(string $name): bool;
}