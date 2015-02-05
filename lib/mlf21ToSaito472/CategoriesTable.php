<?php


    namespace lib\mlf21ToSaito472;

    use lib\Table;

    class CategoriesTable extends Table
    {

        protected $renameColumns = [
          'order_id' => 'category_order'
        ];


        protected $addColumns = [
          'thread_count' => ['integer', ['limit' => 11, 'null' => true]]
        ];

    }
