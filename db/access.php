<?php
/**
 * Plugin strings are defined here.
 *
 * @package     tool_copy_courses
 * @category    string
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'tool/copy_courses:writeinstance'  => [
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => [
            'editingteacher' => CAP_PROHIBIT,
            'guest'=>CAP_PROHIBIT,
            'manager'=>CAP_PROHIBIT,
            'teacher'=>CAP_PROHIBIT,
            'student'=>CAP_PROHIBIT
        ],
    ],
];
