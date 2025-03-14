<?php

if (!class_exists('UmbrellaConnectionInterface', false)):
    interface UmbrellaConnectionInterface
    {
        /**
         * @param string $query
         * @param array  $parameters
         * @param bool   $unbuffered Set to true to not fetch all results into memory and to incrementally read from SQL server.
         *                           See http://php.net/manual/en/mysqlinfo.concepts.buffering.php
         *
         * @return UmbrellaDatabaseStatementInterface
         *
         */
        public function query($query, array $parameters = array(), $unbuffered = false);

        /**
         * No-return-value version of the query() method. Allows adapters
         * to optionally optimize the operation.
         *
         * @param string $query
         *
         */
        public function execute($query);

        /**
         * Escapes string for safe use in statements; quotes are included.
         *
         * @param string $value
         *
         * @return string
         *
         */
        public function escape($value);

        /**
         * Closes the connection.
         */
        public function close();
    }
endif;
