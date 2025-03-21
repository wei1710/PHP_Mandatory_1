<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Employee.php';
require_once ROOT_PATH . '/classes/EmployeeDB.php';
require_once ROOT_PATH . '/classes/DepartmentDB.php';

$employeeDB = new EmployeeDB();
$departmentDB = new DepartmentDB();
$departments = $departmentDB->getAll();

$data = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'birth_date' => '',
    'department_id' => 0
];
$validationErrors = [];

if (!$departments) {
    $errorMessage = 'There was an error while retrieving the department list.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'birth_date' => $_POST['birth_date'] ?? '',
        'department_id' => (int) ($_POST['department_id'] ?? 0),
    ];

    $validationErrors = $employeeDB->validate($data);

    if (empty($validationErrors)) {
        if ($employeeDB->insert(employee: $data)) {
            header('Location: index.php');
            exit;
        } else {
            $errorMessage = 'There was an error inserting the new employee.';
        }
    }
}

$pageTitle = 'Add Employee';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';

?>

<nav>
    <a href="index.php" title="Back to Employees">Back</a>
</nav>

<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
        </section>
    <?php endif; ?>

    <?php if (!empty($validationErrors)): ?>
        <section class="validation-errors">
            <ul>
                <?php foreach ($validationErrors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <h2>Employee</h2>
    <form action="new.php" method="POST">
        <div>
            <label for="first_name">First name</label>
            <input type="text" id="first_name" name="first_name" 
                   value="<?= htmlspecialchars($data['first_name']) ?>" required>
        </div>
        <div>
            <label for="last_name">Last name</label>
            <input type="text" id="last_name" name="last_name" 
                   value="<?= htmlspecialchars($data['last_name']) ?>" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($data['email']) ?>" required>
        </div>
        <div>
            <label for="birth_date">Birth date</label>
            <input type="date" id="birth_date" name="birth_date" 
                   value="<?= htmlspecialchars($data['birth_date']) ?>" required>
        </div>
        <div>
            <label for="department_id">Department</label>
            <select name="department_id" id="department_id">
                <option value="">Select a department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department->getId() ?>" 
                        <?= $data['department_id'] === $department->getId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($department->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button type="submit">Create</button>
        </div>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>