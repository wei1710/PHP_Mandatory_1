<?php

require_once '../../initialise.php';

$projectID = (int) ($_GET['id'] ?? 0);

if ($projectID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/ProjectDB.php';
require_once ROOT_PATH . '/classes/Project.php';

$projectDB = new ProjectDB();
$project = $projectDB->getById($projectID);

if (!$project) {
  $errorMessage = 'There was an error retrieving project information';
}

$pageTitle = 'View Project';
include_once ROOT_PATH . '/public/header.php';

?>

<nav>
  <a href="index.php" title="Back to Projects">Back</a>
</nav>

<main>
  <?php if (isset($errorMessage)): ?>
    <section>
      <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
    </section>
  <?php else: ?>
    <h2>Project</h2>
    <table>
      <thead>
        <tr>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($project->getName()) ?></td>
        </tr>
      </tbody>
    </table>

    <h2>Employees</h2>
    <?php if (empty($project->getEmployees())): ?>
      <p>No employees in this project.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($project->getEmployees() as $employee): ?>
            <tr>
              <td><?= htmlspecialchars($employee->getLastName()) ?></td>
              <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
              <td>
                <a href="../employee/view.php?id=<?= htmlspecialchars($employee->getId()) ?>">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  <?php endif; ?>
</main>