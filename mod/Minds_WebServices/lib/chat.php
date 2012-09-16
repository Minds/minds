<?php
/**
 * Minds Web Services
 * Events
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service to get the count of unread message
 *
 * @return int
 */
expose_function('chats.count',
				"chat_count_unread_messages",
				array(
					),
				"Get undread chat messages count",
				'GET',
				false,
				false);