<?php

require_once '../../initialise.php';

$employeeID = (int) ($_GET['id'] ?? 0);

if ($employeeID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/EmployeeDB.php';
require_once ROOT_PATH . '/classes/DepartmentDB.php';

$employeeDB = new EmployeeDB();
$employee = $employeeDB->getById($employeeID);

if (!$employee) {
  $errorMessage = 'There was an error retrieving employee information.';
}

$departmentDB = new DepartmentDB();
$departments = $departmentDB->getAll();

if (!$departments) {
  $errorMessage = 'There was an error retrieving the list of deparments.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'first_name' => $_POST['first_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'birth_date' => $_POST['birth_date'] ?? '',
    'department_id' => (int) ($_POST['department_id'] ?? 0),
  ];

  $validationErrors = $employeeDB->validate($data);

  if (empty($validationErrors)) {
    if ($employeeDB->update($employeeID, $data)) {
      header('Location: index.php');
      exit;
    } else {
      $errorMessage = 'Failed to update the employee.';
    }
  }
}

$pageTitle = 'Edit Employee';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
  <a href="index.php" title="Back to Employees">Back</a>
</nav>

<main>
  <?php if (isset($errorMessage)): ?>
    <section>
      <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
    </section>
  <?php endif; ?>

  <?php if (!empty($validationErrors)): ?>
    <section class="validation-errors">
      <?php foreach ($validationErrors as $error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>

  <h2>Employee</h2>
  <form method="POST">
    <div>
      <label for="first_name">First Name</label>
      <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($employee->getFirstName()) ?>" required>
    </div>
    <div>
      <label for="last_name">Last Name</label>
      <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($employee->getLastName()) ?>" required>
    </div>
    <div>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($employee->getEmail()) ?>" required>
    </div>
    <div>
      <label for="birth_date">Birth Date</label>
      <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($employee->getBirthDate()->format('Y-m-d')) ?>" required>
    </div>
    <div>
      <label for="department_id">Department</label>
      <select id="department_id" name="department_id" required>
        <option value="">Select a department</option>
        <?php foreach ($departments as $department): ?>
          <option value="<?= $department->getId() ?>" <?= $employee->getDepartmentId() === $department->getId() ? 'selected' : '' ?>>
            <?= htmlspecialchars($department->getName()) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit">Update</button>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>