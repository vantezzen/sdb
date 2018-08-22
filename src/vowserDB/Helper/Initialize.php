<?php
/**
 * vowserDB Creation Helper
 * Helper for the creation and initialization of tables and databases
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://vantezzen.github.io/vowserdb-docs/index.html vowserDB
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @version       4.0.0 - Alpha 1
 */

namespace vowserDB\Helper;

use vowserDB\CSVFile;
use Exception;

class Initialize {
    /**
     * Pre-made templates that can be used when creating a new table
     * 
     * @type array
     */
    protected $templates = [
        "users" => array(
            "username",
            "uuid",
            "password",
            "mail",
            "data"
        ),
        "posts" => array(
            "uuid",
            "post_id",
            "type",
            "data",
            "created_date"
        )
    ];

    /**
     * Create and initialize database folder
     * If the database folder doesn't exist it will be created
     * If vowserDB doesn't have the needed rights it will throw an error
     * 
     * @param string $path  Path to the database folder
     * @return bool True, if initialization and creation was successful, false if not
     */
    public static function database($path) {
        $folder = dirname($path);
        if (!file_exists($folder)) {
            try {
                mkdir($folder);
            } catch(Exception $e) {
                throw new Exception("vowserDB database folder doesn't exist and couldn't be created.");
                throw new $e;
                return false;
            }
        }
        if (!is_readable($folder)) {
            throw new Exception("vowserDB database folder is not readable.");
            return false;
        }
        if (!is_writable($folder)) {
            throw new Exception("vowserDB database folder is not writable.");
            return false;
        }
        return true;
    }

    /**
     * Create and initialize a table with given column names
     * If the table doesn't exist it will be created and the column declreation row will be written
     * This function supports templates as columns
     * 
     * @param string $path  Path to the table
     * @param mixed $columns Column array or template to initialize table with
     * @param array $additionalColumns Columns to add to a given template (optional)
     * @return bool Success state of the initialization
     */
    public static function table($path, $columns, $additionalColumns = false) {
        if (!self::database($path)) {
            return false;
        }
        if (!file_exists($path)) {
            if (empty($columns) || $columns == false) {
                throw new Exception("No columns for vowserDB table given.");
                return false;
            } else if (!is_array($columns)) {
                $columns = self::$templates[$columns];
                if ($additionalColumns !== false) {
                    $columns = array_merge($columns, $additionalColumns);
                }
            }
            CSVFile::writeColumns($path, $columns);
        }
        return true;
    }
}