<div class="login-box">
	<div class="login-box-logo">
		
	</div>
	<div class="login-box-body">
		<p align="center">
			<a href="/"><img src="/assets/images/logo_miaw.png" alt="logo_miaw"></a>
		</p>
		<form id="formLogin" method="post" action="<?php echo $params['formAction']; ?>" class="form form-horizontal">
			<p class="page-title">{{ pageTitle }}</p>
			{% input_text name="email" model="email" label="Email" placeholder="Identifiant" %}
			{% input_password name="password" label="Password" model="password" value="" placeholder="Mot de passe" autocomplete="off" help="Mot de passe oublié? <a href="forgotpassword">Cliquez ici</a>" %}
			{% input_submit name="submit" value="login" formId="formLogin" label="Se connecter" class="btn-primary" %}
		</form>
		<p>
			Pas encore de compte? {% link url="<?php echo $params['signupURL']; ?>" content="S'inscrire" %}
		</p>
	</div>
</div>