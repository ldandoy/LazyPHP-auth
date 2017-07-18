<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_roles" type="secondary" icon="arrow-left" class="btn-sm" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formRole" action="formAction" class="" %}
            {% input_text name="code" model="role.code" label="Code" %}
            {% input_text name="label" model="role.label" label="Nom" %}
            {% input_submit name="submit" value="save" formId="formRole" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
