<?php

namespace App\Controllers\Admin;

use App\Models\SupplierModel;
use Core\Controller;
use Core\Database;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class SupplierController
{
    private $db;
    private $loader;
    private $twig;

    public function __construct()
    {
        $this->loader = new FilesystemLoader('../app/Views/Admin'); // Adjust path as needed
        $this->twig = new Environment($this->loader);
    }

    public function index()
    {
        $suppliers = SupplierModel::getAllSuppliers();
        echo $this->twig->render('Suppliers/index.twig', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        // Render form for creating a new supplier
        echo $this->twig->render('Suppliers/create.twig');
    }

    public function store()
    {
        // Create new supplier
        $supplier = new SupplierModel($_POST);
        print_r($supplier);
        $supplier->save();

        $supplierID = $supplier->getId();
        header("Location: /admin/suppliers/$supplierID");
        exit;
    }
    public function show($vars) {
        $id = $vars['id'];
//        echo json_encode(SupplierModel::getSupplierById($id));
        $supplier = SupplierModel::getSupplierById($id);
        echo $this->twig->render('Suppliers/show.twig', ['supplier' => $supplier]);
    }

    public function edit($vars)
    {
        $id = $vars['id'];
        // Retrieve supplier by ID and render edit form
        $supplier = SupplierModel::getSupplierById($id);
        echo $this->twig->render('Suppliers/edit.twig', ['supplier' => $supplier]);
    }

    public function update($vars)
    {
        $id = $vars['id'];
        $data = $_POST;
        $data['id'] = $id;
        $supplier = new SupplierModel($data);
        $supplier->save();
        header("Location: /admin/suppliers/$id");
    }

    public function delete($vars)
    {
        $id = $vars['id'];
        $db = Database::getConnection();
        $sql = "DELETE FROM supplier WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: /admin/suppliers");
        exit;
    }

    public function api_getSupplierById($vars) {
        $id = $vars['id'];
        echo json_encode(SupplierModel::getSupplierById($id), JSON_PRETTY_PRINT);
    }
}