<?php

Interface IDepartmentDB {
  function getAll(): array|false;
  function search(string $searchText): array|false;
  function getByID(int $departmentID): array|false;
  function insert(string $name): bool;
}