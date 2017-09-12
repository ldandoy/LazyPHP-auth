<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Compte de l'administeur: <?php echo $administrator->id ?></h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_administrators" type="secondary" size="sm" icon="arrow-left" hint="Retour" %}
        </div>
    </div>
    <div class="box-body">
        <p>Prénom: <?php echo $administrator->firstname ?></p>
        <p>Nom: <?php echo $administrator->lastname ?></p>
        <p>Mail: <?php echo $administrator->email ?></p>
        <p>
            Status:
<?php
if ($administrator->active == 1) {
    echo '<span class="badge badge-success">Activé</span>';
} else {
    echo '<span class="badge badge-danger">Désactivé</span>';
}
?>
        </p>
    </div>
</div>
