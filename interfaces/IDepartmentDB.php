<?php

Interface IDepartmentDB {
  function getAll(): array|false;
  function search(string $searchText): array|false;
  function getByID(int $departmentID): Department|false;
  function insert(string $name): bool;
}