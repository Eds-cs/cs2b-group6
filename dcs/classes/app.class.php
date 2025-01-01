<?php
require 'role.class.php';
class Application
{
    private static $instance = null;
    private $pageTitle = "DCS";
    private $currentPageTitle = "";

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Application();
        }
        return self::$instance;
    }

    public function getPageTitle()
    {
        return "{$this->pageTitle} - {$this->currentPageTitle}";
    }
    public function setPageTitle($title)
    {
        $this->currentPageTitle = $title;
    }

    public function getAccountId()
    {
        return $_SESSION['account_id'] ?? null;
    }

    public function getEmail()
    {
        return $_SESSION['email'] ?? null;
    }
    public function getRoles()
    {
        return $_SESSION['roles'] ?? '';
    }
    public function getTeacherId()
    {
        return $_SESSION['teacher_id'] ?? null;
    }
    public function getStudentId()
    {
        return $_SESSION['student_id'] ?? null;
    }

    public function userInRole($roles)
    {
        foreach ($roles as $role) {
            if (str_contains($this->getRoles(), $role)) {
                return true;
            }
        }
        return false;
    }

    public function startSession($accObj)
    {
        session_start();
        $_SESSION['account_id'] = $accObj['account_id'];
        $_SESSION['email'] = $accObj['email'];
        $_SESSION['roles'] = $accObj['roles'];
        $_SESSION['org_ids'] = $accObj['org_ids'];
        $_SESSION['teacher_id'] = $accObj['teacher_id'];
        $_SESSION['student_id'] = $accObj['student_id'];

        $role = Role::getInstance();
        $role->cacheRoles();
    }
}
?>