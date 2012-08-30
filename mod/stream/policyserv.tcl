proc policysend {channel request} {
	global server port
	fileevent $channel readable ""
	append request [read $channel]
	if {[string first "<policy-file-request/>" $request] == 0} {
		puts $channel "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
		puts $channel "<cross-domain-policy xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.adobe.com/xml/schemas/PolicyFileSocket.xsd\">"
		puts $channel "   <allow-access-from domain=\"$server\" to-ports=\"$port\"/>"
		puts $channel "</cross-domain-policy>"
		puts -nonewline $channel "\u0000"
		close $channel
		return
	}
	if {[string length $request] > 24} {
		close $channel
		return
	}
	if {[eof $channel]} {
		close $channel
		return
	}
	fileevent $channel readable [list policysend $channel $request]
}

proc policy {channel clientaddr clientport} {
	fconfigure $channel -blocking false -buffering none -encoding utf-8
	fileevent $channel readable [list policysend $channel ""]
	puts "Policy file request from $clientaddr:$clientport"
}

set server "*"
set port 6667
socket -server policy 843
vwait forever
