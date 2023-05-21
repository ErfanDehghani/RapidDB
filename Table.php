<?php 

include "Database.php";
include "Column.php";

class Table
{
    private $columns = null;
    private $name = null;
    private $database = null;
    private $connection = null;
    private $result = null;
    private $prepareFetchStatus = false;
    private $prepareInsertStatus = false;
    
    public function __construct($tableName, $columns, $database) 
    {
        $this->columns = $columns;
        $this->name = $tableName;
        $this->database = $database;
        $this->connection = $database->getConnection();
    }


    // Getters ----------------------------------------------------------------

    public function getDatabase()
    {
        return $this->database;
    }
 
    public function getName()
    {
        return $this->name;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getColumnNames()
    {
        $columnNames = array();
        foreach ($this->columns as $value) 
            if ($value->getDefault() == null)
                array_push($columnNames ,$value->getName());

        return $columnNames;
    }


    // Creating Table methods ----------------------------------------------------------------

    public function createTableQuery() 
    {
        $query = "CREATE TABLE IF NOT EXISTS `$this->name` (";
        
        foreach ($this->columns as $column)
            $query .= $column . " ,";
        
        $query = rtrim($query, ',');
        
        $query .= ');';

        return $query;
    }

    public function createTable()
    {

        try 
        {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec($this->createTableQuery());
            echo "Table: '" . $this->getName() . "' was created successfully";
        } 
        catch (PDOException $e) 
        {
            throw new Exception("Error creating table: " . $e->getMessage());
        }
    }


    // Delete From Table Methods ----------------------------------------------------------------

    public function delete($id) : void
    {

        // Prepare the SQL statement
        $stmt = $this->connection->prepare('DELETE FROM ' . $this->getName() . ' WHERE id = :id');

        // Bind the parameter to the statement
        $stmt->bindParam(':id', $id);

        // Set the parameter value
        $id = 1;

        // Execute the statement
        $stmt->execute();
    }


    // Update Table Methods ----------------------------------------------------------------

    private function createWhereStatement(array $arguments) : string
    {
        $whereStatement = ' WHERE ';

        foreach ($arguments as $key => $value) {
            $whereStatement .= $key . ' = :' . $key . ' AND ';
        }

        $whereStatement = rtrim($whereStatement, ' AND ');

        return $whereStatement;
    }

    private function createSetStatement(array $arguments) : string
    {
        $setStatement = ' SET ';

        foreach ($arguments as $key => $value) {
            $setStatement .= $key . ' = :idf_' . $key . ', ';
        }

        $setStatement = rtrim($setStatement, ', ');

        return $setStatement;
    }

    public function update(array $identifiers, array $changes) : void
    {
        $statement = "UPDATE " . $this->getName();
        $statement .= $this->createSetStatement($changes);
        $statement .= $this->createWhereStatement($identifiers);

        try {
            $preparedStatement = $this->connection->prepare($statement);

            foreach ($identifiers as $key => $value) {
                $preparedStatement->bindValue(':' . $key, $value);
            }

            foreach ($changes as $key => $value) {
                $preparedStatement->bindValue(':idf_' . $key, $value);
            }

            $preparedStatement->execute();

            $rowCount = $preparedStatement->rowCount();

            if ($rowCount > 0) {
                echo "Successfully updated " . $rowCount . " rows in " . $this->getName();
            } else {
                echo "No rows were updated in " . $this->getName();
            }
        } catch (PDOException $e) {
            throw new Exception("Error updating table: " . $e->getMessage());
        }
    }

    // Drop Table Methods ----------------------------------------------------------------

    public function dropTable() : void
    {
        try
        {
            $sql = "DROP table " . $this->name;
    
            $this->connection->exec($sql);
            echo "Table " . $this->name . " droped successfully";
        }
        catch(PDOException $e) 
        {
            throw new Exception("Error droping table: " . $e->getMessage());
        }
    }


    // insert Methods ----------------------------------------------------------------

    private function prepareInsert() : void
    {
        $keys = implode(', ', $this->getColumnNames());
        $values = ":".implode(", :", $this->getColumnNames());

        $this->result = $this->connection->prepare("INSERT INTO " . $this->getName() . 

            "(". $keys .")
            VALUES 
            (". $values .")"
        );

        $this->prepareInsertStatus = true;
    }

    public function insert($row)
    {
        if ($this->prepareInsertStatus == false) 
            $this->prepareInsert();

        $counter = 0;

        foreach($row as $rowKey => $rowValue)
        {
            foreach($this->getColumnNames() as $columnName)
            {
                if($columnName == $rowKey)
                {
                    $this->result->bindValue(":".$rowKey, $rowValue);
                    $counter++;
                    break;
                }
            }
        }

        try
        {
            $this->result->execute();
            echo "Row inserted successfully";
        }
        catch (PDOException $e)
        {
            throw new Exception("Error inseting into table: " . $e->getMessage());
        }

        if($counter == count($row) && $counter == count($this->getColumnNames()))
            return true;

        else
            return false;

    }


    // Fetch Methods ----------------------------------------------------------------

    public function prepareFetch()
    {
        $this->result = $this->connection->prepare("SELECT * FROM " . $this->getName());
        $this->result->execute();

        $this->prepareFetchStatus = true;
    }

    public function fetchRow(string $fetchMethod = PDO::FETCH_ASSOC)
    {
        if ($this->prepareFetchStatus == false)
            $this->prepareFetch($fetchMethod);

        $tmp = $this->result->fetch($fetchMethod);

        if (!$tmp)
        {
            $this->prepareFetchStatus = false;
        }

        return $tmp;
    } 

    public function fetchAll(string $fetchMethod = PDO::FETCH_ASSOC)
    {
        if ($this->prepareFetchStatus == false)
            $this->prepareFetch();

        return $this->result->fetchAll($fetchMethod);

        $this->prepareFetchStatus = false;
    }
}
?>