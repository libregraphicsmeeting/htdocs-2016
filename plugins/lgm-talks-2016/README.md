# Manage the talks submission and scheduling

- The visitor submits a talk through a Formidable form
- Each talk is converted into a post.
- The content team can
  - see a list of all submitted talks
  - comment on each of them
  - edit the title and the description
  - send a comment to the speaker
  - accept or refuse a submission
  - set the date, time, length and room of each submission
- Each speaker can
  - edit the title and the description
  - add the slides

Eventually:
- the visitors can comment on each talk

## Submission

- For each submission create a user
- For each submission create a post with content type "Talk"

## Review of the submisssions

Create a template that shows the list of all submissions and lets the content team:

- comment on each submission
- set the submission as accepted /refused
- get in touch with the speaker

taxonomy talk-status:
- pending
- accepted
- rejected

for each entry show:
- firstname / lastname
- title
- description
- link to the edit page
- add a custom field for each "content team member"
  - using "advanced custom fields"?
  - using ajax (for both the talks-status and the loged in person's comments)?


## Scheduling

Create a template that shows all the accepted submissions and helps setting the date, time, duration, room

- two divs with scrollbars
  - left scheduled in order
  - right accepted / non scheduled alphabetic (+refused)

## Program

Create a short code that shows the program

## Upload the slides

- add a custom field
  - using "advanced custom fields"

### Todo:

- Choose the right return-to address in the "Formidable > User creation" settings (Lara?).
