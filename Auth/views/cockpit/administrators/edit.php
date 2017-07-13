<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Liste des Administrateurs</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_administrators" type="secondary" class="btn-sm" icon="arrow-left" content="" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formAdministrator" action="formAction" class="form-horizontal" %}
            {% input_text name="lastname" model="administrator.lastname" label="Nom" %}
            {% input_text name="firstname" model="administrator.firstname" label="Prénom" %}
            {% input_text name="email" model="administrator.email" label="Email" %}
        <?php if (isset($this->administrator->id)): ?>
            {% input_password name="newPassword" model="administrator.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
        <?php endif; ?>
            {% input_select name="group_id" model="administrator.group_id" label="Groupe" options="groupOptions" %}
            {% input_checkbox name="active" model="administrator.active" label="Actif" %}
            {% input_submit name="submit" value="save" formId="formAdministrator" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
