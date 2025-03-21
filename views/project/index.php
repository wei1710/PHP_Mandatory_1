<?php

require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/ProjectDB.php';

$searchText = trim($_GET['search'] ?? '');

$projectDB = new ProjectDB();

if ($searchText === '') {
  $projects = $projectDB->getAll();
} else {
  $projects = $projectDB->search($searchText);
}

$pageTitle = 'Projects';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<main>
  <form action="index.php" method="GET">
    <div>
      <label for="txtSearch">Search</label>
      <input type="search" id="txtSearch" name="search">
    </div>
    <div>
      <button type="submit">Search</button>
    </div>
  </form>

  <?php if (empty($projects)): ?>
    <section>
      <p class="error">No results found.</p>
    </section>
  <?php endif; ?>

  <br>

  <nav>
    <form action="new.php" method="GET">
      <button type="submit">Add Project</button>
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
      <?php foreach ($projects as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project->getName()) ?></td>
          <td>
            <a href="view.php?id=<?= htmlspecialchars($project->getId()) ?>" class="button">View</a>
            <a href="edit.php?id=<?= htmlspecialchars($project->getId()) ?>" class="button">Edit</a>
            <a href="delete.php?id=<?= htmlspecialchars($project->getId()) ?>" class="button">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>