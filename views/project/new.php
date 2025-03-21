<?php

require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/ProjectDB.php';

$projectDB = new ProjectDB();

$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $projectName = trim($_POST['name'] ?? '');

  if ($projectName === '') {
    $validationErrors[] = 'Project name is mandatory.';
  }

  if (empty($validationErrors)) {
    if ($projectDB->insert($projectName)) {
      header('Location: index.php');
      exit;
    } else {
      $errorMessage = 'Failed to create the project';
    }
  }
}

$pageTitle = 'Add Project';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
  <a href="index.php" title="Back to Projects">Back</a>
  <br><br>
</nav>

<main>
  <h2>Project</h2>
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
        value="<?= htmlspecialchars($projectName ?? '') ?>" required>
    </div>
    <div>
      <button type="submit">Create</button>
    </div>
  </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>