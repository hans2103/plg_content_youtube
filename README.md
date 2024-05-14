# Youtube content plugin for Joomla

This is a Joomla! plugin which replace `{youtube <url>}` by the Youtube video. Initially only the YouTube image and a button. After pressing the button both elements are replaced by the YouTube embed iframe and the video starts.

## Requirements

* Joomla! 5.0 or newer
* PHP 8.0 or newer

## Support

This plugin is primarily designed for use on HKweb projects and as such priority is given to the use cases there.  Additional features or use cases will be considered on a case-by-case basis.

## Layout/Media Overrides

The plugin's media and layouts may be overridden following the Joomla! override conventions.

To override the layout file, copy the `layouts/media/youtube.php` file to `templates/<template_name>/html/layouts/media/youtube.php`

To override the CSS files, copy the `media/css/*.css` files to `templates/<template_name>/css/youtube/*.css`

To override the JavaScript files, copy the `media/js/*.js` files to `templates/<template_name>/js/youtube/*.js`

### Release steps

- `build/build.sh`
- `git commit -am 'prepare plg_content_youtube 3.0.x'`
- `git tag -s '3.0.x' -m 'plg_content_youtube 3.0.x'`
- `git push origin --tags`
- create the release on GitHub
- `git push origin main`
