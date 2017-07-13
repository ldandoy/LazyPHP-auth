<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Compte de l'administeur: <?php echo $params['administrator']->id ?></h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_administrators" type="secondary" class="btn-sm" icon="arrow-left" content="" %}
        </div>
    </div>
    <div class="box-body">
        <p>Prénom: <?php echo $params['administrator']->firstname ?></p>
        <p>Nom: <?php echo $params['administrator']->lastname ?></p>
        <p>Mail: <?php echo $params['administrator']->email ?></p>
        <p>
            Status:
<?php
if ($params['administrator']->active == 1) {
    echo '<span class="label label-success">Activé</span>';
} else {
    echo '<span class="label label-danger">Désactivé</span>';
}
?>
        </p>
    </div>
</div>
