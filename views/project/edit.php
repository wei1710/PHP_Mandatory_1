<?php
require_once '../../initialise.php';

$projectID = (int) ($_GET['id'] ?? 0);
if (!$projectID) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/ProjectDB.php';

$projectDB = new ProjectDB();
$project = $projectDB->getById($projectID);
$currentEmployees = $project->getEmployees();

if (!$project) {
  $errorMessage = 'Error retrieving project info.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_project'])) {
    $action = 'update';
  } elseif (isset($_POST['remove_employee'])) {
    $action = 'remove';
  } elseif (isset($_POST['add_employee'])) {
    $action = 'add';
  } else {
    $action = '';
  }

  switch ($action) {
    case 'update':
      $name = trim($_POST['name'] ?? '');
      if ($name === '') {
        $errorMessage = 'Name is mandatory.';
      } else {
        if ($projectDB->update($projectID, $name)) {
          header('Location: index.php');
          exit;
        } else {
          $errorMessage = 'Failed to update the project.';
        }
      }
      break;

    case 'remove':
      $employeeId = (int) ($_POST['remove_employee'] ?? 0);
      if ($employeeId && $projectDB->update($projectID, null, null, $employeeId)) {
        header('Location: index.php');
        exit;
      } else {
        $errorMessage = 'Failed to remove employee.';
      }
      break;

    case 'add':
      $employeeId = (int) ($_POST['employee_id'] ?? 0);
      if ($employeeId && $projectDB->update($projectID, null, $employeeId, null)) {
        header('Location: index.php');
        exit;
      } else {
        $errorMessage = 'Failed to add employee.';
      }
      break;

    default:
      break;
  }
}

require_once ROOT_PATH . '/classes/EmployeeDB.php';

$employeeDB = new EmployeeDB();
$availableEmployees = $employeeDB->getAvailableEmployees($projectID);

$pageTitle = 'Edit Project';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
  <a href="index.php" title="Back to Projects">Back</a>
</nav>

<main>
  <?php if (isset($errorMessage)): ?>
    <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
  <?php endif; ?>

  <form method="POST">
    <h2>Project</h2>
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($project->getName()) ?>" required>
    <button type="submit" name="update_project">Update</button>
  </form>

  <h2>Employees</h2>
  <table>
    <thead>
      <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($currentEmployees as $employee): ?>
        <tr>
          <td><?= htmlspecialchars($employee->getLastName()) ?></td>
          <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
          <td>
            <form method="POST">
              <button type="submit" name="remove_employee" value="<?= $employee->getId() ?>">Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <form method="POST">
    <select name="employee_id" required>
      <option value="">Select an employee</option>
      <?php foreach ($availableEmployees as $employee): ?>
        <option value="<?= $employee->getId() ?>">
          <?= htmlspecialchars($employee->getLastName() . ', ' . $employee->getFirstName()) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit" name="add_employee">Add</button>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>