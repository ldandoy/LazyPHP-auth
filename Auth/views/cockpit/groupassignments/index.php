<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
        <div class="box-tools pull-right">
            {% button url="cockpit_auth_groups" type="secondary" size="sm" icon="arrow-left" hint="Retour" %}
        </div>        
    </div>
    <div class="box-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#roleassignments_groups" role="tab" data-toggle="tab">
                    Par groupes
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="roleassignments_groups" class="tab-pane active" role="tabpanel">
                <div id="roleassignments_groups_accordion" role="tablist" aria-multiselectable="true">
                    <?php foreach ($groups as $group): ?>
                        <div class="card">
                            <div class="card-header" role="tab" id="roleassignments_group_<?php echo $group->code; ?>_header">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#roleassignments_groups_accordion" href="#roleassignments_group_<?php echo $group->code; ?>" aria-expanded="false" aria-controls="roleassignments_group_<?php echo $group->code; ?>">
                                        <?php echo $group->label; ?>
                                    </a>
                                </h5>
                            </div>
                            <div id="roleassignments_group_<?php echo $group->code; ?>" class="collapse" role="tabpanel" aria-labelledby="roleassignments_group_<?php echo $group->code; ?>_header">
                                {% form_open id="formRoleAssignments_<?php echo $group->id; ?>" action="formAction" %}
                                    {% input_hidden name="group_id" value="<?php echo $group->id; ?>" label="Group" %}
                                    <div class="card-block" style="padding: 3%;">
                                        <table class="data-table-nopagination table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th width="1%"></th>
                                                    <th width="99%">User</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($users as $user): ?>
                                                    <tr>
                                                        <?php if ( isset($tabAssign[$group->id][$user->id]) ) {  ?>
                                                            <td>{% input_checkbox name="group_assignment_<?php echo $group->id ?>_<?php echo $user->id ?>" checked="checked" %}</td><td><?php echo $user->getFullName() ?></td>
                                                        <?php } else { ?>
                                                            <td>{% input_checkbox name="group_assignment_<?php echo $group->id ?>_<?php echo $user->id ?>" %}</td><td><?php echo $user->getFullName() ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        {% input_submit name="submit" value="save" formId="formRoleAssignments_<?php echo $group->id; ?>" class="btn-primary" label="Enregistrer" %}
                                    </div>
                                {% form_close %}
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
