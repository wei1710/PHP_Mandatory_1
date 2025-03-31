<?php

require_once '../../initialise.php';

$departmentID = (int) ($_GET['id'] ?? 0);

if ($departmentID === 0) {
  header('Location: index.php');
  exit;
}

require_once ROOT_PATH . '/classes/DepartmentDB.php';

$departmentDB = new DepartmentDB();
$department = $departmentDB->getByID($departmentID);

if (!$department) {
  $errorMessage = 'There was an error retrieving department information.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = trim($_POST['name'] ?? '');

  if ($data === '') {
    $errorMessage = 'Name is mandatory.';
  } elseif ($departmentDB->update($departmentID, $data)) {
    header('Location: index.php');
    exit;
  } else {
    $errorMessage = 'Failed to update the department.';
  }
}

$pageTitle = 'Edit Department';
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
  <?php endif; ?>

  <form method="POST">
    <div>
      <label for="name">Name</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($department->getName()) ?>" required>
    </div>
    <button type="submit">Update</button>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>