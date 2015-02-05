<?php

    namespace lib\mlf21ToSaito472;

    use lib\Table;

    class UsersTable extends Table
    {

        protected $removeColumns = [
          'activate_code',
          'auto_login_code',
          'birthday',
          'category_selection',
          'external_links',
          'fold_threads',
          'gender',
          'last_logout',
          'new_posting_notification',
          'new_user_notification',
          'pwf_code',
          'sidebar',
          'thread_display',
          'time_difference',
          'user_ip',
          'user_view',
        ];

        protected $renameColumns = [
          'auto_reload' => 'user_forum_refresh_time',
          'email_contact' => 'personal_messages',
          'thread_order' => 'user_sort_last_answer',
          'user_id' => 'id',
          'user_location' => 'user_place',
          'user_name' => 'username',
          'user_pw' => 'password'
        ];

        protected $changeColumns = [
          'last_login' => ['timestamp', ['null' => true]],
          'personal_messages' => ['integer', ['limit' => 1, 'default' => 1]],
          'personal_messages' => ['integer', ['limit' => 11, 'default' => 0]],
          'registered' => ['timestamp', ['null' => true]],
          'signature' => ['string', ['limit' => 255, 'null' => true]],
          'user_lock' => ['integer', ['limit' => 1, 'default' => 0]],
          'user_type' => ['string', ['limit' => 255]]
        ];

        protected $addColumns = [
          'activate_code' => [ 'integer', ['limit' => 7, 'default' => 0] ],
          'entry_count' => [ 'integer', ['limit' => 11, 'default' => 0] ],
          'ignore_count' => [ 'integer', ['limit' => 10, 'default' => 0] ],
          'inline_view_on_click' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'last_refresh' => [ 'datetime', ['null' => true, 'after' => 'registered'] ],
          'last_refresh_tmp' => [ 'datetime', ['null' => true, 'after' => 'last_refresh'] ],
          'show_recententries' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'show_recentposts' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'show_shoutbox' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'show_userlist' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'slidetab_order' => [ 'string', ['limit' => 512, 'null' => true] ],
          'user_automaticaly_mark_as_read' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'user_category_active' => [ 'integer', ['limit' => 11, 'default' => 0] ],
          'user_category_custom' => [ 'string', ['limit' => 512, 'null' => true] ],
          'user_category_override' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'user_color_actual_posting' => [ 'string', ['limit' => 255, 'null' => true] ],
          'user_color_new_postings' => [ 'string', ['limit' => 255, 'null' => true] ],
          'user_color_old_postings' => [ 'string', ['limit' => 255, 'null' => true] ],
          'user_place_lat' => [ 'float', ['null' => true, 'after' => 'user_place'] ],
          'user_place_lng' => [ 'float', ['null' => true, 'after' => 'user_place_lat'] ],
          'user_place_zoom' => [ 'integer', ['limit' => 4, 'null' => true, 'after' => 'user_place_lng'] ],
          'user_show_thread_collapsed' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'user_signatures_hide' => [ 'integer', ['limit' => 1, 'default' => 0] ],
          'user_signatures_images_hide' => [ 'integer', ['limit' => 1, 'default' => 0]],
          'user_theme' => [ 'string', ['limit' => 255, 'null' => true] ]
        ];

        protected $addIndices = [
          'UNIQUE INDEX username (username)'
        ];

        public function up()
        {
            $this->upColumns();
            $this->upUserType();
            $this->upIndices();
        }


        protected function upUserType()
        {
            $name = $this->getName();
            $adapter = $this->getAdapter();

            $conversions = [0 => 'user', 1 => 'mod', 2 => 'admin'];
            foreach ($conversions as $old => $new) {
                $query = "UPDATE $name SET user_type='$new' WHERE user_type='$old'";
                $adapter->query($query);
            }
        }

    }
