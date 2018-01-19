<?php $url = $user->media != null ? $user->media->getUrl() : '';
    if ($url != '') {
        // $avatar = '{% image src="'.$url.'" width="150px" height="150px" %}';
        $avatar = '<img src="'.$url.'"  class="rounded-circle" />';
    } else {
        $avatar = '<i class="fa fa-user"></i>';
    }
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
			<h1 class="page-title">Votre compte</h1>
			<div class="box box-success">

			    <div class="box-infos">
					<h3>Informations personelles</h3>
		            <div class="card slot-coach">
		                    <div>
		                        <?php echo $avatar; ?>
		                    </div>

		                    <div>
		                        <strong><?php echo $user->fullname ; ?></strong>
		                        <br /><?php echo $user->email; ?> 
		                    </div>
		                </div>
		            </div>     
		         </div>

				<div class="box-orders">
					<h3>Commandes</h3>
		            <div class="card">
						<?php
						foreach ($orders as $order) {
		                             foreach ($order->orderdetails as $orderdetail) {
		                                 $label = $orderdetail->product->label;
		                                 $label_slot = $orderdetail->product->label_slot;
		                                 $amount = $orderdetail->amount;
		                                 $quantity = $orderdetail->quantity ;
		                                        }  
		                                echo
		                                '<div class="slot-order">
		                                <table>
			                                <tr>
			                                    <td>'. $order->user->fullname . /* nom du coach */'</td> 
			                                    <td>'. $label_slot ./* Sous catégorie */'</td>
			                                    <td>'. $label ./* catégorie */'</td>
			                                    <td>'. number_format(round($amount, 2),2) . /* Prix */' euros </td>  
			                                    <td>'. $quantity . /* quantité */ '</td>     
				                                <td>';
				                                ?>
				                                {% button url="cockpit_orders_delete_<?php echo $order->id; ?>" type="danger" size="sm" icon="trash-o" confirmation="Vous confirmer vouloir supprimer cette commande ?" hint="Supprimer" %}
			                                <?php
			                                echo
			                                    '</td>'.
			                                '</tr> 
		                                </table>
		                                <br /></div>';   
		                        }
						?>
					</div>
				 </div>

			 	<div class="box-body">
			        {% link url="user_edit" content="Modifier vos données personnelles" icon="pencil" %}<br />
					{% link url="auth_logout" content="Se déconnecter" icon="sign-out" %}
				</div>

			</div>
		</div>
	</div>
</div>
