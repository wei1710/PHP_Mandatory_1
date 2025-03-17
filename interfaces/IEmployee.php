<?php

interface IEmployee {
  function getAll(): array|false;
  function search(string $searchText): array|false;
  function getByID(int $employeeID): array|false;
  function validate(array $employee): array;
  function insert(array $employee): bool;
}