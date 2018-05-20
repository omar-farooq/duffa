$(document).ready(function() {

	addRemoveButtonHandler();

	function showCart(data) {

		$('#mobile-cart').html("");
		
		var numberOfItems = 0;
		var total = 0;
		var postage = 0;		

		if(data == "") {

			
			window.location.replace("./shop");

		} else {

			for(i=0;i<data.length;i++) {

				data[i].price = (data[i].price.toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }));

				//don't even ask about this upcoming mess. jquery append is a bitch and won't let me separate it.

				$('#mobile-cart').append("<li><div class='product-container'><div><img class='product-image' src='" +data[i].picture+ "' width=250px height=250px></div>" +data[i].pname + " (x" +data[i].quantity+ ") : " + (data[i].quantity * data[i].price).toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }) + " <br><span class='remove_wrapper'> <input type='number' name='remove_quantity' class='remove_quantity' value='1' min='1' max='" + data[i]['quantity'] + "'> <span remove_id='" + data[i].id+ "' class='remove-item'><img src='./icons/bin.png' class='bin-icon'></span></span><br></div></li>");

				total += (data[i].price * data[i].quantity);
				postage += (data[i].postage * data[i].quantity);
				numberOfItems += Number(data[i].quantity);

			}

			$('.cart-total').html("Total : " + total.toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }));
			$('.cart-postage').html("Postage : " + postage.toLocaleString('en-GB', {style: 'currency', currency: 'GBP' }));

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
