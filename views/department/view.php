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

$pageTitle = 'View Department';
include_once ROOT_PATH . '/public/header.php';

?>

<nav>
    <a href="index.php" title="Back to Departments">Back</a>
</nav>

<main>
    <?php if (isset($errorMessage)): ?>
      <section>
        <p class="error" <?= $errorMessage ?>></p>
      </section>
    <?php else: ?>
      <table>
        <tr>
          <th>Name:</th>
          <td><?= htmlspecialchars($department->getName()) ?></td>
        </tr>
      </table>
    <?php endif; ?>
</main>