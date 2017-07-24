<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Liste des utilisateurs</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_users" type="secondary" icon="arrow-left" class="btn-sm" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formUser" action="formAction" %}
<?php if ($selectSite): ?>
            {% input_select name="site_id" model="user.site_id" label="Site" options="siteOptions" %}
<?php endif; ?>
            {% input_text name="lastname" model="user.lastname" label="Nom" %}
            {% input_text name="firstname" model="user.firstname" label="Prénom" %}
            {% input_text name="email" model="user.email" label="Email" %}
            {% input_textaera name="address" model="user.address" label="Adresse" %}
            {% input_password name="newPassword" model="user.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
            {% input_media name="media_id" model="user.media_id" label="Image" mediaType="image" mediaCategory="user" %}
            {% input_select name="group_id" model="user.group_id" label="Groupe" options="groupOptions" %}
            {% input_checkbox name="active" model="user.active" label="Actif" %}
            {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
