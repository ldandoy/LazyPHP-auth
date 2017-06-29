<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_groups_new" type="success" size="xs" icon="plus" %}
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th>Nom</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($groups as $group) {
    echo
        '<tr>'.
            '<td>'.$group->id.'</td>'.
            '<td>'.$group->label.'</td>'.
            '<td>';?>
    {% button url="cockpit_auth_groups_edit_<?php echo $group->id; ?>" type="info" size="xs" icon="pencil" %}
    {% button url="cockpit_auth_groups_delete_<?php echo $group->id; ?>" type="danger" size="xs" icon="trash-o" confirmation="Vous confirmer vouloir supprimer ce group?" %}
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