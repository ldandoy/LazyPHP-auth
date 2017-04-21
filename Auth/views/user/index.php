<h1 class="page-title">Votre compte</h1>
<div class="box box-success">
    <div class="box-header">
    	<h2><img src="{{user.getImageUrl}}" />{{user.getFullname}}</h2>
    </div>
    <div class="box-body">
        {% link url="user_edit" content="Modifier vos données personnelles" icon="pencil" %}<br />
		{% link url="auth_logout" content="Se déconnecter" icon="sign-out" %}
	</div>
</div>
