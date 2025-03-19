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

?>

<nav>
    <a href="index.php" title="Back to Employees">Back</a>
    <br><br>
</nav>

<main>
    <?php if (isset($errorMessage)): ?>
        <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <p><strong>First Name:</strong> <?= htmlspecialchars($employee->getFirstName()) ?></p>
    <p><strong>Last Name:</strong> <?= htmlspecialchars($employee->getLastName()) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($employee->getEmail()) ?></p>
    <p><strong>Birth Date:</strong> <?= htmlspecialchars($employee->getBirthDate()->format('Y-m-d')) ?></p>
    <p><strong>Department:</strong> <?= htmlspecialchars($employee->getDepartmentName()) ?></p>

    <h3>Are you sure you want to delete the following employee?</h3>
    <form method="POST">
        <button type="submit" name="confirm" value="yes">Yes</button>
        <button type="submit" name="confirm" value="no">No</button>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>