<?php

if(!class_exists('DatabaseImportTable', false)):
    class DatabaseImportTable
    {
        public function filterStatement($statement, array $filters)
        {
            foreach ($filters as $filter) {
                $statement = $filter->filter($statement);
            }
            return $statement;
        }

        public function import(UmbrellaConnectionInterface $connection, UmbrellaImportState $state, $maxCount = 10000, $filters = [])
        {
            clearstatcache();
            $maxPacket = $realMaxPacket = 0;

            if (is_array($maxPacketResult = $connection->query("SHOW VARIABLES LIKE 'max_allowed_packet'")->fetch())) {
                $maxPacket = $realMaxPacket = (int)end($maxPacketResult);
            }
            if (!$maxPacket) {
                $maxPacket = 128 << 10;
            } elseif ($maxPacket > 512 << 10) {
                $maxPacket = 512 << 10;
            }

            $shifts = 0;

            while (($dump = $state->next()) !== null) {
                // if (strlen($dump->encoding)) {
                //     $connection->execute('SET NAMES utf8');
                // }

                $filePath = $dump->path;
                $stat = getFsStat($filePath);

                if ($stat->getSize() !== $dump->size) {
                    throw new UmbrellaException(sprintf("Inconsistent table dump file size, file %s transferred %d bytes, but on the disk it's %d bytes", $dump->path, $dump->size, $stat->getSize()), 'different_size');
                }
                $scanner = new UmbrellaDumpScanner($filePath);

                if ($dump->processed !== 0) {
                    $scanner->seek($dump->processed);
                }

                $charsetFixer = new UmbrellaCharsetFixer($connection);
                while (strlen($statements = $scanner->scan($maxCount, $maxPacket))) {
                    if ($realMaxPacket && strlen($statements) + 20 > $realMaxPacket) {
                        throw new UmbrellaException(sprintf("A query in the backup (%d bytes) is too big for the SQL server to process (max %d bytes); please set the server's variable 'max_allowed_packet' to at least %d and retry the process", strlen($statements), $realMaxPacket, strlen($statements) + 20), 'db_max_packet_size_reached', strlen($statements));
                    }
                    if (preg_match('{^\s*(?:/\\*!\d+\s*)?set\s+(?:character_set_client\s*=|names\s+)}i', $statements)) {
                        // Skip all the /*!40101 SET character_set_client=*** */; statements.
                        continue;
                    }

                    try {
                        $statements = $this->filterStatement($statements, $filters);
                        $connection->execute($statements);
                        $shifts = 0;

                        if (strncmp($statements, 'DROP TABLE IF EXISTS ', 21) === 0) {
                            $state->pushNextToEnd();
                            // We just dropped a table; switch to next file if available.
                            // This way we will drop all tables before importing new data.
                            // That helps with foreign key constraints.
                            break;
                        }
                    } catch (UmbrellaException $e) {
                        // Super-powerful recovery switch, un-document it to secure your job.
                        switch ($e->getInternalError()) {
                            case '1005': // SQLSTATE[HY000]: General error: 1005 Can't create table 'dbname.wp_wlm_email_queue' (errno: 150)
                                // This looks like an issue specific to InnoDB storage engine.
                            case '1451': // SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails
                                // For "DROP TABLE IF EXISTS..." queries. Sometimes they DO exist.
                            case '1217': // Cannot delete or update a parent row: a foreign key constraint fails
                                // @todo we could drop keys before dropping the database, but we would have to parse SQL :/
                            case '1146': // Table '%s' doesn't exist
                            case '1824': // Failed to open the referenced table '%s'
                            case '1215': // Cannot add foreign key constraint
                                // Possible table reference error, we should suspend this import and go to next file.
                                // Push the currently imported file to end if and only if we're certain that the number of pushes
                                // without a successful statement execution doesn't exceed the number of files being imported;
                                // that would mean that we rotated all the files and would enter an infinite loop.
                                if ($shifts + 1 < count($state->files)) {
                                    // Switch to next file.
                                    $state->pushNextToEnd();
                                    $scanner->close();
                                    $shifts++;
                                    continue 3;
                                }
                                throw new UmbrellaException(cloner_format_query_error($e->getMessage(), $statements, $dump->path, $dump->processed, $scanner->tell(), $dump->size), 'db_query_error', $e->getInternalError());
                            case '1115':
                            case '1273':
                                $newStatements = preg_replace_callback('{utf8mb4[a-z0-9_]*}', [$charsetFixer, 'replaceCharsetOrCollation'], $statements, -1, $count);
                                if ($count) {
                                    try {
                                        $connection->execute($newStatements);
                                        break;
                                    } catch (UmbrellaException $e2) {
                                    }
                                }
                                throw new UmbrellaException(cloner_format_query_error($e->getMessage(), $statements, $dump->path, $dump->processed, $scanner->tell(), $dump->size), 'db_query_error', $e->getInternalError());
                            case '2013':
                                // 2013 Lost connection to MySQL server during query
                            case '2006':
                                // 2006 MySQL server has gone away
                            case '1153':
                                // SQLSTATE[08S01]: Communication link failure: 1153 Got a packet bigger than 'max_allowed_packet' bytes
                                $attempt = 1;
                                $maxAttempts = 4;
                                while (++$attempt <= $maxAttempts) {
                                    usleep(100000 * pow($attempt, 2));
                                    try {
                                        $connection->close();
                                        if ($realMaxPacket && (strlen($statements) * 1.2) > $realMaxPacket) {
                                            // We are certain that the packet size is too big.
                                            $connection->execute(sprintf('SET GLOBAL max_allowed_packet=%d', strlen($statements) + 1024 * 1024));
                                        }
                                        $connection->execute($statements);
                                        break 2;
                                    } catch (Exception $e2) {
                                        trigger_error(sprintf('Could not increase max_allowed_packet: %s for file %s at offset %d', $e2->getMessage(), $dump->path, $scanner->tell()));
                                    }
                                }
                                // We aren't certain of what happened here. Maybe reconnect once?
                                throw new UmbrellaException(cloner_format_query_error($e->getMessage(), $statements, $dump->path, $dump->processed, $scanner->tell(), $dump->size), 'db_query_error', $e->getInternalError());
                            case '1231':
                                // Ignore errors like this:
                                // SQLSTATE[42000]: Syntax error or access violation: 1231 Variable 'character_set_client' can't be set to the value of 'NULL'
                                // We don't save the SQL variable state between imports since we only care about the relevant ones (encoding, timezone).
                                break;
                                //case 1065:
                                // Ignore error "[1065] Query was empty"
                                //  break;
                            case '1067': // SQLSTATE[42000]: Syntax error or access violation: 1067 Invalid default value for 'access_granted'
                                // Most probably NO_ZERO_DATE is ON and the default value is something like 0000-00-00.
                                $currentMode = $connection->query('SELECT @@sql_mode')->fetch();
                                $currentMode = @end($currentMode);
                                if (strlen($currentMode)) {
                                    $modes = explode(',', $currentMode);
                                    $removeModes = ['NO_ZERO_DATE', 'NO_ZERO_IN_DATE'];
                                    foreach ($modes as $i => $mode) {
                                        if (!in_array($mode, $removeModes)) {
                                            continue;
                                        }
                                        unset($modes[$i]);
                                    }
                                    $newMode = implode(',', $modes);
                                    try {
                                        $connection->execute("SET SESSION sql_mode = '$newMode'");
                                        $connection->execute($statements);
                                        // Recovered.
                                        break;
                                    } catch (Exception $e2) {
                                        trigger_error($e2->getMessage());
                                    }
                                }
                                throw new UmbrellaException(cloner_format_query_error($e->getMessage(), $statements, $dump->path, $dump->processed, $scanner->tell(), $dump->size), 'db_query_error', $e->getInternalError());
                            case '1064':
                                // MariaDB compatibility cases.
                                // This is regarding the PAGE_CHECKSUM property.
                            case '1286':
                                // ... and this is regarding the unknown storage engine, e.g.:
                                // CREATE TABLE `name` ( ... ) ENGINE=Aria  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci PAGE_CHECKSUM=1;
                                // results in
                                // SQLSTATE[42000]: Syntax error or access violation: 1286 Unknown storage engine 'Aria'
                                if (strpos($statements, 'PAGE_CHECKSUM') !== false) {
                                    // MariaDB's CREATE TABLE statement has some options
                                    // that MySQL doesn't recognize.
                                    $connection->query(strtr($statements, [
                                        ' ENGINE=Aria ' => ' ENGINE=MyISAM ',
                                        ' PAGE_CHECKSUM=1' => '',
                                        ' PAGE_CHECKSUM=0' => '',
                                    ]));
                                    break;
                                }
                                throw new UmbrellaException(cloner_format_query_error($e->getMessage(), $statements, $dump->path, $dump->processed, $scanner->tell(), $dump->size), 'db_query_error', $e->getInternalError());
                            case '1298':
                                // 1298 Unknown or incorrect time zone
                                break;
                            case '1419':
                                // Triggers require super-user permissions.
                                //
                                //   Query:
                                //   /*!50003 CREATE*/ /*!50003 TRIGGER wp_hmenu_mega_list BEFORE UPDATE ON wp_hmenu_mega_list FOR EACH ROW SET NEW.lastModified = NOW() */;
                                //
                                //   Error:
                                //   SQLSTATE[HY000]: General error: 1419 You do not have the SUPER privilege and binary logging is enabled (you *might* want to use the less safe log_bin_trust_function_creators variable)
                                $state->skipStatement($statements);
                                break;
                            case '1227':
                                if (strncmp($statements, 'SET @@SESSION.', 14) === 0 || strncmp($statements, 'SET @@GLOBAL.', 13) === 0) {
                                    // SET @@SESSION.SQL_LOG_BIN= 0;
                                    // SET @@GLOBAL.GTID_PURGED='';
                                    break;
                                }
                                // Remove strings like DEFINER=`user`@`localhost`, because they generate errors like this:
                                // "[1227] Access denied; you need (at least one of) the SUPER privilege(s) for this operation"
                                // Example of a problematic query:
                                //
                                //  /*!50003 CREATE*/ /*!50017 DEFINER=`user`@`localhost`*/ /*!50003 TRIGGER `wp_hlogin_default_storage_table` BEFORE UPDATE ON `wp_hlogin_default_storage_table`
                                $newStatements = preg_replace('{(/\*!\d+) DEFINER=`[^`]+`@`[^`]+`(\*/ )}', '', $statements, 1, $count);
                                if ($count) {
                                    try {
                                        $connection->execute($newStatements);
                                        break;
                                    } catch (UmbrellaException $e) {
                                    }
                                }

                                if ($dump->type === UmbrellaTableType::PROCEDURE || $dump->type === UmbrellaTableType::FUNC || $dump->type === UmbrellaTableType::VIEW) {
                                    // Try for procedure, function or view to remove strings like DEFINER=`user`@`localhost`
                                    // If it fails just continue, we don't want to break due to problem with functions, procedures or views
                                    $newStatements = preg_replace('{DEFINER=`[^`]+`@`[^`]+`}', '', $statements, 1, $count);
                                    if ($count) {
                                        try {
                                            $connection->execute($newStatements);
                                        } catch (UmbrellaException $e) {
                                            $state->skipStatement($statements);
                                        }
                                    }

                                    break;
                                }

                                throw new UmbrellaException(cloner_format_query_error($e->getMessage(), $statements, $dump->path, $dump->processed, $scanner->tell(), $dump->size), 'db_query_error', $e->getInternalError());
                            case '3167':
                                if (strpos($statements, '@is_rocksdb_supported') !== false) {
                                    // RocksDB support handling for the following case:
                                    //
                                    // /*!50112 SELECT COUNT(*) INTO @is_rocksdb_supported FROM INFORMATION_SCHEMA.SESSION_VARIABLES WHERE VARIABLE_NAME='rocksdb_bulk_load' */;
                                    // /*!50112 SET @save_old_rocksdb_bulk_load = IF (@is_rocksdb_supported, 'SET @old_rocksdb_bulk_load = @@rocksdb_bulk_load', 'SET @dummy_old_rocksdb_bulk_load = 0') */;
                                    // /*!50112 PREPARE s FROM @save_old_rocksdb_bulk_load */;
                                    // /*!50112 EXECUTE s */;
                                    // /*!50112 SET @enable_bulk_load = IF (@is_rocksdb_supported, 'SET SESSION rocksdb_bulk_load = 1', 'SET @dummy_rocksdb_bulk_load = 0') */;
                                    // /*!50112 PREPARE s FROM @enable_bulk_load */;
                                    // /*!50112 EXECUTE s */;
                                    // /*!50112 DEALLOCATE PREPARE s */;
                                    // ... table creation and insert statements ...
                                    // /*!50112 SET @disable_bulk_load = IF (@is_rocksdb_supported, 'SET SESSION rocksdb_bulk_load = @old_rocksdb_bulk_load', 'SET @dummy_rocksdb_bulk_load = 0') */;
                                    // /*!50112 PREPARE s FROM @disable_bulk_load */;
                                    // /*!50112 EXECUTE s */;
                                    // /*!50112 DEALLOCATE PREPARE s */;
                                    //
                                    // Error on the first statement:
                                    //   #3167 - The 'INFORMATION_SCHEMA.SESSION_VARIABLES' feature is disabled; see the documentation for 'show_compatibility_56'
                                    try {
                                        $connection->execute('SET @is_rocksdb_supported = 0');
                                    } catch (UmbrellaException $e2) {
                                        throw new UmbrellaException('Could not recover from RocksDB support patch: ' . $e2->getMessage());
                                    }
                                    break;
                                }
                                throw new UmbrellaException($e->getMessage(), 'db_query_error');
                            default:
                                if ($dump->type !== UmbrellaTableType::REGULAR) {
                                    $state->skipStatement($statements);
                                    break;
                                }
                                throw new UmbrellaException($e->getMessage(), 'db_query_error');
                        }
                    } catch (Exception $e) {
                        error_log($e->getMessage());
                    }

                    $dump->processed = $scanner->tell();
                    // if ($deadline->done()) {
                    // If there are any locked tables we might hang forever with the next query, unlock them.
                    // $connection->execute('UNLOCK TABLES');
                    // We're cutting the import here - remember the encoding!!!
                    // $charset = $connection->query("SHOW VARIABLES LIKE 'character_set_client'")->fetch();
                    // $dump->encoding = (string)end($charset);
                    // break 2;
                    // }
                }

                $dump->processed = $scanner->tell();
                $scanner->close();
            }

            return $state;
        }
    }
endif;
