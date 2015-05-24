#!/bin/bash
#
# simple script to test boxcar connectivity
#

TOKEN=$1

if [ -z "$TOKEN" ]; then
	echo "Usage: $0 <ACCESS TOKEN>"
	exit 1
fi

time curl -v -d "user_credentials=$TOKEN" \
     -d "notification[title]=message title" \
     -d "notification[long_message]=<b>Some text or HTML for the full layout page notification</b>" \
     -d "notification[sound]=bird-1" \
     -d "notification[source_name]=My own alert" \
     -d "notification[icon_url]=http://new.boxcar.io/images/rss_icons/boxcar-64.png" \
     https://new.boxcar.io/api/notifications
