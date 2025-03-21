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

$pageTitle = 'Employees';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>
<main>
    <form action="index.php" method="GET">
        <div>
            <label for="txtSearch">Search</label>
            <input type="search" id="txtSearch" name="search">
        </div>
        <div>
            <button type="submit">Search</button>
        </div>
    </form>

    <?php if (empty($employees)): ?>
        <section>
            <p class="error">No results found.</p>
        </section>
    <?php endif; ?>

    <br>

    <nav>
        <form action="new.php" method="get">
            <button type="submit">Add employee</button>
        </form>
    </nav>

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
</main>
<?php include_once ROOT_PATH . '/public/footer.php'; ?>