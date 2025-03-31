<?php

Interface IProjectDB
{
  public function getAll(): array|false;
  public function search(string $searchText): array|false;
  public function getById(int $projectID): Project|false;
  public function insert(string $name): bool;
  public function update(int $projectID, ?string $name = null, ?int $newEmployee = null, ?int $oldEmployee = null): bool;
  public function delete(int $projectID): bool;
}
