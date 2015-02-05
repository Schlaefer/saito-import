<?php

    use lib\mlf21ToSaito472\MigrationsClass;

    /**
     * migrates mylittleforum 2.1 tables to Saito 4.7
     */
    class Mlf21ToSaito472 extends MigrationsClass
    {

        /**
         * Migrate Up.
         */
        public function up()
        {
            $this->removePrefix();
            $this->mlf2DropUnusedTables();
            $this->mlf2RenameReusedTables();
            $this->upTable('categories');
            $this->upTable('users');
            $this->upTable('entries');
            $this->changeDbEngine();
        }

        /**
         * Migrate Down.
         */
        public function down()
        {

        }

        protected function changeDbEngine()
        {
            $adapter = $this->getAdapter();
            $query = 'ALTER TABLE users ENGINE=InnoDB;';
            $adapter->execute($query);
        }

        protected function removePrefix()
        {
            echo 'Please enter your table prefix: ';
            $tablePrefix = trim(fgets(STDIN));

            if (empty($tablePrefix)) {
                return;
            }


            $tables = [
              'banlists',
              'categories',
              'entries',
              'entries_cache',
              'logincontrol',
              'pages',
              'settings',
              'smilies',
              'userdata',
              'userdata_cache',
              'useronline'
            ];
            foreach ($tables as $name) {
                $old = $tablePrefix . $name;
                if ($this->hasTable($old)) {
                    $table = $this->table($old);
                    $table->rename($name);
                }
            }
        }

        protected function mlf2RenameReusedTables()
        {
            $reused = ['userdata' => 'users'];
            foreach ($reused as $name => $new) {
                if ($this->hasTable($name)) {
                    $table = $this->table($name);
                    $table->rename($new);
                }
            }
        }

        protected function mlf2DropUnusedTables()
        {
            $unused = [
              'banlists',
              'entries_cache',
              'logincontrol',
              'pages',
              'settings',
              'smilies',
              'userdata_cache',
              'useronline'
            ];
            foreach ($unused as $name) {
                if ($this->hasTable($name)) {
                    $this->dropTable($name);
                }
            }
        }

    }