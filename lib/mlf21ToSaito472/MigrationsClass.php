<?php

    namespace lib\mlf21ToSaito472;

    use Phinx\Db\Table;
    use Phinx\Migration\AbstractMigration;

    class MigrationsClass extends AbstractMigration {

        public function table($tableName, $options = array())
        {
            $tableClass = '\lib\mlf21ToSaito472\\' . ucfirst($tableName) . 'Table';
            if (!class_exists($tableClass)) {
                $tableClass ='\Phinx\Db\Table';
            }
            return new $tableClass($tableName, $options, $this->getAdapter());
        }

        public function upTable($table) {
            $table = $this->table($table);
            $table->up();
        }

    }