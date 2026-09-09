<?php
class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'expensetracker');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function registerUser($name, $username, $password) {
        $stmt = $this->conn->prepare("INSERT INTO login (name, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT user_id, name, username, password FROM login WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, name, username FROM login WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateProfile($user_id, $name, $username) {
        $stmt = $this->conn->prepare("UPDATE login SET name = ?, username = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $name, $username, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updatePassword($user_id, $password) {
        $stmt = $this->conn->prepare("UPDATE login SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $password, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function addExpense($user_id, $type, $amount, $category, $description, $expense_date) {
        $stmt = $this->conn->prepare("INSERT INTO expenses (user_id, type, amount, category, description, expense_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $user_id, $type, $amount, $category, $description, $expense_date);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getTotalByType($user_id, $type) {
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses WHERE user_id = ? AND type = ?");
        $stmt->bind_param("is", $user_id, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }

    public function getRecentExpenses($user_id, $limit = 5) {
        $stmt = $this->conn->prepare("SELECT type, amount, category, description, expense_date FROM expenses WHERE user_id = ? ORDER BY expense_date DESC, expense_id DESC LIMIT ?");
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }
        $stmt->close();
        return $expenses;
    }

    public function getCategoryBreakdown($user_id, $limit = 5) {
        $stmt = $this->conn->prepare("SELECT category, SUM(amount) AS total FROM expenses WHERE user_id = ? AND type = 'spent' GROUP BY category ORDER BY total DESC LIMIT ?");
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    public function getSpendingByDateRange($user_id, $start_date, $end_date) {
        $stmt = $this->conn->prepare("SELECT expense_date, SUM(amount) AS total FROM expenses WHERE user_id = ? AND type = 'spent' AND expense_date BETWEEN ? AND ? GROUP BY expense_date");
        $stmt->bind_param("iss", $user_id, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[$row['expense_date']] = (float)$row['total'];
        }
        $stmt->close();
        return $rows;
    }

    public function getAllExpenses($user_id) {
        $stmt = $this->conn->prepare("SELECT expense_id, type, amount, category, description, expense_date FROM expenses WHERE user_id = ? ORDER BY expense_date DESC, expense_id DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }
        $stmt->close();
        return $expenses;
    }

    public function getExpenseById($user_id, $expense_id) {
        $stmt = $this->conn->prepare("SELECT expense_id, type, amount, category, description, expense_date FROM expenses WHERE expense_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $expense_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function updateExpense($user_id, $expense_id, $type, $amount, $category, $description, $expense_date) {
        $stmt = $this->conn->prepare("UPDATE expenses SET type = ?, amount = ?, category = ?, description = ?, expense_date = ? WHERE expense_id = ? AND user_id = ?");
        $stmt->bind_param("sdsssii", $type, $amount, $category, $description, $expense_date, $expense_id, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteExpense($user_id, $expense_id) {
        $stmt = $this->conn->prepare("DELETE FROM expenses WHERE expense_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $expense_id, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getBudget($user_id) {
        $stmt = $this->conn->prepare("SELECT budget FROM login WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row['budget'] : 0;
    }

    public function updateBudget($user_id, $budget) {
        $stmt = $this->conn->prepare("UPDATE login SET budget = ? WHERE user_id = ?");
        $stmt->bind_param("di", $budget, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function adjustBudget($user_id, $delta) {
        $stmt = $this->conn->prepare("UPDATE login SET budget = budget + ? WHERE user_id = ?");
        $stmt->bind_param("di", $delta, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
