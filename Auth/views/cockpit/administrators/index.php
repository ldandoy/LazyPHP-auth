<h1 class="page-title"><i class="fa fa-user-secret"></i> Administrateurs <small>test</small></h1>

<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Liste des Administrateurs</h3>
        <div class="box-tools pull-right">
            <a href="<?php echo url('cockpit_administrators_new'); ?>" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="39%">Nom</th>
                    <th width="40%">Email</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php
        foreach ($params['administrators'] as $administrator) {
            echo '<tr>';
            echo '<td>'.$administrator->id.'</td>';
            echo '<td>'.trim(implode(' ', array($administrator->lastname, $administrator->firstname))).'</td>';
            echo '<td>'.$administrator->email.'</td>';
            echo '<td>';
            echo '<a href="'.url('cockpit_administrators_edit', array('id' => $administrator->id)).'" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a> ';
            echo '<a href="'.url('cockpit_administrators_delete', array('id' => $administrator->id)).'" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
            </tbody>
        </table>
    </div>
</div>