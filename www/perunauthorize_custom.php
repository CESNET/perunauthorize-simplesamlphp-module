<?php

use SimpleSAML\Error\BadRequest;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Locale\Translate;
use SimpleSAML\XHTML\Template;

/**
 * Show a Forbidden page with custom header and text (added as param)
 *
 * @package SimpleSAMLphp
 *
 * @author Pavel Vyskocil <pavel.vyskocil@cesnet.cz>
 */
const HEADER_TAG = '403_header_tag';
const TEXT_TAG = '403_text_tag';

const HEADER = '{perunauthorize:Perunauthorize:403_custom_default_header}';
const TEXT = '{perunauthorize:Perunauthorize:403_custom_default_text}';

if (!array_key_exists('StateId', $_REQUEST)) {
    throw new BadRequest('Missing required StateId query parameter.');
}

$config = Configuration::getInstance();

$contactMail = $config->getString('technicalcontact_email', null);

$state = State::loadState($_REQUEST['StateId'], 'perunauthorize:Perunauthorize');
$translate = new Translate($config);

$t = new Template($config, 'perunauthorize:perunauthorize_custom.php');

header('HTTP/1.0 403 Forbidden');

if (isset($_REQUEST[HEADER_TAG], $_REQUEST[TEXT_TAG])) {
    $header_tag = $_REQUEST[HEADER_TAG];
    $text_tag = $_REQUEST[TEXT_TAG];
}

if (isset($header_tag, $text_tag)) {
    $t->data['page_header'] = $translate->t($header_tag);
    $t->data['page_text'] = $translate->t($text_tag);
} else {
    $t->data['page_header'] = $translate->t(HEADER);
    $t->data['page_text'] = $translate->t(TEXT);
}
$t->data['contactMail'] = $contactMail;
$t->show();
