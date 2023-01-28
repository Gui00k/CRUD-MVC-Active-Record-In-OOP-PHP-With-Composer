<?php 
namespace app\database\activerecord;

use Exception;
use Throwable;
use app\database\connection\Connection;
use app\database\interfaces\ActiveRecordInterface;
use app\database\interfaces\ActiveRecordExecuteInterface;

class Delete implements ActiveRecordExecuteInterface{
    public function __construct(private string $field, private string|int $value){

    }
    public function execute(ActiveRecordInterface $activeRecordInterface){
        try {
            $query = $this->createQuery($activeRecordInterface);
            $connection = Connection::connect();
            $prepare = $connection->prepare($query);
            $prepare->execute([
                $this->field => $this->value
            ]);
            return $prepare->rowCount();
        } catch (Throwable $th) {
            formatException($th);
        }
    }
    public function createQuery(ActiveRecordInterface $activeRecordInterface){
        if($activeRecordInterface->getAttributes()){
            throw new Exception("Para deletar nao precisa de parametros", 1);
            
        }
        $sql = "delete from {$activeRecordInterface->getTable()}";
        $sql.=" where {$this->field} = :{$this->field}";
        return $sql;
    }
}

?>