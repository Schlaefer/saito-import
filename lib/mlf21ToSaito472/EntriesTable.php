<?php

    namespace lib\mlf21ToSaito472;

    use lib\Table;

    class EntriesTable extends Table
    {

        protected $removeColumns = [
          'edit_key',
          'email',
          'hp',
          'location',
          'marked',
          'show_signature',
          'spam',
          'spam_check_status',
          'tags',
          'uniqid',
        ];

        protected $renameColumns = [
          'category' => 'category_id',
          'email_notification' => 'email_notify',
          'last_reply' => 'last_answer',
          'sticky' => 'fixed',
        ];

        protected $changeColumns = [
          'edited' => ['timestamp', ['null' => true]],
          'edited_by' => ['string', ['limit' => 255, 'null' => true]],
          'ip' => ['string', ['limit' => 39, 'null' => true]],
          'last_answer' => ['timestamp', ['null' => true]],
          'time' => ['timestamp', ['null' => true]],
        ];

        protected $addColumns = [
            'solves' => ['integer', ['limit' => 11, 'default' => 0]]
        ];

        protected $removeIndices = ['id', 'tid', 'pid', 'fixed', 'category_id'];

        protected $addIndices = [
          'INDEX tid (tid)',
          'INDEX entries_userId (user_id)',
          'INDEX last_answer (last_answer)',
          'INDEX pft (pid, fixed, time, category_id)',
          'INDEX pfl (pid, fixed, last_answer, category_id)',
          'INDEX pid_category (pid, category_id)',
          'INDEX entries_userId_time (time, user_id)',
          'FULLTEXT INDEX fulltext_search (subject, name, text)',
        ];

        public function up()
        {
            $this->upColumns();

            $this->upEditedBy();
            $this->upName();
            $this->updateTableAddCreatedAndModifiedColumns();

            $this->upIndices();
        }

        protected function upEditedBy() {
            $name = $this->getName();
            $adapter = $this->getAdapter();

            $users = $adapter->fetchAll("SELECT DISTINCT(edited_by), username FROM $name LEFT JOIN users ON $name.edited_by = users.id WHERE edited_by IS NOT NULL AND user_id != 0");
            foreach ($users as $user) {
                $username = $this->quote($user['username']);
                $adapter->execute("UPDATE $name SET edited_by=$username WHERE edited_by={$user['edited_by']}");
            }
        }

        protected function upName()
        {
            $name = $this->getName();
            $adapter = $this->getAdapter();

            $users = $adapter->fetchAll("SELECT DISTINCT(user_id), username FROM $name LEFT JOIN users ON $name.user_id = users.id WHERE name LIKE '' AND user_id != 0");
            foreach ($users as $user) {
                $username = $this->quote($user['username']);
                $adapter->execute("UPDATE $name SET name=$username WHERE user_id={$user['user_id']}");
            }
        }

    }
