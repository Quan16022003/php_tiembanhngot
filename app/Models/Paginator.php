<?php

namespace App\Models;

use stdClass;

class Paginator
{
    private $conn;
    private $limit;
    private $page;
    private $query;
    private $total;
    public function __construct( $conn, $query ) {

        $this->conn = $conn;
        $this->query = $query;
        $rs= $this->conn->query( $this->query );
        $this->total = $rs->num_rows;
    }
    public function getData($limit = 10, $page=1): stdClass
    {
        $this->limit   = $limit;
        $this->page    = $page;
        if ( $this->limit == 'all' ) {
            $query      = $this->query;
        } else {
            $query      = $this->query . " LIMIT " . ( ( $this->page - 1 ) * $this->limit ) . ", $this->limit";
        }
        $rs             = $this->conn->query( $query );
        $results = array();
        while ( $row = $rs->fetch_assoc() ) {
            $results[]  = $row;
        }
        $result         = new stdClass();
        $result->page   = $this->page;
        $result->limit  = $this->limit;
        $result->total  = $this->total;
        $result->data   = $results;
        return $result;
    }
}