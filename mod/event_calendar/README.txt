/**
 * Manage and display events
 * 
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008-2011
 * @link http://radagast.biz/
 * 
 */
 
 Note: this README.txt file is a bit outdated because it does not describe numerous
 recently added features. For more information, check the CHANGES.txt file
 or look at the event calendar settings available through Tools Administration.
 
Version: 0.85

Requires: Elgg 1.8 or higher

Should be installed in mod/event_calendar

*Description*

The event_calendar plugin adds a site-wide event calendar as well as
an event calendar to each group. Various options related to the site and group
calendars can be set using the event calendar settings on the tool
administration page.

Group members can view events by month, week and day using a jQuery date 
picker widget, and submit event descriptions including the venue, start date,
end date, tags, description, organiser, contact person, event access level, 
and fees if any.

Group events are aggregated into the site wide event calendar accessible from
the Tools menu drop down.

Site admins (or optionally any user) can also add non-group-specific events to 
the site-wide calendar.

Users can add group or site-wide events to a personal calendar to showcase
events that they plan to attend or are interested in. They can optionally 
display these events by dragging an Event calendar widget onto their profile
or dashboard.

The number of users who have added an event to their personal gallery is listed
on each event page along with a link to a page that displays these users in a 
gallery format. It is thus easy to find other people interested in the same
event.

*Admin settings*

Numerous options for the event calendar can be set in the event_calendar settings 
area under Tool Administration.

These include:

Add starting and ending times as well as dates to events (default: no)

Automatically add events a user creates to his/her personal calendar (default: yes)

Automatically add group events for all members to their personal calendars (default: no)

(If activated, the autogroup function automatically adds all group events to a 
user's calendar for all groups that the user is a member of. Group events are 
also automatically removed if the user leaves the group.)

Use Agenda view (default: no)

Useful for conferences with multiple events on the same day.

Display venue in event listings (default: no)

Add region dropdown (default: no), plus a way to specify the allowable regions

First date displayable on show events pages (default: no first date)

Last date displayable on show events pages (default: no last date)

As well, there are numerous options for configuring the site wide and group calendars.

*Acknowledgment*

The initial development of the event calendar plugin was funded by the Research
& Development department at the Royal Institute of British Architects
(RIBA). Several other clients have funded enhancements.