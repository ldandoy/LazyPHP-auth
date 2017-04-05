<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Liste des utilisateurs</h3>
		<div class="box-tools pull-right">
			{% button url="cockpit_auth_users_new" type="success" size="xs" icon="plus" %}
		</div>
	</div>
	<div class="box-body">
		<table class="table table-hover">
			<thead>
				<tr>
					<th width="1%">ID</th>
					<th>Nom</th>
					<th>Email</th>
					<th>Adresse</th>
					<th>Actif</th>
					<th width="10%">Actions</th>
				</tr>
			</thead>
			<tbody>
<?php
foreach ($params['users'] as $user) {
	if ($user->active == 1) {
		$active = '<i class="fa fa-check"></i>';
	} else {
		$active = '<i class="fa fa-times"></i>';
	}

	echo
		'<tr>'.
			'<td>'.$user->id.'</td>'.
			'<td>'.$user->getFullname().'</td>'.
			'<td>'.$user->email.'</td>'.
			'<td>'.$user->address.'</td>'.
			'<td>'.$active.'</td>'.
			'<td>';?>
	{% button url="cockpit_auth_users_edit_<?php echo $user->id; ?>" type="info" size="xs" icon="pencil" %}
	{% button url="cockpit_auth_users_delete_<?php echo $user->id; ?>" type="danger" size="xs" icon="trash-o" confirmation="Vous confirmer vouloir supprimer cet utilisateur?" %}
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