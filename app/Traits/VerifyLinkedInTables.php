<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait VerifyLinkedInTables {

    private function getTablePrimaryKeyLinked() {
        return null;
    }

    public function countLinkedInTable($tableName,$fk)
    {
        $results = DB::select('SELECT COUNT(*) AS '.$tableName.' FROM '.$tableName." WHERE ".$fk.'= ?',[
            $this->getTablePrimaryKeyLinked()
        ]);

        return $results[0]->{$tableName};
    }

    public function isLinkedInTable($tableName,$fk)
    {
        $count = $this->countLinkedInTable($tableName,$fk);

        return $count > 0 ;
    }

    public function isLinkedInTables($tablesLinked= [])
    {
        $isLinked = false;

        $tables = count($tablesLinked) > 0 ? $tablesLinked : $this->tablesLinked;

        foreach ($tables as $link)
        {
            $isLinked = $this->isLinkedInTable($link[0],$link[1]);

            if($isLinked)
            {
                break;
            }
        }

        return $isLinked;
    }

}
