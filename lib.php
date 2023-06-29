<?php
/**
 * Anybody can login with a Typo3 session id.
 *
 * @package    auth_cmoffice
 * @category   check
 * @copyright  2022 Stefan Swerk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add security check to make sure this isn't on in production.
 *
 * @return array check
 */
function auth_cmoffice_security_checks() {
    return [new auth_cmoffice\check\cmoffice()];
}

