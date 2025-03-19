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

require_once ROOT_PATH . '/classes/EmployeeDB.php';
require_once ROOT_PATH . '/classes/DepartmentDB.php';

$employeeDB = new EmployeeDB();
$departmentDB = new DepartmentDB();
$departments = $departmentDB->getAll();

$initialData = [
    'first_name' => $employee->getFirstName(),
    'last_name' => $employee->getLastName(),
    'email' => $employee->getEmail(),
    'birth_date' => $employee->getBirthDate()->format('Y-m-d'),
    'department_id' => $employee->getDepartmentId(),
];

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
        if ($employeeDB->update($employeeID, $data)) {
            unset($_SESSION['employee']);
            header("Location: view.php?id=$employeeID");
            exit;
        }
    }
}

$pageTitle = 'Edit Employee';
include_once ROOT_PATH . '/public/header.php';

?>

<nav>
    <a href="view.php?id=<?= $employeeID ?>" title="Back to Employee">Back</a>
    <br><br>
</nav>

<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= htmlspecialchars(string: $errorMessage) ?></p>
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

    <form method="POST">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($employee->getFirstName()) ?>" required>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($employee->getLastName()) ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($employee->getEmail()) ?>" required>
        </div>
        <div>
            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($employee->getBirthDate()->format('Y-m-d')) ?>" required>
        </div>
        <div>
            <label for="department_id">Department:</label>
            <select id="department_id" name="department_id" required>
                <option value="">Select a department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department->getId() ?>" <?= $employee->getDepartmentId() === $department->getId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($department->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Update</button>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>