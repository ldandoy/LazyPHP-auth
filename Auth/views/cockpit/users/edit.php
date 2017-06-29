<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Liste des utilisateurs</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_users" type="default" icon="arrow-left" class="btn-xs" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formUser" action="formAction" class="form-horizontal" %}
            {% input_text name="lastname" model="user.lastname" label="Nom" %}
            {% input_text name="firstname" model="user.firstname" label="Prénom" %}
            {% input_text name="email" model="user.email" label="Email" %}
            {% input_password name="newPassword" model="user.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
            {% input_media name="media_id" model="user.media_id" label="Image" mediaType="image" mediaCategory="user" %}
            {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
