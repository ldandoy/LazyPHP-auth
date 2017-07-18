<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Liste des Administrateurs</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_administrators_new" type="success" size="sm" icon="plus" %}
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th>Site</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Groupe</th>
                    <th>Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($params['administrators'] as $administrator) {
    if ($administrator->active == 1) {
        $active = '<span class="label label-success">Activé</span>';
    } else {
        $active = '<span class="label label-danger">Désactivé</span>';
    }

    $site = $administrator->site_id !== null ? $administrator->site->label : '*';

    echo
        '<tr>'.
            '<td>'.$administrator->id.'</td>'.
            '<td>'.$site.'</td>'.
            '<td>'.$administrator->getFullname().'</td>'.
            '<td>'.$administrator->email.'</td>'.
            '<td>'.($administrator->group_id != null ? $administrator->group->label : '').'</td>'.
            '<td>'.$active.'</td>'.
            '<td>';?>
    {% button url="cockpit_auth_administrators_show_<?php echo $administrator->id; ?>" type="primary" size="sm" icon="eye" %}
    {% button url="cockpit_auth_administrators_edit_<?php echo $administrator->id; ?>" type="info" size="sm" icon="pencil" %}
    {% button url="cockpit_auth_administrators_delete_<?php echo $administrator->id; ?>" type="danger" size="sm" icon="trash-o" confirmation="Vous confirmer vouloir supprimer cet administrateur?" %}
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
