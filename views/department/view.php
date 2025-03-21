<?php

require_once '../../initialise.php';

$departmentID = (int) ($_GET['id'] ?? 0);

if ($departmentID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/DepartmentDB.php';
require_once ROOT_PATH . '/classes/Employee.php';

$departmentDB = new DepartmentDB();
$department = $departmentDB->getByID($departmentID);

if (!$department) {
  $errorMessage = 'There was an error retrieving department information.';
}

$pageTitle = 'View Department';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
  <a href="index.php" title="Back to Departments">Back</a>
</nav>

<main>
  <h2>Department</h2>
  <?php if (isset($errorMessage)): ?>
    <section>
      <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
    </section>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($department->getName()) ?></td>
        </tr>
      </tbody>
    </table>

    <h2>Employees</h2>
    <?php if (empty($department->getEmployees())): ?>
      <p>No employees in this department.</p>
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
          <?php foreach ($department->getEmployees() as $employee): ?>
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