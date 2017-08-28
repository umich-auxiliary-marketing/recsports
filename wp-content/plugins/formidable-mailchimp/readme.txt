=== Formidable MailChimp ===
Requires at least: 3.6
Tested up to: 4.5
Stable tag: 1.06

== Changelog ==
= 2.0 =
* Enhancement: Add compatibility with MailChimp API 3.0
* Enhancement: Migrate any old settings to actions
* Enhancement: When importing a form, migrate old settings to actions
* Enhancement: Add Update Now button to migrate old settings
* Enhancement: Increase minimum version to Formidable 2.0
* Enhancement: Load minimal hooks if Formidable is not compatible
* Fixed: Make sure editing entry updates MailChimp record
* Fixed: Fix passing numeric separate values to MailChimp

= 1.06 =
* Fix PHP7 warnings
* Allow a text field to be used for the email address
* Address issue with get_options function

= 1.05 =
* Make it work with the free version
* Make sure the license number is correct for this plugin instead of accepting any license
* Increase the max number of lists fetched from MailChimp from 50 to 100

= 1.04.01 =
* Fix the Douple opt-in label when adding a MailChimp action for the first time

= 1.04.01 =
* Fix for v1.07.11

= 1.04 =
* Formidable v2.0 compatabilty

= 1.03 =
* Switched to MailChimp API v2.0
* Added an api key check on the settings page that holds the key

= 1.02.01 =
* Fixed synching in combination with Formidable v1.07.02
* Fixed synching when using a user ID field for the email

= 1.02 =
* Only load MailChimp settings when they are needed
* Removed constants for reduced overhead

= 1.01 =
* Fixed more strict error messages
* UI improvements
* Formidable v1.07.02 compatibility
* Eliminated unnecessary globals

= 1.0 =
* Fixed auto updating when used with Formidable 1.07+
* Fixed strict error messages
* Added frm_mlcmp_update_existing hook for allowing/disallowing editing
* Added frm_mlcmp_send_welcome hook for turning the welcome email on/off
* Send dates in the format determined by MC settings

= 1.0rc1 =
* Send checkboxes to MailChimp as a comma-separated list instead of an array
* Send dates in yyyy-mm-dd format
* Remove conflict with saving settings if caching plugins are activated
* Allow empty group settings
* Update conditional logic to allow fields with separate values

= 1.0b3 =
* Added automatic syncing to update MailChimp users when the Formidable record is updated
* Fixed issue causing multiple list settings to not save for all users

= 1.0b2 =
* REQUIRES v1.6
* Changed MCAPI class name to prevent MailChimp API version conflicts
* Added support for custom groups