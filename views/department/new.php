<?php

require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/DepartmentDB.php';

$departmentDB = new DepartmentDB();

$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $departmentName = trim($_POST['name'] ?? '');

  if ($departmentName === '') {
    $validationErrors[] = 'Department name is mandatory.';
  }

  if (empty($validationErrors)) {
    if ($departmentDB->insert($departmentName)) {
      header('Location: index.php');
      exit;
    } else {
      $errorMessage = 'Failed to create the department';
    }
  }
}

$pageTitle = 'Add Department';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
  <a href="index.php" title="Back to Departments">Back</a>
  <br><br>
</nav>

<main>
  <h2>Department</h2>
  <?php if (isset($errorMessage)): ?>
    <section>
      <p class="error"><?= htmlspecialchars($errorMessage); ?></p>
    </section>
  <?php endif; ?>

  <?php if (!empty($validationErrors)): ?>
    <section class="validation-errors">
      <?php foreach ($validationErrors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>

  <form action="new.php" method="POST">
    <div>
      <label for="name">Name</label>
      <input type="text" id="name" name="name"
        value="<?= htmlspecialchars($departmentName ?? '') ?>" required>
    </div>
    <div>
      <button type="submit">Create</button>
    </div>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>