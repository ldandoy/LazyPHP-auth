<h1 class="page-title">{{ pageTitle }}</h1>
<form id="formUser" method="post" action="<?php echo $params['formAction']; ?>" class="form form-horizontal">
    {% input_text name="lastname" model="user.lastname" label="Nom" %}
    {% input_text name="firstname" model="user.firstname" label="Prénom" %}
    {% input_text name="email" model="user.email" label="Email" %}
    {% input_password name="password" model="password" value="" label="Mot de passe" autocomplete="off" %}
<?php if (isset($this->user->id)) : ?>
    {% input_password name="newPassword" model="user.newPassword" label="Mot de passe" autocomplete="off" %}
<?php endif; ?>
    {% input_textarea name="address" model="user.address" label="Adresse" rows="5" %}
    {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" label="Enregistrer" %}
</form>
