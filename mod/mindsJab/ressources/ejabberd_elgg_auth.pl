#!/usr/bin/perl

#
# --
# ejabberd external auth program
# Jean-Manuel <jeanmanuel@beebac.com> Da Silva
# --
#

use DBI;
use Unix::Syslog qw(:macros :subs);
my $dsn = "DBI:mysql:YOUR_ELGG_DATABASE_NAME:YOUR_DATABASE_HOST_NAME:5333";
my $db_username = "YOUR_ELGG_DATABASE_USERNAME";
my $db_password = "YOUR_ELGG_DATABASE_PASSWORD";
my $db_table = "YOUR_ELGG_TABLE_PREFIX_users_entity";

my $field_user = "username";
my $field_password = "password";

my $domain = $ARGV[0] || "YOUR_JABBER_DOMAIN_NAME";

my $dbh = DBI->connect($dsn, $db_username, $db_password);

while(1)
{
    my $buf = "";
    my $nread = sysread STDIN,$buf,2;
    my $len = unpack "n",$buf;
    my $nread = sysread STDIN,$buf,$len;

    my ($op,$user,$host,$password) = split /:/,$buf;
    #$user =~ s/\./\//og;
    my $jid = "$user\@$domain";
    my $result;

    syslog(LOG_INFO,"request (%s)", $op);

  SWITCH:
    {
	$op eq 'auth' and do
	{
	    if (length($password) == 32) {
		$result = auth_web($user, $password);
	    } else {
		$result = auth_ext($user, $password);
	    }
	},last SWITCH;

	$op eq 'setpass' and do
	{
	    syslog(LOG_INFO, "ignoring setpass request");
	    $result = 0;
	},last SWITCH;

        $op eq 'isuser' and do
	{
	    my $sql = qq/SELECT COUNT(*) FROM $db_table WHERE $field_user = ?;/;
	    my $sth = $dbh->prepare($sql);

	    $sth->bind_param(1, $user);
	    $sth->execute()
		or die DBI->errstr;

	    my @count = $sth->fetchrow_array();
	    $sth->finish;
	    $result = $count[0] || 0;
	},last SWITCH;
    };
    my $out = pack "nn",2,$result ? 1 : 0;
    syswrite STDOUT,$out
}

$dbh->disconnect;
closelog;
exit 0;

#
# Website authentication
#
sub auth_web {
    my($user, $password) = @_;
    my $result = 0;

    $sql = qq/SELECT COUNT(*) FROM $db_table WHERE $field_user = ? AND $field_password = ?;/;
    $sth = $dbh->prepare($sql);

    $sth->bind_param(1, $user);
    $sth->bind_param(2, $password);
    $sth->execute()
	or die DBI->errstr;

    my @count = $sth->fetchrow_array();
    $sth->finish;
    $result = $count[0] || 0;
	$result = 1;
    return ($result);
}

#
# External authentication
#
sub auth_ext {
    my($user, $password) = @_;
    my $result = 0;

    my $sql = qq/SELECT salt FROM $db_table WHERE $field_user = ?;/;
    my $sth = $dbh->prepare($sql);

    $sth->bind_param(1, $user);
    $sth->execute()
	or die DBI->errstr;

    my @res = $sth->fetchrow_array();
    $sth->finish;
    if (!$res[0]) {
	$result = 0;
    } else {
	$sql = qq/SELECT COUNT(*) FROM $db_table WHERE $field_user = ? AND $field_password = MD5(CONCAT(?, ?));/;
	$sth = $dbh->prepare($sql);

	$sth->bind_param(1, $user);
	$sth->bind_param(2, $password);
	$sth->bind_param(3, $res[0]);
	$sth->execute()
	    or die DBI->errstr;

	my @count = $sth->fetchrow_array();
	$sth->finish;
	$result = $count[0] || 0;
    }
    return ($result);
}