<h1 class="page-title">{{ pageTitle }}</h1>
<form id="formLogin" method="post" action="<?php echo $params['formAction']; ?>" class="form form-horizontal">
    {% input_text name="email" model="email" label="Identifiant" %}
    {% input_password name="password" model="password" value="" label="Mot de passe" autocomplete="off" %}
    {% input_submit name="submit" value="login" formId="formLogin" label="Se connecter" class="btn-primary" %}
</form>
<p>
	Pas encore de compte? {% link url="user_signup" content="S'inscrire" new_window="1" %}
</p>
