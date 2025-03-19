<?php

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

$pageTitle = 'View Employee';
include_once ROOT_PATH . '/public/header.php';

?>

<nav>
    <a href="index.php" title="Back to Employees">Back</a>
</nav>

<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php else: ?>
        <table>
            <tr>
                <th>First name:</th>
                <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
            </tr>
            <tr>
                <th>Last name:</th>
                <td><?= htmlspecialchars($employee->getLastName()) ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?= htmlspecialchars($employee->getEmail()) ?></td>
            </tr>
            <tr>
                <th>Birth date:</th>
                <td><?= htmlspecialchars($employee->getBirthDate()->format('Y-m-d')) ?></td>
            </tr>
            <tr>
                <th>Department:</th>
                <td><?= htmlspecialchars($employee->getDepartmentName()) ?></td>
            </tr>
        </table>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>