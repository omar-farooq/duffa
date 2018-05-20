$(document).ready(function(){ 
	$('.addtocart').each(function() {

		var _id = $(this).attr('prod_id');


		$(this).on('click', function() {

		var _quantity = $(this).parent().find('.quantity').val();
			
			$.post('./ajax/php/cart.php', 

				{
					task: "add",
					id: _id,
					quantity: _quantity
				}

			)
			.success (
				function(data) {
					showCart( jQuery.parseJSON(data) );
				}
			)

		});

	});

	$('.remove-item').each(function() {

		var _remove_id = $(this).attr('remove_id');

		$(this).on('click', function() {

			var _remove_quantity = $(this).parent().find('.remove_quantity').val();

			$.post('./ajax/php/cart.php', 

				{
					task: "remove",
					remove_id: _remove_id,
					remove_quantity: _remove_quantity
				}

			)
			.success (
				function(data) {
					showCart( jQuery.parseJSON(data) );
				}
			)

		});

	}); 

	function showCart(data) {

		$('#cart').html("your cart contains: <br><br>");
		
		var numberOfItems = 0;
		var total = 0;	
		var postage = 0;	

		if(data == "") {

			$('#cart').html("your cart is empty");
			$('.number-of-items-large').html('0');

		} else {

			for(i=0;i<data.length;i++) {

				data[i].price = (data[i].price.toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }));

				$('#cart').append(data[i].pname + " (x" +data[i].quantity+ ") : " + (data[i].quantity * data[i].price).toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }) + " <span class='remove_wrapper'> <input type='number' name='remove_quantity' class='remove_quantity' min='1' max='" + data[i]['quantity'] + "'> <span remove_id='" + data[i].id+ "' class='remove-item'><img src='./icons/bin.png' class='bin-icon'></span></span><br>");

				total += (data[i].price * data[i].quantity);
				postage += (data[i].postage * data[i].quantity);
				numberOfItems += Number(data[i].quantity);

			}

			$('#cart').append("<br>Your subtotal is: " + total.toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }) + "<br>postage (optional) is: " + postage.toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }) + "<br><br> <a href='./checkout'>Proceed to Checkout</a><br>");
			$('.number-of-items-large').html(numberOfItems);

			addRemoveButtonHandler();

		}
	}

	function addRemoveButtonHandler() {

		$('.remove-item').each(function() {

			var _remove_id = $(this).attr('remove_id');

			$(this).on('click', function() {

				var _remove_quantity = $(this).parent().find('.remove_quantity').val();

				$.post('./ajax/php/cart.php', 

					{
						task: "remove",
						remove_id: _remove_id,
						remove_quantity: _remove_quantity
					}

				)
				.success (
					function(data) {
						showCart( jQuery.parseJSON(data) );
					}
				)

			});

		}); 

	}

});
