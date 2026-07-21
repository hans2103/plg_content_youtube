# Configurable YouTube thumbnail quality

## Problem

`layouts/media/youtube.php` hardcodes the thumbnail image URL to
`https://img.youtube.com/vi/<id>/maxresdefault.jpg`. `maxresdefault` doesn't
exist for every video (older or low-resolution uploads) and can 404, leaving
a broken thumbnail. There's no way to opt into the smaller, always-available
`hqdefault` size without editing the layout override.

## Scope

Global plugin setting only. No per-`{youtube}`-tag override (out of scope for
this change).

## Design

Add a new plugin param, `thumbnail_quality`, alongside the existing
`wrapper_class` field in `youtube.xml`'s `basic` fieldset. It's a `list`
field with two options, `hqdefault` and `maxresdefault`, defaulting to
`hqdefault`.

### `youtube.xml`

```xml
<field
    name="thumbnail_quality"
    type="list"
    label="PLG_CONTENT_YOUTUBE_THUMBNAILQUALITY_LABEL"
    default="hqdefault"
    filter="string"
>
    <option value="hqdefault">PLG_CONTENT_YOUTUBE_THUMBNAILQUALITY_HQDEFAULT</option>
    <option value="maxresdefault">PLG_CONTENT_YOUTUBE_THUMBNAILQUALITY_MAXRESDEFAULT</option>
</field>
```

### `language/en-GB/plg_content_youtube.ini`

Three new keys:

```ini
PLG_CONTENT_YOUTUBE_THUMBNAILQUALITY_LABEL = "Thumbnail Quality"
PLG_CONTENT_YOUTUBE_THUMBNAILQUALITY_HQDEFAULT = "High quality (hqdefault, always available)"
PLG_CONTENT_YOUTUBE_THUMBNAILQUALITY_MAXRESDEFAULT = "Max resolution (maxresdefault, not available for all videos)"
```

### `layouts/media/youtube.php`

Read the param and build the thumbnail URL from it instead of the hardcoded
string:

```php
$thumbnailQuality = $params->get('thumbnail_quality', 'hqdefault');
...
$videoAttribs['data-video-src'] = 'https://img.youtube.com/vi/' . $id . '/' . $thumbnailQuality . '.jpg';
```

The `'hqdefault'` fallback in `$params->get()` covers sites that installed
the plugin before this param existed and haven't resaved its config yet.

## Default value

`hqdefault` — chosen over preserving the current `maxresdefault` behavior,
since `hqdefault` is guaranteed to exist for every video and won't produce
broken thumbnails. This is a behavior change for existing installs (thumbnail
resolution drops) but avoids the 404 risk.

## Out of scope

- Per-tag override of quality (e.g. `{youtube ... quality="hq"}`).
- Any other thumbnail sizes YouTube exposes (`default`, `mqdefault`,
  `sddefault`) — only the two sizes already in use/considered are exposed.

## Testing

No automated test suite exists in this repo. Verify manually: set the param
to each value in the plugin's config screen, view an article containing a
`{youtube ...}` tag, and confirm the rendered `data-video-src` attribute
matches the selected quality.
