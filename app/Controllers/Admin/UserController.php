<?php

namespace App\Controllers\Admin;

use App\Models\AdminUserModel;

class UserController extends AdminController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new AdminUserModel();
    }

    #[\Override] function index(): void
    {
        $data['list'] = $this->userModel->selectAll();
        parent::render('user', $data);
    }

    public function showAddUserPage(): void
    {
        parent::render('user_add');
    }

    public function searchUser(): void
    {
        $option = htmlspecialchars($_POST["option"]);
        $text = htmlspecialchars($_POST["text"]);
        echo json_encode($this->userModel->search($option, $text));
    }

    public function addUser(): void
    {
        $name = htmlspecialchars($_POST['name']);
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        if ($this->userModel->check($username)) {
            echo json_encode(array('success' => false, 'message' => 'Username đã tồn tại'));
        } else {
            if ($this->userModel->insert($name, $username, $password)) {
                // Thêm thành công, trả về thông báo thành công
                echo json_encode(array('success' => true, 'message' => 'Người dùng đã được thêm thành công'));
            } else {
                // Lỗi khi thêm vào cơ sở dữ liệu
                echo json_encode(array('success' => false, 'message' => 'Đã xảy ra lỗi khi thêm người dùng'));
            }

        }
    }

    public function showEditUserPage(): void
    {
        $id = htmlspecialchars($_GET['id']);
        $data['user'] = $this->userModel->selectByID($id);
        parent::render('user_edit', $data);
    }

    public function editUser(): void
    {
        $id = htmlspecialchars($_POST['id']);
        $name = htmlspecialchars($_POST['name']);
        $data = [
            'name' => $name
        ];
        if ($this->userModel->update($id, $data)) {
            echo json_encode(array('success' => true, 'message' => 'Cập nhật thành công'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Cập nhật thất bại'));
        }
    }

    public function showRolesPage(): void
    {
        parent::render('roles');
    }

    public function showAddRolesPage(): void
    {
        $data['functions'] = $this->userModel->selectAllFunctions();
        parent::render('roles_add', $data);
    }

    public function addRole(): void
    {

        echo json_encode(array('status'=> 'success', 'data' => $_POST['permissions']));
    }
}