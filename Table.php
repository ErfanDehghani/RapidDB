<?php 

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

    public function createTableQuery() 
    {
        // Start creating the query
        $query = "CREATE TABLE IF NOT EXISTS `$this->name` (";
        
        // Add columns query string;
        foreach ($this->columns as $column)
            $query .= $column . " ,";
        
        // Remove the last comma from the query
        $query = rtrim($query, ',');
        
        // Close the query
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

    public function delete($id)
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

    public function update()
    {
    }

    // insert Method ----------------------------------------------------------------

    public function dropTable()
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


    // insert Method ----------------------------------------------------------------

    private function prepareInsert()
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

    // End of insert Method ----------------------------------------------------------------


    // Fetch Method ----------------------------------------------------------------

    public function prepareFetch(string $fetchMethod = null)
    {
        $this->result = $this->connection->prepare("SELECT * FROM " . $this->getName());
        $this->result->execute();

        $this->result->setFetchMethod($fetchMethod);
        $this->prepareFetchStatus = true;
    }

    public function fetchRow(string $fetchMethod = PDO::FETCH_ASSOC)
    {
        if ($this->prepareFetchStatus == false)
            $this->prepareFetch($fetchMethod);

        $tmp = $this->result->fetch();

        if (!$tmp)
        {
            $this->prepareFetchStatus = false;
        }

        return $tmp;
    } 

    public function fetchAll(string $fetchMethod = PDO::FETCH_ASSOC)
    {
        if ($this->prepareFetchStatus == false)
            $this->prepareFetch($fetchMethod);

        return $this->result->fetchAll();

        $this->prepareFetchStatus = false;
    }

    // End of fetch Method ----------------------------------------------------------------
}
?>