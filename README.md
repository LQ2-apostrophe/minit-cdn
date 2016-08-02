# Minit CDN

A WordPress plugin as CDN support for [Minit](https://github.com/kasparsd/minit) by Kaspars Dambis.

Author: **LQ2'** ([Website](http://www.LQ2music.com/) | [GitHub](https://github.com/LQ2-apostrophe))

## Installation
1. Install Minit and Minit CDN.
2. Activate Minit and Minit CDN.

## CDN settings
Use the hook `minit-cdn-settings` in your theme's `functions.php` file. For example:
```php
add_filter( 'minit-cdn-settings', 'my_cdn_settings' );
function my_cdn_settings() {
	$cdn_settings = array(
		'http://cdn.example.com', // CDN URL (without trailing slashes)
		true, // Use CDN over HTTPS?
		true // Apply CDN for URLs of Minit files too?
	);
	return $cdn_settings;
}
```
**Note:** All array items must be set (not optional).

## Known bugs
Minit itself will combine all remaining CSS/JS files if it can't combine them on its last try. So if this plugin is configured to apply CDN for URLs of Minit files, Minit will think of these files as external files and not combine them.

## License
This plugin is released under [GPL](https://www.gnu.org/licenses/gpl.txt).

## Version history
#### 1.0.0
First release
