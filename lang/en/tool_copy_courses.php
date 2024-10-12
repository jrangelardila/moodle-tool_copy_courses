<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     tool_copy_courses
 * @category    string
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Massive Course Copy';
$string['titlepage'] = 'Copy Courses Massively';
$string['copy_courses:writeinstance'] = 'Massive Course Copy';
$string['uploadcsv'] = 'Upload CSV File';
$string['type_csv'] = 'File separated by';
$string['error_num_columns'] = 'Not enough columns';
$string['error_in_columns'] = 'Check the file columns';
$string['nocapability'] = 'You do not have permission for this page';
$string['cat_no_validate'] = '<span class="text-danger">Invalid category ID</span>';
$string['courseid_no_validate'] = '<span class="text-danger">Invalid course shortname</span>';
$string['enddate_no_validate'] = '<span class="text-danger">Invalid end date</span>';
$string['fullname_no_validate'] = '<span class="text-danger">Invalid full name</span>';
$string['shortname_no_validate'] = '<span class="text-danger">A course with the entered shortname already exists</span>';
$string['startdate_no_validate'] = '<span class="text-danger">Invalid start date</span>';
$string['visible_no_validate'] = '<span class="text-danger">Visible can only be 0 or 1</span>';
$string['validate'] = '<span class="text-success">Validation successful</span>';
$string['dates_not_validates'] = '<span class="text-danger">The start date must be earlier than the end date</span>';
$string['shortname_no_duplicate'] = '<span class="text-danger">Duplicate shortname</span>';
$string['notification_validate'] = "<hr>Note that by continuing, tasks will only be created for the courses that passed the validation";
$string['create_task'] = 'Create Tasks';
$string['finaly_notification'] = 'Tasks have been created';
$string['return_site'] = 'Return';
$string['linkfile'] = 'File example';
$string['indications'] = 'The uploaded CSV file must have the following columns:';
$string['copyshortname'] = 'Refers to the shortname of the course from which you want to copy.';
$string['fullname'] = 'Is the name or fullname of the new course.';
$string['shortname'] = 'Is the shortname of the new course.';
$string['category'] = 'Is the category id for the new course.';
$string['visible'] = 'Indicates whether the new course should be visible or not. Use 1 for visible, 0 for hidden.';
$string['startdate'] = 'Is the start date of the course, for example: 2024-09-15 15:00:00.';
$string['enddate'] = 'Is the end date of the course, for example: 2024-12-15 15:00:00.';

