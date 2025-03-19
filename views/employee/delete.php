<?php
session_start();

require_once '../../initialise.php';

if (!isset($_SESSION['employee'])) {
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Employee.php';

$employee = unserialize($_SESSION['employee']);
$employeeID = $employee->getId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        if ($_POST['confirm'] === 'yes') {
            require_once ROOT_PATH . '/classes/EmployeeDB.php';
            $employeeDB = new EmployeeDB();
            
            if ($employeeDB->delete($employeeID)) {
                unset($_SESSION['employee']);
                header('Location: index.php');
                exit;
            } else {
                $errorMessage = 'There was an error deleting the employee.';
            }
        } else {
            header(header: 'Location: view.php?id=' . $employeeID);
            exit;
        }
    }
}

$pageTitle = 'Delete Employee';
include_once ROOT_PATH . '/public/header.php';
?>

<main>
    <?php if (isset($errorMessage)): ?>
        <p class="error"><?= $errorMessage ?></p>
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