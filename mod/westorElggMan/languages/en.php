<?php

	$english = array(
	'ElggMan_' => "Contact Manager",
	'ElggMan_SMS_Sitename' => "Minds.org", // 11 Characters for sender while sending automatic SMS from your Website
	'ElggMan:displayname' => "display name",
	'ElggMan:name' => "Username",
	'ElggMan:email' => "E-Mail",
	'ElggMan:mobile' => "Mobile",
//	'ElggMan_:known_since' => "Known Since",
	'ElggMan_:friend_status' => "Status",
	'ElggMan_:friend_friend' => "Friend",
	'ElggMan_:intro' => "Manage your friends, external contacts and groups",
	'ElggMan_:loading' => "Loading... please wait!",
	'ElggMan_:messages:new' => "NEW",


// settings
	'ElggMan_:Info' => "Community users are registered users of this community. Every user can manage external contacts, they are private and accessible only for that user.<br>
Note: Community users can get messages via mail and / or via their inbox. External contacts don't have any inbox.
",

	'ElggMan_:adminOnlyOption' => "Restrict general use of the plugin to admins",
	'ElggMan_:FullMail' => "Send the full message to community users, drop a copy in the users inbox (default Elgg behaviour)",
	'ElggMan_:NotifyOnly' => "Send notification only about new mail in inbox to community users (user has to login to read full incoming mail)",
	'ElggMan_:NoMessage' => "Send no message notification to users, drop only the mail to their inbox.",
	'ElggMan_:NoInbox' => "Send full message to users, don't save a copy in their inbox",
	'ElggMan_:CopyOutboxOption' => "Save a copy of message in senders outbox (Default Elgg behaviour is 'Yes'. If you send mass emails, you may want to switch this off.)",

	'ElggMan_:UseCronOption' => "Use defererred message delivery. Default setting is 'No'. But sending a bunch of messages can result in breaking your script by timeout or simply long answer time. Therefore it is suggested to send messages in server background processes. You have to setup your cron configuration.<br>
A line in a cron script for checking the task table for open messages every minute looks like this:<br>
*/1 * * * * lynx --dump http://localhost/pg/cron/minute/ > /dev/null<br>
<br>Cron and Lynx have to be installed. For more information regarding cron please refer to your linux manual. Or use a likely timer in case of using a windows servers.
	",

	'ElggMan_:AllowSendToAllOption' => "User is allowed to send messages to all users (no: only to his friends)",

	'ElggMan_:FriendsToRiverOption' => "Add friend requests to river",

	'ElggMan_:varColumnsAdmin' => "Show these columns to Admins:",
	'ElggMan_:varColumnsUser' => "Show these columns to Users:",

	// variable
	'ElggMan_:cUserName' => "username",
	'ElggMan_:cEmail' => "e-mail",
	'ElggMan_:cMobile' => "mobile",
	'ElggMan_:cSince' => "known since",
	'ElggMan_:cLastAction' => "last action",
	'ElggMan_:cLastLogin' => "last login",
	'ElggMan_:cLocation' => "location",


// backend
	'ElggMan_:sessionError' => "Your session seems to be invalid or outdated, please login again.",
	'ElggMan_:adminError' => "You have to be admin for this action.",

	'ElggMan_:usersDeletedSuccess' => "Users successfull deleted.",
	'ElggMan_:usersDeletedFailed' => "Not all users could be deleted.",

	'ElggMan_:massagesDeletedFailed' => "Not all messages could be deleted.",
	'ElggMan_:messagesSaved' => "Your messages for %s recipients were saved for submission.",
	'ElggMan_:noMessageTxt' => "Sorry, but there is no message text to send.",
	'ElggMan_:noSubject' => '[no subject]',


	'ElggMan_:newMessageNotification' => 'You have a new message in your inbox.',

	'ElggMan_:sms:test:left' => "Free test of SMS functionality. You have %s messages left.",

	'ElggMan_:sms:noNumber' => "No mobile number found for the following receipients: ",

	'ElggMan_:sms:noNumber2' => "You have to enter your mobile number!",
	'ElggMan_:sms:noMoreVerifyRetry' => "You have no more trys to enter your verify code, please restart the process!",

	'ElggMan_:password:problem' => "Problem with not matching password",


	// frontend


	'ElggMan:welcome'  =>  "Welcome ",
	'ElggMan:tab:user'  =>  "User",
	'ElggMan:tab:groups'  =>  "Groups",
  'ElggMan:tab:objects'  =>  "Elgg-Entities",
	'ElggMan:tab:settings'  =>  "Settings",

	'ElggMan:search'  =>  "Search:",
	'ElggMan:contextAdvice'  =>  "To edit user data and for more options, please right click on a table line.",
// friends
	'ElggMan:friends'  =>  "My Friends",
	'ElggMan:friends:add' => "Add a person to your friend list",
	'ElggMan:friends:addAdvice' => "To make a friend request, use the list of activated users in dropdown.",
	'ElggMan:friends:addShort' => "Add Friend",
	'ElggMan:friends:addTo' => "Send Friend Request",
	'ElggMan:friends:delete' => "Remove a person from your friend list",
	'ElggMan:friends:delShort' => "Remove Friend",
	'ElggMan:friends:dialog:add:advice' => "Please select one or more users to make a friend request!",
	'ElggMan:friends:dialog:remove:advice' => "Please select friend to remove from your friendlist!",
	'ElggMan:friends:dialog:add' => "Do you really want to make a friend request to selected user?",
	'ElggMan:friends:dialog:remove' => "Do you really want to remove selected user from your friendlist?",
	'ElggMan:friends:incoming' => "Incoming Requests",
	'ElggMan:friends:outgoing' => "Outgoing Requests",

	'ElggMan_:friends:FR:reason_sent' => "request already sent?",
	'ElggMan_:friends:FR:reason_friend' => "user is already a friend",
	'ElggMan_:friends:FR:reason_self' => "cannot add yourself",

	'ElggMan_:friend_request:new' => "New friend request",

	'ElggMan_:FR:newfriend:subject' => "%s wants to be your friend!",
	'ElggMan_:FR:newfriend:body' => "%s wants to be your friend!
But he or she is waiting for you to approve the request. Please login to approve or reject the request!
You can view your pending friend requests at:
%s

Please note: You cannot reply to this email.",

	'ElggMan_:sms:senderNumber' => "Your sender number currently is: ",
	'ElggMan_:sms:senderNotKnown' => "[unknown]",
	'ElggMan_:sms:verify:sendCode' => "Please enter this code to verify your number: ",
	'ElggMan_:sms:verify:sendCodeResult' => "Your verify code was sent via SMS to %s.",
	'ElggMan_:sms:verify:codeMismatch' => "The code you entered did not match the code, we sent to you. You have %s more trys.",
	'ElggMan_:sms:verify:sendCodeVerified' => "Code verified, everything is fine. You can use SMS functionality.",

	'ElggMan_:sms:problems' => "Problems sending message.",

	// frontend

	'ElggMan:acceppt:FR' => "Accept Friend Request",
	'ElggMan:reject:FR' => "Reject Friend Request",
	'ElggMan:send:FR' => "Send Friend Request",
	'ElggMan:delete:FR' => "Delete My Friend Request",


// external contacts
	'ElggMan:contacts'  =>  "External Contacts",
	'ElggMan:contacts:delete' => "Delete an external person from your contact list",
	'ElggMan:contacts:dialog:delete' => "Do you really want to delete selected contacts?",
	'ElggMan:contacts:dialog:delete:advice' => "Please select contacts to delete!",
	'ElggMan:contacts:add' => "Add an external person to your contact list",
	'ElggMan:contacts:modify' => "Modify contact data",

// users
	'ElggMan:users'  =>  "All Activated Users",
	'ElggMan:users:add' => "Add a new user to community",
	'ElggMan:users:delete' => "Remove a user and his content from community",
	'ElggMan:users:dialog:delete' => "Do you really want to delete selected users?",
	'ElggMan:users:dialog:delete:advice' => "Please select users to delete!",
// users Online
	'ElggMan:usersOnline'  =>  "Users Online",

	'ElggMan:notActivatedUsers'  =>  "Not Activated User",
	'ElggMan:activateUser'  =>  "Activate user",
	'ElggMan:deactivateUser'  =>  "Deactivate user",
	'ElggMan:activateUser:dialog' => "Do you really want to activate selected users?",
	'ElggMan:deactivateUser:dialog' => "Do you really want to deactivate selected users and all of related content?",

	'ElggMan:blockedUsers'  =>  "Banned users",
	'ElggMan:blockUser'  =>  "Ban user",
	'ElggMan:unblockUser'  =>  "Unban user",
	'ElggMan:blockUser:dialog' => "Do you really want to ban selected users?",
	'ElggMan:unblockUser:dialog' => "Do you really want to unban selected users?",

	'ElggMan:resetPassword'  =>  "Reset password",

	'ElggMan:makeAdmin'  =>  "Make admin",
	'ElggMan:removeAdmin'  =>  "Remove admin",

	'ElggMan:editUser'  =>  "Edit user",
	'ElggMan:editContact'  =>  "Edit contact",
	'ElggMan:showProfile'  =>  "Show profile",

	'ElggMan:groups'  =>  "Groups",

	'ElggMan:mark_all' => "Select all",

	'ElggMan:contact_invite' => "contact/invite",
	'ElggMan:manage' => "manage",

	'ElggMan:selectedUser' => "Selected recipients:",

	'ElggMan:delete' => "Delete",
	'ElggMan:add' => "Add",

  'ElggMan:ok' => "OK",
  'ElggMan:cancel' => "Cancel",
	'ElggMan:save' => "Save",
	'ElggMan:done' => "Done",
  'ElggMan:ERROR' => "Sorry, an error occured.",
  'ElggMan:WARNING' => "Warning, please note!",
	'ElggMan:INFO' => "Please note!",
	'ElggMan:CONFIRMATION' => "Do you eally want to do that?",
	
	'ElggMan:import' => "Import contacts",

	'ElggMan:messages:show' => 'Message Center',


	'ElggMan:email:label' => 'Send e-mail',
	'ElggMan:email:label:descr' => 'Send an e-mail to the following recipients:',
	'ElggMan:email:label:subject' => 'Subject:',
  'ElggMan:email:schedule' => "Schedule e-mail",
  'ElggMan:email:now' => "Send e-mail now",
	
	'ElggMan:email:save_as' => 'Save as',
	'ElggMan:email:load_from' => 'Load from',

	'ElggMan:alert:email:select' => 'Please select at least one item with e-mail address!',
	'ElggMan:email:newdraft' => '[new draft]',
	'ElggMan:email:deldraft' => 'delete draft',
	'ElggMan:alert:draft:name' => '',
	'ElggMan:alert:draft:delete' => '',

// messages
	'ElggMan:messages:delete' => "Delete",
  'ElggMan:messages:maximize' => "Maximize",
  'ElggMan:messages:dialog:delete' => "Do you really want to delete selected messages?",
	'ElggMan:messages:dialog:delete:advice' => "Please select messages to delete!",

	'ElggMan:messages:recipient' => "Recipient",
	'ElggMan:messages:sender' => "Sender",
	'ElggMan:messages:datetime' => "Date and time",
	'ElggMan:messages:subject' => "Subject",
	'ElggMan:messages:state' => "State",
	'ElggMan:messages:search' => "Search in messages or subject",
	'ElggMan:messages:re' => "Re: ",

	'ElggMan:messages:outbox' => "Outbox",
	'ElggMan:messages:inbox' => "Inbox",
	'ElggMan:messages:answer' => "Answer",

	'ElggMan:email:help' => '',

	'ElggMan:email:draft' => 'use drafts',

// Groups
	'ElggMan:groups:other' => 'Other groups',
	'ElggMan:groups:my' => 'I\'m a member of',
	'ElggMan:groups:member' => 'Group members',
	'ElggMan:groups:help' => "Please use the mouse to <strong>drag and drop groups</strong> you want to join or leave.<br>For the groups <strong>marked with an *</strong> you are the group owner. <span style='color : grey'>[Groups in grey]</span> are closed groups.",
	'ElggMan:groups:error:owner' => "You are the group owner. You can't leave the group until you have transmitted the ownership.",
	'ElggMan:groups:error:closed' => "This is a closed group. You can not join until you haven't asked for membership.",

// SMS
	'ElggMan:sms' => "SMS",
	'ElggMan:sms:label' => "Send SMS",
	'ElggMan:sms:label:descr' => "Send SMS to the following recipients:",

	'ElggMan:alert:sms:select' => 'Please select at least one item with mobile number!',


	'ElggMan:sms:confirm' => "Confirm Number",
	'ElggMan:sms:history' => "SMS History",
	'ElggMan:sms:balance' => "Recharge Account Balance",
	'ElggMan:sms:char' => "character",
	'ElggMan:sms:chars' => "characters",
	'ElggMan:sms:sendnow' => "Send now",
	'ElggMan:sms:schedule' => "SMS schedule",
	'ElggMan:sms:enternumber' => "No users selected. Please select users before or add new personal contacts.",

	'ElggMan:sms:confirm:helpHeaderH' => "Please confirm your mobile phone number!",
	'ElggMan:sms:confirm:helpHeader' => "To be shure you are the person, who is allowed to send with your number, you have to verify this.",

	'ElggMan:sms:confirm:helpNumberH' => "Enter your phone number",
	'ElggMan:sms:confirm:helpNumber' => "Please enter your phone number, starting with the plus and the code for your country. Valid numbers are e.g. +49 173 123 4 5678",
	'ElggMan:sms:confirm:start' => "Submit Number",

	'ElggMan:sms:confirm:helpCodeH' => "Enter the validation code",
	'ElggMan:sms:confirm:helpCode' => "Please check your mobile phone for new message. Within a short time you should receive a SMS with your validation code. Enter this code here.",
	'ElggMan:sms:confirm:verify' => "Submit Code",

	'ElggMan:sms:sender:restart' => "Restart Validation",
	'ElggMan:sms:sender:ready' => "Ready",
	'ElggMan:sms:sender:ready' => "Ready",

	// settings
	'ElggMan:save:reload' => "Save theme and reload",
	'ElggMan:theme:modern' => "Modern Theme",
	'ElggMan:theme:dark' => "Dark Theme",
	'ElggMan:theme:cs24' => "Silverblue Theme",
	'ElggMan:helpTableColumns' => 'This is the users table with all your selected columns. Please use your mouse in the header column to drag the columns width exactly to your needs.',
	'ElggMan:rb:view:admin' => "Admin View",
	'ElggMan:rb:view:user' => "User View",

	);

	add_translation("en",$english);

?>