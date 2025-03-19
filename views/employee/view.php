<?php

session_start();

require_once '../../initialise.php';

$employeeID = (int) ($_GET['id'] ?? 0);

if ($employeeID === 0) {
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/EmployeeDB.php';

$employeeDB = new EmployeeDB();
$employee = $employeeDB->getByID($employeeID);

if (!$employee) {
    $errorMessage = 'There was an error retrieving employee information.';
}

$_SESSION['employee'] = serialize($employee);

$pageTitle = 'View Employee';
include_once ROOT_PATH . '/public/header.php';

?>

    <nav>
        <a href="index.php" title="Back to Employees">Back</a>
    </nav>
    <main>
        <?php if (isset($errorMessage)): ?>
            <section>
                <p class="error"><?=$errorMessage ?></p>
            </section>
        <?php else: ?>
            <p><strong>First name: </strong><?= htmlspecialchars_decode($employee->getFirstName()) ?></p>
            <p><strong>Last name: </strong><?= htmlspecialchars($employee->getLastName()) ?></p>
            <p><strong>Email: </strong><?= htmlspecialchars($employee->getEmail()) ?></p>
            <p><strong>Birth date: </strong><?= htmlspecialchars($employee->getBirthDate()->format(format: 'Y-m-d')) ?></p>
            <p><strong>Department: </strong><?= htmlspecialchars($employee->getDepartmentName()) ?></p>
            <a href="edit.php" class="button" title="Edit Employee">Edit</a>
            <a href="delete.php" class="button" title="Delete Employee">Delete</a>
        <?php endif; ?>
    </main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>