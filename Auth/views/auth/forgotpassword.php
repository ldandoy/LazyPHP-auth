<div class="login-box">
	<div class="login-box-body">
		<p align="center">
			<a href="/"><img src="/assets/images/logo_miaw.png" alt="logo_miaw"></a>
		</p>
		{% form_open id="formForgotpassword" class="" noBootstrapCol="1" %}
			<p class="page-title">{{ pageTitle }}</p>
			{% input_text name="email" model="email" label="Saisissez l'adresse email de votre compte" placeholder="Email" %}
			{% input_submit name="submit" value="forgotpassword" formId="formForgotpassword" label="Valider" class="btn-primary" %}
		{% form_close %}
	</div>
</div>