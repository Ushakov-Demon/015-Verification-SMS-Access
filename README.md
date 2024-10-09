# 015-Verification-SMS-Access
A plugin that allows you to add SMS verification, using the service https://www.015.cloud/, to view the contents of pages (posts)

# Install plugin
1. **Download the archive with the plugin.**
2. **Unzip the archive to the root directory of your site, or install from the admin panel by clicking on "Add New Plugin"->"Upload Plugin", and upload the plugin zip**
3. **Activate the plugin in the "Plugins" section of the admin panel.**
4. **Make the necessary settings, following the instructions below.**

## Plugin settings
CODE LIFETIME: This sets the time (in minutes) between sending a verification code to a user and deleting it from the database. The minimum value is 5 minutes.

VERIFICATION COOKIE LIFETIME: This determines how long a user's access cookie for restricted pages remains valid. Options include hours, days, weeks, and months.

AUTHENTICATION USERNAME: This is the "auth_username" parameter specified in the service settings. It's stored in an encrypted format.

AUTHENTICATION PASSWORD: This is the "auth_password" parameter specified in the service settings. It's stored in an encrypted format.

THE TELEPHONE LINE OR NUMBER TO SEND FROM: This is the "snumber" parameter specified in the service settings. It's stored in an encrypted format.

MESSAGE TEMPLATE: This allows you to customize the text of the SMS message. The verification code can be inserted anywhere using {{XXXX}}. It's recommended to use XXXX, but the quotes are not essential.

REDIRECT USER TO PAGE IF ACCESS IS DENIED: This allows you to specify a page where users will be redirected for verification. Any page can be used, but it cannot be restricted to verified users. To add verification forms, use the shortcode [print_verify_form] anywhere on the selected page. If no page is selected, the forms will be displayed on the restricted page, covering all content. After successful verification, the content will become accessible for the duration specified in the "VERIFICATION COOKIE LIFETIME" settings.

VERIFICATION FORM HTML: This allows you to customize the verification form. The form can be customized, but it must have a <form> tag with an "id" attribute (any valid value) and a "type='tel'" field with a "name" attribute (any valid value). If these requirements are not met, the form will not be saved (the admin will receive a message).

CONFIRM CODE FORM HTML: This allows you to customize the code confirmation form. The form can be customized, but it must have a <form> tag with the "id='verify_confirm_form'" attribute and an "<input type='text' name='confirm_code'>" field. If these requirements are not met, the form will not be saved (the admin will receive a message).