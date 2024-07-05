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

$string['pluginname'] = 'Copia masiva de cursos';
$string['titlepage'] = 'Copiar cursos de forma masiva';
$string['copy_courses:writeinstance'] = 'Copia masiva de cursos';
$string['uploadcsv'] = 'Subir archivo CSV';
$string['type_csv'] = 'Archivo separado por';
$string['error_num_columns'] = 'No hay suficientes columnas';
$string['error_in_columns'] = 'Revise las columnas del archivo';
$string['nocapability'] = 'No tiene permiso para esta página';
$string['cat_no_validate'] = '<span class="text-danger">ID de categoría no válido</span>';
$string['courseid_no_validate'] = '<span class="text-danger">Nombre corto del curso no válido</span>';
$string['enddate_no_validate'] = '<span class="text-danger">Fecha de finalización no válida</span>';
$string['fullname_no_validate'] = '<span class="text-danger">Nombre completo no válido</span>';
$string['shortname_no_validate'] = '<span class="text-danger">Ya existe un curso con el nombre corto ingresado</span>';
$string['startdate_no_validate'] = '<span class="text-danger">Fecha de inicio no válida</span>';
$string['visible_no_validate'] = '<span class="text-danger">Visible solo puede ser 0 o 1</span>';
$string['validate'] = '<span class="text-success">Validación correcta</span>';
$string['dates_not_validates'] = '<span class="text-danger">La fecha de inicio debe ser menor a la fecha de cierre</span>';
$string['shortname_no_duplicate'] = '<span class="text-danger">Nombre corto duplicado</span>';
$string['notification_validate'] = "<hr>Tenga en cuenta que al continuar, solo se crearán las tareas para los cursos que pasaron la validación";
$string['create_task'] = 'Crear tareas';
$string['finaly_notification'] = 'Las tareas han sido creadas';
$string['return_site'] = 'Volver';
$string['linkfile'] = 'Archivo de ejemplo';
$string['indications'] = 'El archivo CSV subido debe tener las siguientes columnas:';
$string['copyshortname'] = 'Hace referencia al shortname del curso desde el que se desea copiar';
$string['fullname'] = 'Es el nombre o fullname del curso nuevo';
$string['shortname'] = 'Es el shortname o nombre corto del nuevo curso';
$string['category'] = 'Es el id de la categoria para el nuevo curso';
$string['visible'] = 'Indica si el nuevo curso debe quedar visible o no. Con 1 queda visible, con 0 queda oculto';
$string['startdate'] = 'Es la fecha de inicio del curso, por ejemplo: 2024-09-15 15:00:00';
$string['enddate'] = 'Es la fecha de finalización del curso, por ejemplo: 2024-12-15 15:00:00';