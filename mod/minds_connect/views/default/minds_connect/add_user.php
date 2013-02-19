<?php

$data = $vars['data'];
$link = $vars['link'];
$user = $vars['user'];

$type = $link ? 'link' : 'register';

$reg_email    = ($link != 'email') ? $data['email'] : '';
$reg_username = ($link != 'username') ? $data['username'] : '';

?>

<script type="text/javascript">

    $(document).ready(function() {

        $('#link-register').click(function() {
            $('#type').val('register');
            $('#link').hide();
            $('#register').slideDown();     
        });

        $('#register-link').click(function() {
            $('#type').val('link');
            $('#register').hide();
            $('#link').slideDown();     
        });
    });

    function MCValidatePwd() {

        if ($('#type').val() != 'register') {
            return true;
        }

        var pw1 = document.myForm.password.value;
        var pw2 = document.myForm.password2.value;

        if (pw1 == pw2) {
            return true;
        } else {
            alert('Passwords do not match!');
            return false;
        }
    }

</script>

    <?php if ($link): ?>

        <div id="link">

            <?php if ($link == 'email'): ?>

                <span>
                    An existing account was found with the email address <strong><?php echo $data['email']; ?></strong>.<br /><br />
                    Enter your username and password to link the accounts or click 'Register New Account'
                    to create a new account which will be linked to your minds.com account. 
                </span>

            <?php else: ?>
    
                <span>
                    An existing account was found with the username <strong><?php echo $data['username']; ?></strong>.<br /><br />
                    Enter your username and password to link the accounts or click 'Register New Account'
                    to create a new account which will be linked to your minds.com account. 
                </span>
    
            <?php endif; ?>

            <br /><br />

            <form name="link" action="<?php echo elgg_get_site_url(); ?>action/minds_connect/add_user" method="post" onSubmit="return MCValidatePwd()">

                <?php echo elgg_view('input/securitytoken'); ?>

                <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">

                <div>
                    <label><?php echo elgg_echo('Username'); ?></label>
                    <?php echo elgg_view('input/text', array('name' => 'username', 'value' => $user->username)); ?>
                </div><br />
                
                <div>
                    <label><?php echo elgg_echo('Password'); ?></label>
                    <?php echo elgg_view('input/password', array('name' => 'password')); ?>
                </div><br />
    
                <input type="submit" value="Submit" name="Submit" class="elgg-button elgg-button-action">
                <a id="link-register" href="javascript:void(0);" class="elgg-button elgg-button-action">Register New Account</a>

            </form>

        </div>
            
    <?php endif; ?>

    <div id="register" <?php if ($link) { echo 'style="display:none;"'; } ?> >

        <form name="register" action="<?php echo elgg_get_site_url(); ?>action/minds_connect/add_user" method="post" onSubmit="return MCValidatePwd()">

            <?php echo elgg_view('input/securitytoken'); ?>

            <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">

            <div>
                <label>Display name</label><br>
                <input type="text" name="name" value="<?php echo $data['name']; ?>" class="elgg-input-text elgg-autofocus">
            </div><br />

            <div>
                <label>Email address</label><br>
                <input type="text" name="email" value="<?php echo $reg_email; ?>" class="elgg-input-text">
            </div><br />

            <div>
                <label>Username</label><br>
                <input type="text" name="username" value="<?php echo $reg_username; ?>" class="elgg-input-text">
            </div><br />

            <div>
                <label>Password</label><br>
                <input type="password" value="" name="password" class="elgg-input-password">
            </div><br />

            <div>
                <label>Password (again for verification)</label><br>
                <input type="password" value="" name="password2" class="elgg-input-password">
            </div><br />

            <input type="submit" value="Register" name="Register" class="elgg-button elgg-button-action">

            <?php if ($link): ?>
                <a id="register-link" href="javascript:void(0);" class="elgg-button elgg-button-action">Link Existing Account</a>
            <?php endif; ?>

        </form>

    </div>

</form>

