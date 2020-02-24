<?php

/**
 * Database Abstraction Layer implementing CRUD methods.
 * 
 * PHP Version 7
 * 
 * @category  Default
 * @package   Default
 * @author    Jordan Newland <github@jenewland.me.uk>
 * @copyright 2020 Jordan Newland
 * @license   All Rights Reserved
 * @link      https://github.com/jenewland1999/
 */

/**
 * Database Abstraction Layer class implementing CRUD methods
 * 
 * @category Default
 * @package  Default
 * @author   Jordan Newland <github@jenewland.me.uk>
 * @license  All Rights Reserved
 * @link     https://github.com/jenewland1999/
 */
class DatabaseTable
{
    private $_pdo;
    private $_table;
    private $_primaryKey;

    /**
     * Represents a entity (table) in the database.
     * 
     * @param resource $pdo        Active PDO database connection.
     * @param string   $table      The entity (table) to interact with.
     * @param string   $primaryKey The entity (table)'s primary key.
     */
    public function __construct(PDO $pdo, string $table, string $primaryKey)
    {
        $this->_pdo = $pdo;
        $this->_table = $table;
        $this->_primaryKey = $primaryKey;
    }

    /**
     * Standard Query Function.
     * 
     * @param string $sql        The SQL to send to the database.
     * @param array  $parameters Values to bind in execute stage.
     * 
     * @return object PDOStatement object from the database query.
     */
    private function _query($sql, $parameters = [])
    {
        $query = $this->_pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }

    /**
     * Processes instances of DateTime found in fields for the DB.
     * 
     * Searches the array of fields for instances of PHP DateTime and formats 
     * them to the MySQL standard datetime format (YYYY-MM-DD HH:MM:SS) and 
     * returns the newly updated fields array.
     * 
     * @param array $fields The initial array of fields to check.
     * 
     * @return array The updated fields array with correctly formatted dates/times.
     */
    private function _processDates($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof DateTime) {
                $fields[$key] = $value->format('Y-m-d H:i:s');
            }
        }
        return $fields;
    }

    /**
     * Retrieve total number of database records.
     * 
     * @return int the number of rows in the table. 
     */
    public function total()
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT COUNT(`%s`) FROM `%s`', 
            $this->_primaryKey, 
            $this->_table
        );

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->_query($query);

        // Return the fetched results (and convert to int)
        return intval($query->fetch()[0]);
    }

    /**
     * Retrieve total number of database records with a restriction.
     * 
     * @param string $field The field to restrict by.
     * @param string $value The value to restrict by.
     * 
     * @return int the number of rows in the table. 
     */
    public function totalBy($field, $value)
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT COUNT(`%s`) FROM `%s` WHERE `%s` = :value', 
            $this->_primaryKey, 
            $this->_table,
            $field
        );

        // Bind the parameters and store them
        $parameters = [
            'value' => $value
        ];

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->_query($query, $parameters);

        // Return the fetched results (and convert to int)
        return intval($query->fetch()[0]);
    }

    /**
     * Retrieve all database records matching the restriction.
     * 
     * @param string $field The field to restrict by.
     * @param string $value The value to restrict by.
     * 
     * @return array The result of the query.
     */
    public function find($field, $value)
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `%s` = :value',
            $this->_table,
            $field
        );

        // Bind the parameters and store them
        $parameters = [
            'value' => $value
        ];

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->_query($query, $parameters);

        // Return the fetched results
        return $query->fetchAll();
    }

    /**
     * Retrieve all database records.
     * 
     * @return array The result of the query.
     */
    public function findAll()
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT * FROM `%s`',
            $this->_table
        );

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->_query($query);

        // Return the fetched results
        return $query->fetchAll();
    }

    /**
     * Delete a database record.
     * 
     * @param string $field The field to restrict by.
     * @param string $value The value to restrict by
     * 
     * @return null
     */
    public function delete($field, $value)
    {
        // Generate the query and store it
        // ? "LIMIT 1" for sanity's sake.
        $query = sprintf(
            'DELETE FROM `%s` WHERE `%s` = :value LIMIT 1',
            $this->_table,
            $field
        );

        // Bind the parameters and store them
        $parameters = [
            'value' => $value
        ];

        // Execute the query
        // ? No need to return as deleting a record
        $this->_query($query, $parameters);
    }

    /**
     * Insert a database record.
     * 
     * @param array $fields An associative array representing the record.
     * 
     * @return null
     */
    public function insert($fields)
    {
        // Create the initial part of the SQL query
        $query = 'INSERT INTO `' . $this->_table . '` (';

        // Loop over each of the fields key values for attributes
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`,';
        }

        // Trim the trailing comma
        $query = rtrim($query, ',');

        // Append the next part of the SQL query
        $query .= ') VALUES (';

        // Loop over each of the fields key values again for placeholders
        foreach ($fields as $key => $value) {
            $query .= ':' . $key . ',';
        }

        // Trim the trailing comma
        $query = rtrim($query, ',');

        // Append the final part of the SQL query
        $query .= ')';

        // Process any dates in the fields array
        $fields = $this->_processDates($fields);

        // Execute the query
        // ? No need to return as inserting a record
        $this->_query($query, $fields);
    }

    /**
     * Update a database record.
     * 
     * @param array $fields An associative array representing the record.
     * 
     * @return null
     */
    public function update($fields)
    {
        // Create the initial part of the SQL query
        $query = ' UPDATE `' . $this->_table .'` SET ';

        // Loop over each of the fields key values for attributes
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }

        // Trim the trailing comma
        $query = rtrim($query, ',');

        // Append the next part of the SQL query
        $query .= ' WHERE `' . $this->_primaryKey . '` = :primaryKey';

        // Set the :primaryKey variable
        $fields['primaryKey'] = $fields['id'];

        // Process any dates in the fields array
        $fields = $this->_processDates($fields);

        // Execute the query
        // ? No need to return as updating a record
        $this->_query($query, $fields);
    }

    /**
     * Attempts to insert a record to the database. 
     * If the insert fails it will instead update a record.
     * 
     * @param array $fields An associative array representing the record.
     * 
     * @return null
     */
    public function save($fields)
    {
        try {
            $this->insert($fields);
        } catch (PDOException $e) {
            $this->update($fields);
        }
    }
}
