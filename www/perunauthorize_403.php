<?php
/**
 * Show a 403 Forbidden page about not authorized to access an application.
 *
 * @package SimpleSAMLphp
 */


if (!array_key_exists('StateId', $_REQUEST)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = \SimpleSAML_Auth_State::loadState($_REQUEST['StateId'], 'perunauthorize:Perunauthorize');

$globalConfig = \SimpleSAML_Configuration::getInstance();
$t = new SimpleSAML_XHTML_Template($globalConfig, 'perunauthorize:perunauthorize_403.php');

header('HTTP/1.0 403 Forbidden');
if (isset($_REQUEST['informationURL'])) {
    $t->data['informationURL'] = $_REQUEST['informationURL'];
}
$t->data['administrationContact'] = $_REQUEST['administrationContact'];
$t->data['serviceName'] = $_REQUEST['serviceName'];
$t->show();
