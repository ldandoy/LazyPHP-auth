<div class="login-box">
	<div class="login-box-logo">
		<b>Admin</b>LTE
	</div>
	<div class="login-box-body">
		<form id="formLogin" method="post" action="<?php echo $params['formAction']; ?>" class="form form-horizontal">
			<p class="page-title">{{ pageTitle }}</p>
		    {% input_text name="email" model="email" placeholder="Identifiant" %}
		    {% input_password name="password" model="password" value="" placeholder="Mot de passe" autocomplete="off" %}
		    {% input_submit name="submit" value="login" formId="formLogin" label="Se connecter" class="btn-primary" %}
		</form>
		<p>
			Pas encore de compte? {% link url="user_signup" content="S'inscrire" new_window="1" %}
		</p>
	</div>
</div>