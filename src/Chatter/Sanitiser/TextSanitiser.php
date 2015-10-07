<?php

namespace Chatter\Sanitiser;

class TextSanitiser
{
    public function sanitise($text)
    {
        return $text;
    }
    
    /* Tidy up tags in a post to send to a page
     * Bits of this are a bit hacky, but its pretty efficient. It can be extended simply enough too. 
     * Just define the tag and what it needs to become in $parseList. If its more complicated write a new function thats called from this
     */
    public function buildTags($text)
    {
		$parseList = [
			"[VIDEO]" => "<iframe width=\"560\" height=\"315\" src=\"_X_\" frameborder=\"0\" allowfullscreen></iframe>",
			"[IMG]" => "<img style=\"max-width: 90%; max-height: 1200px;\" src=\"_X_\"/>",
			"[URL]" => "<a class=\"posturl\" href=\"_X_\">_X_</a>",
			"[B]" => "<span class=\"bold\">_X_</span>",
			"[I]" => "<span class=\"italic\">_X_</span>",
			"[SIZE=" => "<span style=\"font-size:_N_px;\">_X_</span>"
		];

		$formatted = nl2br($text);

		foreach($parseList as $key => $value){
			$openLocation = strpos($formatted, $key);
			$closeTag = substr_replace($key, "/", 1, 0);
			if ($key == "[SIZE=") {
				$closeTag = "[/SIZE]";
			}	
			$closeLocation = strpos($formatted, $closeTag);
			
			while($openLocation !== false && $closeLocation != false){
				$url = strstr($formatted, $closeTag, true);	// Remove everything after/including end tag
				$semiUrl = strstr($url, $key, false); 		// Remove everything before/including open tag
				$fullUrl = substr($semiUrl, strlen($key) );

				$format = str_replace("_X_", $fullUrl, $value); // Build html to be inserted
				if ($key == "[VIDEO]") {			// Make sure youtube URL is embedded version
					$format = str_replace("watch?v=", "embed/", $format);
				}
				if ($key == "[SIZE=") {
					$providedFontSize = explode("=", strstr(strstr($url, "]", true), "[SIZE=", false))[1];
					
					if ($providedFontSize > 24){
						$fontSize = 24;
					} else {
						$fontSize = $providedFontSize;
					}
					$format2 = str_replace("_N_", $fontSize, $format);
					$newKey = $providedFontSize . "]";
					$format = str_replace($newKey, "", $format2);
				}
				$remove = $key.$fullUrl.$closeTag;		// Build tags and between that need to be removed

				$formatted = str_replace( $remove, $format, $formatted);

				$openLocation = strpos($formatted, $key);
				$closeLocation = strpos($formatted, $closeTag);
			}	
		}
		return $formatted;
    }
}

