<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_roles_new" type="success" size="sm" icon="plus" %}
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th>Code</th>
                    <th>Nom</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($roles as $role) {
    echo
        '<tr>'.
            '<td>'.$role->id.'</td>'.
            '<td>'.$role->code.'</td>'.
            '<td>'.$role->label.'</td>'.
            '<td>';?>
    {% button url="cockpit_auth_roles_edit_<?php echo $role->id; ?>" type="info" size="sm" icon="pencil" %}
    {% button url="cockpit_auth_roles_delete_<?php echo $role->id; ?>" type="danger" size="sm" icon="trash-o" confirmation="Vous confirmer vouloir supprimer ce rôle?" %}
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

<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Modifier les affectations des rôles</h3>
    </div>
    <div class="box-body">
        {% button url="cockpit_auth_roleassignments" type="info" content="Affectations des rôles" icon="list" %}
    </div>
</div>
