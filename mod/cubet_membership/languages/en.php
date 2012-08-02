<?php
	/**
	 * Elgg en page
	 * 
	 * @package Elgg Membership
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgg.in/
	 */
	$english = array(
                'membership' => 'Membership',
                'usertype' => 'Usertype',
                'membership:category'=>'Category Name',
                'membership:display:name' => 'Display Name',
                'membership:settings' => 'Membership Settings',
                'membership:description' =>"Description",
                'account:settings'=>'Account Settings',
                "confirm:your:payment"=>"Confirm your payment",
                "membership:log" => "Membership Log",
                "upgrade:membership" => "Upgrade Membership",
                'cancel:validate:subject'=>'%s your premium membership is canceled now!',
                'reconfirm:validate:subject'=>'%s please reconfirm your payment for being a premium member!',
                'membership:deleted'=>'Category deleted sucessfully',
                'membership:notdeleted'=>'Category not deleted',
                'membership:edited'=>'Category edited sucessfully',
                'membership:confirm' => 'Confirm',
                'membership:alreadyregistered' => 'You are already registered as a %s member',
                'membership:denied' => 'You are currently registered as a %s member. You cannot change to a lower level',
                'membership:alreadyexists' => 'Category already exists',
                'allow:upgrade' => 'Do you want to allow users to upgrade membership?',
                'show:membership' => 'Do you want to show membership types in registration form?',
                'new:category' => 'Add new category',
                'no:permission:upgrade' => 'You do not have the permission to upgrade your membership.',
                'upgrade:ok' => 'You have upgraded your membership successfully',
                'receive:notifications:membership' => 'Do you want to receive notifications when a user upgrades his membership?',
                'no:users:membership' => 'No users in this membership category',
                'receive:notifications:subject' => 'Membership upgraded',
                'payment:settings:error' => 'The administrator has not saved plugin settings.',
                'category:fields:blank' => 'All fields are mandatory.',
                'amount:field:blank' => 'Please enter a valid amount',
                'allow:trial' => 'Do you want to allow trial period for premium users?',
                'trial:period:units' => 'Trial Period Units',
                'trial:period:duration' => 'Trial Duration',
                'trial:amount' => 'Trial Amount',
                'trial:period: ' => 'Trial Period: ',
                'trial:amount: ' => 'Trial Amount: ',
                'allow:recurring' => 'Do you prefer recurring payments/subscriptions?',
                'recurring:times' => 'Recurring times / Number of installments',
                'subscr:details' => 'Subscription Details',
                'subscr:period:units' => 'Subscription Period Units',
                'subscr:period:duration' => 'Subscription Duration',
                'subscr:amount' => 'Subscription Amount',
                'add:premium:category' => 'Add Premium Category',
                'edit:premium:category' => 'Edit Premium Category',
                'payment:result'=>"Payment Result",
                'select:membership:category'=>"Select a membership category",
                'cancelled:subscription:subject' => 'Cancelation of subscription',
                'membership:authorizenet' => 'Authorize.net',
                'membership:paypal'=> 'Paypal',
                'membership:settings:saved'=> 'The settings for the membership plugin has saved successfully',
                'membership:settings:error'=>'Something went wrong while saving the settings of the membership plugin',
                'membership:paypal_email:error' => 'Please enter a paypal email to save Paypal Settings',
                'membership:fields:empty:error'=> 'Please enter all fields to save Authorize.net settings',
                'no:payment:methods' => 'No payment methods set by the administrator',
                'receive:notifications:body' => 'Hi %s,
			
%s renewed %s membership to \'%s\'.',
                'receive:notification:admin:body' => 'Hi %s,
			
%s successfully upgraded %s membership to \'%s\'. To view, click on the following link:

%s
',
                'receive:notification:body' => 'Hi %s,
			
%s successfully upgraded %s membership to \'%s\'. If you have previous subscription, please cancel by clicking on the following link:

%s
',
                'receive:notification:success:body' => 'Hi %s,

%s successfully upgraded %s membership to \'%s\'. You can login by clicking on the following link:

%s
',
                'transaction:receive:notification:body' => ' Hello %s,

%s successfully upgraded %s membership to \'%s\'.

Please cancel the previous subscription with Profile ID Number  \'%s\' by clicking on the following link:

%s

A summary of your  previous transaction is shown below. 

Transaction Details

Subscription Id   : %s
Merchant Email    : %s
If you will not cancel the above active subscription, it will deduct amount from your payapal account. So please do the needful to cancel the subscription from your paypal acount.' ,
                'auth:receive:notification:body' => 'Hi %s,

%s successfully upgraded %s membership to \'%s\'. And your previous subscription is canceled.
',
                'reconfirm:validate:body'=>'Hi %s,
				
Please reconfirm your payment for being a premium member by clicking on the link below:

%s

If you want to register as a free user instead,confirm by clicking on the link below:

%s
',						
                'expire:membership:subject' => 'Membership will expire within 15 days',
                'expire:membership:body' => 'Hi %s,
			
Your membership \'%s\' will be expired on %s. Please renew your membership by clicking on the link below:

%s

',
                'cancelled:membership:subject' => 'Cancelation of Membership',
                'cancelled:membership:body' => 'Hi %s,
			
You have successfully canceled subscription for your membership \'%s\' with Profile ID No \'%s\'. If you want to renew your membership, click on the link below:

%s

',
                'cancelled:membership:admin:body' => 'Hi %s,
			
%s has successfully cancelled the membership \'%s\' with Profile ID No \'%s\'. If you want to upgrade membership, click on the following link:

%s

',
                'cancelled:delete:membership:body' => 'Hi %s,

You have successfully canceled subscription for your membership \'%s\' with Profile ID No \'%s\'. And so you have deleted successfully from the site.

',
                'cancelled:delete:membership:admin:body' => 'Hi %s,

%s has successfully canceled subscription for the membership \'%s\' with Profile ID No \'%s\'.  And so the user has deleted successfully from the site.

',
                'cancelled:previous:membership:body' => 'Hi %s,

Your have successfully canceled the previous subscription with Profile ID No \'%s\'. You can login by clicking on the following link:

%s

',
                'cancelled:previous:membership:admin:body' => 'Hi %s,

%s has uccessfully canceled the previous subscription  with Profile ID No \'%s\'. If you want to upgrade the membership, click on the following link:

%s

',
                'cancel:validate:body'=>'Hi %s,

If you want to reconfirm your payment for being a premium member by clicking on the link below:

%s
',
                'membership:disable:user:subject' => 'Cancel subscriptions',
                'membership:disable:user:body' => 'Hi %s,

The administrator has deleted you from the membership site. So please cancel the active subscription with Profile ID Number \'%s\' if not expired in your paypal account. ',
                'failed:membership:subject' => 'Payment failed',
                'failed:membership:body' => 'Hi %s,
			
Sorry, we still haven\'t gotten your payment. Please check your account to avoid cancellation of the membership.

',
			/*'failed:membership:admin:body' => 'Hi %s,
			
%s couldn\'t pay for the membership \'%s\' successfully. If you want to upgrade the membership, click on the following link:

%s

',*/
                'expired:membership:subject' => 'Membership expired',
                'expired:membership:body' => 'Hi %s,
			
Your membership \'%s\' has expired. Please renew your membership by clicking on the link below:

%s

',
                'expired:membership:admin:body' => 'Hi %s,
			
%s\'s membership \'%s\' has expired. If you want to upgrade membership, click on the following link:

%s

',
                'expired:subscription:subject' => 'Subscription error',
                'expired:subscription:admin:body' => 'Hi %s,

The system could not find the user based on subscription id:\'%s\'
',
                'membership:report' => 'Membership Log',
                'membership:upgrade' => 'Upgrade your membership',
	        'membership:paypalid' => 'Your Paypal Email ID?',
	        'membership:paypalamount'=>'Paypal amount(in dollars)',
                'uservalidationbyemail:premiumfailed'=>'Transaction failed.To activate your premium account, please confirm your email address by clicking on the link we just sent you.',

		'authorizenet:instruction' => "To integrate Authorize.net into your store you need to follow a few simple steps, which are shown below:<br>
										<ul style='list-style-type:circle;'>
											<li><a style='text-decoration:underline;padding:0;margin:0;background-image:none;display:inline;' href='http://www.authorize.net/solutions/merchantsolutions/merchantinquiryform/'>Register for an Authorize.net merchant account here</a></li>
											<li>Type the API login ID you received from Authorize.net into the 'API login ID' field below</li>
											<li>Login to your Authorize.net account and generate a transaction key from the Settings -> Security -> Obtain Transaction Key link</li>
											<li>Copy the transaction key that you generated into the 'Transaction Key' field below</li>
                                                                                        <li>To make the registration in test mode, login to your Authorize.net account and turn '<b>Test ON</b>' in Settings -> Test Mode </li>
                                                                                        <li>When everything appears to be working change test mode to turn '<b>Test OFF</b>' to accept live payments from your site.</li>
                                                                                        <li>Also choose '<b>No</b>' from the test account options below to accept live payments in live account. </li>
										</ul>",
                'settings' => 'Settings',
		'api:login:id' => "API Login ID",
		'transaction:key' => 'Transaction Key',
		'test:account' => 'Test Account',
                'authorizenet' => 'Authorize.net',
                'show:checkout' => 'Do you want which type of checkout method for registration?',
                'pay' => "Pay now",
                'trial:occurrences' => "Trial Occurrences",
                'confirm:payment' => "Confirm your payment",

                'subscr:period' => "Subscription Period: ",
                'recurr:times' => 'Recurring times: ',
                'trial:note' => 'If you have trial period then subscription starts after trial period',
                'note' => "Note",
                'start:date:' => "Start date: ",
                
                /*
                * Authorize.net payment details
                */
                'Authorize.net Details' => 'Authorize.net Details',
                'Payment Information' => 'Payment Information',
                'Credit Card Number' => 'Credit Card Number:',
                'Security Code (CVV)' => 'Security Code (CVV):',
                'Expiration Date' => 'Expiration Date:',
                'Billing Information' => 'Billing Information',
                'First Name' => 'First Name:',
                'Last Name' => 'Last Name:',
                'Address 1' => 'Address 1:',
                'Address 2' => 'Address 2:',
                'City' => 'City:',
                'State' => 'State:',
                'Zip Code' => 'Zip Code:',

                'back:text' => "Go To Home",
                'cancel:authorizenet' => "Sorry, Authorize.net transaction has declined.<br> Please check your authorize.net settings.",

                'approvalcode' => "Authorization Code:",
                'transaction:id' => "Transaction ID:",
                'subscription:id' => "Subscription ID:",

                'auth:admin:user:delete:no' => "Can not delete user, please check your authorize.net settings",
                'cancel:membership:desc' => "Cancel Membership",
                'you must:register' => "You have to register first",
                'user:cancel:yes' => "Your account and membership type has removed",
                'user:cancel:no' => "Sorry, your membership type cannot be removed",
                'not selected any category' => "You have not selected any category",
                'membership:details' => 'Membership Details',
                'amount:' => 'Amount: ',
                'title:' => 'Title: ',
                'description:' => 'Description',
            
                // Membership Permission
                'access:not:permitted' => "Sorry you have no permission to access that action",
                'membership:permissions' => "Membership permissions",
                'membership:permissions:free' => "Membership permissions for 'Free' membership type",

                'membership:group:blog' => 'Blog',
                'membership:permission:blog_read' => 'Read',
                'membership:permission:blog_add' => 'Add',
                'membership:permission:blog_edit' => 'Edit',
                'membership:permission:blog_delete' => 'Delete',

                'access:blog:add' => "Sorry you have no permission to add new blog with out upgrade your membership type",
                'access:blog:read' => "Sorry you have no permission to read the blog with out upgrade your membership type",
                'access:blog:edit' => "Sorry you have no permission to edit this blog with out upgrade your membership type",
                'access:blog:delete' => "Sorry you have no permission to delete this blog with out upgrade your membership type",

                'membership:group:file' => 'File',
                'membership:permission:file_read' => 'Read',
                'membership:permission:file_add' => 'Add',
                'membership:permission:file_edit' => 'Edit',
                'membership:permission:file_delete' => 'Delete',

                'access:file:add' => "Sorry you have no permission to add new file with out upgrade your membership type",
                'access:file:read' => "Sorry you have no permission to read the file with out upgrade your membership type",
                'access:file:edit' => "Sorry you have no permission to edit this file with out upgrade your membership type",
                'access:file:delete' => "Sorry you have no permission to delete this file with out upgrade your membership type",

                'membership:group:group' => 'Groups',
                'membership:permission:groups_read' => 'Read',
                'membership:permission:groups_add' => 'Add',
                'membership:permission:groups_edit' => 'Edit',
                'membership:permission:groups_delete' => 'Delete',

                'access:groups:add' => "Sorry you have no permission to add new group with out upgrade your membership type",
                'access:groups:read' => "Sorry you have no permission to read the group with out upgrade your membership type",
                'access:groups:edit' => "Sorry you have no permission to edit this group with out upgrade your membership type",
                'access:groups:delete' => "Sorry you have no permission to delete this group with out upgrade your membership type",
            
                'membership:group:page' => 'Pages',
                'membership:permission:pages_read' => 'Read',
                'membership:permission:pages_add' => 'Add',
                'membership:permission:pages_edit' => 'Edit',
                'membership:permission:pages_delete' => 'Delete',

                'access:pages:add' => "Sorry you have no permission to add new page using current membership type",
                'access:pages:read' => "Sorry you have no permission to read the pages using current membership type",
                'access:pages:edit' => "Sorry you have no permission to edit this page using current membership type",
                'access:pages:delete' => "Sorry you have no permission to delete this page using current membership type",
            
                'membership:group:thewire' => 'The wire',
                'membership:permission:thewire_read' => 'Read',
                'membership:permission:thewire_add' => 'Add',
                'membership:permission:thewire_reply' => 'Reply',
                'membership:permission:thewire_delete' => 'Delete',

                'access:thewire:add' => "Sorry you have no permission to add wire post using current membership type",
                'access:thewire:read' => "Sorry you have no permission to read the wire using current membership type",
                'access:thewire:reply' => "Sorry you have no permission to reply to this wire using current membership type",
                'access:thewire:delete' => "Sorry you have no permission to delete this wire using current membership type",

                'membership:group:bookmarks' => 'Bookmarks',
                'membership:permission:bookmarks_read' => 'Read',
                'membership:permission:bookmarks_add' => 'Add',
                'membership:permission:bookmarks_edit' => 'Edit',
                'membership:permission:bookmarks_delete' => 'Delete',

                'access:bookmarks:add' => "Sorry you have no permission to add new bookmark using current membership type",
                'access:bookmarks:read' => "Sorry you have no permission to read the bookmark using current membership type",
                'access:bookmarks:edit' => "Sorry you have no permission to edit the bookmark using current membership type",
                'access:bookmarks:delete' => "Sorry you have no permission to delete this bookmark using current membership type",

                'membership:group:message' => 'Messages',
                'membership:permission:messages_read' => 'Read',
                'membership:permission:messages_compose' => 'Compose',
                'membership:permission:messages_delete' => 'Delete',

                'access:messages:read' => "Sorry you have no permission to read the message using current membership type",
                'access:messages:compose' => "Sorry you have no permission to compose new message using current membership type",
                'access:messages:delete' => "Sorry you have no permission to delete this message using current membership type",

                'membership:coupon:code' => 'Coupon Code',
                'mem_coupon:code:desc' => 'The coupon codes allows you to provide customers with discounts on membership categories for register in your site.',
                'mem_coupon:code:btn' => 'Create Coupon Code',
                'mem:coupon:name' => 'Coupon name',
                'mem:coupon:discount' => 'Discount',
                'mem:coupon:exp:date' => 'Coupon Expiry Date',
                'mem:coupon:no:of:users' => 'No of Users',
                'mem:coupon:applay:products' => 'Apply',
                'mem:coupon:validate:error' => 'Please enter the Coupon Code',
                'mem:coupon:name:validate:error' => 'Please enter the Coupon Name',
                'mem:coupon:discount:validate:error' => 'Please enter the Discount Amount',
                'mem:coupon:validation:null' => 'Please Enter the following mandatory filelds \n %s.',
                'mem:coupon:saved' => 'Coupon Successfully Saved',
                'mem:coupon:maxuses:limit' => 'The entered coupon code is expired, because the maximum uses are over',
                'mem:no:coupon' => 'No coupons Found',
                'mem:coupon:applied' => 'The coupon that you are entered has been applied.',
                'mem:coupon:not_applied' => "Sorry! the coupon code you entered could not be applied. ",
                'mem:coupon:addfailed' => 'Sorry! we could not be added this coupom at this time, please try after some times.',
                'mem:coupon:actions' => 'Actions',
                'mem:coupon:deletefailed' => 'Sorry! we could not be deleted this coupon at this time.',
                'mem:coupon:delete:confirm' => 'Do you want to delete this coupon?',
                'mem:coupon:header' => 'Redeem Coupon',
                'mem:coupon:description' => "To apply a coupon code to this order, please enter the code below and click 'Apply'.",
                'mem:coupon:apply' => 'Apply',
                'mem:coupon:empty' => 'Please enter the Coupon Code',
                'mem:coupon:not:in:couponcode' => "This is not a valid coupon code",
                'mem:coupon:exp_date' => "The entered coupon code is expired on ",
                'mem:coupon:not_applied' => "The coupon code you entered could not be applied to this user type. ",
                'mem:coupon:applied' => "The coupon that you are entered has been applied to this user type.",
                'mem:oupon:maxuses:limit' => "The entered coupon code is expired, because the maximum uses are over",
                'admin:user:disabled:yes' => 'You have disabled \'%s\' successfully. Whenever the user cancels the paypal subscription, the account will be deleted automatically.',
                'payment:method' => 'Payment Method',
                'allow:payment_registration' =>'Do you want payment at registration?',

                'log:name' => "Name",
                'log:email' => "Email",
                'log:category' => "Category",
                'log:upgrade' => "Upgrade",
				
				/* Minds changes 
				 */
				 'membership:premiumfailed' => 'Your premium account subscription failed! Please try again.'
            
	);
					
	add_translation("en",$english);
?>
