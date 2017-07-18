<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
    </div>
    <div class="box-body">
        {% form_open id="formRoleAssignments" action="formAction" %}
            <ul class="nav nav-tabs" role="tablist">
                <li class="active" role="presentation">
                    <a href="#roleassignments_groups" role="tab" data-toggle="tab">
                        Par groupes
                    </a>
                </li>
                <li role="presentation">
                    <a href="#roleassignments_administrators" role="tab" data-toggle="tab">
                        Par administrateurs
                    </a>
                </li>
                <li role="presentation">
                    <a href="#roleassignments_users" role="tab" data-toggle="tab">
                        Par utilisateurs
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="roleassignments_groups" class="tab-pane in active" role="tabpanel">
                    <div class="panel-group" id="roleassignments_groups_accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($groups as $group): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="roleassignments_group_<?php echo $group->code; ?>_heading">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#roleassignments_groups_accordion" href="#roleassignments_group_<?php echo $group->code; ?>" aria-expanded="false" aria-controls="roleassignments_group_<?php echo $group->code; ?>">
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
                <div id="roleassignments_administrators" class="tab-pane" role="tabpanel">
                    <div class="panel-group" id="roleassignments_administrators_accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($administrators as $administrator): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="roleassignments_administrator_<?php echo $administrator->id; ?>_heading">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#roleassignments_administrators_accordion" href="#roleassignments_administrator_<?php echo $administrator->id; ?>" aria-expanded="false" aria-controls="roleassignments_administrator_<?php echo $administrator->id; ?>">
                                        <?php echo $administrator->getFullName(); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="roleassignments_administrator_<?php echo $administrator->id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="roleassignments_administrator_<?php echo $administrator->code; ?>_heading">
                                <div class="panel-body">
    <?php foreach ($roles as $role): ?>
<?php
    $valueGroup = (int)isset($roleAssignments['groups'][$administrator->group_id]) && in_array($role->id, $roleAssignments['groups'][$administrator->group_id]);
    if ($valueGroup == 1) {
        $readOnly = '1';
        $value = 1;
    } else {
        $readOnly = '0';
        $value = (int)isset($roleAssignments['administrators'][$administrator->id]) && in_array($role->id, $roleAssignments['administrators'][$administrator->id]);
    }
?>
                                {% input_checkbox name="administrator_<?php echo $administrator->id; ?>_role_<?php echo $role->id; ?>" label="<?php echo $role->label ?>" value="<?php echo $value; ?>" readOnly="<?php echo $readOnly; ?>" %}
    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
<?php endforeach; ?>
                    </div>
                </div>
                <div id="roleassignments_users" class="tab-pane" role="tabpanel">
                    <div class="panel-group" id="roleassignments_users_accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($users as $user): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="roleassignments_user_<?php echo $user->id; ?>_heading">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#roleassignments_users_accordion" href="#roleassignments_user_<?php echo $user->id; ?>" aria-expanded="false" aria-controls="roleassignments_user_<?php echo $user->id; ?>">
                                        <?php echo $user->getFullName(); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="roleassignments_user_<?php echo $user->id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="roleassignments_user_<?php echo $user->code; ?>_heading">
                                <div class="panel-body">
    <?php foreach ($roles as $role): ?>
<?php
    $valueGroup = (int)isset($roleAssignments['groups'][$user->group_id]) && in_array($role->id, $roleAssignments['groups'][$user->group_id]);
    if ($valueGroup == 1) {
        $readOnly = '1';
        $value = 1;
    } else {
        $readOnly = '0';
        $value = (int)isset($roleAssignments['users'][$user->id]) && in_array($role->id, $roleAssignments['users'][$user->id]);
    }
?>
                                {% input_checkbox name="user_<?php echo $user->id; ?>_role_<?php echo $role->id; ?>" label="<?php echo $role->label ?>" value="<?php echo $value; ?>" readOnly="<?php echo $readOnly; ?>" %}
    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
<?php endforeach; ?>
                    </div>
                </div>
            </div>
            {% input_submit name="submit" value="save" formId="formRoleAssignments" class="btn-primary" label="Enregistrer" %}
        {% form_close %}
    </div>
</div>
