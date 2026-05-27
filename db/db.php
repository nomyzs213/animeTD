$dbSchema = file_get_contents("dbSchema.sql");

function startDB(){
    static $db = null;
      if($db !== null){
        return $db;
    }
    try{
        $db = new PDO("sqlite:clicker.db");
        $db->exec($dbSchema);
        $db->exec('PRAGMA foreign_keys = ON;');
        return $db;
    }
    catch(PDOException $e){
        throw 
    }
    
};

