<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_groups" type="secondary" size="sm"  icon="arrow-left" hint="Retour" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formGroup" action="formAction" %}
            {% input_text name="code" model="group.code" label="Code" %}
            {% input_text name="label" model="group.label" label="Nom" %}
            {% input_checkbox name="cockpit" model="group.cockpit" label="Accès au cockpit" %}
            {% input_submit name="submit" value="save" formId="formGroup" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
