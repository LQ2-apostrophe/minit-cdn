<?php
/*
Plugin Name: Minit CDN
Plugin URI: https://github.com/LQ2-apostrophe/minit-cdn
Description: CDN support for Minit
Version: 1.0.0
Author: LQ2'
Author URI: http://www.LQ2music.com
License: GPL (https://www.gnu.org/licenses/gpl.txt)
*/

$minit_cdn = Minit_CDN::instance();

class Minit_CDN {

	protected function __construct() {
		// Add filters for CDN support
		add_filter( 'minit-item-css', array( $this, 'use_cdn' ), 12 );
		add_filter( 'minit-url-css', array( $this, 'use_cdn_url' ), 14 );
		add_filter( 'minit-url-js', array( $this, 'use_cdn_url' ), 14 );
	}

	public static function instance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new Minit_CDN();
		}
		return $instance;
	}

	// This function change base URL of assets to CDN URL
	public function use_cdn( $content ) {
		// Escape if $content is blank, since preg_replace is greedy
		if ( ! $content )
			return $content;
		return $this->apply_cdn( $content, false );
	}

	// This function change base URL of Minit files to CDN URL
	public function use_cdn_url( $url ) {
		return $this->apply_cdn( $url, true );
	}

	// This function applies CDN
	protected function apply_cdn( $content, $positionisurl ) {
		// Get CDN settings via this plugin's filter
		$cdn_setting = apply_filters( 'minit-cdn-settings', array() );
		// $cdn_setting[0] (string): CDN URL (without trailing slashes)
		// $cdn_setting[1] (boolean): Use CDN for HTTPS?
		// $cdn_setting[2] (boolean): Apply CDN for URLs of Minit files too?

		// Build PCRE for changing URL
		$base_url_pcre = '/https?:\/\/' . str_replace(".", "\.", $_SERVER['HTTP_HOST']) . '/i';

		// CDN settings must be available in order to change URLs
		if ( !empty( $cdn_setting ) ) {
			// Escape if $cdn_setting[2] is FALSE and this function applies CDN on URLs of Minit files
			if ( $positionisurl ) {
				if ( ( is_bool( $cdn_setting[2] ) ) && ( ! $cdn_setting[2] ) ) {
					return $content;
				}
			}
			// $cdn_setting[0] must be a string
			if ( is_string( $cdn_setting[0] ) ) {
				// OK. There's ability to apply CDN now.
				// Check if the site is using HTTPS
				if ( is_ssl() ) {
					// $cdn_setting[1] must be TRUE
					if ( ( is_bool( $cdn_setting[1] ) ) && ( $cdn_setting[1] ) ) {
						$content = preg_replace( $base_url_pcre, $cdn_setting[0], $content );
					}
				} else {
					$content = preg_replace( $base_url_pcre, $cdn_setting[0], $content );
				}
			}
		}

		return $content;
	}

}
