# Documentation: E-mails and Messages

E-mails for LGM 2016 are sent out in several ways:

## Announcements

The email address to use to send out the call: (INFO MISSING)

## Automatic notifications from the website

The WordPress platform sends out different notification messages.

By default, they are sent from the host website (via [PHPMailer](https://codex.wordpress.org/Plugin_API/Action_Reference/phpmailer_init)), with the sender address defined under **Settings > General > Email Address**.

## Notifications linked to Proposal Submission Form

### Message to admins:

To: lab at v-ac-uum.xyz, snelting at collectifs.net, ale at graphicslab.org, ms at ms-studio.net

Content:

> [default-message]
> 
> Check out all new submissions at: http://libregraphicsmeeting.org/2016/wp/wp-admin/edit.php?post_type=talk

### Message to submitter:

Subject: Thank you for your submission to LGM 2016: Other Dimensions

From: Libre Graphics Meeting - ale at graphicslab.org

Content:

> Hello [13],
> 
> Thank you for your submission to LGM 2016!
> 
> We will notify you by the end of January 2016 whether your proposal «[8]» is accepted.
> 
> All the best from the LGM-content team,
> 
> Larisa Blažić, Øyvind Kolås, Phil Langley, Ale Rimoldi, Femke Snelting

### Note about SPF records / spam filters

For the email domain that sends notifications via the WordPress site / Tuxfamily server, the **SPF (DNS record)** is an issue - without a good setting, a large part of the notifications will go into the spam folders. 

The following entry would probably do the job:

`v=spf1 mx a:libregraphicsmeeting.org/20 a:tuxfamily.org/20 -all`

See [openspf.org](http://www.openspf.org/SPF_Record_Syntax) for more info about the SPF record syntax.

Here's a testing tool: [http://www.kitterman.com/spf/validate.html](http://www.kitterman.com/spf/validate.html)