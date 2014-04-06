To upgrade deprecated Wordpress Plugin [CF7-3rdparty Integration](http://wordpress.org/plugins/contact-form-7-3rd-party-integration/) to the new version [Forms 3rdparty Integration](http://wordpress.org/plugins/forms-3rdparty-integration/).

From discussion at http://wordpress.org/support/topic/how-to-upgrade-from-old-version-to-this-one?replies=1

Your mileage may vary...

0. You should have both plugins enabled with at least one service configured and saved.  This is so you have both complete sets of options available for comparison, because you may have to add some extra properties beyond just copy/paste.
0. Make sure to change the 'secret expected key' value
1. Upload this to your plugins folder, or
2. Upload somewhere, then include in `functions.php` with `include('path/to/Forms-3rdparty-Migrate-Hack.php')` -- or just paste it directly into `functions.php` in the WP editor
3. Go to your site, but add `?forms-3rdparty-migrate=your-temporary-secret-plz-change-this` to view the 'editor'
    * add `&raw=true` will print the whole array(s) out for extra debugging
4. Scroll to the bottom of the page
5. Copy the 'old' textarea into the 'new' textarea **if you haven't configured** Forms-3rdparty yet (this will overwrite everything)  If you have already configured some services, you'll need to copy those parts and insert them into the old value.  
    * You don't need to change the old keys (`cf7`) to the new (`src`) -- this will do it automatically
6. Make sure to add any additional properties present in the 'new' field are added, otherwise you might get some PHP warnings when you go back to the Forms-3rdparty admin page.  This part might not be necessary if you don't have PHP warnings turned on.
7. Test it first with the "Test" button -- see what the serialized values look like.
8. When you're happy things look about right, use the "Update" button.
9. Check the admin page to make sure your settings are all there.
10. Try the form.