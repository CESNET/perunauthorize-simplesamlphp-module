<?php

$message = $this->data['message'];

$this->data['header'] = '';
$this->data['403_header'] = $this->t('{perunauthorize:Perunauthorize:403_header}');

$this->includeAtTemplateBase('includes/header.php');

?>

<h1><?php echo $this->data['403_header']; ?></h1>
<div class="alert alert-warning" role="alert">
    <?php echo $message; ?>
</div>

<?php

$this->includeAtTemplateBase('includes/footer.php');
