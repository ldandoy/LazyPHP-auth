<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_users" type="secondary" size="sm" icon="arrow-left" hint="retour" %}
        </div>
    </div>
    <div class="box-body">
        {% form_open id="formImportCsv" action="formAction" %}
<?php if ($selectSite): ?>
            {% input_select name="site_id" model="site_id" label="Site" options="siteOptions" %}
<?php endif; ?>
            {% input_file name="file" model="file" label="Fichier CSV" %}
            {% input_submit name="submit" value="importcsv" formId="formImportCsv" class="btn-primary" icon="download" label="Importer" %}
        {% form_close %}
    </div>
</div>
