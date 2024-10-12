<?php
/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     tool_copy_courses
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_copy_courses\privacy;

use core_privacy\local\metadata\null_provider;

class provider implements null_provider
{

    /**
     * @inheritDoc
     */
    public static function get_reason(): string
    {
        return 'privacy:metadata';
    }
}