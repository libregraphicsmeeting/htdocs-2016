# Documentation: Talk Proposals

This document explains how the LGM 2016 talk proposals are handled

## Submitting the proposal

A talk submission form has been created (using the WordPress plugin *Formidable*).

What happens when a talk proposal gets submitted:

1. A **message** is displayed after the form is submitted (defined under *Formidable > Forms > LGM 2016 Proposal Submissions > Settings > General*)
3. The form gets saved to the database, entries can be seen under *Formidable > Entries*.
4. **Create Talk:** A new "Talk" (custom post type) is created, and populated with the information from the submission form.
2. **Email to Site Admin:** a notification email gets sent after each talk submission.
6. **Email to Submitter:** The newly registered user receives an email notification. 

Those actions are defined under *Formidable > Forms > "LGM 2016 Proposal Submissions" > Settings > Form Actions*

## User account creation

The user accounts for Speakers will get created at a later step. Some methods we could use:

Create users:
https://codex.wordpress.org/Function_Reference/wp_create_user

Set talk authorship:
http://wordpress.stackexchange.com/questions/19735/how-can-i-set-the-post-author-of-a-post-i-just-created-with-php

Send custom notifications:
https://codex.wordpress.org/Function_Reference/wp_mail 



