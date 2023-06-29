<?php

namespace auth_cmoffice\privacy;
defined('MOODLE_INTERNAL') || die();
/**
 * Privacy Subsystem for auth_cmoffice implementing null_provider.
 *
 * @copyright  2022 Stefan Swerk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\null_provider {
    /**
     * Get the language string identifier with the component's language
     * file to explain why this plugin stores specific data.
     *
     * @return  string
     */
    public static function get_reason() : string {
        return 'privacy:metadata';
    }
}