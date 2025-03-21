<?php
require_once '../../initialise.php';

$employeeID = (int) ($_GET['id'] ?? 0);
if ($employeeID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/EmployeeDB.php';

$employeeDB = new EmployeeDB();
$employee = $employeeDB->getById($employeeID);

if (!$employee) {
  $errorMessage = 'There was an error retrieving employee information.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] === 'yes') {
      if ($employeeDB->delete($employeeID)) {
        header('Location: index.php');
        exit;
      } else {
        $errorMessage = 'There was an error deleting the employee.';
      }
    } else {
      header('Location: index.php');
      exit;
    }
  }
}

$pageTitle = 'Delete Employee';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
  <a href="index.php" title="Back to Employees">Back</a>
</nav>

<main>
  <?php if (isset($errorMessage)): ?>
    <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
  <?php endif; ?>

  <h2>Employee</h2>
  <table>
    <tr>
      <th>First Name</th>
      <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
    </tr>
    <tr>
      <th>Last Name</th>
      <td><?= htmlspecialchars($employee->getLastName()) ?></td>
    </tr>
    <tr>
      <th>Email</th>
      <td><?= htmlspecialchars($employee->getEmail()) ?></td>
    </tr>
    <tr>
      <th>Birth Date</th>
      <td><?= htmlspecialchars($employee->getBirthDate()->format('Y-m-d')) ?></td>
    </tr>
    <tr>
      <th>Department</th>
      <td><?= htmlspecialchars($employee->getDepartmentName()) ?></td>
    </tr>
  </table>

  <h3>Are you sure you want to delete this employee?</h3>
  <form method="POST">
    <div>
      <button type="submit" name="confirm" value="yes">Yes</button>
      <button type="submit" name="confirm" value="no">No</button>
    </div>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>