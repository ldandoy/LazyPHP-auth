<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
    </div>
    <div class="box-body">
        {% form_open id="formRoleAssignment" action="formAction" class="form-horizontal" %}
            <div class="panel-group" id="roleassignment_accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="roleassignment_accordion_group_heading">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#roleassignment_accordion" href="#roleassignment_accordion_group" aria-expanded="false" aria-controls="roleassignment_accordion">
                                Boîte
                            </a>
                        </h4>
                    </div>
                    <div id="roleassignment_accordion_group" class="panel-collapse collapse" role="tabpanel" aria-labelledby="roleassignment_accordion_group_heading">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
<?php

foreach ($groups as $group) {

}

?>        
        {% form_close %}
    </div>
</div>
