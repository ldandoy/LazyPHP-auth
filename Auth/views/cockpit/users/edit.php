<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_users" type="secondary" size="sm" icon="arrow-left" hint="Retour" %}
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
<?php if ($user->id !== null): ?>
            {% input_password name="newPassword" model="user.newPassword" label="Nouveau mot de passe" autocomplete="off" help="8 à 32 caractères, au moins une lettre et un chiffre" %}
<?php endif; ?>
            {% input_media name="media_id" model="user.media_id" label="Image" mediaType="image" mediaCategory="user" %}
            <?php if ($this->current_user !== null && $this->current_user->group->code == 'administrators'): ?>
                {% input_select name="group_id" model="user.group_id" label="Groupe Principal" options="groupOptions" %}
            <?php endif; ?>
            {% input_select name="groups[]" model="groups" label="Groupes" multiple="1" options="groupOptions" %}
            {% input_text name="poste" model="user.poste" label="Poste" %}
            {% input_checkbox name="active" model="user.active" label="Actif" %}
            {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
