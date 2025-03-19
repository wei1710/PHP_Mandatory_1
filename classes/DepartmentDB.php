<?php

require_once 'Database.php';
require_once 'Logger.php';
require_once ROOT_PATH . '/interfaces/IDepartmentDB.php';
require_once 'Department.php';

Class DepartmentDB extends Database implements IDepartmentDB
{
    /**
     * It retrieves all department from the database
     * @return <array> An associative array of Department objects,
     *         or false if there was an error
     */
    function getAll(): array|false
    {
        $sql =<<<SQL
            SELECT nDepartmentID, cName
            FROM department
            ORDER BY cName
        SQL;
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $departments = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $departments[] = new Department(id: $row['nDepartmentID'], name: $row['cName']);
            }
            return $departments;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::logText('Error getting all departments: ', $e);
            return false;
        }
    }

    /**
     * It retrieves departments from the database based
     * on a text search on the name
     * @param string $searchText The text to search in the database
     * @return <array> An associative array of Department objects,
     *         or false if there was an error
     */
    public function search(string $searchText): array|false
    {
        $sql =<<<SQL
            SELECT nDepartmentID, cName
            FROM department
            WHERE cName LIKE :name
            ORDER BY cName;
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', "%$searchText%");
            $stmt->execute();
            
            $departments = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $departments[] = new Department(id: $row['nDepartmentID'], name: $row['cName']);
            }

            return $departments;
        }  catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::logText('Error searching for departments: ', $e);
            return false;
        }
    }

    /**
     * It retrieves information regarding one department
     * @param int $departmentID The ID of the department whose info to retrieve
     * @return <array> An associative array of Department objects,
     *         or false if there was an error
     */
    function getByID(int $departmentID): Department|false
    {
        $sql =<<<SQL
            SELECT d.nDepartmentID, d.cName,
                   e.nEmployeeID, e.cFirstName, e.cLastName
            FROM department d
            LEFT JOIN employee e ON d.nDepartmentID = e.nDepartmentID
            WHERE d.nDepartmentID = :departmentID
            ORDER BY e.cLastName, e.cFirstName;
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':departmentID', $departmentID);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new Department(id: $row['nDepartmentID'], name: $row['cName']);
            }
            return false;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::logText('Error getting all departments: ', $e);
            return false;
        }
    }

    /**
     * It inserts a new department in the database
     * @param string $name for the department name.
     * @return bool true if the insert was successful,
     *         or false if there was an error
     */
    public function insert(string $name): bool
    {
        $sql =<<<SQL
            INSERT INTO department (cName)
            VALUES (:name);
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::logText('Error inserting a new department: ', $e);
            return false;
        }
    }

    /**
     * It update a department in the database
     * @param int $departmentID The ID of the department to update.
     * @param string $name The name to update the department to.
     * @return bool true if the update was successful,
     *         or false if there was an error
     */
    public function update(int $departmentID, string $name): bool
    {
        $sql =<<<SQL
            UPDATE department
            SET cName = :name
            WHERE nDepartmentID = :departmentID;
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':departmentID', $departmentID);
            $stmt->execute();

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::logText('Error updating department: ', $e);
            return false;
        }
    }

    /**
     * It delete a department from the database.
     * @param int $departmentID The ID of the department, the department to delete.
     * @return bool true if the delete was successful,
     *         or false if there was an error
     */
    public function delete(int $departmentID): bool
    {
        $sql =<<<SQL
            DELETE FROM department
            WHERE nDepartmentID = :departmentID
            AND (SELECT COUNT(*) FROM employee WHERE nDepartmentID = :departmentID) = 0;
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':departmentID', $departmentID);
            $stmt->execute();

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::logText('Error deleting department: ', $e);
            return false;
        }
    }
}