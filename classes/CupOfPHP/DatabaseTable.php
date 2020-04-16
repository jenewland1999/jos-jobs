<?php

namespace CupOfPHP;

/**
 * Database Abstraction Layer class implementing CRUD methods.
 *
 * @package  CupOfPHP
 * @author   Jordan Newland <github@jenewland.me.uk>
 * @license  All Rights Reserved
 * @link     https://github.com/jenewland1999/
 */
class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;
    private $className;
    private $constructorArgs;

    /**
     * Represents a entity (table) in the database.
     *
     * @param \PDO     $pdo             Active PDO database connection.
     * @param string   $table           The entity (table) to interact with.
     * @param string   $primaryKey      The entity (table)'s primary key.
     * @param string   $className       The class name to store the fetched object in.
     * @param string   $constructorArgs Additional constructor args for fetchObject method.
     */
    public function __construct(
        \PDO $pdo,
        string $table,
        string $primaryKey,
        string $className = '\stdClass',
        array $constructorArgs = []
    ) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->constructorArgs = $constructorArgs;
    }

    /**
     * Standard Query Function.
     *
     * @param string $sql        The SQL to send to the database.
     * @param array  $parameters Values to bind in execute stage.
     *
     * @return object PDOStatement object from the database query.
     */
    private function query($sql, $parameters = [])
    {
        $query = $this->pdo->prepare($sql);
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
    private function processDates($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] = $value->format('Y-m-d H:i:s');
            }
        }
        return $fields;
    }

    /**
     * Retrieve total number of database records with a restriction.
     *
     * @param string $field The field to restrict by.
     * @param string $value The value to restrict by.
     *
     * @return int the number of rows in the table.
     */
    public function total($field = null, $value = null)
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT COUNT(`%s`) FROM `%s`',
            $this->primaryKey,
            $this->table
        );
        $parameters = [];

        // Check if $field and $value aren't null
        if (!empty($field) && !empty($value)) {
            $query .= sprintf(' WHERE `%s` = :value', $field);
            // Bind the parameters and store them
            $parameters = ['value' => $value];
        }

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->query($query, $parameters);

        // Return the fetched results (and convert to int)
        return intval($query->fetch()[0]);
    }

    /**
     * Retrieve total number of database records with a restriction.
     *
     * totalComplex takes an array of associative arrays with the following keys:
     *  - field - The column the restriction is being applied to.
     *  - operator - The operator to use in the comparison e.g. > < = LIKE etc.
     *  - value - The value of the comparison e.g. a primary key, date, etc.
     *  - type - Optional value that can be either AND or OR
     *
     *  @param array $whereArgs An array of associative arrays describing query restrictions.
     *
     * @return int the number of rows in the table.
     */
    public function totalComplex($whereArgs)
    {
        // Generate the query and store it
        $query = sprintf('SELECT COUNT(%s) FROM `%s` WHERE ', $this->primaryKey, $this->table);

        // PLace to store parameters (bind values)
        $parameters = [];

        foreach ($whereArgs as $whereArg) {
            if (isset($whereArg['type'])) {
                $query .= sprintf(
                    '`%s` %s :%s %s',
                    $whereArg['field'],
                    $whereArg['operator'],
                    $whereArg['field'],
                    $whereArg['type']
                );
            } else {
                $query .= sprintf(
                    '`%s` %s :%s',
                    $whereArg['field'],
                    $whereArg['operator'],
                    $whereArg['field']
                );
            }

            $parameters[$whereArg['field']] = $whereArg['value'];
        }

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->query($query, $parameters);

        // Return the fetched results (and convert to int)
        return intval($query->fetch()[0]);
    }

    public function findById($value)
    {
        $query = sprintf('SELECT * FROM `%s` WHERE `%s` = :value', $this->table, $this->primaryKey);

        $parameters = [
            'value' => $value
        ];

        $query = $this->query($query, $parameters);

        return $query->fetchObject($this->className, $this->constructorArgs);
    }

    /**
     * Retrieve all database records matching the restriction.
     *
     * @param string $field   The field to restrict by.
     * @param string $value   The value to restrict by.
     * @param string $orderBy The order in which to retrieve results.
     * @param int    $limit   The number of results to return.
     * @param int    $offset  The number to offset results by.
     *
     * @return array The result of the query.
     */
    public function find($field, $value, $orderBy = null, $limit = null, $offset = null)
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `%s` = :value',
            $this->table,
            $field
        );

        // Bind the parameters and store them
        $parameters = [
            'value' => $value
        ];

        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if ($limit != null) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset != null) {
            $query .= ' OFFSET ' . $offset;
        }

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->query($query, $parameters);

        // Return the fetched results
        return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    /**
     * Create complex queries with multiple restrictions.
     *
     * findComplex takes an array of associative arrays with the following keys:
     *  - field - The column the restriction is being applied to.
     *  - operator - The operator to use in the comparison e.g. > < = LIKE etc.
     *  - value - The value of the comparison e.g. a primary key, date, etc.
     *  - type - Optional value that can be either AND or OR
     *
     *  @param array $whereArgs An array of associative arrays describing query restrictions.
     *
     * @return array The result of the query.
     */
    public function findComplex($whereArgs, $orderBy = null, $limit = null, $offset = null)
    {
        $query = sprintf('SELECT * FROM `%s` WHERE ', $this->table);
        $parameters = [];

        foreach ($whereArgs as $whereArg) {
            if (isset($whereArg['type'])) {
                $query .= sprintf(
                    '`%s` %s :%s %s',
                    $whereArg['field'],
                    $whereArg['operator'],
                    $whereArg['field'],
                    $whereArg['type']
                );
            } else {
                $query .= sprintf(
                    '`%s` %s :%s',
                    $whereArg['field'],
                    $whereArg['operator'],
                    $whereArg['field']
                );
            }

            $parameters[$whereArg['field']] = $whereArg['value'];
        }

        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if ($limit != null) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset != null) {
            $query .= ' OFFSET ' . $offset;
        }

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->query($query, $parameters);

        // Return the fetched results
        return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    /**
     * Retrieve all database records.
     *
     * @return array The result of the query.
     */
    public function findAll($orderBy = null, $limit = null, $offset = null)
    {
        // Generate the query and store it
        $query = sprintf(
            'SELECT * FROM `%s`',
            $this->table
        );

        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if ($limit != null) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset != null) {
            $query .= ' OFFSET ' . $offset;
        }

        // Execute the query and store the results
        // (Overriding the previous value)
        $query = $this->query($query);

        // Return the fetched results
        return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    /**
     * Delete a database record with a matching primary key.
     *
     * @param string $primaryKey The id of the record to delete.
     *
     * @return null
     */
    public function deleteById($primaryKey)
    {
        // Generate the query and store it
        $query = sprintf('DELETE FROM %s WHERE %s = :value', $this->table, $this->primaryKey);

        // Bind the parameters and store them
        $parameters = [
            'value' => $primaryKey
        ];

        // Execute the query
        // ? No need to return as deleting a record
        $this->query($query, $parameters);
    }

    /**
     * Delete a database record where a column matches a value.
     *
     * @param string  $field      The field to restrict by.
     * @param string  $value      The value to restrict by
     * @param boolean $safeDelete Prevents deletion of more than one record.
     *
     * @return null
     */
    public function delete($field, $value, $safeDelete = true)
    {
        // Generate the query and store it
        $query = sprintf(
            'DELETE FROM `%s` WHERE `%s` = :value',
            $this->table,
            $field
        );

        // If safe delete is off delete as many records that match the query.
        // Defaults to false to prevent messy situations
        if (!$safeDelete) {
            $query .= ' LIMIT 1 ';
        }

        // Bind the parameters and store them
        $parameters = [
            'value' => $value
        ];

        // Execute the query
        // ? No need to return as deleting a record
        $this->query($query, $parameters);
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
        $query = sprintf('INSERT INTO `%s` (', $this->table);

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
        $fields = $this->processDates($fields);

        // Execute the query
        $this->query($query, $fields);

        // Return the primary key of the record just inserted.
        return $this->pdo->lastInsertId();
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
        $query = ' UPDATE `' . $this->table . '` SET ';

        // Loop over each of the fields key values for attributes
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }

        // Trim the trailing comma
        $query = rtrim($query, ',');

        // Append the next part of the SQL query
        $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

        // Set the :primaryKey variable
        $fields['primaryKey'] = $fields[$this->primaryKey];

        // Process any dates in the fields array
        $fields = $this->processDates($fields);

        // Execute the query
        // ? No need to return as updating a record
        $this->query($query, $fields);
    }

    /**
     * Attempts to insert a record to the database.
     * If the insert fails it will instead update a record.
     *
     * @param array $record An associative array representing the record.
     *
     * @return null
     */
    public function save($record)
    {
        $entity = new $this->className(...$this->constructorArgs);

        try {
            if ($record[$this->primaryKey] === '') {
                $record[$this->primaryKey] = null;
            }

            $insertId = $this->insert($record);

            $entity->{$this->primaryKey} = $insertId;
        } catch (\PDOException $e) {
            $this->update($record);
        }

        foreach ($record as $key => $value) {
            if (!empty($value)) {
                $entity->$key = $value;
            }
        }

        return $entity;
    }
}
