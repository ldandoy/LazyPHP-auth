<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_groups_new" type="success" size="sm" icon="plus" hint="Ajouter" %}
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Accès cockpit</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($groups as $group) {
    if ($group->cockpit == 1) {
        $cockpit = '<i class="fa fa-check text-success"></i>';
    } else {
        $cockpit = '<i class="fa fa-times text-danger"></i>';
    }

    echo
        '<tr>'.
            '<td>'.$group->id.'</td>'.
            '<td>'.$group->code.'</td>'.
            '<td>'.$group->label.'</td>'.
            '<td>'.$cockpit.'</td>'.
            '<td>';?>
                {% button url="cockpit_auth_groups_edit_<?php echo $group->id; ?>" type="info" size="sm" icon="pencil" hint="Modifier" %}
                {% button url="cockpit_auth_groups_delete_<?php echo $group->id; ?>" type="danger" size="sm" icon="trash-o" confirmation="Vous confirmer vouloir supprimer ce groupe ?" hint="Supprimer" %}
<?php
    echo
            '</td>'.
        '</tr>';
}
?>
            </tbody>
        </table>
    </div>
</div>
