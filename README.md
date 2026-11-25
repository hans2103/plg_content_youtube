# YouTube content plugin for Joomla

A Joomla! plugin that replaces `{youtube <url> "Optional caption"}` in content with a privacy-friendly YouTube embed.

Until the visitor clicks, only a thumbnail and a play button are shown. The player sits inside a closed `<details>` element with `loading="lazy"`, so nothing is loaded from YouTube before that click. Opening it starts the video immediately (the click counts as user activation for `autoplay=1`) via `youtube-nocookie.com`. No JavaScript is involved.

```
{youtube https://www.youtube.com/watch?v=CnvmK6OMJV0 "Christmas concert 2020"}
{youtube https://youtu.be/CnvmK6OMJV0}
```

The quoted text becomes the visible caption, the accessible name of the play button ("Play video: …") and the `title` of the iframe.

## Requirements

* Joomla! 5.0 or newer (tested on 6.2)
* PHP 8.0 or newer

## Settings

* **Wrapper Class**: extra class on the `<figure>`.
* **Thumbnail Quality**: `hqdefault` (always available) or `maxresdefault`, which adds a 1280w thumbnail to the `srcset` (not every older video has one).
* **Thumbnail Sizes**: the `sizes` attribute of the thumbnail, so the browser picks the right `srcset` entry for the width the video has in your template. Empty uses `(min-width: 80rem) 80rem, 100vw` (a full-width embed). For a text column, for example: `(min-width: 60rem) 57rem, calc(100vw - 2rem)`.

## Content Security Policy

When your site sends a CSP, allow:

* `img-src https://img.youtube.com`
* `frame-src https://www.youtube-nocookie.com`

and, for a `Permissions-Policy`, `autoplay=(self "https://www.youtube-nocookie.com")`.

## Support

This plugin is primarily designed for use on HKweb projects and as such priority is given to the use cases there.  Additional features or use cases will be considered on a case-by-case basis.

## Layout/Media Overrides

The plugin's layout and stylesheet may be overridden following the Joomla! override conventions.

* Layout: copy `layouts/plugins/content/youtube/embed.php` to `templates/<template_name>/html/layouts/plugins/content/youtube/embed.php`
* Styling: the stylesheet is wrapped in `@layer plg-content-youtube`, so any template CSS wins. Only the three layout rules that keep the thumbnail inside the 16:9 frame (`display: grid` on the summary, hiding it when open, and the thumbnail's `block-size`) sit outside the layer, so unlayered resets such as Bootstrap's `summary { display: list-item }` and `img { height: auto }` in Cassiopeia cannot crop the thumbnail. Theme it with the custom properties on `.youtube` (`--youtube-play-background`, `--youtube-radius`, …), or replace the file via `media/templates/site/<template_name>/css/plg_content_youtube/youtube.css`

### Release steps

- `build/build.sh`
- `git commit -am 'prepare plg_content_youtube YY.WW.N'`
- `git tag -s 'YY.WW.N' -m 'plg_content_youtube YY.WW.N'`
- `git push origin --tags`
- create the release on GitHub
- `git push origin main`
