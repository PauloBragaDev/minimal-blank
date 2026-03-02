<?php $this->layout("_theme"); $this->start("styles"); ?>

<?php $this->end(); ?>

<div class="container py-4">
    <div class="ajax_response"><?= flash(); ?></div>
    <h1><?php echo $userOnline->name; ?></h1>
    <a href="<?php echo url("/app/logoff"); ?>">Sair</a>
</div>