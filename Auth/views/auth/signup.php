<div class="login-box">
	<div class="login-box-logo">
	</div>
	<div class="login-box-body">
		<p align="center">
			<a href="/"><img src="{{ imageLogin }}" alt="{{ altImageLogin }}"></a>
		</p>
		{% form_open id="formUser" action="formAction" noBootstrapCol="1" %}
			<p class="page-title">{{ pageTitle }}</p>
            {% input_text name="lastname" model="user.lastname" label="Nom" %}
            {% input_text name="firstname" model="user.firstname" label="Prénom" %}
            {% input_text name="email" model="user.email" label="Email" %}
            {% input_password name="password" model="password" value="" label="Mot de passe" autocomplete="off" %}
			{% input_password name="password2" model="password" value="" label="Confirmer" autocomplete="off" %}
            <?php if (isset($this->user->id)) : ?>
                {% input_password name="newPassword" model="user.newPassword" label="Mot de passe" autocomplete="off" %}
            <?php endif; ?>
            {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" label="Créez votre compte" %}
		{% form_close %}
	</div>
</div>
