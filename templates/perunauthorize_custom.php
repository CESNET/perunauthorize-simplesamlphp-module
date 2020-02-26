<?php
/**
 * Template perunauthorize_custom.php
 *
 * @author Pavel Vyskocil <pavel.vyskocil@cesnet.cz>
 */
$this->data['header'] = '';
$this->data['head'] = '<link rel="stylesheet"  media="screen" type="text/css" href="' .
    SimpleSAML\Module::getModuleUrl('perunauthorize/res/css/authorize.css') . '" />';
$contactMail = $this->data['contactMail'];
$this->includeAtTemplateBase('includes/header.php');
?>

    <div class="error_message">
        <h1><?php echo $this->data['page_header']; ?></h1>
        <p><?php echo $this->data['page_text'];?></p>
        <br/>
        <?php
        if (isset($contactMail)) {
            echo '<p>' . $this->t('{perunauthorize:Perunauthorize:403_custom_contact_us}') . '<a href="mailto:' .
                $contactMail . '">' . $this->t('{perunauthorize:Perunauthorize:here}') . '</a></p>' ;
        }
        ?>
    </div>

<?php

$this->includeAtTemplateBase('includes/footer.php');
