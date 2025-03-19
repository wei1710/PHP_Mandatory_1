<?php

Interface IEmployeeDB {
  public function getAll(): array|false;
  public function search(string $searchText): array|false;
  public function getByID(int $employeeID): Employee|false;
  public function validate(array $employee): array;
  public function insert(array $employee): bool;
}