<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_groups" type="default" icon="arrow-left" class="btn-xs" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formGroup" action="formAction" class="form-horizontal" %}
            {% input_text name="label" model="group.label" label="Nom" %}
            {% input_submit name="submit" value="save" formId="formGroup" class="btn-primary" icon="save" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
