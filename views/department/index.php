<?php

require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/DepartmentDB.php';

$searchText = trim($_GET['search'] ?? '');

$departmentDB = new DepartmentDB();

if ($searchText === '') {
  $departments = $departmentDB->getAll();
} else {
  $departments = $departmentDB->search($searchText);
}
if (!$departments) {
  $errorMessage = 'No results found.';
}

$pageTitle = 'Departments';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<main>
  <?php if (isset($errorMessage)): ?>
    <section>
      <p class="error"><?= $errorMessage ?></p>
    </section>
  <?php else: ?>
    <form action="index.php" method="GET">
      <div>
        <label for="txtSearch">Search</label>
        <input type="search" id="txtSearch" name="search">
      </div>
      <div>
        <button type="submit">Search</button>
      </div>
    </form>

    <br>

    <nav>
      <form action="new.php" method="get">
        <button type="submit">Add department</button>
      </form>
    </nav>

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($departments as $department): ?>
          <tr>
            <td><?= htmlspecialchars($department->getName()) ?></td>
            <td>
              <a href="view.php?id=<?= htmlspecialchars($department->getId()) ?>" class="button">View</a>
              <a href="edit.php?id=<?= htmlspecialchars($department->getId()) ?>" class="button">Edit</a>
              <a href="delete.php?id=<?= htmlspecialchars($department->getId()) ?>" class="button">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>