jQuery( function( $ ) {
	'use strict';

	var
		dt = smChatData,
		$chat = $( '#sm-chat' ),
		$side = $chat.find( 'aside' ),
		$sideHead = $side.find( 'header' ),
		$chats = $side.find('.chats' ).find( 'li' ),
		$header = $chat.find( '#chat-header' ),
		$body = $chat.find( '#chat-body' ),
		$send = $chat.find( '#chat-send' ),
		$msg = $chat.find( '#chat-msg' ),
		timeoutI;

	function loadMessages() {

		var u2 = dt.usr2;
		$.post( dt.url + '?action=sm_chat_get', {
			uid1: dt.user.id,
			uid2: dt.usr2.id,
		}, function( response ) {

			var res,html,msg,user;
			try {
				res = JSON.parse( response );
			} catch (e) {
				console.log( e );
			}
			if ( res && res.success ) {
				if ( res.messages ) {
					$body.html( '' );
					for ( var i = res.messages.length - 1; i > -1; i -- ) {
						msg = res.messages[i];
						msg.info = msg.info.replace( 'msg-', '' ).split( '::' );
						msg.sender = msg.info[2];
						msg.time = msg.info[1];

						user = dt.user.id == msg.sender ? dt.user : dt.users[ msg.sender ];
						var $msg = $( '<div class="message" />' );
						$msg
							.append(
								$( '<div class="user" />' )
									.append( $( '<img class="user-img">' ).attr( 'src', user.avatar ) )
									.append( '<div class="name">' + user.name + '</div>' )
							)
							.append( '<div class="text">' + msg.text + '</div>' );

						$body.append( $msg );
						$body.scrollTop( $body[0].scrollHeight );

						clearTimeout( timeoutI );
						timeoutI = setTimeout( loadMessages, 500 );
					}
				} else {

				}
				$body.html( html );

			}
		} );
	};

	function sendMessage() {
		$.post( dt.url, {
			action: 'sm_chat_add',
			sender: dt.user.id,
			recipient: dt.usr2.id,
			msg: $msg.val(),
		}, function( resp ) {

			$msg.val( '' );

			console.log( resp );

			$chat.removeClass( 'sending-message' );

			// Load new messages
			loadMessages();
		} );
	}

	$chat.keyup( function (e) {
		if ( e.keyCode == 13 ) {
			$send.click();
		}
	} );

	$send.click( function() {
		$chat.addClass( 'sending-message' );

		if ( $msg.val() ) {
			$msg.css( 'background-color', '#fff' );
			sendMessage();
		} else {
			$msg.css( 'background-color', '#f99' );
		}
	} );

	$sideHead.find( 'a' ).click( function() {

		var
			$t = $( this ),
			$target = $( $t.attr( 'href' ) );

		$t.siblings().removeClass( 'active' );
		$t.addClass( 'active' );

		$side.find( '.toggled' ).not( $target ).hide();
		$target.show()

		return false;

	} );

	$chat.find( '.chat-init' ).click( function() {

		var $t = $( this );

		dt.usr2 = dt.users[ $t.data( 'user' ) ];

		$t.siblings().removeClass( 'active' );
		$t.addClass( 'active' );

		$header.html( dt.usr2.name );
		$body.html( dt.loader );

		if ( $t.hasClass( 'new' ) )

		loadMessages();

	} );

	if ( $chats.length ) {
		$chats.first().click();
	}
} );