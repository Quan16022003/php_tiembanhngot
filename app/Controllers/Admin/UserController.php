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
        $data['permissions'] = $this->userModel->getPermissions();
        parent::render('user_add', $data);
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
        $permission = htmlspecialchars($_POST['permission']);
        if ($this->userModel->check($username)) {
            echo json_encode(array('success' => false, 'message' => 'Username đã tồn tại'));
        } else {
            if ($this->userModel->insert($name, $username, $password, $permission)) {
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
        $data['permissions'] = $this->userModel->getPermissions();
        parent::render('user_edit', $data);
    }

    public function editUser(): void
    {
        $id = htmlspecialchars($_POST['id']);
        $name = htmlspecialchars($_POST['name']);
        $id_per = htmlspecialchars($_POST['id_per']);
        $data = [
            'name' => $name,
            'id_per' => $id_per
        ];
        if ($this->userModel->update($id, $data)) {
            echo json_encode(array('success' => true, 'message' => 'Cập nhật thành công'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Cập nhật thất bại'));
        }
    }

    public function deleteUser(): void
    {
        $id = $_POST['id'];
        if ($this->userModel->delete($id)) {
            echo json_encode(array('success' => true, 'message' => 'Xóa thành công'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Xóa thất bại'));
        }
    }

    public function showPermissionPage(): void
    {
        $data['permissions'] = $this->userModel->getPermissions();
        parent::render('permissions', $data);
    }

    public function showAddPermissionPage(): void
    {
        $data['actions'] = $this->userModel->selectAllActions();
        parent::render('permissions_add', $data);
    }

    public function addPermission(): void
    {
        $name               = htmlspecialchars($_POST['name']);
        $describe           = htmlspecialchars($_POST['describe']);
        $newPermissionId    = $this->userModel->createPermission($name, $describe);
        unset($_POST['name']);
        unset($_POST['describe']);
        foreach ($_POST as $actID => $actions) {
            foreach ($actions as $f) {
                $this->userModel->assignActionToPermission($newPermissionId, $actID, $f);
            }
        }
        echo json_encode(Array('success' => true, 'message' => 'Thêm thành công'));
    }
    public function showEditPermissionPage(): void
    {
        $id = htmlspecialchars($_GET['id']);
        $data['permission'] = $this->userModel->selectPermissionById($id);
        $data['actions'] = $this->userModel->selectAllActions();
        $data['per_act'] = $this->userModel->selectAllPerAction($id);

        parent::render('permissions_edit', $data);
    }

    public function editPermission(): void
    {
        $id         = htmlspecialchars($_POST['id']);
        $name       = htmlspecialchars($_POST['name']);
        $describe   = htmlspecialchars($_POST['describe']);
        unset($_POST['id']);
        unset($_POST['name']);
        unset($_POST['describe']);
        $this->userModel->updatePermission($id, $name, $describe);
        $this->userModel->deleteAllActionsFromPermission($id);
        foreach ($_POST as $actID => $actions) {
            foreach ($actions as $f) {
                $this->userModel->assignActionToPermission($id, $actID, $f);
            }
        }
        echo json_encode(Array('success' => true, 'message' => 'Sửa thành công'));
    }

    public function deletePermission(): void
    {
        $id = $_POST['id'];
        if ($this->userModel->deletePermission($id)) {
            echo json_encode(array('success' => true, 'message' => 'Xóa thành công'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Xóa thất bại'));
        }
    }
}