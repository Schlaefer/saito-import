<?php

    namespace lib;

    class Table extends \Phinx\Db\Table
    {

        protected $removeColumns = [];

        protected $renameColumns = [];

        protected $changeColumns = [];

        protected $addColumns = [];

        protected $removeIndices = [];

        protected $addIndices = [];

        public function up()
        {
            $this->upColumns();
            $this->upIndices();
        }

        protected function upColumns() {
             foreach ($this->removeColumns as $name) {
                $this->removeColumn($name);
            }

            foreach ($this->renameColumns as $old => $new) {
                $this->renameColumn($old, $new);
            }

            foreach ($this->changeColumns as $name => $args) {
                call_user_func_array(
                  [$this, 'changeColumn'],
                  array_merge([$name], $args)
                );
                $this->update();
            }

            foreach ($this->addColumns as $name => $args) {
                call_user_func_array(
                  [$this, 'addColumn'],
                  array_merge([$name], $args)
                );
                $this->save();
            }
        }

        protected function upIndices() {
            foreach ($this->removeIndices as $name) {
                $this->removeIndex([$name]);
            }

            $name = $this->getName();
            $adapter = $this->getAdapter();

            foreach ($this->addIndices as $query) {
                $sql = "ALTER TABLE $name ADD " . $query;
                $adapter->execute($sql);
            }
        }

        protected function quote($string)
        {
            return $this->getAdapter()->getConnection()->quote($string);
        }

        protected function updateTableAddCreatedAndModifiedColumns()
        {
            foreach (['created', 'modified'] as $key) {
                $this->addColumn($key, 'datetime', ['null' => true]);
            }
            $this->save();
        }


    }