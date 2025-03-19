<?php

require_once '../../initialise.php';

$departmentID = (int) ($_GET['id'] ?? 0);

if ($departmentID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/DepartmentDB.php';

$departmentDB = new DepartmentDB();
$department = $departmentDB->getById($departmentID);

if (!$department) {
  $errorMessage = 'There was an error retrieving department information.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] === 'yes') {
      if ($departmentDB->delete($departmentID)) {
          header('Location: index.php');
          exit;
      } else {
        $errorMessage = 'There was an error deleting the department.';
      }
    } else {
      header('Location: index.php');
      exit;
    }
  }
}

$pageTitle = 'Delete Department';
include_once ROOT_PATH . '/public/header.php';

?>

<nav>
  <a href="index.php" title="Back to Departments">Back</a>
  <br><br>
</nav>

<main>
  <?php if (isset($errorMessage)): ?>
    <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
  <?php endif; ?>

  <h2>Department</h2>
  <table>
    <tr>
      <th>Name</th>
    </tr>
    <tr>
      <td><?= htmlspecialchars($department->getName()) ?></td>
    </tr>
  </table>

  <h2>Employees</h2>
  <?php $employees = $department->getEmployees(); ?>
  <?php if (empty($employees)): ?>
    <p>No employees in this department.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Last Name</th>
          <th>First Name</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($employees as $employee): ?>
          <tr>
            <td><?= htmlspecialchars($employee->getLastName()) ?></td>
            <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($employees)): ?>
      <p class="warning">This department cannot be deleted because it has employees.</p>
    <?php endif; ?>

    <?php if (empty($employees)): ?>
    <h3>Are you sure you want to delete this department?</h3>
    <form method="POST">
      <button type="submit" name="confirm" value="yes">Yes</button>
      <button type="submit" name="confirm" value="no">No</button>
    </form>
  <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>