<h1 class="page-title">{{ pageTitle }}</h1>
<div class="actions">
    {% button url="cockpit_administrators" type="default" icon="arrow-left" content="Retour" %}
</div>
{% form_open id="formAdministrator" action="formAction" class="form-horizontal" %}
    {% input_text name="lastname" model="administrator.lastname" label="Nom" %}
    {% input_text name="firstname" model="administrator.firstname" label="Prénom" %}
    {% input_text name="email" model="administrator.email" label="Email" %}
<?php if (isset($this->administrator->id)): ?>
    {% input_password name="newPassword" model="administrator.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
<?php endif; ?>
    {% input_checkbox name="active" model="administrator.active" label="Actif" %}
    {% input_submit name="submit" value="save" formId="formAdministrator" class="btn-primary" icon="save" label="Enregistrer" %}
{% form_close %}
