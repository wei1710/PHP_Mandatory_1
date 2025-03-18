<?php

Interface IEmployeeDB {
  function getAll(): array|false;
  function search(string $searchText): array|false;
  function getByID(int $employeeID): Employee|false;
  function validate(array $employee): array;
  function insert(array $employee): bool;
}