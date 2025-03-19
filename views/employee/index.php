<?php

require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/EmployeeDB.php';

$searchText = trim($_GET['search'] ?? '');

$employeeDB = new EmployeeDB();

if ($searchText === '') {
    $employees = $employeeDB->getAll();
} else {
    $employees = $employeeDB->search($searchText);
}
if (!$employees) {
    $errorMessage = 'No results found.';
}

$pageTitle = 'Employees';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>
    <nav>
        <a href="new.php" title="Create employee">Add employee</a>
        <br><br>
    </nav>
    <main>
        <?php if (isset($errorMessage)): ?>
            <section>
                <p class="error"><?= $errorMessage ?></p>
            </section>
        <?php else: ?>
            <form action="index.php" method="GET">
                <div>
                    <label for="txtSearch">Search</label>
                    <input type="search" id="txtSearch" name="search">
                </div>
                <div>
                    <button type="submit">Search</button>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Birth Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
                            <td><?= htmlspecialchars($employee->getLastName()) ?></td>
                            <td><?= htmlspecialchars($employee->getBirthDate()->format('Y-m-d')) ?></td>
                            <td>
                                <a href="view.php?id=<?= htmlspecialchars($employee->getId()) ?>">View</a>
                                <a href="edit.php?id=<?= htmlspecialchars($employee->getId()) ?>" class="button">Edit</a>
                                <a href="delete.php?id=<?= htmlspecialchars($employee->getId()) ?>" class="button">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
<?php include_once ROOT_PATH . '/public/footer.php'; ?>