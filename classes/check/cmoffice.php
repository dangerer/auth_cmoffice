<?php

namespace auth_cmoffice\check;

defined('MOODLE_INTERNAL') || die();

use core\check\result;

/**
 * Verifies unsupported typo3_cmoffice setting
 *
 * @copyright  2022 Stefan Swerk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cmoffice extends \core\check\check {

    public function get_action_link(): ?\action_link {
        return new \action_link(
            new \moodle_url('/admin/settings.php?section=manageauths'),
            get_string('authsettings', 'admin'));
    }

    public function get_result(): result {
        if (is_enabled_auth('cmoffice')) {
            $status = result::ERROR;
            $summary = get_string('checknoautherror', 'auth_cmoffice');
        } else {
            $status = result::OK;
            $summary = get_string('checknoauthok', 'auth_cmoffice');
        }
        $details = get_string('checknoauthdetails', 'auth_cmoffice');

        return new result($status, $summary, $details);
    }
}

