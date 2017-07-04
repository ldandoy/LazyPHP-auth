<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
    </div>
    <div class="box-body">
        {% form_open id="formRoleAssignments" action="formAction" class="form-horizontal" %}
            <ul class="nav nav-tabs" role="tablist">
                <li class="active" role="presentation">
                    <a href="#roleassignements_groups" role="tab" data-toggle="tab">
                        Par groupes
                    </a>
                </li>
                <li role="presentation">
                    <a href="#roleassignements_administrators" role="tab" data-toggle="tab">
                        Par administrateurs
                    </a>
                </li>
                <li role="presentation">
                    <a href="#roleassignements_users" role="tab" data-toggle="tab">
                        Par utilisateurs
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="roleassignements_groups" class="tab-pane in active" role="tabpanel">
                    <div class="panel-group" id="roleassignments_groups_accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($groups as $group): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="roleassignments_group_<?php echo $group->code; ?>_heading">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#roleassignements_groups_accordion" href="#roleassignments_group_<?php echo $group->code; ?>" aria-expanded="false" aria-controls="roleassignments_group_<?php echo $group->code; ?>">
                                        <?php echo $group->label; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="roleassignments_group_<?php echo $group->code; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="roleassignments_group_<?php echo $group->code; ?>_heading">
                                <div class="panel-body">
    <?php foreach ($roles as $role): ?>
        <?php $value = (int)isset($roleAssignments['groups'][$group->id]) && in_array($role->id, $roleAssignments['groups'][$group->id]); ?>
                                {% input_checkbox name="group_<?php echo $group->id; ?>_role_<?php echo $role->id; ?>" label="<?php echo $role->label ?>" value="<?php echo $value; ?>" %}
    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
<?php endforeach; ?>
                    </div>
                </div>
                <div id="roleassignements_administrators" class="tab-pane" role="tabpanel">
                    
                </div>
                <div id="roleassignements_users" class="tab-pane" role="tabpanel">
                </div>
            </div>
            {% input_submit name="submit" value="save" formId="formRoleAssignments" class="btn-primary" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
