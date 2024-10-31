(function( $ )
{
	'use strict';
	
	var blockui_options = { 
		message: null,
		centerX: true,
		centerY: true,
	};
	
	$(document).on('click', '.rankworks-form-section #btn_close_validation', function(e) 
	{
		var main_ele = $(this).closest( '.rankworks-form-section' );
		$(main_ele).find(".validation-container").css( {"display": "none"} );
		$(main_ele).find(".login-container").css( {"display": "flex"} );
		
	});
	
	$(document).on('click', '.rankworks-form-section #btn_account_form_submit', function(e) 
	{
		var main_ele = $(this).closest( '.rankworks-form-section' );
		var firstName = $(main_ele).find("#first_name").val();
		var lastName = $(main_ele).find("#last_name").val();
		var email = $(main_ele).find("#email").val();
		
		if( firstName && lastName && email )
		{
			$(main_ele).find(".create-accout-container").css( {"display": "none"} );
			$(main_ele).find(".business-info-container").css( {"display": "flex"} );
			
		}
	});
	
	$(document).on('click', '.rankworks-form-section #btn_business_info_submit', function(e) 
	{
		var main_ele = $(this).closest( '.rankworks-form-section' );
		var first_name = $(main_ele).find("#first_name").val();
		var last_name = $(main_ele).find("#last_name").val();
		var email = $(main_ele).find("#email").val();
		var website_url = $(main_ele).find("#website_url").val();
		var company_name = $(main_ele).find("#company_name").val();
		var industry_type = $(main_ele).find("#industry_type").val();
		var service_area = $(main_ele).find("#service_area").val();
		var service_type = $(main_ele).find('input[name="s-type"]:checked').val();
		
		if( website_url == '' || company_name == '' || industry_type == '' || service_area == '' || service_area == null || service_type == '' )
		{
			alert( 'Please enter all info!' );
			return;
		}
		
		var service_area_data = $(main_ele).find("#service_area").select2('data')[0];
		var loc_obj = service_area_data.loc_obj;
		
		if( ! website_url.includes( "http" ) )
			website_url = 'https://' + website_url;
		
		service_type = service_type == "eCommerce" ? true : false;
		
		var customer_data = {
			"email": email,
			"company": {
				"name": company_name,
				"website": website_url,
				"is_ecommerce": service_type,
				"primary_category": industry_type,
				"primary_service_area": {
					"_id" : loc_obj._id,
					"geo" : loc_obj.geo,
					"city": loc_obj.city,
					"state": loc_obj.state,
					"country": loc_obj.country
				}
			},
			"name": {
				"first_name": first_name,
				"last_name": last_name
			}
		};
		func_js_create_customer_script( main_ele, customer_data );
		
		
	});
	
	function func_js_create_customer_script( main_ele, customer_data )
	{
		
		$.ajax(
		{
			type: 'POST',
			accept: "application/json",
			contentType: "application/json",
			url: "https://api.plugins.rankworks.com/customer",
			data: JSON.stringify( customer_data ),
			success: function( response )
			{
				// console.log(response);
				if( response.hasOwnProperty('src') )
				{
					$(main_ele).find(".business-info-container").css( {"display": "none"} );
					$(main_ele).find(".validation-container").css( {"display": "flex"} );
					
					func_js_save_rankworks_script( main_ele, response.src, response.id );
					
					if( customer_data.company.is_ecommerce )
					{
						func_js_create_wc_rest_api_keys( main_ele, response.id );
						
					}
					
				}
				else
				{
					alert( 'An error has occured.' );
				}
			},
			error: function( response )
			{
				$( main_ele ).unblock();
				// console.log(response);
				if( response.hasOwnProperty('responseJSON') && typeof response.responseJSON.detail === 'string' )
				{
					alert( response.responseJSON.detail );
				}
				else
				{
					alert( 'An error has occured.' );
				}
			},
			beforeSend: function( response )
			{
				if( ! func_js_is_blocked( main_ele ) )
					$( main_ele ).block( blockui_options );
			},
			complete: function( response )
			{
				// $( main_ele ).unblock();
			}
		});
	}
	
	function func_js_save_rankworks_script( main_ele, script_url, id )
	{
		
		$.ajax(
		{
			type: 'POST',
			url: rankworks_connection.ajax_url,
			data: {
				action: "save_rankworks_script",
				script_url: script_url,
				id: id,
				nonce: rankworks_connection.admin_nonce
				
			},
			success: function( response )
			{
				// console.log(response);
				
			},
			error: function( response )
			{
				if( func_js_is_blocked( main_ele ) )
					$( main_ele ).unblock();
			},
			beforeSend: function( response )
			{
				if( ! func_js_is_blocked( main_ele ) )
					$( main_ele ).block( blockui_options );
			},
			complete: function( response )
			{
				if( func_js_is_blocked( main_ele ) )
					$( main_ele ).unblock();
			}
		});
	}
	
	function func_js_create_wc_rest_api_keys( main_ele, id )
	{
		
		$.ajax(
		{
			type: 'POST',
			url: rankworks_connection.ajax_url,
			data: {
				action: "rankworks_create_wc_rest_api_keys",
				id: id,
				nonce: rankworks_connection.admin_nonce
				
			},
			success: function( response )
			{
				// console.log(response);
				if( response.success )
				{
					$.ajax(
					{
						type: 'POST',
						accept: "application/json",
						contentType: "application/json",
						url: response.endpoint,
						data: JSON.stringify( response.body ),
						success: function( response_2 )
						{
							// console.log(response_2);
							
						}
					});
				}
			},
			error: function( response )
			{
				if( func_js_is_blocked( main_ele ) )
					$( main_ele ).unblock();
			},
			beforeSend: function( response )
			{
				if( ! func_js_is_blocked( main_ele ) )
					$( main_ele ).block( blockui_options );
			},
			complete: function( response )
			{
				if( func_js_is_blocked( main_ele ) )
					$( main_ele ).unblock();
			}
		});
	}
	
	function func_js_is_blocked( ele )
	{
		var data = $( ele ).data();
		if( data["blockUI.isBlocked"] == 1 )
			return true;
		else
			return false;
	}
	
})( jQuery );

jQuery(document).ready(function($)
{
	$( '.rankworks-form-section #industry_type' ).select2(
	{
		width: '100%',
	});
	
	$( '.rankworks-form-section #service_area' ).select2(
	{
		ajax: {
			url: 'https://api.plugins.rankworks.com/service_area',
			dataType: 'json',
			delay: 250,
			data: function( params ) {
				return {
					search: params.term,
					limit: 10,
				};
			},
			processResults: function( data )
			{
				var options = [];
				if( data )
				{
					jQuery.each( data, function( index, obj )
					{
						var label = obj.city + ', ' + obj.state;
						options.push(
						{
							id: obj._id,
							text: label,
							loc_obj: obj
						});
					});
				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 2,
		width: '100%',
	} );
	
});
