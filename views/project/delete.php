<?php

require_once '../../initialise.php';

$projectID = (int) ($_GET['id'] ?? 0);

if ($projectID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/ProjectDB.php';

$projectDB = new ProjectDB();
$project = $projectDB->getById($projectID);

if (!$project) {
  $errorMessage = 'There was an error retrieving project information.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] === 'yes') {
      if ($projectDB->delete($projectID)) {
        header('Location: index.php');
        exit;
      } else {
        $errorMessage = 'There was an error deleting the project.';
      }
    } else {
      header('Location: index.php');
      exit;
    }
  }
}

$pageTitle = 'Delete project';
include_once ROOT_PATH . '/public/header.php';

?>

<nav>
  <a href="index.php" title="Back to Project">Back</a>
  <br><br>
</nav>

<main>
  <?php if (isset($errorMessage)): ?>
    <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
  <?php endif; ?>

  <h2>Project</h2>
  <table>
    <tr>
      <th>Name</th>
    </tr>
    <tr>
      <td><?= htmlspecialchars($project->getName()) ?></td>
    </tr>
  </table>

  <h2>Employees</h2>
  <?php $employees = $project->getEmployees(); ?>
  <?php if (empty($employees)): ?>
    <p>No employees in this project.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Last Name</th>
          <th>First Name</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($employees as $employee): ?>
          <tr>
            <td><?= htmlspecialchars($employee->getLastName()); ?></td>
            <td><?= htmlspecialchars($employee->getFirstName()); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <h3>Are you sure you want to delete this project?</h3>
  <form method="POST">
    <button type="submit" name="confirm" value="yes">Yes</button>
    <button type="submit" name="confirm" value="no">No</button>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>