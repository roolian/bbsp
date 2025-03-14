<?php

if(!class_exists('UmbrellaMySQLiConnection', false)):
    class UmbrellaMySQLiConnection implements UmbrellaConnectionInterface
    {
        protected $connection;

        protected $configuration;

        public function getConfiguration()
        {
            return $this->configuration;
        }

        /**
         * @param UmbrellaDatabaseConfiguration
         */
        public function __construct(UmbrellaDatabaseConfiguration $configuration)
        {
            if (!extension_loaded('mysqli')) {
                throw new UmbrellaException('Mysqli extension is not enabled.', 'mysqli_disabled');
            }

            $this->configuration = $configuration;

            mysqli_report(MYSQLI_REPORT_OFF);

            // Silence possible warnings thrown by mysqli
            // e.g. Warning: mysqli::mysqli(): Headers and client library minor version mismatch. Headers:50540 Library:50623

            $flag = 0;
            if ($configuration->useSSL) {
                $flag = MYSQLI_CLIENT_SSL;
            }

            $this->connection = mysqli_init();
            $success = $this->connection->real_connect(
                $configuration->getHostname(),
                $configuration->user,
                $configuration->password,
                $configuration->name,
                $configuration->getPort(),
                null,
                $flag
            );

            if ($success) {
                $this->connection->set_charset(UmbrellaDatabaseFunction::getDatabaseCharset($this));
                return;
            }

            if ($this->connection->connect_errno === 2002 && strtolower($configuration->getHostname()) === 'localhost') {
                // Attempt to recover from "[2002] No such file or directory" error.
                $this->connection = mysqli_init();
                $success = $this->connection->real_connect(
                    '127.0.0.1',
                    $configuration->user,
                    $configuration->password,
                    $configuration->name,
                    $configuration->getPort(),
                    null,
                    $flag
                );
            }

            if (!$success) {
                // Note: The error message is not always accurate, so we don't use it.
                // if(strpos($this->connection->connect_error, 'require_secure_transport') !== false) {
                $this->connection = mysqli_init();
                $success = $this->connection->real_connect(
                    $configuration->getHostname(),
                    $configuration->user,
                    $configuration->password,
                    $configuration->name,
                    $configuration->getPort(),
                    null,
                    MYSQLI_CLIENT_SSL
                );
                // }
            }

            if(!$success) {
                throw new UmbrellaException($this->connection->connect_error, 'db_connect_error_mysqli', $this->connection->connect_errno);
            }

            $this->connection->set_charset(UmbrellaDatabaseFunction::getDatabaseCharset($this));
        }

        public function query($query, array $parameters = [], $unbuffered = false)
        {
            $query = UmbrellaDatabaseFunction::bindQueryParams($this, $query, $parameters);

            $resultMode = $unbuffered ? MYSQLI_USE_RESULT : 0;
            $result = $this->connection->query($query, $resultMode);

            // There are certain warnings that result in $result being false, eg. PHP Warning:  mysqli::query(): Empty query,
            // but the error number is 0.
            if ($result === false && $this->connection->errno !== 0) {
                throw new UmbrellaException($this->connection->error, 'db_query_error', $this->connection->errno);
            }

            return new UmbrellaMySQLiStatement($this->connection, $result);
        }

        public function execute($query)
        {
            $this->query($query);
        }

        public function escape($value)
        {
            return $value === null ? 'null' : "'" . $this->connection->real_escape_string($value) . "'";
        }

        public function close()
        {
            if (empty($this->connection)) {
                return;
            }
            $this->connection->close();
            $this->connection = null;
        }
    }

endif;
