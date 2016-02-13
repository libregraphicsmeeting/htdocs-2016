## Documentation: the Talk content type

The Talk content type is created by our custom plugin, [with those functions](https://github.com/libregraphicsmeeting/htdocs-2016/blob/master/plugins/lgm-talks-2016/lgm-custom-post-types.php).

### Taxonomies

The following taxonomies are available:

- **Tags** : those tags can be used to attach keywords to the talk proposals (such as: *type design, hardware hacking, knitting, SVG, video, architecture*...). They can be useful when the content gets transfered into the LGM video archive. The plan is that the Speakers will be able to add tags to their talk, once they are approved.
- **Talk Format** : initially set by the submitter. Options are: *Presentation, BoF, Workshop, Party, Break*.
- **Talk Status** : this taxonomy is visible to admins only, and allows to mark a talk as: pending, accepted, rejected, needs discussion, etc.
- **Room** : will be useful once the organization team prepares the schedule.

### Custom fields

The following [custom fields](https://codex.wordpress.org/Custom_Fields) are being populated from the submission form : 

* Title = title field
* Summary of your presentation ( = main content field)

* Speaker email: lgm_speaker_email
* Speaker first name: lgm_speaker_firstname
* Speaker last name: lgm_speaker_lastname

* Additional speakers = lgm_additional_speakers
* Short biography = lgm_short_bio
* Website = lgm_speaker_website
* Preferred day = lgm_preferred_day
* Travel funding = lgm_travel_funding
* Travel costs = lgm_travel_costs
* Currency = lgm_currency
* Comments, questions = lgm_speaker_comments

The following custom fields are available when the user edits the Talk after approval:

* Slides = lgm_slides

### Timestamps

We use the [Minimalistic Event Manager](https://github.com/ms-studio/minimalistic-event-manager/) plugin, so the timestamps are saved like this:

* Start date: `_mem_start_date`
* End date `_mem_end_date`