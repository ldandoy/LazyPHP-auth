<div class="container">
    <div class="row">
        <div class="col-md-12">
			<h1 class="page-title">Votre compte</h1>
			<div class="box box-success">
			    <div class="box-header">
			    	<h2><img src="{{user.getImageUrl}}" />{{user.getFullName}}</h2>
			    </div>

<?php
				foreach ($orders as $order) {
                             foreach ($order->orderdetails as $orderdetail) {
                                 $label = $orderdetail->product->label;
                                 $amount = $orderdetail->amount;
                                 $quantity = $orderdetail->quantity ;
                                        }  
                                echo
                                '<tr>
                                    <td>'. $order->user->fullname .'</td>
                                    <td>'. $label .'</td>
                                    <td>'. number_format(round($amount, 2),2) .' euros </td>  
                                    <td>'. $quantity .'</td>     
                                <td>';
                                echo
                                    '</td>'.
                                '</tr> <br />';   
                        }
?>

			    <div class="box-body">

			        {% link url="user_edit" content="Modifier vos données personnelles" icon="pencil" %}<br />
					{% link url="auth_logout" content="Se déconnecter" icon="sign-out" %}
				</div>
			</div>
		</div>
	</div>
</div>
