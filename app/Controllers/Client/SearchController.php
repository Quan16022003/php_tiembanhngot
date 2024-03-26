<?php

namespace App\Controllers\Client;

use App\Models\SearchModel;

class SearchController extends ClientController
{
    public function index() {
        $keyword = $_GET['q'] ?? '';
        $searchModel = new SearchModel();
        $results = $searchModel->searchByTitle($keyword);

        parent::render('search', ['products' => $results]);
    }
}