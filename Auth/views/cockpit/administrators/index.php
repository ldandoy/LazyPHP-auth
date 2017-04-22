<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Liste des Administrateurs</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_administrators_new" type="success" size="xs" icon="plus" %}
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($params['administrators'] as $administrator) {
    if ($administrator->active == 1) {
        $label = '<span class="label label-success">Activé</span>';
    } else {
        $label = '<span class="label label-danger">Désactivé</span>';
    }
    echo
        '<tr>'.
            '<td>'.$administrator->id.'</td>'.
            '<td>'.$administrator->getFullname().'</td>'.
            '<td>'.$administrator->email.'</td>'.
            '<td>'.$label.'</td>'.
            '<td>';?>
    {% button url="cockpit_auth_administrators_show_<?php echo $administrator->id; ?>" type="primary" size="xs" icon="eye" %}
    {% button url="cockpit_auth_administrators_edit_<?php echo $administrator->id; ?>" type="info" size="xs" icon="pencil" %}
    {% button url="cockpit_auth_administrators_delete_<?php echo $administrator->id; ?>" type="danger" size="xs" icon="trash-o" confirmation="Vous confirmer vouloir supprimer cet administrateur?" %}
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
