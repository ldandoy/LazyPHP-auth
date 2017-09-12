<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-title">Modification de vos données personelles</h1>
            <div class="box box-success">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        {% button url="user_index" type="secondary" size="sm" icon="arrow-left" hit="Retour" %}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body">
                    {% form_open id="formUser" action="formAction" %}
                        {% input_text name="lastname" model="user.lastname" label="Nom" %}
                        {% input_text name="firstname" model="user.firstname" label="Prénom" %}
                        {% input_text name="email" model="user.email" label="Email" %}
                        {% input_password name="newPassword" model="user.newPassword" label="Nouveau mot de passe" autocomplete="off" %}
                        {% input_media name="media_id" model="user.media_id" label="Avatar" mediaType="Image" mediaCategory="user" %}
                        {% input_submit name="submit" value="save" formId="formUser" class="btn-primary" icon="save" label="Enregistrer" %}
                    {% form_close %}
            	</div>
            </div>
        </div>
    </div>
</div>
