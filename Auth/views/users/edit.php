<h1 class="page-title">Modification de votre compte</h1>
<div class="box box-success">
    <div class="box-header">
        <div class="box-tools pull-right">
            {% button url="" type="default" icon="arrow-left" class="btn-xs" %}
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="box-body">
{% form_open id="formUser" action="formAction" class="form-horizontal" %}
    {% input_text name="lastname" model="user.lastname" label="Nom" %}
    {% input_text name="firstname" model="user.firstname" label="Prénom" %}
    {% input_text name="email" model="user.email" label="Email" %}
    {% input_textarea name="address" model="user.address" label="Adresse" %}
    {% input_password name="newPassword" model="user.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
    {% input_media name="image" model="user.image" label="Image" %}
    {% input_checkbox name="active" model="user.active" label="Actif" %}
    {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" icon="save" label="Enregistrer" %}
{% form_close %}
	</div>
</div>
