<div class="login-box">
	<div class="login-box-logo">

	</div>
	<div class="login-box-body">
		<p align="center">
			<a href="/"><img src="{{ imageLogin }}" alt="{{ altImageLogin }}"></a>
		</p>
		{% form_open id="formLogin" action="formAction" noBootstrapCol="1" %}
			<p class="page-title">{{ pageTitle }}</p>
			{% input_text name="email" model="email" label="Email" placeholder="Identifiant" %}
			{% input_password name="password" label="Password" model="password" value="" placeholder="Mot de passe" autocomplete="off" %}
			{% input_submit name="submit" value="login" formId="formLogin" label="Se connecter" class="btn-primary" %}
		{% form_close %}
		<p>
			Mot de passe oublié? <a href="forgotpassword">Cliquez ici</a>
		</p>
		<p>
			Pas encore de compte? {% link url="<?php echo $signupURL; ?>" content="S'inscrire" %}
		</p>
	</div>
</div>
