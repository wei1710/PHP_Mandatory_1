<?php

require_once '../../initialise.php';

require_once DB_ENTITIES_PATH . '/EmployeeDB.php';

$searchText = trim($_GET['search'] ?? '');

$employeeDB = new EmployeeDB();
if ($searchText === '') {
    $employees = $employeeDB->getAll();
} else {
    $employees = $employeeDB->search($searchText);
}
if (!$employees) {
    $errorMessage = 'There was an error while retrieving the list of employees.';
}

$pageTitle = 'Employees';
include_once PUBLIC_PATH . '/header.php';
include_once PUBLIC_PATH . '/nav.php';
?>
    <nav>
        <ul>
            <li><a href="new.php" title="Create new employee">Add employee</a></li>
        </ul>
    </nav>
    <main>
        <?php if (isset($errorMessage)): ?>
            <section>
                <p class="error"><?=$errorMessage ?></p>
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
            <section>
                <?php foreach ($employees as $employee): ?>
                    <article>
                        <p><strong>First name: </strong><?= $employee->getFirstName() ?></p>
                        <p><strong>Last name: </strong><?= $employee->getLastName() ?></p>
                        <p><strong>Birth date: </strong><?= $employee->getBirthDate()->format('Y-m-d') ?></p>
                        <p><a href="view.php?id=<?= $employee->getId() ?>">View details</a></p>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
<?php include_once PUBLIC_PATH . '/footer.php'; ?>