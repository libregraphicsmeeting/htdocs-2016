<?php
/**
 * Inspired by https://gist.github.com/jakebellacera/635416
 *
 * ICS checkers:
 * - http://icalvalid.cloudapp.net/
 * - http://severinghaus.org/projects/icv/
 *
 * TODO:
 * - find a solution for sequence and the updates: it should be incremented for each change
 */
include(get_stylesheet_directory().'/page-schedule-class.php');
$pageSchedule = new LGMPageSchedule();

$list = [];
while ($item = $pageSchedule->next()) {
    if ($item['time']) {
        // echo("<pre>item: ".print_r($item, 1)."</pre>");
        $list[] = strtr(
            "BEGIN:VEVENT
DTSTART:%start

DTEND:%end

UID:%id

DTSTAMP:%timestamp

LOCATION:%location

DESCRIPTION:%description

URL;VALUE=URI:%url

SUMMARY:%summary

END:VEVENT
",
            array(
                '%start' => lgmGetIcsDateFromIso($item['start']),
                '%end' => lgmGetIcsDateFromIso($item['end']),
                '%id' => base64_encode($item['start'].$item['title']).'@libregraphicsmeeting.org',
                '%timestamp' => lgmGetIcsDateFromIso($item['timestamp']),
                '%location' => lgmGetIcsStringEscaped(''),
                '%description' =>
                    implode(": ", [
                        lgmGetIcsStringEscaped($item['speakers']),
                        lgmGetIcsStringEscaped($item['title']),
                        // lgmGetIcsStringEscaped($item['url']),
                    ]
                    ),
                '%url' => $item['url'],
                '%summary' => lgmGetIcsStringEscaped($item['title']),
            )
        );
    }
}

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . 'schedule.ics');

$content =  strtr(
    "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:LGM 2016 London
X-WR-TIMEZONE:UTC
X-WR-CALDESC:Schedule for the Libre Graphics Meeting 2016 in London
%list
END:VCALENDAR
",
    array(
        '%list' => implode("\n", $list),
    )
);

echo(str_replace("\n", "\r\n", $content));

/** Converts an ISO date time to an ics-friendly format */
function lgmGetIcsDateFromIso($isodate = null) {
  return gmdate('Ymd\THis\Z', isset($isodate) ? strtotime($isodate) : time());
}
     
/** @return string the string escaped for ICS files */
function lgmGetIcsStringEscaped($string) {
  return preg_replace('/([\,;])/','\\\$1', $string);
}
