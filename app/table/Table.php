<?php
class Table
{
    private $table;
    private $db;

    /**
     * DbAuth constructor.
     * @param Database $db
     */
    public function __construct(Database $db, $table)
    {
        $this->db = $db;
        $this->table= $table;

    }


    public function Query($statement, $attributes=null, $one=false){
        if ($attributes){
            return $this->db->prepare(
                $statement,
                $attributes,
                $this->table,
                $one
            );
        }else{
            return $this->db->query(
                $statement,
                $this->table,
                $one
            );
        }
    }


    public function create($fields){
        $sql_parts=[];
        $attributes=[];
        foreach ($fields as $k => $v){
            $sql_parts[]="$k= ?"; //pour gestion ecole $k=$k+?
            $attributes []=$v;
        }

        $sql_part=implode(',',$sql_parts);


        return $this->Query("INSERT INTO {$this->table} SET $sql_part ", $attributes, true);
    }

    public function update($id,$primaryKey, $fields){
        $sql_parts=[];
        $attributes=[];
        foreach ($fields as $k => $v){
            $sql_parts[]="$k= ?"; //pour gestion ecole $k=$k+?
            $attributes []=$v;
        }
        $attributes[]=$id;
        $sql_part=implode(',',$sql_parts);


        return $this->Query("UPDATE {$this->table} SET $sql_part WHERE $primaryKey=?", $attributes, true);
    }

    public function delete($id, $primaryKey){
        return $this->Query("DELETE FROM {$this->table} WHERE $primaryKey=?", [$id], true);
    }
}