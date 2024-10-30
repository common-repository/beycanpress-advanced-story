<?php

namespace BeycanPress\Story\Helpers;

/**
 * WordPress wpdb allows you to perform database operations without doing sql queries.
 */
class EasyQuery
{

    public $tableName;
    private $db;
    private $query;
    
    public function __construct(string $tableName, object $wpdb)
    {
        $this->tableName = $tableName;
        $this->db = &$wpdb;
    }

    public function orderBy(array $orderBy)
    {
        $query = null;
        if (!empty($orderBy)) {
            $query = ' ORDER BY';

            foreach($orderBy as $col => $val) {
                $val = strtoupper($val);
                $query .= " `$col` %s, ";
            }

            $query = rtrim($query, ', ');
        }
        return $query;
    }

    public function where(array $where)
    {
        $query = null;
        if (!empty($where)) {
            $query = ' WHERE';

            foreach($where as $col => $val) {
                $type = is_string($val) ? '%s' : '%d';
                $query .= " `$col` = $type AND ";
            }

            $query = rtrim($query, 'AND ');
        }
        return $query;
    }

    public function limit(int $limit)
    {
        $query = null;
        if ($limit != 0) {
            $query = " LIMIT %d ";
        }
        return $query;
    }

    public function offset(int $offset)
    {
        $query = null;
        if ($offset != 0) {
            $query = " LIMIT %d ";
        }
        return $query;
    }

    public function findBy(array $where = [], array $orderBy = [], int $limit = 0, $offset = 0)
    {
        $whereData = array_merge(
            array_values($where), 
            array_values($orderBy)
        );
        if ($limit) $whereData[] = $limit;
        if ($offset) $whereData[] = $offset;
        
        $where = $this->where($where);
        $orderBy = $this->orderBy($orderBy);
        $limit = $this->limit($limit);
        $offset = $this->offset($offset);
        
        $query = $where.$orderBy.$limit.$offset;

        if (empty($prepareData)) {
            return $this->db->get_results("SELECT * FROM `{$this->tableName}`");
        } else {
            return $this->db->get_results($this->db->prepare(
                "SELECT * FROM `{$this->tableName}` $query",
                $prepareData
            ));
        }
    }
    
    public function findAll(array $orderBy = [])
    {
        if (empty($orderBy)) {
            return $this->db->get_results("SELECT * FROM `{$this->tableName}`");
        } else {
            $orderByData = array_values($orderBy);
            $orderBy = $this->orderBy($orderBy);
            return $this->db->get_results($this->db->prepare(
                "SELECT * FROM `{$this->tableName}` $orderBy",
                $orderByData
            )); 
        }
    }

    public function findOneBy(array $where = [])
    {
        if (empty($where)) {
            return $this->db->get_row("SELECT * FROM `{$this->tableName}`");
        } else {
            $whereData = array_values($where);
            $where = $this->where($where);
            return $this->db->get_row($this->db->prepare(
                "SELECT * FROM `{$this->tableName}` $where",
                $whereData
            )); 
        }
    }

    public function getCount(array $where = [])
    {
        if (empty($where)) {
            return (int) $this->db->get_var("SELECT COUNT(*) FROM `{$this->tableName}`");
        } else {
            $whereData = array_values($where);
            $where = $this->where($where);
            return (int) $this->db->get_var($this->db->prepare(
                "SELECT COUNT(*) FROM `{$this->tableName}` $where",
                $whereData
            )); 
        }
    }

    public function insert(array $data, $format = null)
    {
        return $this->db->insert($this->tableName, $data, $format); 
        return $this->db->insert_id;
    }

    public function update(array $data, array $where, $format = null, $whereFormat = null)
    {
        return $this->db->update($this->tableName, $data, $where, $format, $whereFormat);
    }

    public function delete(array $where, $whereFormat = null)
    {
        return $this->db->delete($this->tableName, $where, $whereFormat);
    }

}