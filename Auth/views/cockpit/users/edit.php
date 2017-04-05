<h1 class="page-title">{{ pageTitle }}</h1>
<div class="actions">
    {% button url="cockpit_users" type="default" icon="arrow-left" content="Retour" %}
</div>
{% form_open id="formUser" action="formAction" class="form-horizontal" %}
    {% input_text name="lastname" model="user.lastname" label="Nom" %}
    {% input_text name="firstname" model="user.firstname" label="Prénom" %}
    {% input_text name="email" model="user.email" label="Email" %}
    {% input_textarea name="address" model="user.address" label="Adresse" %}
<?php if (isset($this->user->id)): ?>
    {% input_password name="newPassword" model="user.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
<?php endif; ?>
    {% input_checkbox name="active" model="user.active" label="Actif" %}
    {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" icon="save" label="Enregistrer" %}
{% form_close %}
