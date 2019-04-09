<script>
    // When the user clicks on <div>, open the popup
    function myFunction() {
        var popup = document.getElementById("myPopup");
        popup.classList.toggle("show");
    }
</script>

<?php

/**
 * Template which is shown when there is only a short interval since the user was last authenticated.
 *
 * Parameters:
 * - 'target': Target URL.
 * - 'params': Parameters which should be included in the request.
 *
 * @package SimpleSAMLphp
 */

$this->data['header'] = "";
$this->data['403_header'] = $this->t('{perunauthorize:Perunauthorize:403_header}');
$this->data['403_text'] = $this->t('{perunauthorize:Perunauthorize:403_text}');
$this->data['403_subject'] = $this->t('{perunauthorize:Perunauthorize:403_subject}');
$this->data['403_informationPage'] = $this->t('{perunauthorize:Perunauthorize:403_informationPage}');
$this->data['403_contactSupport'] = $this->t('{perunauthorize:Perunauthorize:403_contactSupport}');

function getBaseURL($t, $type = 'get', $key = null, $value = null)
{
    if (isset($t->data['informationURL'])) {
        $vars = array(
            'informationURL' => $t->data['informationURL'],
            'administrationContact' => $t->data['administrationContact'],
            'serviceName' => $t->data['serviceName'],
        );
    } else {
        $vars = array(
            'administrationContact' => $t->data['administrationContact'],
            'serviceName' => $t->data['serviceName'],
        );
    }

    if (isset($key)) {
        if (isset($vars[$key])) {
            unset($vars[$key]);
        }
        if (isset($value)) {
            $vars[$key] = $value;
        }
    }

    if ($type === 'get') {
        return 'perunauthorize_403.php?' . http_build_query($vars, '', '&amp;');
    } else {
        $text = '';
        foreach ($vars as $k => $v) {
            $text .= '<input type="hidden" name="' . $k . '" value="' . htmlspecialchars($v) . '" />' . "\n";
        }
        return $text;
    }
}

$this->includeAtTemplateBase('includes/header.php');

?>

<style>
    .error_message {
        word-wrap: break-word;
    }
</style>

<div class="error_message">
    <h1><?php echo $this->data['403_header']; ?></h1>
    <p><?php echo $this->data['403_text'] . $this->data['serviceName'] . "<br>";
    if (isset($this->data['informationURL'])) {
        echo "( " . $this->data['403_informationPage'] . "<a href=\"" . $this->data['informationURL'] . "\">" .
            $this->data['informationURL'] . "</a>" . " )";
    } ?></p>

    <p>
        <?php echo $this->data['403_contactSupport'] .
            "<a href=\"mailto:" . $this->data['administrationContact'] . "?subject=" .
            $this->data['403_subject'] . $this->data['serviceName'] .
            "\">" . $this->data['administrationContact'] . "</a>" . "."; ?>
    </p>
</div>

<?php
$this->includeAtTemplateBase('includes/footer.php');
